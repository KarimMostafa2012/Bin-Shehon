<?php

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

	<style>
		.elementor-add-section:not(.elementor-dragging-on-child) .elementor-add-section-inner {
			background-color: #fff;
		}
	</style>
</head>
<body <?php body_class(); ?>>

<?php
	get_header();

	$meta  = get_post_meta( get_the_ID(), 'okthemes_tb_settings', true );
	$width = 420;

	if ( isset( $meta['offcanvas_width']['width'] ) ) {
		$width = $meta['offcanvas_width']['width'];
	}
	?>

	<div class="okthemes-offcanvas-wrapper">
		<div class="offcanvas-overly"></div>
		<div class="offcanvas-container" style="width: <?php echo esc_attr( $width ); ?>px;">
			<div class="offcanvas-close"><i class="fal fa-times"></i></div>
			<?php Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content(); ?>
		</div>
	</div>

	<?php
	get_footer();
	wp_footer();
?>

</body>
</html>
