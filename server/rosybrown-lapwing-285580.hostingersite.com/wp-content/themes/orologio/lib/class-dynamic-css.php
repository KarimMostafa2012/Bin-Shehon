<?php
use OrologioTheme\Classes\Orologio_Helper;

class Orologio_Dynamic_Css {
	/**
	 * CSS Cache Storage Key
	 */
	const CACHE_KEY = 'orologio_dynamic_css_cache';

	/**
	 * CSS Cache Expiration (in seconds)
	 * 86400 = 24 hours
	 */
	const CACHE_EXPIRATION = 86400;

	/**
	 * Register actions.
	 */
	public function __construct() {
		add_action('enqueue_block_editor_assets', array($this, 'enqueue'), 100);
		add_action('wp_enqueue_scripts', array($this, 'enqueue'), 100);
		add_action('csf_orologio_options_saved', array($this, 'clear_cache'));
		add_action('customize_save_after', array($this, 'clear_cache'));
	}

	/**
	 * Load frontend style.
	 */
	public function enqueue() {
		$is_for_gutenberg = (current_action() === 'enqueue_block_editor_assets');
		
		// Get CSS styles (from cache if available)
		$style = $this->get_dynamic_styles();
		
		// Add inline style
		wp_add_inline_style(
			$is_for_gutenberg ? 'orologio-editor-style' : 'orologio-style',
			$style
		);
	}

	/**
	 * Get all dynamic styles (with caching)
	 *
	 * @return string Compiled and minified CSS
	 */
	private function get_dynamic_styles() {
		// Check if we have cached CSS
		$cached_css = get_transient(self::CACHE_KEY);
		
		// Return cached CSS if available and not in customizer preview
		if ($cached_css !== false && !is_customize_preview()) {
			return $cached_css;
		}
		
		// Generate fresh CSS
		$style = '';
		$style .= self::get_root_css();
		$style = self::minify_css($style);
		
		// Cache the CSS (unless in customizer preview)
		if (!is_customize_preview()) {
			set_transient(self::CACHE_KEY, $style, self::CACHE_EXPIRATION);
		}
		
		return $style;
	}

	/**
	 * Clear CSS cache when theme options are updated
	 */
	public function clear_cache() {
		delete_transient(self::CACHE_KEY);
	}

	/**
	 * Basic CSS minification that preserves important styles.
	 *
	 * @param string $css The CSS to minify
	 * @return string Minified CSS
	 */
	public static function minify_css($css) {
		// Skip minification if the CSS is already quite small
		if (strlen($css) < 500) {
			return $css;
		}
		
		// Save some processing if this is already minified
		if (!preg_match('/[\n\r\t]/', $css)) {
			return $css;
		}
		
		// Remove comments
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
		
		// Remove whitespace around common symbols
		$css = preg_replace('/\s*([{}:;,])\s*/', '$1', $css);
		
		// Remove unnecessary semicolons
		$css = preg_replace('/;}/', '}', $css);
		
		// Remove line breaks, tabs, and extra spaces
		$css = preg_replace('/\s+/', ' ', $css);
		
		// Remove leading/trailing whitespace
		return trim($css);
	}

	/**
	 * Get root style (css variables)
	 *
	 * @return string
	 */
	public static function get_root_css() {
		$css = ':root{';
		$css .= self::get_css_vars();
		$css .= '}';
		
		$css .= apply_filters('orologio_after_css_root', $css);
		
		return $css;
	}

	/**
	 * Process typography styles and include units with specific naming
	 * 
	 * @param array $typography Typography settings
	 * @param string $prefix CSS variable prefix
	 * @param string $media_query Optional media query
	 * @return string Generated CSS
	 */
	public static function process_typography_styles($typography, $prefix, $media_query = '') {
		if (!is_array($typography) || empty($typography)) {
			return '';
		}
		
		$inline_css = '';
		$exclude_keys = ['type', 'unit'];
		
		foreach ($typography as $prop => $value) {
			if (!in_array($prop, $exclude_keys) && strpos($prop, '-unit') === false && $value !== '') {
				$unit_key = $prop . '-unit';
				$unit = isset($typography[$unit_key]) ? $typography[$unit_key] : '';
				
				$css_var_name = "--orologio-{$prefix}-typography-" . str_replace('_', '-', $prop);
				$inline_css .= "{$css_var_name}: {$value}{$unit}; ";
			}
		}
		
		if (!empty($media_query) && !empty($inline_css)) {
			$inline_css = "@media {$media_query} { {$inline_css} }";
		}
		
		return rtrim($inline_css);
	}

	/**
	 * Process padding styles with combined format
	 * 
	 * @param array $padding_values Padding settings
	 * @param string $prefix CSS variable prefix
	 * @param string $media_query Optional media query
	 * @return string Generated CSS
	 */
	public static function process_combined_padding_styles($padding_values, $prefix, $media_query = '') {
		if (!isset($padding_values['top'], $padding_values['right'], 
				   $padding_values['bottom'], $padding_values['left'], 
				   $padding_values['unit'])) {
			return '';
		}
		
		$unit = $padding_values['unit'];
		$padding_style = "{$padding_values['top']}{$unit} {$padding_values['right']}{$unit} " . 
						 "{$padding_values['bottom']}{$unit} {$padding_values['left']}{$unit}";
		$css_variable = "--orologio-{$prefix}-padding: {$padding_style};";
		
		if (!empty($media_query)) {
			return "@media {$media_query} { {$css_variable} }";
		}
		
		return $css_variable;
	}

	/**
	 * Process border styles
	 * 
	 * @param array $border_values Border settings
	 * @param string $prefix CSS variable prefix
	 * @param string $unit Unit for border values
	 * @return string Generated CSS
	 */
	public static function process_border_styles($border_values, $prefix, $unit = 'px') {
		if (!is_array($border_values)) {
			return '';
		}
		
		$inline_css = '';
		
		if (isset($border_values['top'], $border_values['right'], 
				  $border_values['bottom'], $border_values['left'])) {
			$border_widths = "{$border_values['top']}{$unit} {$border_values['right']}{$unit} " . 
							"{$border_values['bottom']}{$unit} {$border_values['left']}{$unit}";
			$inline_css .= "--orologio-{$prefix}-border: {$border_widths};";
		}
		
		if (isset($border_values['style'])) {
			$border_style = $border_values['style'];
			$inline_css .= "--orologio-{$prefix}-border-style: {$border_style};";
		}
		
		return $inline_css;
	}

	/**
	 * Process numeric element with media query
	 * 
	 * @param array $element Element settings
	 * @param string $prefix CSS variable prefix
	 * @param string $media_query Optional media query
	 * @return string Generated CSS
	 */
	public static function process_number_el_with_media_query($element, $prefix, $media_query = '') {
		if (!is_array($element) || !isset($element['number'])) {
			return '';
		}
		
		$value = $element['number'] . (isset($element['unit']) ? $element['unit'] : '');
		$css_var_name = "--orologio-{$prefix}";
		
		if (!empty($media_query)) {
			return "@media {$media_query} { {$css_var_name}: {$value}; }";
		}
		
		return "{$css_var_name}: {$value};";
	}

	/**
	 * Efficiently generate CSS variables
	 * 
	 * @return string CSS variables as a string
	 */
	public static function get_css_vars() {
		// Start with empty array to store CSS variables
		$inline_css = [];
		
		// Create a reusable function to add variable if it exists
		$add_if_exists = function($var, $prefix, $property, $suffix = '') use (&$inline_css) {
			if ($var) {
				$inline_css[] = "--orologio-{$prefix}-{$property}: {$var}{$suffix}";
			}
		};
		
		// Container width
		$container_width = Orologio_Helper::get_option('container_width');
		if (is_array($container_width) && isset($container_width['number'])) {
			$inline_css[] = '--orologio-container-width: ' . $container_width['number'] . $container_width['unit'];
		}

		// Header Styling
		$menu_item_color = Orologio_Helper::get_option('menu_item_color');
		$menu_item_hover_color = Orologio_Helper::get_option('menu_item_hover_color');
		
		$add_if_exists($menu_item_color, 'menu-item', 'color');
		$add_if_exists($menu_item_hover_color, 'menu-item-hover', 'color');

		// Menu Typography
		$menu_typography = Orologio_Helper::get_option('menu_typography');
		$menu_typography_css = self::process_typography_styles($menu_typography, 'menu');
		if ($menu_typography_css) {
			$inline_css[] = $menu_typography_css;
		}

		// Submenu Styling
		$submenu_bg = Orologio_Helper::get_option('submenu_bg');
		$submenu_item_color = Orologio_Helper::get_option('submenu_item_color');
		$submenu_item_hover_color = Orologio_Helper::get_option('submenu_item_hover_color');
		
		$add_if_exists($submenu_bg, 'submenu', 'bg');
		$add_if_exists($submenu_item_color, 'submenu-item', 'color');
		$add_if_exists($submenu_item_hover_color, 'submenu-item-hover', 'color');

		// Mobile Background
		$mobile_bg = Orologio_Helper::get_option('mobile_bg');
		$add_if_exists($mobile_bg, 'mobile', 'bg');

		// SubMenu Typography
		$submenu_typography = Orologio_Helper::get_option('submenu_typography');
		$submenu_typography_css = self::process_typography_styles($submenu_typography, 'submenu');
		if ($submenu_typography_css) {
			$inline_css[] = $submenu_typography_css;
		}

		// Page header height
		$page_header_min_height = Orologio_Helper::get_option('page_header_min_height');
		if (is_array($page_header_min_height) && isset($page_header_min_height['height'])) {
			$inline_css[] = '--orologio-page-header-min-height: ' . $page_header_min_height['height'] . $page_header_min_height['unit'];
		}

		// Page header padding
		$page_header_padding = Orologio_Helper::get_option('page_header_padding');
		$page_header_padding_css = self::process_combined_padding_styles($page_header_padding, 'page-header');
		if ($page_header_padding_css) {
			$inline_css[] = $page_header_padding_css;
		}

		// Page header background
		$page_header_bg = Orologio_Helper::get_option('page_header_bg');
		$add_if_exists($page_header_bg, 'page-header-bg', 'color');

		// Sidebar width
		$sidebar_width = Orologio_Helper::get_option('sidebar_width');
		if (is_array($sidebar_width) && isset($sidebar_width['width'])) {
			$inline_css[] = '--orologio-sidebar-width: ' . $sidebar_width['width'] . '%';
		}

		// Typography - Process all base typography settings
		$typography_elements = [
			'body', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
		];
		
		$breakpoints = [
			'' => '',
			'_tablet' => '(min-width: 768px) and (max-width: 1023px)',
			'_mobile' => '(max-width: 767px)'
		];
		
		foreach ($typography_elements as $element) {
			foreach ($breakpoints as $suffix => $media_query) {
				$option_key = $element . '_typography' . $suffix;
				$typography = Orologio_Helper::get_option($option_key);
				$typography_css = self::process_typography_styles($typography, $element, $media_query);
				if ($typography_css) {
					$inline_css[] = $typography_css;
				}
			}
		}

		// Paragraph spacing with responsive variations
		$spacing_elements = ['paragraph_spacing', 'paragraph_spacing_tablet', 'paragraph_spacing_mobile'];
		$spacing_media_queries = [
			'paragraph_spacing' => '',
			'paragraph_spacing_tablet' => '(min-width: 768px) and (max-width: 1023px)',
			'paragraph_spacing_mobile' => '(max-width: 767px)'
		];
		
		foreach ($spacing_elements as $element) {
			$spacing = Orologio_Helper::get_option($element);
			$spacing_css = self::process_number_el_with_media_query(
				$spacing, 
				'paragraph-spacing', 
				$spacing_media_queries[$element]
			);
			if ($spacing_css) {
				$inline_css[] = $spacing_css;
			}
		}

		// Buttons
		// Color
		$buttons_text_color = Orologio_Helper::get_option('buttons_text_color');
		if (is_array($buttons_text_color)) {
			$add_if_exists($buttons_text_color['color'], 'buttons', 'color');
			$add_if_exists($buttons_text_color['hover'], 'buttons-color', 'hover');
		}

		// Background Color
		$buttons_background_color = Orologio_Helper::get_option('buttons_background_color');
		if (is_array($buttons_background_color)) {
			$add_if_exists($buttons_background_color['color'], 'buttons-bg', 'color');
			$add_if_exists($buttons_background_color['hover'], 'buttons-bg-color', 'hover');
		}

		// Border
		$buttons_border = Orologio_Helper::get_option('buttons_border');
		$buttons_border_css = self::process_border_styles($buttons_border, 'buttons', 'px');
		if ($buttons_border_css) {
			$inline_css[] = $buttons_border_css;
		}

		// Border Color
		$buttons_border_color = Orologio_Helper::get_option('buttons_border_color');
		if (is_array($buttons_border_color)) {
			$add_if_exists($buttons_border_color['color'], 'buttons-border', 'color');
			$add_if_exists($buttons_border_color['hover'], 'buttons-border-color', 'hover');
		}

		// Border radius
		$buttons_border_radius = Orologio_Helper::get_option('buttons_border_radius');
		if (is_array($buttons_border_radius) && isset($buttons_border_radius['number'])) {
			$inline_css[] = '--orologio-buttons-border-radius: ' . $buttons_border_radius['number'] . $buttons_border_radius['unit'];
		}

		// Typography
		foreach ($breakpoints as $suffix => $media_query) {
			$option_key = 'buttons_typography' . $suffix;
			$typography = Orologio_Helper::get_option($option_key);
			$typography_css = self::process_typography_styles($typography, 'buttons', $media_query);
			if ($typography_css) {
				$inline_css[] = $typography_css;
			}
		}

		// Buttons spacing
		foreach ($breakpoints as $suffix => $media_query) {
			$option_key = 'buttons_spacing' . $suffix;
			$spacing = Orologio_Helper::get_option($option_key);
			$spacing_css = self::process_combined_padding_styles($spacing, 'buttons', $media_query);
			if ($spacing_css) {
				$inline_css[] = $spacing_css;
			}
		}

		// Colors
		$colors = Orologio_Helper::get_global_colors();
		foreach ($colors as $key => $color) {
			if (isset($color['slug']) && isset($color['value'])) {
				$inline_css[] = '--' . $color['slug'] . ':' . $color['value'];
			}
		}

		// Elementor compatibility
		if (did_action('elementor/loaded')) {
			foreach ($colors as $key => $color) {
				if (isset($key) && isset($color['value'])) {
					$inline_css[] = '--e-global-color-' . $key . ':' . $color['value'];
				}
			}
		}

		// Join all CSS variables with semicolons
		$output = esc_attr(implode('; ', array_filter($inline_css)) . ';');
		
		return $output;
	}
}

return new Orologio_Dynamic_Css();