<?php

/**
 * Plugin Name: Bin Shihon Tire Viewer
 * Description: Builds a Three.js tire viewer from front, back, and side tire images for WooCommerce products.
 * Version: 1.0.8
 * Author: Bin Shihon
 * Text Domain: binshihon-tire-viewer
 */

if (! defined('ABSPATH')) {
	exit;
}

define('BTV_PATH', plugin_dir_path(__FILE__));
define('BTV_URL', plugin_dir_url(__FILE__));
define('BTV_VERSION', '1.0.8');

add_action('acf/init', 'btv_register_acf_fields');
add_shortcode('binshihon_tire_viewer', 'btv_tire_viewer_shortcode');
add_shortcode('bst_tire_viewer', 'btv_tire_viewer_shortcode');
add_action('woocommerce_product_thumbnails', 'btv_render_gallery_tire_viewer', 30);
add_action('wp_enqueue_scripts', 'btv_register_assets');

/**
 * Register the product image fields used by the viewer.
 */
function btv_register_acf_fields()
{
	if (! function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_btv_tire_viewer',
			'title'    => __('3D Tire Viewer', 'binshihon-tire-viewer'),
			'fields'   => array(
				array(
					'key'           => 'field_btv_tire_front_image',
					'label'         => __('Front Tire Image', 'binshihon-tire-viewer'),
					'name'          => 'btv_tire_front_image',
					'type'          => 'image',
					'instructions'  => __('Transparent PNG/WebP is best.', 'binshihon-tire-viewer'),
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'           => 'field_btv_tire_back_image',
					'label'         => __('Back Tire Image', 'binshihon-tire-viewer'),
					'name'          => 'btv_tire_back_image',
					'type'          => 'image',
					'instructions'  => __('If empty, the front image is reused.', 'binshihon-tire-viewer'),
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'           => 'field_btv_tire_side_image',
					'label'         => __('Side / Tread Tire Image', 'binshihon-tire-viewer'),
					'name'          => 'btv_tire_side_image',
					'type'          => 'image',
					'instructions'  => __('Use the tread/sidewall strip image that wraps around the tire.', 'binshihon-tire-viewer'),
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'product',
					),
				),
			),
		)
	);
}

/**
 * Register frontend assets.
 */
function btv_register_assets()
{
	wp_register_style(
		'binshihon-tire-viewer',
		BTV_URL . 'assets/css/tire-viewer.css',
		array(),
		BTV_VERSION
	);

	wp_register_script(
		'binshihon-tire-viewer',
		BTV_URL . 'assets/js/tire-viewer.js',
		array(),
		BTV_VERSION,
		true
	);

	wp_add_inline_script(
		'binshihon-tire-viewer',
		'window.BinShihonTireViewer = window.BinShihonTireViewer || ' . wp_json_encode(
			array(
				'threeUrl' => BTV_URL . 'assets/js/vendor/three.module.js',
			)
		) . ';',
		'before'
	);
}

/**
 * Render the manual viewer as a real WooCommerce gallery slide.
 */
function btv_render_gallery_tire_viewer()
{
	if (! function_exists('is_product') || ! is_product()) {
		return;
	}

	$product_id = get_the_ID();

	if (! btv_product_has_tire_images($product_id)) {
		return;
	}

	$images        = btv_get_product_tire_images($product_id);
	$thumbnail_src = $images['front'];
	$alt_text      = get_the_title($product_id) . ' 3D';

	echo '<div data-thumb="' . esc_url($thumbnail_src) . '" data-thumb-alt="' . esc_attr($alt_text) . '" class="woocommerce-product-gallery__image btv-woocommerce-gallery-slide">';
	echo btv_render_tire_viewer(
		array(
			'product_id' => $product_id,
			'mode'       => 'manual',
			'auto'       => 'no',
			'context'    => 'gallery',
		)
	);
	echo '</div>';
}

/**
 * Shortcode: [binshihon_tire_viewer product_id="123" mode="scroll" auto="no"].
 */
function btv_tire_viewer_shortcode($atts)
{
	$atts = shortcode_atts(
		array(
			'product_id' => get_the_ID(),
			'mode'       => 'scroll',
			'auto'       => 'no',
			'class'      => '',
			'context'    => 'shortcode',
		),
		$atts,
		'binshihon_tire_viewer'
	);

	return btv_render_tire_viewer($atts);
}

/**
 * Render a configured viewer.
 */
function btv_render_tire_viewer($args)
{
	$product_id = isset($args['product_id']) ? absint($args['product_id']) : get_the_ID();

	if (! $product_id || ! btv_product_has_tire_images($product_id)) {
		return '';
	}

	$images = btv_get_product_tire_images($product_id);
	$mode   = isset($args['mode']) && 'manual' === $args['mode'] ? 'manual' : 'scroll';
	$auto   = ! empty($args['auto']) && in_array(strtolower((string) $args['auto']), array('1', 'yes', 'true'), true) ? 'yes' : 'no';
	$class  = isset($args['class']) ? sanitize_html_class($args['class']) : '';
	$uid    = 'btv-viewer-' . $product_id . '-' . wp_rand(1000, 9999);

	wp_enqueue_style('binshihon-tire-viewer');
	wp_enqueue_script('binshihon-tire-viewer');

	ob_start();
?>
	<div
		id="<?php echo esc_attr($uid); ?>"
		class="btv-tire-viewer btv-tire-viewer--<?php echo esc_attr($mode); ?> btv-tire-viewer--<?php echo esc_attr(isset($args['context']) ? sanitize_html_class($args['context']) : 'default'); ?> <?php echo esc_attr($class); ?>"
		data-btv-viewer
		data-mode="<?php echo esc_attr($mode); ?>"
		data-auto="<?php echo esc_attr($auto); ?>"
		data-front="<?php echo esc_url($images['front']); ?>"
		data-back="<?php echo esc_url($images['back']); ?>"
		data-side="<?php echo esc_url($images['side']); ?>"
		aria-label="<?php esc_attr_e('Interactive tire viewer', 'binshihon-tire-viewer'); ?>">
		<div class="black-bg"></div>
		<div class="btv-tire-viewer__stage" data-btv-stage></div>
		<div class="btv-tire-viewer__loading" data-btv-loading><?php esc_html_e('Loading tire...', 'binshihon-tire-viewer'); ?></div>
	</div>
<?php

	return ob_get_clean();
}

/**
 * Check whether a product has the minimum needed images.
 */
function btv_product_has_tire_images($product_id)
{
	$images = btv_get_product_tire_images($product_id);

	return ! empty($images['front']) && ! empty($images['side']);
}

/**
 * Resolve product tire images.
 */
function btv_get_product_tire_images($product_id)
{
	$front = btv_get_image_field_url($product_id, array('btv_tire_front_image', 'tire_front_image', 'front_tire_image'));
	$back  = btv_get_image_field_url($product_id, array('btv_tire_back_image', 'tire_back_image', 'back_tire_image'));
	$side  = btv_get_image_field_url($product_id, array('btv_tire_side_image', 'tire_side_image', 'side_tire_image', 'tire_tread_image'));

	return array(
		'front' => $front,
		'back'  => $back ? $back : $front,
		'side'  => $side,
	);
}

/**
 * Resolve an ACF/post meta image field to a usable URL.
 */
function btv_get_image_field_url($product_id, $field_names)
{
	foreach ($field_names as $field_name) {
		$value = null;

		if (function_exists('get_field')) {
			$value = get_field($field_name, $product_id);
		}

		if (empty($value)) {
			$value = get_post_meta($product_id, $field_name, true);
		}

		$url = btv_image_value_to_url($value);

		if ($url) {
			return $url;
		}
	}

	return '';
}

/**
 * Convert ACF image return formats into a URL.
 */
function btv_image_value_to_url($value)
{
	if (empty($value)) {
		return '';
	}

	if (is_numeric($value)) {
		$url = wp_get_attachment_image_url(absint($value), 'full');
		return $url ? $url : '';
	}

	if (is_array($value)) {
		if (! empty($value['url'])) {
			return esc_url_raw($value['url']);
		}

		if (! empty($value['ID'])) {
			$url = wp_get_attachment_image_url(absint($value['ID']), 'full');
			return $url ? $url : '';
		}
	}

	if (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
		return esc_url_raw($value);
	}

	return '';
}
