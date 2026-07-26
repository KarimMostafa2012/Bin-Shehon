<?php

use Elementor\Plugin;
use OKThemes\Toolkit\TemplateBuilder\Frontend;

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
</head>
<body <?php body_class(); ?>>

<?php
	get_header();

	ob_start();
	the_content();
	$content = ob_get_clean();

	$editing_active = Plugin::$instance->preview->is_preview_mode();

	Frontend::popup_markup( $content, get_the_ID(), $editing_active );

	get_footer();
	wp_footer();
?>

</body>
</html>
