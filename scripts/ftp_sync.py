#!/usr/bin/env python3
import argparse
from concurrent.futures import ThreadPoolExecutor, as_completed
import fnmatch
import ftplib
import getpass
import os
import time
from queue import Queue
from threading import Lock, Thread
from pathlib import Path, PurePosixPath


DEFAULT_HOST = "82.25.102.200"
DEFAULT_USER = "u580285425"
DEFAULT_REMOTE = "/domains/binshihontrd.com/public_html"
DEFAULT_LOCAL = "server/public_html"
DEFAULT_EXCLUDES = {
    ".DS_Store",
    "__pycache__",
    ".git",
}


def env_or_prompt(name, prompt, secret=False, default=None):
    value = os.environ.get(name)
    if value:
        return value
    if default is not None:
        entered = input(f"{prompt} [{default}]: ").strip()
        return entered or default
    if secret:
        return getpass.getpass(f"{prompt}: ")
    return input(f"{prompt}: ").strip()


def connect(host, user, password):
    ftp = ftplib.FTP(host, timeout=60)
    ftp.login(user, password)
    ftp.set_pasv(True)
    return ftp


def should_exclude(path, patterns):
    name = Path(path).name
    return any(fnmatch.fnmatch(path, pattern) or fnmatch.fnmatch(name, pattern) for pattern in patterns)


def ensure_remote_dir(ftp, remote_dir):
    parts = PurePosixPath(remote_dir).parts
    current = "/" if remote_dir.startswith("/") else ""
    for part in parts:
        if part == "/":
            continue
        current = str(PurePosixPath(current) / part)
        try:
            ftp.mkd(current)
        except ftplib.error_perm as exc:
            if not str(exc).startswith("550"):
                raise


def list_remote(ftp, remote_dir):
    entries = []
    try:
        ftp.dir(remote_dir, entries.append)
    except ftplib.error_perm:
        return []

    parsed = []
    for line in entries:
        parts = line.split(maxsplit=8)
        if len(parts) < 9:
            continue
        kind = parts[0][0]
        size = int(parts[4]) if parts[4].isdigit() else None
        name = parts[8]
        if name in {".", ".."}:
            continue
        parsed.append((name, kind == "d", size))
    return parsed


def quiet_quit(ftp):
    try:
        ftp.quit()
    except Exception:
        try:
            ftp.close()
        except Exception:
            pass


def collect_remote_files(host, user, password, remote_dir, local_dir, excludes, workers):
    files = []
    files_lock = Lock()
    count_lock = Lock()
    queue = Queue()
    queue.put((remote_dir, local_dir, 0))
    scanned = 0

    def worker():
        nonlocal scanned
        ftp = connect(host, user, password)
        try:
            while True:
                item = queue.get()
                if item is None:
                    queue.task_done()
                    break
                current_remote, current_local, attempts = item
                current_local.mkdir(parents=True, exist_ok=True)
                try:
                    entries = list_remote(ftp, current_remote)
                    for name, is_dir, size in entries:
                        remote_path = str(PurePosixPath(current_remote) / name)
                        local_path = current_local / name
                        relative = str(local_path)
                        if should_exclude(relative, excludes):
                            print(f"skip {remote_path}", flush=True)
                            continue
                        if is_dir:
                            queue.put((remote_path, local_path, 0))
                        else:
                            with files_lock:
                                files.append((remote_path, local_path, size))
                    with count_lock:
                        scanned += 1
                        if scanned % 100 == 0:
                            print(f"scanned {scanned} directories, found {len(files)} files", flush=True)
                except Exception as exc:
                    quiet_quit(ftp)
                    ftp = connect(host, user, password)
                    if attempts < 3:
                        print(f"retry {current_remote} ({type(exc).__name__})", flush=True)
                        queue.put((current_remote, current_local, attempts + 1))
                    else:
                        print(f"fail {current_remote} ({type(exc).__name__})", flush=True)
                finally:
                    queue.task_done()
        finally:
            quiet_quit(ftp)

    threads = [Thread(target=worker, daemon=True) for _ in range(workers)]
    for thread in threads:
        thread.start()

    queue.join()
    for _ in threads:
        queue.put(None)
    queue.join()
    for thread in threads:
        thread.join()

    return files


def download_one(host, user, password, remote_path, local_path, size):
    if local_path.exists() and size is not None and local_path.stat().st_size == size:
        return f"same {remote_path}"
    local_path.parent.mkdir(parents=True, exist_ok=True)
    temp_path = local_path.with_name(f"{local_path.name}.part")
    for attempt in range(4):
        ftp = connect(host, user, password)
        try:
            with temp_path.open("wb") as handle:
                ftp.retrbinary(f"RETR {remote_path}", handle.write)
            temp_path.replace(local_path)
            break
        except Exception:
            if attempt == 3:
                raise
        finally:
            quiet_quit(ftp)
    return f"get  {remote_path}"


def pull_tree(host, user, password, remote_dir, local_dir, excludes, workers):
    files = collect_remote_files(host, user, password, remote_dir, local_dir, excludes, workers)

    print(f"found {len(files)} remote files", flush=True)
    with ThreadPoolExecutor(max_workers=workers) as executor:
        futures = [
            executor.submit(download_one, host, user, password, remote_path, local_path, size)
            for remote_path, local_path, size in files
        ]
        for future in as_completed(futures):
            print(future.result(), flush=True)


def remote_size(ftp, remote_path):
    try:
        return ftp.size(remote_path)
    except Exception:
        return None


def upload_one(host, user, password, local_path, remote_path):
    ftp = connect(host, user, password)
    try:
        ensure_remote_dir(ftp, str(PurePosixPath(remote_path).parent))
        local_size = local_path.stat().st_size
        if remote_size(ftp, remote_path) == local_size:
            return f"same {remote_path}"
        with local_path.open("rb") as handle:
            ftp.storbinary(f"STOR {remote_path}", handle)
        return f"put  {remote_path}"
    finally:
        quiet_quit(ftp)


def collect_local_files(local_dir, remote_dir, excludes):
    files = []
    for local_path in sorted(local_dir.rglob("*")):
        if not local_path.is_file():
            continue
        relative_path = local_path.relative_to(local_dir)
        relative = relative_path.as_posix()
        if local_path.name.endswith(".part") or should_exclude(relative, excludes):
            print(f"skip {local_path}", flush=True)
            continue
        remote_path = str(PurePosixPath(remote_dir) / PurePosixPath(relative))
        files.append((local_path, remote_path))
    return files


def push_changed_files(host, user, password, local_dir, remote_dir, excludes, previous_snapshot, workers):
    current_snapshot = snapshot_local_files(local_dir, excludes)
    changed = [
        (local_dir / relative, str(PurePosixPath(remote_dir) / PurePosixPath(relative)))
        for relative, state in current_snapshot.items()
        if previous_snapshot.get(relative) != state
    ]
    if not changed:
        print("watch: no local changes to push", flush=True)
        return current_snapshot

    print(f"watch: pushing {len(changed)} local change(s)", flush=True)
    with ThreadPoolExecutor(max_workers=workers) as executor:
        futures = [
            executor.submit(upload_one, host, user, password, local_path, remote_path)
            for local_path, remote_path in changed
        ]
        for future in as_completed(futures):
            print(future.result(), flush=True)
    return snapshot_local_files(local_dir, excludes)


def push_tree(host, user, password, local_dir, remote_dir, excludes, workers):
    files = collect_local_files(local_dir, remote_dir, excludes)
    print(f"found {len(files)} local files", flush=True)
    with ThreadPoolExecutor(max_workers=workers) as executor:
        futures = [
            executor.submit(upload_one, host, user, password, local_path, remote_path)
            for local_path, remote_path in files
        ]
        for future in as_completed(futures):
            print(future.result(), flush=True)


def push_tree_old(ftp, local_dir, remote_dir, excludes):
    ensure_remote_dir(ftp, remote_dir)
    for local_path in sorted(local_dir.iterdir()):
        relative = str(local_path.relative_to(local_dir.parent))
        if should_exclude(relative, excludes):
            print(f"skip {local_path}", flush=True)
            continue
        remote_path = str(PurePosixPath(remote_dir) / local_path.name)
        if local_path.is_dir():
            print(f"dir  {remote_path}", flush=True)
            push_tree(ftp, local_path, remote_path, excludes)
        elif local_path.is_file():
            print(f"put  {remote_path}", flush=True)
            with local_path.open("rb") as handle:
                ftp.storbinary(f"STOR {remote_path}", handle)


def snapshot_local_files(local_dir, excludes):
    snapshot = {}
    for local_path in local_dir.rglob("*"):
        if not local_path.is_file() or local_path.name.endswith(".part"):
            continue
        relative = local_path.relative_to(local_dir).as_posix()
        if should_exclude(relative, excludes):
            continue
        stat = local_path.stat()
        snapshot[relative] = (stat.st_size, stat.st_mtime_ns)
    return snapshot


def watch_tree(host, user, password, remote_dir, local_dir, excludes, workers, interval):
    print("watch: initial pull", flush=True)
    pull_tree(host, user, password, remote_dir, local_dir, excludes, workers)
    snapshot = snapshot_local_files(local_dir, excludes)
    print(f"watch: streaming every {interval}s; press Ctrl-C to stop", flush=True)
    while True:
        time.sleep(interval)
        snapshot = push_changed_files(host, user, password, local_dir, remote_dir, excludes, snapshot, workers)
        print("watch: pulling server changes", flush=True)
        pull_tree(host, user, password, remote_dir, local_dir, excludes, workers)
        snapshot = snapshot_local_files(local_dir, excludes)


def main():
    parser = argparse.ArgumentParser(description="Sync Hostinger FTP files with a local mirror.")
    parser.add_argument("action", choices=["pull", "push", "watch"], help="pull downloads, push uploads, or watch both ways")
    parser.add_argument("--host", default=os.environ.get("FTP_HOST", DEFAULT_HOST))
    parser.add_argument("--user", default=os.environ.get("FTP_USER", DEFAULT_USER))
    parser.add_argument("--remote", default=os.environ.get("FTP_REMOTE", DEFAULT_REMOTE))
    parser.add_argument("--local", default=os.environ.get("FTP_LOCAL", DEFAULT_LOCAL))
    parser.add_argument(
        "--exclude",
        action="append",
        default=[],
        help="Glob pattern to exclude. Can be repeated.",
    )
    parser.add_argument("--workers", type=int, default=3, help="Parallel workers for pull.")
    parser.add_argument("--interval", type=int, default=30, help="Seconds between watch sync cycles.")
    args = parser.parse_args()

    password = os.environ.get("FTP_PASSWORD") or getpass.getpass("FTP password: ")
    excludes = set(DEFAULT_EXCLUDES)
    excludes.update(args.exclude)

    local_dir = Path(args.local).resolve()
    if args.action == "pull":
        pull_tree(args.host, args.user, password, args.remote, local_dir, excludes, args.workers)
    elif args.action == "watch":
        watch_tree(args.host, args.user, password, args.remote, local_dir, excludes, args.workers, args.interval)
    else:
        push_tree(args.host, args.user, password, local_dir, args.remote, excludes, args.workers)


if __name__ == "__main__":
    main()
