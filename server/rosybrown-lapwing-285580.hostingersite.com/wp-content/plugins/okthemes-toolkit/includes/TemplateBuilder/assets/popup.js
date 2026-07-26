let lenisActive = true;
// Popup functionality with Lenis smooth scroll support
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all popups on the page
    initPopups();
        
    // Add event listeners for close buttons
    document.querySelectorAll('.popup-close').forEach(function(closeBtn) {
        closeBtn.addEventListener('click', function() {
            const popupId = this.getAttribute('data-popup');
            closePopup(popupId);
        });
    });
    
    // Close popup when clicking on overlay
    document.querySelectorAll('.popup-overly').forEach(function(overlay) {
        overlay.addEventListener('click', function() {
            const popup = this.closest('.okthemes-popup-wrapper');
            if (popup) {
                closePopup(popup.id);
            }
        });
    });
    
    // Close popup with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activePopup = document.querySelector('.okthemes-popup-wrapper.show');
            if (activePopup) {
                closePopup(activePopup.id);
            }
        }
    });
});

function initPopups() {
    document.querySelectorAll('.okthemes-popup-wrapper:not(.editing)').forEach(function(popup) {
        const delay = parseInt(popup.getAttribute('data-delay')) || 0;
        
        if (delay > 0) {
            setTimeout(function() {
                openPopup(popup.id);
            }, delay * 1000);
        }
    });
}

// Modify your openPopup function
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (!popup) return;
       
    // Prevent body scrolling
    document.body.classList.add('popup-open');
    
    // Show popup
    popup.classList.add('show');
    
    // Trigger custom event
    const event = new CustomEvent('popupOpened', { detail: { popupId: popupId } });
    document.dispatchEvent(event);
}

// Modify your closePopup function
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (!popup) return;
    
    // Hide popup
    popup.classList.remove('show');
    
    // Re-enable body scrolling
    document.body.classList.remove('popup-open');
    
    
    // Trigger custom event
    const event = new CustomEvent('popupClosed', { detail: { popupId: popupId } });
    document.dispatchEvent(event);
}

// Function to manually open a popup (can be called from other scripts)
window.openOkthemesPopup = function(popupId) {
    if (!popupId.startsWith('popup-')) {
        popupId = 'popup-' + popupId;
    }
    openPopup(popupId);
};

// Function to manually close a popup (can be called from other scripts)
window.closeOkthemesPopup = function(popupId) {
    if (!popupId.startsWith('popup-')) {
        popupId = 'popup-' + popupId;
    }
    closePopup(popupId);
};