<?php
/**
 * Default Page Header
 *
 * @package WordPress
 * @subpackage orologio
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php
    /**
     * Functions hooked into orologio_preloader action
     *
     * @hooked orologio_preloader                   - 10
     */
    do_action( 'orologio_preloader' );

    /**
     * Functions hooked into orologio_header action
     *
     * @hooked orologio_header_wrapper_open              - 5
     * @hooked orologio_primary_navigation               - 10
     * @hooked orologio_site_branding                    - 20
     * @hooked orologio_main_navigation                  - 30
     * @hooked orologio_header_wrapper_close             - 60
     */
    do_action( 'orologio_header' );

    /**
     * Functions hooked into orologio_header action
     *
     * @hooked orologio_header_wrapper_open              - 5
     */
    do_action( 'orologio_dynamic_header' );

    /**
     * Functions hooked in to orologio_before_content action
     *
     * @hooked orologio_begin_content - 10
     */
    do_action( 'orologio_before_content' );
?>