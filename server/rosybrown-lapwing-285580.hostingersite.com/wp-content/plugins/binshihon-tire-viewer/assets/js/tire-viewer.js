(function () {
	"use strict";

	const config = window.BinShihonTireViewer || {};
	let threePromise = null;

	function loadThree() {
		if (!threePromise) {
			threePromise = import(config.threeUrl);
		}

		return threePromise;
	}

	function loadImage(url) {
		return new Promise((resolve, reject) => {
			const image = new Image();

			image.crossOrigin = "anonymous";
			image.onload = () => resolve(image);
			image.onerror = reject;
			image.src = url;
		});
	}

	function cropAlphaBounds(image) {
		const canvas = document.createElement("canvas");
		canvas.width = image.naturalWidth || image.width;
		canvas.height = image.naturalHeight || image.height;

		const context = canvas.getContext("2d", { willReadFrequently: true });
		context.drawImage(image, 0, 0);

		let data;

		try {
			data = context.getImageData(0, 0, canvas.width, canvas.height).data;
		} catch (error) {
			return canvas;
		}

		let minX = canvas.width;
		let minY = canvas.height;
		let maxX = 0;
		let maxY = 0;
		let found = false;

		for (let y = 0; y < canvas.height; y++) {
			for (let x = 0; x < canvas.width; x++) {
				const alpha = data[(y * canvas.width + x) * 4 + 3];

				if (alpha > 6) {
					found = true;
					minX = Math.min(minX, x);
					minY = Math.min(minY, y);
					maxX = Math.max(maxX, x);
					maxY = Math.max(maxY, y);
				}
			}
		}

		if (!found) {
			return canvas;
		}

		const output = document.createElement("canvas");
		output.width = maxX - minX + 1;
		output.height = maxY - minY + 1;
		output.getContext("2d").drawImage(
			canvas,
			minX,
			minY,
			output.width,
			output.height,
			0,
			0,
			output.width,
			output.height
		);

		return output;
	}

	function buildTreadCanvas(image) {
		const sourceWidth = image.naturalWidth || image.width;
		const sourceHeight = image.naturalHeight || image.height;
		const shouldRotate = sourceHeight > sourceWidth;
		const base = document.createElement("canvas");

		if (shouldRotate) {
			base.width = sourceHeight;
			base.height = sourceWidth;

			const context = base.getContext("2d");
			context.translate(base.width / 2, base.height / 2);
			context.rotate(-Math.PI / 2);
			context.drawImage(image, -sourceWidth / 2, -sourceHeight / 2);
		} else {
			base.width = sourceWidth;
			base.height = sourceHeight;
			base.getContext("2d").drawImage(image, 0, 0);
		}

		const output = document.createElement("canvas");
		output.width = base.width * 4;
		output.height = base.height;

		const context = output.getContext("2d");

		for (let i = 0; i < 4; i++) {
			const x = i * base.width;

			if (i % 2 === 0) {
				context.drawImage(base, x, 0);
			} else {
				context.save();
				context.translate(x + base.width, 0);
				context.scale(-1, 1);
				context.drawImage(base, 0, 0);
				context.restore();
			}
		}

		return output;
	}

	function imageCanvasToTexture(THREE, renderer, canvas) {
		const texture = new THREE.CanvasTexture(canvas);
		texture.colorSpace = THREE.SRGBColorSpace;
		texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
		texture.needsUpdate = true;

		return texture;
	}

	function createFaceTexture(THREE, renderer, canvas) {
		const texture = imageCanvasToTexture(THREE, renderer, canvas);
		texture.wrapS = THREE.ClampToEdgeWrapping;
		texture.wrapT = THREE.ClampToEdgeWrapping;
		texture.minFilter = THREE.LinearMipmapLinearFilter;
		texture.magFilter = THREE.LinearFilter;

		return texture;
	}

	function createTreadTexture(THREE, renderer, canvas) {
		const texture = imageCanvasToTexture(THREE, renderer, canvas);
		texture.wrapS = THREE.RepeatWrapping;
		texture.wrapT = THREE.ClampToEdgeWrapping;
		texture.repeat.set(0.5, 0.9);
		texture.offset.set(0.125, 0);
		texture.minFilter = THREE.LinearMipmapLinearFilter;
		texture.magFilter = THREE.LinearFilter;

		return texture;
	}

	function isElementVisible(element) {
		const rect = element.getBoundingClientRect();

		return rect.bottom > 0 && rect.top < window.innerHeight;
	}

	function getPageTop(element) {
		return element.getBoundingClientRect().top + window.scrollY;
	}

	function getStageStopSection(element) {
		let parent = element.parentElement;

		while (parent) {
			if (parent.classList && parent.classList.contains("black-bg")) {
				return parent;
			}

			parent = parent.parentElement;
		}

		const internalBlackSection = element.querySelector(".black-bg");

		if (internalBlackSection && internalBlackSection.offsetHeight > window.innerHeight * 0.5) {
			return internalBlackSection;
		}

		return element.closest(".elementor-section, .e-con, section") || element;
	}

	function initScrollStageStop(element, stage, mode) {
		if (mode !== "scroll" || !element.classList.contains("btv-tire-viewer--shortcode")) {
			return;
		}

		let frame = 0;

		function updateStageStop() {
			frame = 0;

			const stopSection = getStageStopSection(element);
			const sectionRect = stopSection.getBoundingClientRect();
			const sectionHeight = stopSection.offsetHeight || sectionRect.height;

			if (!sectionHeight) {
				element.classList.remove("is-stage-stopped");
				stage.style.removeProperty("--btv-stage-stop-top");
				return;
			}

			const sectionTop = getPageTop(stopSection);
			const stopScrollY = sectionTop + sectionHeight / 2 - window.innerHeight / 2;
			const shouldStop = window.scrollY >= stopScrollY;

			element.classList.toggle("is-stage-stopped", shouldStop);

			if (shouldStop) {
				const stopTop = Math.max(0, stopScrollY - getPageTop(element));
				stage.style.setProperty("--btv-stage-stop-top", `${Math.round(stopTop)}px`);
			} else {
				stage.style.removeProperty("--btv-stage-stop-top");
			}
		}

		function requestStageStopUpdate() {
			if (frame) {
				return;
			}

			frame = requestAnimationFrame(updateStageStop);
		}

		window.addEventListener("scroll", requestStageStopUpdate, { passive: true });
		window.addEventListener("resize", requestStageStopUpdate);
		window.addEventListener("load", requestStageStopUpdate);
		requestStageStopUpdate();
	}

	async function initViewer(element) {
		if (element.dataset.btvReady === "1") {
			return;
		}

		element.dataset.btvReady = "1";

		const loading = element.querySelector("[data-btv-loading]");
		const stage = element.querySelector("[data-btv-stage]");
		const mode = element.dataset.mode === "manual" ? "manual" : "scroll";
		const autoSpin = element.dataset.auto === "yes" && !window.matchMedia("(prefers-reduced-motion: reduce)").matches;
		const frontUrl = element.dataset.front;
		const backUrl = element.dataset.back || frontUrl;
		const sideUrl = element.dataset.side;

		if (!stage || !frontUrl || !sideUrl) {
			return;
		}

		initScrollStageStop(element, stage, mode);

		try {
			const THREE = await loadThree();
			const renderer = new THREE.WebGLRenderer({
				antialias: true,
				alpha: true,
				powerPreference: "high-performance"
			});

			renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
			renderer.outputColorSpace = THREE.SRGBColorSpace;
			stage.appendChild(renderer.domElement);

			const scene = new THREE.Scene();
			const camera = new THREE.PerspectiveCamera(34, 1, 0.1, 100);
			camera.position.set(0, 0.08, 5.25);

			scene.add(new THREE.AmbientLight(0xffffff, 1.85));

			const keyLight = new THREE.DirectionalLight(0xffffff, 1.65);
			keyLight.position.set(3.4, 3.2, 5.2);
			scene.add(keyLight);

			const fillLight = new THREE.DirectionalLight(0xffffff, 0.78);
			fillLight.position.set(-4, 1.5, 2.4);
			scene.add(fillLight);

			const rimLight = new THREE.DirectionalLight(0xffffff, 0.55);
			rimLight.position.set(-3.2, -1.2, -4.8);
			scene.add(rimLight);

			const tireGroup = new THREE.Group();
			tireGroup.rotation.x = THREE.MathUtils.degToRad(-1.8);
			tireGroup.rotation.y = 0.55;
			tireGroup.position.z = -1;
			scene.add(tireGroup);

			const [frontImage, backImage, sideImage] = await Promise.all([
				loadImage(frontUrl),
				loadImage(backUrl),
				loadImage(sideUrl)
			]);

			const frontTexture = createFaceTexture(THREE, renderer, cropAlphaBounds(frontImage));
			const backTexture = createFaceTexture(THREE, renderer, cropAlphaBounds(backImage));
			const treadTexture = createTreadTexture(THREE, renderer, buildTreadCanvas(sideImage));

			const tireRadius = 1.68;
			const tireDepth = 0.9;
			const faceRadius = tireRadius * 1.001;
			const radialSegments = 192;
			const faceOffset = tireDepth / 2 + 0.012;
			const backingOffset = tireDepth / 2 - 0.04;

			const treadMesh = new THREE.Mesh(
				new THREE.CylinderGeometry(tireRadius, tireRadius, tireDepth, radialSegments, 1, true),
				new THREE.MeshStandardMaterial({
					map: treadTexture,
					roughness: 0.92,
					metalness: 0.03,
					side: THREE.DoubleSide
				})
			);
			treadMesh.rotation.z = Math.PI / 2;
			tireGroup.add(treadMesh);

			const frontFace = new THREE.Mesh(
				new THREE.CircleGeometry(faceRadius, 128),
				new THREE.MeshStandardMaterial({
					map: frontTexture,
					transparent: true,
					side: THREE.DoubleSide,
					roughness: 0.72,
					metalness: 0.08
				})
			);
			frontFace.rotation.y = -Math.PI / 2;
			frontFace.position.x = faceOffset;
			tireGroup.add(frontFace);

			const backFace = new THREE.Mesh(
				new THREE.CircleGeometry(faceRadius, 128),
				new THREE.MeshStandardMaterial({
					map: backTexture,
					transparent: true,
					side: THREE.DoubleSide,
					roughness: 0.72,
					metalness: 0.08
				})
			);
			backFace.rotation.y = Math.PI / 2;
			backFace.position.x = -faceOffset;
			tireGroup.add(backFace);

			const backingMaterial = new THREE.MeshStandardMaterial({
				color: 0x171a1d,
				roughness: 1,
				metalness: 0,
				side: THREE.DoubleSide
			});

			const frontBacking = new THREE.Mesh(
				new THREE.CircleGeometry(tireRadius * 0.985, 128),
				backingMaterial
			);
			frontBacking.rotation.y = -Math.PI / 2;
			frontBacking.position.x = backingOffset;
			tireGroup.add(frontBacking);

			const rearBacking = new THREE.Mesh(
				new THREE.CircleGeometry(tireRadius * 0.985, 128),
				backingMaterial
			);
			rearBacking.rotation.y = Math.PI / 2;
			rearBacking.position.x = -backingOffset;
			tireGroup.add(rearBacking);

			const innerCavity = new THREE.Mesh(
				new THREE.CylinderGeometry(tireRadius * 0.56, tireRadius * 0.56, tireDepth * 0.92, 96),
				new THREE.MeshStandardMaterial({
					color: 0x0d0f11,
					roughness: 1,
					metalness: 0
				})
			);
			innerCavity.rotation.z = Math.PI / 2;
			tireGroup.add(innerCavity);

			const ringMaterial = new THREE.MeshStandardMaterial({
				color: 0x202428,
				roughness: 0.95,
				metalness: 0.02,
				side: THREE.DoubleSide
			});

			const frontRing = new THREE.Mesh(
				new THREE.TorusGeometry(tireRadius * 0.985, 0.035, 16, 96),
				ringMaterial
			);
			frontRing.rotation.y = Math.PI / 2;
			frontRing.position.x = tireDepth / 2 + 0.004;
			tireGroup.add(frontRing);

			const rearRing = new THREE.Mesh(
				new THREE.TorusGeometry(tireRadius * 0.985, 0.035, 16, 96),
				ringMaterial
			);
			rearRing.rotation.y = Math.PI / 2;
			rearRing.position.x = -tireDepth / 2 - 0.004;
			tireGroup.add(rearRing);

			let isDragging = false;
			let startX = 0;
			let startRotation = 0;
			let targetRotationY = 0.55;
			let shouldAutoSpin = autoSpin;
			let lastScrollY = window.scrollY;
			let lastTime = performance.now();
			const interactionTarget = renderer.domElement;

			function resize() {
				const rect = element.getBoundingClientRect();
				const availableWidth = Math.round(rect.width);
				const availableHeight = Math.round(rect.height);
				const squareSize = Math.max(
					260,
					Math.min(
						availableWidth || availableHeight || 260,
						availableHeight || availableWidth || 260
					)
				);

				renderer.setSize(squareSize, squareSize, false);
				camera.aspect = 1;
				camera.updateProjectionMatrix();
			}

			function onPointerDown(event) {
				isDragging = true;
				shouldAutoSpin = false;
				startX = event.clientX;
				startRotation = targetRotationY;
				element.classList.add("is-dragging");
				interactionTarget.setPointerCapture(event.pointerId);
			}

			function onPointerMove(event) {
				if (!isDragging) {
					return;
				}

				const rect = interactionTarget.getBoundingClientRect();
				const delta = (event.clientX - startX) / Math.max(1, rect.width);
				targetRotationY = startRotation + delta * Math.PI * 2.1;
			}

			function endDrag(event) {
				if (!isDragging) {
					return;
				}

				isDragging = false;
				element.classList.remove("is-dragging");

				if (interactionTarget.hasPointerCapture(event.pointerId)) {
					interactionTarget.releasePointerCapture(event.pointerId);
				}
			}

			function onScroll() {
				if (mode !== "scroll" || !isElementVisible(element)) {
					lastScrollY = window.scrollY;
					return;
				}

				const delta = window.scrollY - lastScrollY;
				lastScrollY = window.scrollY;

				if (Math.abs(delta) > 0) {
					targetRotationY += delta * 0.008;
				}
			}

			function animate(now) {
				const delta = Math.min(40, now - lastTime);
				lastTime = now;

				if (shouldAutoSpin) {
					targetRotationY += delta * 0.00095 * Math.PI;
				}

				tireGroup.rotation.y += (targetRotationY - tireGroup.rotation.y) * 0.14;
				renderer.render(scene, camera);
				requestAnimationFrame(animate);
			}

			interactionTarget.addEventListener("pointerdown", onPointerDown);
			interactionTarget.addEventListener("pointermove", onPointerMove);
			interactionTarget.addEventListener("pointerup", endDrag);
			interactionTarget.addEventListener("pointercancel", endDrag);
			window.addEventListener("scroll", onScroll, { passive: true });

			if (window.ResizeObserver) {
				new ResizeObserver(resize).observe(element);
			} else {
				window.addEventListener("resize", resize);
			}

			resize();
			element.classList.add("is-loaded");
			requestAnimationFrame(animate);
		} catch (error) {
			console.error(error);
			element.classList.add("has-error");

			if (loading) {
				loading.textContent = "Failed to load tire viewer.";
			}
		}
	}

	function boot() {
		document.querySelectorAll("[data-btv-viewer]").forEach(initViewer);
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", boot);
	} else {
		boot();
	}
})();
