#!/usr/bin/env python3
import argparse
import fnmatch
import ftplib
import os
import subprocess
from pathlib import Path, PurePosixPath


ZERO_SHA = "0000000000000000000000000000000000000000"
DEFAULT_EXCLUDES = {
    ".DS_Store",
    "__pycache__",
    "*.part",
}


def connect(host, user, password):
    ftp = ftplib.FTP(host, timeout=60)
    ftp.login(user, password)
    ftp.set_pasv(True)
    return ftp


def should_exclude(relative_path, patterns):
    name = PurePosixPath(relative_path).name
    return any(
        fnmatch.fnmatch(relative_path, pattern) or fnmatch.fnmatch(name, pattern)
        for pattern in patterns
    )


def ensure_remote_dir(ftp, remote_dir):
    current = "/" if remote_dir.startswith("/") else ""
    for part in PurePosixPath(remote_dir).parts:
        if part == "/":
            continue
        current = str(PurePosixPath(current) / part)
        try:
            ftp.mkd(current)
        except ftplib.error_perm as exc:
            if not str(exc).startswith("550"):
                raise


def upload_file(ftp, local_path, remote_path):
    ensure_remote_dir(ftp, str(PurePosixPath(remote_path).parent))
    with local_path.open("rb") as handle:
        ftp.storbinary(f"STOR {remote_path}", handle)
    print(f"put  {remote_path}", flush=True)


def delete_file(ftp, remote_path):
    try:
        ftp.delete(remote_path)
        print(f"del  {remote_path}", flush=True)
    except ftplib.error_perm as exc:
        if not str(exc).startswith("550"):
            raise
        print(f"gone {remote_path}", flush=True)


def git_output(args):
    return subprocess.check_output(["git", *args])


def is_initial_push(base):
    return not base or base == ZERO_SHA


def changed_entries(base, head, git_local_root):
    local_root_posix = git_local_root.as_posix().rstrip("/") + "/"

    if is_initial_push(base):
        raw = git_output(["ls-tree", "-r", "--name-only", "-z", head, "--", git_local_root.as_posix()])
        for item in raw.rstrip(b"\0").split(b"\0"):
            if item:
                path = item.decode()
                if path.startswith(local_root_posix):
                    yield ("A", None, path[len(local_root_posix):])
        return

    raw = git_output(["diff", "--name-status", "-z", base, head, "--", git_local_root.as_posix()])
    parts = [part.decode() for part in raw.rstrip(b"\0").split(b"\0") if part]
    index = 0
    while index < len(parts):
        status = parts[index]
        index += 1

        if status.startswith("R") or status.startswith("C"):
            old_path = parts[index]
            new_path = parts[index + 1]
            index += 2
            yield (
                status[0],
                old_path[len(local_root_posix):] if old_path.startswith(local_root_posix) else None,
                new_path[len(local_root_posix):] if new_path.startswith(local_root_posix) else None,
            )
            continue

        path = parts[index]
        index += 1
        yield (
            status[0],
            None,
            path[len(local_root_posix):] if path.startswith(local_root_posix) else None,
        )


def deploy_change(ftp, status, old_path, path, local_root, remote_root, excludes, dry_run):
    old_relative = old_path
    relative = path

    if old_relative and should_exclude(old_relative, excludes):
        old_relative = None
    if relative and should_exclude(relative, excludes):
        relative = None

    if status in {"D", "R"} and old_relative:
        remote_path = str(PurePosixPath(remote_root) / PurePosixPath(old_relative))
        if dry_run:
            print(f"dry del  {remote_path}", flush=True)
        else:
            delete_file(ftp, remote_path)

    if status == "D" or not relative:
        return

    local_path = local_root / relative
    if not local_path.is_file():
        return

    remote_path = str(PurePosixPath(remote_root) / PurePosixPath(relative))
    if dry_run:
        print(f"dry put  {remote_path}", flush=True)
    else:
        upload_file(ftp, local_path, remote_path)


def main():
    parser = argparse.ArgumentParser(description="Deploy changed git files to Hostinger over FTP.")
    parser.add_argument("--base", default=os.environ.get("GITHUB_EVENT_BEFORE", ""))
    parser.add_argument("--head", default=os.environ.get("GITHUB_SHA", "HEAD"))
    parser.add_argument("--local-root", required=True)
    parser.add_argument("--remote-root", required=True)
    parser.add_argument("--host", default=os.environ.get("FTP_HOST"))
    parser.add_argument("--user", default=os.environ.get("FTP_USER"))
    parser.add_argument("--password", default=os.environ.get("FTP_PASSWORD"))
    parser.add_argument("--exclude", action="append", default=[])
    parser.add_argument("--dry-run", action="store_true")
    args = parser.parse_args()

    excludes = set(DEFAULT_EXCLUDES)
    excludes.update(args.exclude)
    local_root_arg = Path(args.local_root)
    local_root = local_root_arg if local_root_arg.is_absolute() else Path.cwd() / local_root_arg
    git_local_root = local_root if not local_root.is_absolute() else local_root.relative_to(Path.cwd())

    entries = list(changed_entries(args.base, args.head, git_local_root))
    if not entries:
        print("No changed files to deploy.", flush=True)
        return

    if args.dry_run:
        ftp = None
    else:
        missing = [name for name, value in {
            "FTP_HOST": args.host,
            "FTP_USER": args.user,
            "FTP_PASSWORD": args.password,
        }.items() if not value]
        if missing:
            raise SystemExit(f"Missing required environment value(s): {', '.join(missing)}")
        ftp = connect(args.host, args.user, args.password)

    try:
        for status, old_path, path in entries:
            deploy_change(
                ftp,
                status,
                old_path,
                path,
                local_root,
                args.remote_root,
                excludes,
                args.dry_run,
            )
    finally:
        if ftp:
            ftp.quit()


if __name__ == "__main__":
    main()
