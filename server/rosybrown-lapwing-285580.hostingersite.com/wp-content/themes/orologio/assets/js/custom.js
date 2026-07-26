(function ($) {
    "use strict";

    // **Lenis Smooth Scroll Integration**
    function initLenis() {
        window.lenis = new Lenis({
            duration: 1.2,
            easing: (t) => 1 - Math.pow(1 - t, 3),
            smooth: true,
            smoothTouch: true,
        });

        function raf(time) {
            if (window.lenis) {
                window.lenis.raf(time);
            }
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
    }

    // Initialize Lenis
    initLenis();

	$(document).ready(function() {
		"use strict";
	
		// Scroll top & sticky header
		var $siteHeader = $('.site-header');
		var $siteFooter = $('.site-footer');
	
		if ($siteHeader.length && $siteFooter.length) {
			var $body = $('body');
	
			$(window).on('scroll', function() {
				var scrollTop = $(window).scrollTop();
				var footerOffsetTop = $siteFooter[0].getBoundingClientRect();
				
				$siteHeader.toggleClass('sticky', scrollTop > 0);
				$body.toggleClass('is-scrolled', scrollTop > 100);
				$body.toggleClass('is-over-footer', footerOffsetTop.y < 200);
			});
		}
	});

    /*Next arrow animation */
    document.addEventListener("DOMContentLoaded", function () {
        // Select the SVG element
        const svgs = document.querySelectorAll(".icon-theme-next-arrow");

        svgs.forEach((svg) => {
            // Find elements inside each SVG instance
            const circle = svg.querySelector(".circle");
            const shortOblique = svg.querySelector(".short-oblique");
            const linePath = svg.querySelectorAll(".line");
            const horizontalLine = svg.querySelector(".horizontal-line");

            svg.addEventListener("mouseenter", () => {
                gsap.to(circle, {
                    r: 20, // Increase circle size
                    x:10,
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(linePath, {
                    x:12,
                    duration: 0.3,
                    ease: "power2.out"
                });

                gsap.to(horizontalLine, {
                    attr: { x1: 8.267 }, 
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(shortOblique, {
                    attr: { x2: 29.496, y2:18.375 }, 
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            svg.addEventListener("mouseleave", () => {
                gsap.to(circle, {
                    r: 12.5, // Reset circle size
                    x:0,
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(linePath, {
                    x:0,
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(horizontalLine, {
                    attr: { x1: 0 }, 
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(shortOblique, {
                    attr: { x2: 32.56, y2:16.618 }, 
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });
    });
	
    /* Prev arrow animation */
    document.addEventListener("DOMContentLoaded", function () {
        // Select the SVG element
        const svgs = document.querySelectorAll(".icon-theme-prev-arrow");
    
        svgs.forEach((svg) => {
            // Find elements inside each SVG instance
            const circle = svg.querySelector(".circle");
            const shortOblique = svg.querySelector(".short-oblique");
            const linePath = svg.querySelectorAll(".line");
            const horizontalLine = svg.querySelector(".horizontal-line");
    
            svg.addEventListener("mouseenter", () => {
                gsap.to(circle, {
                    r: 20, // Increase circle size
                    x: -10, // Move left instead of right
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(linePath, {
                    x: -12, // Move left instead of right
                    duration: 0.3,
                    ease: "power2.out"
                });
    
                gsap.to(horizontalLine, {
                    attr: { x1: 39.733 },  // Move the horizontal line endpoint left
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(shortOblique, {
                    attr: { x2: 18.504, y2: 18.375 }, // Adjust arrow angle
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
    
            svg.addEventListener("mouseleave", () => {
                gsap.to(circle, {
                    r: 12.5, // Reset circle size
                    x: 0,
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(linePath, {
                    x: 0,
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(horizontalLine, {
                    attr: { x1: 48 },  // Reset line position
                    duration: 0.3,
                    ease: "power2.out"
                });
                gsap.to(shortOblique, {
                    attr: { x2: 15.44, y2: 16.618 },  // Reset arrow position
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });
    });
    

    // Trigger overlay search form
    $(document).ready(function() {
        var toggleSearchBox = $('.toggle-search-box');
        var searchOverlay = $('.searchform-overlay-wrapper');
        var closeOverlay = $('.close-overlay');
        var searchForm = searchOverlay.find('form');

        // Listen for clicks on the toggle search box trigger
        if (toggleSearchBox.length && searchOverlay.length) {
            toggleSearchBox.on('click', function() {
                searchOverlay.toggleClass('is-visible');
            });
        }

        // Listen for clicks on the close overlay link
        if (closeOverlay.length && searchOverlay.length) {
            closeOverlay.on('click', function() {
                searchOverlay.removeClass('is-visible');
            });
        }

        // Prevent clicks inside the form from closing the overlay
        if (searchOverlay.length) {
            searchOverlay.on('click', function(event) {
                event.stopPropagation();
            });

            searchForm.on('click', function(event) {
                event.stopPropagation();
            });
        }
    });

    $(window).on("elementor/frontend/init", function() {
        // CartDrawer
        var cartDrawerToggle = function($scope, $) {
            $scope.find('.cart-drawer-widget').each(function() {
                var selector = $(this),
                    toggle = selector.find('#cart-drawer-trigger'),
                    overlay = selector.find('#panelOverlay'),
                    close = selector.find('#closeDrawerbtn'),
                    wrapper = selector.find('#cartDrawer');
                toggle.on('click', function(e) {
                    e.preventDefault();
                    overlay.toggleClass('open');
                    wrapper.toggleClass('open');
                });
                overlay.on('click', function(e) {
                    overlay.toggleClass('open');
                    wrapper.removeClass('open');
                });
                close.on('click', function(e) {
                    overlay.toggleClass('open');
                    wrapper.removeClass('open');
                });
            });
        };

        elementorFrontend.hooks.addAction("frontend/element_ready/okthemes-cart-drawer-widget.default", cartDrawerToggle);
    });

})(jQuery);

