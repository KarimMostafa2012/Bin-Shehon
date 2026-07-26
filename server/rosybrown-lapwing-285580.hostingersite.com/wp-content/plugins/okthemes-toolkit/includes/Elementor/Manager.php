<?php
/**
 * OKThemes Toolkit Elementor Manager
 *
 * @package OKThemes\Toolkit\Elementor
 */

namespace OKThemes\Toolkit\Elementor;

defined('ABSPATH') || exit;

use Elementor\Plugin as Elementor;

final class Manager {

    private $dir_path;
    private $modules_manager;

    public static function init() {
        static $instance = null;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }

    private function __construct() {
        $this->dir_path = plugin_dir_path(__FILE__);

        // Initialize modules manager
        $this->modules_manager = new ModulesManager();

        if ( did_action( 'elementor/init' ) ) {
            // Late init: Elementor already fully loaded, call everything directly.
            $this->override_categories( \Elementor\Plugin::$instance->elements_manager );
            $this->init_widgets();
            $this->elementor_init();
        } else {
            // Normal flow: hook directly onto the right actions.
            // These fire inside Elementor's init_components(), BEFORE elementor/init.
            add_action( 'elementor/elements/categories_registered', [ $this, 'override_categories' ] );
            add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
            // elementor/init fires after init_components() — use it for injections/helpers.
            add_action( 'elementor/init', [ $this, 'elementor_init' ] );
        }
        $this->register_assets_hooks();
    }

	

    public function elementor_init() {
        // Note: override_categories and init_widgets are hooked directly in __construct().
        // This method handles injections and helpers only.

        // Initialize Custom Modules using new ModulesManager
        // Modules are automatically initialized in ModulesManager constructor        
        //Initialize Injections
        require $this->dir_path .'injections/button-widget.php';
        require $this->dir_path .'injections/attributes.php';

        //Initialize Utils
        require $this->dir_path .'Helpers/Utils.php';
        require $this->dir_path .'Helpers/Posts_Helper.php';
        require $this->dir_path .'Helpers/Products.php';
    }

    /**
     * Override Elementor categories
     */
    public function override_categories($elements_manager) {
        $elements_manager->add_category(
            'okthemes_elements',
            [
                'title' => esc_html__('OKThemes Elements', 'okthemes-toolkit'),
                'icon'  => 'fa fa-smile-o',
            ]
        );
    }

    /**
     * Auto-register Elementor widgets
     */
    public function init_widgets() {
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

		$widgets = [

			\OKThemes\Toolkit\Elementor\Widgets\Navigation::class,
			\OKThemes\Toolkit\Elementor\Widgets\SiteLogo::class,
			\OKThemes\Toolkit\Elementor\Widgets\Search::class,

			\OKThemes\Toolkit\Elementor\Widgets\AdvancedHeading::class,
			\OKThemes\Toolkit\Elementor\Widgets\DualHeading::class,
			\OKThemes\Toolkit\Elementor\Widgets\PriceList::class,
			\OKThemes\Toolkit\Elementor\Widgets\PlayVideo::class,
			\OKThemes\Toolkit\Elementor\Widgets\ScrollingText::class,
			\OKThemes\Toolkit\Elementor\Widgets\LayeredImages::class,
			\OKThemes\Toolkit\Elementor\Widgets\ContentCarousel::class,
			\OKThemes\Toolkit\Elementor\Widgets\TestimonialsCarousel::class,
			\OKThemes\Toolkit\Elementor\Widgets\SimpleLink::class,
			\OKThemes\Toolkit\Elementor\Widgets\PopupTrigger::class,
			\OKThemes\Toolkit\Elementor\Widgets\ParallaxSection::class,
			\OKThemes\Toolkit\Elementor\Widgets\ImagesListHover::class,
			\OKThemes\Toolkit\Elementor\Widgets\ImagesMarquee::class,
			\OKThemes\Toolkit\Elementor\Widgets\FeaturedImageText::class,
			\OKThemes\Toolkit\Elementor\Widgets\PricingTable::class,
			\OKThemes\Toolkit\Elementor\Widgets\Posts::class,
			\OKThemes\Toolkit\Elementor\Widgets\Lottie::class,
			\OKThemes\Toolkit\Elementor\Widgets\Gallery::class,
			\OKThemes\Toolkit\Elementor\Widgets\Particles::class,

			// WooCommerce widgets (conditionally)
			\OKThemes\Toolkit\Elementor\Widgets\WooCommerce\CartDrawer::class,
			\OKThemes\Toolkit\Elementor\Widgets\WooCommerce\MyAccount::class,
			\OKThemes\Toolkit\Elementor\Widgets\WooCommerce\Products::class,

			// Villenoir-specific widgets — only registered when the Villenoir theme is active.
			...( 'villenoir' === get_template() ? [
				\OKThemes\Toolkit\Elementor\Widgets\VillenoirNavigation::class,
				\OKThemes\Toolkit\Elementor\Widgets\VillenoirMyAccount::class,
				\OKThemes\Toolkit\Elementor\Widgets\VillenoirSearch::class,
				\OKThemes\Toolkit\Elementor\Widgets\VillenoirMinicart::class,
			] : [] ),
		];

		foreach ( $widgets as $class ) {
			// Optional: skip WooCommerce widgets if WC is inactive
			if (
				strpos( $class, 'WooCommerce' ) !== false &&
				! class_exists( 'WooCommerce' )
			) {
				continue;
			}

			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}



    public function register_assets_hooks() {
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_styles']);
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_module_scripts' ] );
		add_action( 'admin_init', [ $this, 'invalidate_elementor_assets_cache_on_update' ] );
    }

    public function register_scripts() {
		//Vendor scripts
		wp_register_script( 'okthemes-swiper', OKT_URL . 'assets/js/vendor/swiper-bundle.min.js', [], OKT_VERSION, true );
		wp_register_script( 'okthemes-lightbox', OKT_URL . 'assets/js/vendor/glightbox.min.js', [], OKT_VERSION, true );
		wp_register_script( 'okthemes-gsap', OKT_URL . 'assets/js/vendor/gsap.min.js', [], OKT_VERSION, true );
		wp_register_script( 'okthemes-packery', OKT_URL . 'assets/js/vendor/packery.js', [], OKT_VERSION, true );
		wp_register_script( 'okthemes-object-fit', OKT_URL . 'assets/js/vendor/object-fit.js', [], OKT_VERSION, true );

		wp_register_script( 'okthemes-sticky', OKT_URL . 'includes/Elementor/assets/js/sticky/sticky.js', [ 'elementor-frontend', 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-sticky-el', OKT_URL . 'includes/Elementor/assets/js/sticky/sticky-el.js', [ 'elementor-frontend', 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-webpack-runtime', OKT_URL . 'includes/Elementor/assets/js/motion-fx/build/runtime.bundle.js', [], OKT_VERSION, true );
		wp_register_script( 'okthemes-motion-fx', OKT_URL . 'includes/Elementor/assets/js/motion-fx/build/motion-fx.bundle.js', [ 'okthemes-webpack-runtime', 'elementor-frontend-modules', 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-search-widget', OKT_URL . 'includes/Elementor/assets/js/search-widget.js', [ 'elementor-frontend', 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-cart-drawer', OKT_URL . 'includes/Elementor/assets/js/cart-drawer.js', [ 'elementor-frontend', 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-play-video', OKT_URL . 'includes/Elementor/assets/js/play-video.js', [ 'jquery', 'okthemes-lightbox' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-scrolling-text', OKT_URL . 'includes/Elementor/assets/js/scrolling-text.js', [ 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-images-list-hover', OKT_URL . 'includes/Elementor/assets/js/images-list-hover.js', [ 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-content-carousel', OKT_URL . 'includes/Elementor/assets/js/content-carousel.js', [ 'jquery', 'okthemes-swiper' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-testimonials-carousel', OKT_URL . 'includes/Elementor/assets/js/testimonials-carousel.js', [ 'jquery', 'okthemes-swiper' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-popup-trigger', OKT_URL . 'includes/Elementor/assets/js/popup-trigger.js', [ 'jquery', 'okthemes-gsap' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-parallax-section', OKT_URL . 'includes/Elementor/assets/js/parallax-section.js', [ 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-layered-images', OKT_URL . 'includes/Elementor/assets/js/layered-images.js', [ 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-products-carousel', OKT_URL . 'includes/Elementor/assets/js/products-carousel.js', [ 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-masonry-metro', OKT_URL . 'includes/Elementor/assets/js/masonry-metro.js', [ 'jquery' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-lottie-lib', OKT_URL . 'assets/js/vendor/lottie.min.js', [], OKT_VERSION, true );
		wp_register_script( 'okthemes-lottie', OKT_URL . 'includes/Elementor/assets/js/lottie.js', [ 'okthemes-lottie-lib' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-gallery', OKT_URL . 'includes/Elementor/assets/js/gallery.js', [ 'imagesloaded', 'okthemes-packery', 'okthemes-lightbox' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-scroll-motion', OKT_URL . 'includes/Elementor/assets/js/scroll-motion.js', [ 'jquery', 'okthemes-gsap' ], OKT_VERSION, true );
		wp_register_script( 'okthemes-particles-lib', OKT_URL . 'assets/js/vendor/particles.min.js', [], '2.0.0', true );
		wp_register_script( 'okthemes-particles', OKT_URL . 'includes/Elementor/assets/js/particles.js', [ 'okthemes-particles-lib' ], OKT_VERSION, true );
	}
	
	public function register_styles() {
		//Vendor styles
		wp_register_style( 'okthemes-lightbox', OKT_URL . 'assets/css/vendor/glightbox.min.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-swiper', OKT_URL . 'assets/css/vendor/swiper-bundle.min.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-layout', OKT_URL . 'assets/css/layout.css', [], OKT_VERSION );

		wp_register_style( 'okthemes-search-widget', OKT_URL . 'includes/Elementor/assets/css/search-widget.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-site-logo', OKT_URL . 'includes/Elementor/assets/css/site-logo.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-cart-drawer', OKT_URL . 'includes/Elementor/assets/css/cart-drawer.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-price-list', OKT_URL . 'includes/Elementor/assets/css/price-list.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-play-video', OKT_URL . 'includes/Elementor/assets/css/play-video.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-pricing-table', OKT_URL . 'includes/Elementor/assets/css/pricing-table.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-advanced-heading', OKT_URL . 'includes/Elementor/assets/css/advanced-heading.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-dual-heading', OKT_URL . 'includes/Elementor/assets/css/dual-heading.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-simple-link', OKT_URL . 'includes/Elementor/assets/css/simple-link.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-scrolling-text', OKT_URL . 'includes/Elementor/assets/css/scrolling-text.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-images-list-hover', OKT_URL . 'includes/Elementor/assets/css/images-list-hover.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-content-carousel', OKT_URL . 'includes/Elementor/assets/css/content-carousel.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-testimonials-carousel', OKT_URL . 'includes/Elementor/assets/css/testimonials-carousel.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-popup-trigger', OKT_URL . 'includes/Elementor/assets/css/popup-trigger.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-parallax-section', OKT_URL . 'includes/Elementor/assets/css/parallax-section.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-layered-images', OKT_URL . 'includes/Elementor/assets/css/layered-images.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-images-marquee', OKT_URL . 'includes/Elementor/assets/css/images-marquee.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-featured-image-text', OKT_URL . 'includes/Elementor/assets/css/featured-image-text.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-posts', OKT_URL . 'includes/Elementor/assets/css/posts.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-lottie', OKT_URL . 'includes/Elementor/assets/css/lottie.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-gallery', OKT_URL . 'includes/Elementor/assets/css/gallery.css', [], OKT_VERSION );
		wp_register_style( 'okthemes-particles', OKT_URL . 'includes/Elementor/assets/css/particles.css', [], OKT_VERSION );
	}

	public function enqueue_module_scripts() {
		wp_enqueue_script('okthemes-sticky');
		wp_enqueue_script( 'okthemes-sticky-el' );
		wp_enqueue_script('okthemes-webpack-runtime');
		wp_enqueue_script('okthemes-motion-fx');
		wp_enqueue_script('okthemes-scroll-motion');
	}

	/**
	 * When the plugin version changes, clear Elementor's _elementor_page_assets
	 * post meta so it rebuilds the widget asset dependency cache on next render.
	 * Without this, header/footer templates saved before a plugin update won't
	 * include our new or changed widget style/script handles.
	 */
	public function invalidate_elementor_assets_cache_on_update() {
		$option_key = 'okt_plugin_version_cache';
		if ( get_option( $option_key ) !== OKT_VERSION ) {
			delete_post_meta_by_key( '_elementor_page_assets' );
			update_option( $option_key, OKT_VERSION, false );
		}
	}

}
