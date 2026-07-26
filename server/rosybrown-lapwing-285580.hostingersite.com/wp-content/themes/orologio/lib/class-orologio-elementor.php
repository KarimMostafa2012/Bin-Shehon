<?php
/**
 * Elementor Compatibility File.
 *
 * @package Orologio
 */

// If plugin - 'Elementor' not exist then return.
if ( ! class_exists( '\Elementor\Plugin' ) ) {
	return;
}

use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager;
use ElementorPro\Modules\ThemeBuilder\Module;
use Elementor\Plugin;
use OrologioTheme\Classes\Orologio_Helper;
use OrologioTheme\Classes\Orologio_Page_Header_Typography;

/**
 * Orologio Elementor Compatibility
 */
if ( ! class_exists( 'Orologio_Elementor' ) ) :

	/**
	 * Orologio Elementor Compatibility
	 *
	 * @since 1.0.0
	 */
	class Orologio_Elementor {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'elementor_default_setting' ), 20 );
			add_action( 'elementor/preview/init', array( $this, 'elementor_default_setting' ) );
				
			add_action( 'wp_loaded', array( $this, 'sync_theme_colors_with_elementor_kit' ) );
			add_action( 'csf_orologio_options_saved', array( $this, 'sync_theme_colors_with_elementor_kit' ) );
		
			
			add_filter( 'elementor/document/save/data', function( $data, $document ) {
				$theme_colors = Orologio_Helper::get_global_colors();
				$orologio_options = get_option('orologio_options', []);
				$needs_update = false; // Flag to track changes
			
				// Synchronize Colors
				if (isset($data['settings'], $data['settings']['custom_colors'])) {
					foreach ($theme_colors as $key => $theme_color) {
						foreach ($data['settings']['custom_colors'] as $elementor_color) {
							if ($elementor_color['_id'] === $key && $elementor_color['color'] !== $theme_color['value']) {
								$orologio_options[$key] = $elementor_color['color'];
								$needs_update = true;
								break;
							}
						}
					}
				}
			
				// Synchronize Container Width
				if (isset($data['settings'], $data['settings']['container_width'])) {
					$elementor_container_width = $data['settings']['container_width']['size'] . $data['settings']['container_width']['unit'];
					if ($orologio_options['container_width']['number'] . $orologio_options['container_width']['unit'] !== $elementor_container_width) {
						$orologio_options['container_width']['number'] = $data['settings']['container_width']['size'];
						$orologio_options['container_width']['unit'] = $data['settings']['container_width']['unit'];
						$needs_update = true;
					}
				}
			
				// Update the theme options if changes were made
				if ($needs_update) {
					update_option('orologio_options', $orologio_options);
				}
			
				return $data;
			}, 10, 2 );
		
				
			//Elementor Pro
			if ( ! class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
				return;
			}
			// Add locations.
			add_action( 'elementor/theme/register_locations', array( $this, 'register_locations' ) );

			// Override theme templates.
			add_action( 'orologio_header', array( $this, 'do_header' ), 0 );
			add_action( 'orologio_footer', array( $this, 'do_footer' ), 0 );
		
		}

		public function sync_theme_colors_with_elementor_kit() {
			// Ensure Elementor is loaded
			if (!did_action('elementor/loaded')) {
				return;
			}
		
			$updates_made = false;
			$orologio_options = get_option('orologio_options', []);
			$theme_colors = Orologio_Helper::get_global_colors();
			
			// Make sure Elementor is fully initialized
			if (!class_exists('Elementor\Plugin') || !isset(Elementor\Plugin::$instance) || !Elementor\Plugin::$instance->kits_manager) {
				return;
			}
			
			$kit = Elementor\Plugin::$instance->kits_manager->get_active_kit();
			if (!$kit) {
				return;
			}
			
			$kit_settings = $kit->get_settings();
			
			// Theme container width check with robust validation
			$theme_container_width = '';
			if (isset($orologio_options['container_width']) && 
				is_array($orologio_options['container_width']) &&
				isset($orologio_options['container_width']['number']) && 
				isset($orologio_options['container_width']['unit'])) {
				$theme_container_width = $orologio_options['container_width']['number'] . $orologio_options['container_width']['unit'];
			}
			
			// Update theme colors
			$custom_colors = $kit->get_settings('custom_colors') ?: [];
			foreach ($theme_colors as $key => $color) {
				$color_exists = false;
		
				foreach ($custom_colors as $index => &$custom_color) {
					if ($custom_color['_id'] === $key) {
						$color_exists = true;
						
						if ($custom_color['color'] !== $color['value']) {
							$custom_color['color'] = $color['value'];
							$updates_made = true;
						}
						break;
					}
				}
		
				if (!$color_exists) {
					$custom_colors[] = [
						'_id' => $key,
						'title' => $color['title'],
						'color' => $color['value']
					];
					$updates_made = true;
				}
			}
		
			// Only update container width if we have valid theme settings and Elementor settings
			if (!empty($theme_container_width) && 
				!empty($kit_settings['container_width']) && 
				isset($kit_settings['container_width']['size']) &&
				isset($kit_settings['container_width']['unit']) &&
				$kit_settings['container_width']['size'] . $kit_settings['container_width']['unit'] !== $theme_container_width) {
				
				$kit_settings['container_width']['size'] = intval($orologio_options['container_width']['number']);
				$kit_settings['container_width']['unit'] = $orologio_options['container_width']['unit'];
				$updates_made = true;
			}
		
			// Apply updates if any were made
			if ($updates_made) {
				Elementor\Plugin::instance()->kits_manager->update_kit_settings_based_on_option('custom_colors', $custom_colors);
				
				// Only update container width if it exists and is valid
				if (!empty($theme_container_width) && isset($kit_settings['container_width'])) {
					Elementor\Plugin::instance()->kits_manager->update_kit_settings_based_on_option(
						'container_width', 
						$kit_settings['container_width']
					);
				}
				
				// Save the kit
				$kit->save([]);
			}
		}


		public function sync_theme_options_and_colors_with_elementor_kit() {
			$orologio_options = get_option('orologio_options', []);
			$theme_colors = Orologio_Helper::get_global_colors(); // Assuming this returns an array of your colors
			$kit = Elementor\Plugin::$instance->kits_manager->get_active_kit();

			if (!$kit) {
				return;
			}
		
			$updates_made = false; // Flag to track if any updates are made
		
			// Update theme colors
			$custom_colors = $kit->get_settings('custom_colors') ?: [];
			foreach ($theme_colors as $key => $color) {
				$color_exists = false;
		
				foreach ($custom_colors as $index => &$custom_color) {
					if ($custom_color['_id'] === $key) {
						$color_exists = true;
						
						if ($custom_color['color'] !== $color['value']) {
							$custom_color['color'] = $color['value'];
							$updates_made = true;
						}
						break;
					}
				}
		
				if (!$color_exists) {
					$custom_colors[] = [
						'_id' => $key,
						'title' => $color['title'],
						'color' => $color['value']
					];
					$updates_made = true;
				}
			}
		
			if ($updates_made) {
				Elementor\Plugin::instance()->kits_manager->update_kit_settings_based_on_option('custom_colors', $custom_colors);
			}
		
			// Update typography settings
			$typography_elements = ['body', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
		
			foreach ($typography_elements as $element) {
				// Check if the typography setting exists in the theme options
				if (isset($orologio_options[$element . '_typography'])) {
					foreach ($orologio_options[$element . '_typography'] as $prop => $value) {
						$elementor_prop = $element . '_typography_' . str_replace('-', '_', $prop);
		
						// Special handling for font-family, text-transform, font-weight
						if (in_array($prop, ['font-family', 'text-transform', 'font-weight'])) {
							// Update the individual typography setting in the Elementor kit
							Elementor\Plugin::instance()->kits_manager->update_kit_settings_based_on_option($elementor_prop, $value);
							$updates_made = true;
						} elseif (in_array($prop, ['font-size', 'line-height', 'letter-spacing'])) {
							// Special handling for size properties with units
							$unit_key = $prop . '-unit';
							$elementor_prop_value = [
								'unit' => $orologio_options[$element . '_typography'][$unit_key] ?? 'px',
								'size' => $value,
								'sizes' => []
							];
		
							// Update the individual typography setting in the Elementor kit
							Elementor\Plugin::instance()->kits_manager->update_kit_settings_based_on_option($elementor_prop, $elementor_prop_value);
							$updates_made = true;
						} elseif ($prop === 'color') {
							// Handle the color property
							$color_prop = $element . '_color'; // Construct the color property name for Elementor
							Elementor\Plugin::instance()->kits_manager->update_kit_settings_based_on_option($color_prop, $value);
							$updates_made = true;
						}
						
					}
				}
			}
		
			// Save the kit if there were any updates
			if ($updates_made) {
				$kit->save([]);
			}
		}

		/**
		 * Elementor set content width
		 *
		 * @return void
		 * @since  1.0.2
		 */
		public function orologio_elementor_set_content_width() {
			return $GLOBALS['content_width'] = 1500;
		}
		

		/**
		 * Ensure pages edited with Elementor use the Elementor full width template
		 * This function sets the template when a page is first created or edited with Elementor
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function elementor_default_setting() {
			// Check if we're in Elementor editor (either admin or frontend)
			if (!$this->is_elementor_editor()) {
				return;
			}
			
			// Get current post
			global $post;
			if (!isset($post) || empty($post)) {
				return;
			}
			
			$post_id = $post->ID;
			
			// Check the current template
			$current_template = get_post_meta($post_id, '_wp_page_template', true);
			
			// Check if Elementor is being used for this post/page
			$is_built_with_elementor = Plugin::$instance->documents->get($post_id)->is_built_with_elementor();
			
			// If this is a new Elementor page or an existing page without the correct template
			if ($is_built_with_elementor && 
				($current_template === '' || $current_template === 'default')) {
				
				// Set the page template to Elementor fullwidth
				update_post_meta($post_id, '_wp_page_template', 'elementor_header_footer');
			}
		}

		/**
		 * Check if Elementor Editor is open.
		 *
		 * @since 1.2.7
		 *
		 * @return boolean True if Elementor Editor is loaded, False otherwise.
		 */
		private function is_elementor_editor() {
			// Check several ways Elementor might be active
			if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
				return true;
			}
			
			if (isset($_GET['elementor-preview'])) {
				return true;
			}
			
			// Safer way to check HTTP_REFERER
			$http_referer = filter_input(INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_URL);
			if ($http_referer && strpos($http_referer, 'elementor') !== false) {
				return true;
			}
			
			// Check if using Elementor in admin context
			if (is_admin() && isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] === 'edit') {
				$post_id = $_GET['post'];
				// Check if this post is built with Elementor
				if (get_post_meta($post_id, '_elementor_edit_mode', true) === 'builder') {
					return true;
				}
			}
			
			return false;
		}


		/**
		 * Check is elementor activated.
		 *
		 * @param int $id Post/Page Id.
		 * @return boolean
		 */
		public function is_elementor_activated( $id ) {

			$document = Plugin::$instance->documents->get( $id );
			if ( $document ) {
				return $document->is_built_with_elementor();
			} else {
				return false;
			}

		}


		/**
		 * Register Locations
		 *
		 * @since 1.2.7
		 * @param object $manager Location manager.
		 * @return void
		 */
		public function register_locations( $manager ) {
			$manager->register_all_core_location();
		}

		/**
		 * Header Support
		 *
		 * @since 1.2.7
		 * @return void
		 */
		public function do_header() {
			$did_location = Module::instance()->get_locations_manager()->do_location( 'header' );
			if ( $did_location ) {
				remove_all_actions( 'orologio_header' );
				//subheader
				remove_action( 'orologio_subheader', 'orologio_page_header', 0 );
			}
		}

		/**
		 * Footer Support
		 *
		 * @since 1.2.7
		 * @return void
		 */
		public function do_footer() {
			$did_location = Module::instance()->get_locations_manager()->do_location( 'footer' );
			if ( $did_location ) {
				remove_all_actions( 'orologio_footer' );
			}
		}
		
	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Orologio_Elementor::get_instance();
