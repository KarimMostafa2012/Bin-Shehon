/**
 * File primary-navigation.js.
 *
 * Required to open and close the mobile navigation.
 */

 /**
 * traverses the DOM up to find elements matching the query
 *
 * @param {HTMLElement} target
 * @param {string} query
 * @return {NodeList} parents matching query
 */
function OrologioFindParents( target, query ) {
	var parents = [];

	// recursively go up the DOM adding matches to the parents array
	function traverse( item ) {
		var parent = item.parentNode;
		if ( parent instanceof HTMLElement ) {
			if ( parent.matches( query ) ) {
				parents.push( parent );
			}
			traverse( parent );
		}
	}

	traverse( target );

	return parents;
}

/**
 * Toggle an attribute's value
 *
 * @param {Element} el - The element.
 * @param {boolean} withListeners - Whether we want to add/remove listeners or not.
 * @since 1.0.0
 */
function OrologioToggleAriaExpanded( el, withListeners ) {
	if ( 'true' !== el.getAttribute( 'aria-expanded' ) ) {
		el.setAttribute( 'aria-expanded', 'true' );
		if ( withListeners ) {
			document.addEventListener( 'click', OrologioCollapseMenuOnClickOutside );
		}
	} else {
		el.setAttribute( 'aria-expanded', 'false' );
		if ( withListeners ) {
			document.removeEventListener( 'click', OrologioCollapseMenuOnClickOutside );
		}
	}
}

function OrologioCollapseMenuOnClickOutside( event ) {
	if ( ! document.getElementById( 'main-navbar' ).contains( event.target ) ) {
		document.getElementById( 'main-navbar' ).querySelectorAll( '.sub-menu-toggle' ).forEach( function( button ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} );
	}
}

/**
 * Handle clicks on submenu toggles.
 *
 * @param {Element} el - The element.
 */
function OrologioExpandSubMenu(el) { // jshint ignore:line

	// Toggle aria-expanded on the button.
	OrologioToggleAriaExpanded( el, true );

	// On tab-away collapse the menu.
	el.parentNode.querySelectorAll( 'ul > li:last-child > a' ).forEach( function( linkEl ) {
		linkEl.addEventListener( 'blur', function( event ) {
			if ( ! el.parentNode.contains( event.relatedTarget ) ) {
				el.setAttribute( 'aria-expanded', 'false' );
			}
		} );
	} );
}

( function() {
	/**
	 * Menu Toggle Behaviors
	 *
	 * @param {string} id - The ID.
	 */
	var navMenu = function( id ) {
		var wrapper = document.body, // this is the element to which a CSS class is added when a mobile nav menu is open
			mobileButton = document.getElementById( id + '-mobile-menu' );

		if ( mobileButton ) {
			mobileButton.onclick = function() {
				wrapper.classList.toggle( id + '-navigation-open' );
				wrapper.classList.toggle( 'lock-scrolling' );
				OrologioToggleAriaExpanded( mobileButton );
				mobileButton.focus();
			};
		}

	};

	window.addEventListener( 'load', function() {
		new navMenu( 'primary' );
	} );
}() );


/**
 * Vertical Navigation Script
 * 
 * Handle vertical navigation menus in both regular navigation and popups.
 * For vertical menus, this enables clickable submenu toggles similar to the mobile menu behavior.
 */
document.addEventListener("DOMContentLoaded", function() {
    initVerticalNavigation();
});

function initVerticalNavigation() {
    // Find all vertical menus
    const verticalMenus = document.querySelectorAll('.main-menu.vertical');
    
    if (verticalMenus.length === 0) return;
    
    verticalMenus.forEach(menu => {
        // For each vertical menu, find all items with submenu
        const menuItemsWithChildren = menu.querySelectorAll('li.menu-item-has-children, li.page_item_has_children');
        
        menuItemsWithChildren.forEach(item => {
            // Get or create the submenu toggle button
            let subMenuToggle = item.querySelector('.sub-menu-toggle');
            
            // If no toggle exists (likely in desktop view), create one
            if (!subMenuToggle) {
                // Create toggle button
                subMenuToggle = document.createElement('button');
                subMenuToggle.className = 'sub-menu-toggle';
                subMenuToggle.setAttribute('aria-expanded', 'false');
                
                // Add SVG icons
                subMenuToggle.innerHTML = `
                    <span class="icon-plus">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M18 11H13V6C13 5.45 12.55 5 12 5C11.45 5 11 5.45 11 6V11H6C5.45 11 5 11.45 5 12C5 12.55 5.45 13 6 13H11V18C11 18.55 11.45 19 12 19C12.55 19 13 18.55 13 18V13H18C18.55 13 19 12.55 19 12C19 11.45 18.55 11 18 11Z"/>
                        </svg>
                    </span>
                    <span class="icon-minus">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M18 11H6C5.45 11 5 11.45 5 12C5 12.55 5.45 13 6 13H18C18.55 13 19 12.55 19 12C19 11.45 18.55 11 18 11Z"/>
                        </svg>
                    </span>
                `;
                
                // Add to DOM
                item.appendChild(subMenuToggle);
            }
            
            // Make sure the toggle is displayed in vertical menus
            subMenuToggle.style.display = 'block';
            
            // Prevent default link behavior for parent items with submenus in vertical menus
            const parentLink = item.querySelector('a');
            if (parentLink) {
                parentLink.addEventListener('click', function(e) {
                    // Only prevent default if it's a vertical menu
                    if (isVerticalMenu(this)) {
                        e.preventDefault();
                        const toggle = this.parentNode.querySelector('.sub-menu-toggle');
                        if (toggle) {
                            OrologioExpandSubMenu(toggle);
                        }
                    }
                });
            }
            
            // Handle click on submenu toggle
            subMenuToggle.addEventListener('click', function() {
                OrologioExpandSubMenu(this);
            });
        });
    });

    // Handle clicks inside popups with vertical menus
    const popupTriggers = document.querySelectorAll('.popup-trigger');
    if (popupTriggers) {
        popupTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                // After popup opens, initialize vertical menus inside it (with slight delay to ensure DOM is ready)
                setTimeout(() => {
                    const popupId = this.getAttribute('data-popup');
                    if (popupId) {
                        const popupElement = document.getElementById(popupId.replace('popup-', 'post-'));
                        if (popupElement) {
                            const popupVerticalMenus = popupElement.querySelectorAll('.main-menu.vertical');
                            if (popupVerticalMenus.length) {
                                // Force display for popup vertical menus
                                popupVerticalMenus.forEach(menu => {
                                    menu.style.display = 'block';
                                });
                            }
                        }
                    }
                }, 100);
            });
        });
    }
}

// Helper function to check if an element is in a vertical menu
function isVerticalMenu(element) {
    const parents = OrologioFindParents(element, '.main-menu');
    return parents.some(parent => parent.classList.contains('vertical'));
}

// Add function to CSS class manipulation for vertical menu state
function setVerticalMenuState(menu, isOpen) {
    const parentNav = OrologioFindParents(menu, '.main-navigation-wrapper')[0];
    if (parentNav) {
        if (isOpen) {
            parentNav.classList.add('vertical-navigation-open');
        } else {
            parentNav.classList.remove('vertical-navigation-open');
        }
    }
}