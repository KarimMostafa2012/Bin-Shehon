<?php
/**
 * Product attributes
 *
 * This template overrides WooCommerce's default attributes table so the
 * product details ACF field can render below the attributes table.
 *
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$orologio_details_value = '';

if ( $product && function_exists( 'get_field' ) && function_exists( 'orologio_format_acf_product_value' ) ) {
	$orologio_details_value = orologio_format_acf_product_value( get_field( 'details', $product->get_id() ) );
}

if ( ! $product_attributes && '' === $orologio_details_value ) {
	return;
}
?>
<?php if ( $product_attributes ) : ?>
	<table class="woocommerce-product-attributes shop_attributes" aria-label="<?php esc_attr_e( 'Product Details', 'woocommerce' ); ?>">
	<?php foreach ( $product_attributes as $product_attribute_key => $product_attribute ) : ?>
		<tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--<?php echo esc_attr( $product_attribute_key ); ?>">
			<th class="woocommerce-product-attributes-item__label" scope="row"><?php echo wp_kses_post( $product_attribute['label'] ); ?></th>
			<td class="woocommerce-product-attributes-item__value"><?php echo wp_kses_post( $product_attribute['value'] ); ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

<?php if ( '' !== $orologio_details_value ) : ?>
	<div class="orologio-product-details-acf"><?php echo wp_kses_post( $orologio_details_value ); ?></div>
<?php endif; ?>
