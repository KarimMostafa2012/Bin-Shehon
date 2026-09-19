document.addEventListener("DOMContentLoaded", () => {
    const getOverlay = () => document.querySelector("#wcqr-overlay");
    const getForm = () => document.querySelector("#wcqr-form");

    const closeModal = () => {
        const overlay = getOverlay();

        if (!overlay) {
            return;
        }

        overlay.classList.remove("is-active");
        overlay.style.display = "none";
        overlay.setAttribute("aria-hidden", "true");
        document.body.classList.remove("wcqr-modal-open");
    };

    const openModal = (button, event) => {
        if (event) {
            event.preventDefault();
        }

        const overlay = getOverlay();
        const form = getForm();
        const success = document.querySelector("#wcqr-success");
        const productId = document.querySelector("#wcqr-product-id");
        const productName = document.querySelector("#wcqr-product-name");
        const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

        if (!overlay || !form || !productId || !productName) {
            return;
        }

        form.hidden = false;
        form.reset();

        if (success) {
            success.hidden = true;
        }

        productId.value = button.dataset.id || "";
        productName.value = button.dataset.name || "";

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = "Submit Quote";
        }

        overlay.classList.add("is-active");
        overlay.style.display = "flex";
        overlay.setAttribute("aria-hidden", "false");
        document.body.classList.add("wcqr-modal-open");
    };

    window.wcqrOpenQuote = openModal;

    document.addEventListener("click", (event) => {
        const openButton = event.target.closest(".wcqr-open");

        if (openButton) {
            event.preventDefault();
            openModal(openButton, event);
            return;
        }

        if (event.target.closest("#wcqr-close, #wcqr-close-success")) {
            event.preventDefault();
            closeModal();
            return;
        }

        if (event.target.matches("#wcqr-overlay")) {
            closeModal();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeModal();
        }
    });

    document.addEventListener("submit", async (event) => {
        const form = event.target.closest("#wcqr-form");

        if (!form) {
            return;
        }

        event.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = "Submitting...";
        }

        try {
            const data = new FormData(form);

            data.append("action", "wcqr_submit_quote");
            data.append("nonce", wcqr.nonce);

            const response = await fetch(wcqr.ajax, {
                method: "POST",
                body: data
            });

            const json = await response.json();

            if (json.success) {
                const success = document.querySelector("#wcqr-success");
                const order = document.querySelector("#wcqr-success-order");

                form.hidden = true;

                if (order) {
                    order.textContent = `Quote #${json.data.order_id}`;
                }

                if (success) {
                    success.hidden = false;
                }

                return;
            }

            alert(json.data && json.data.message ? json.data.message : "Quote could not be submitted.");
        } catch (err) {
            console.error(err);
            alert("Something went wrong. Please try again.");
        }

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = "Submit Quote";
        }
    });
});
