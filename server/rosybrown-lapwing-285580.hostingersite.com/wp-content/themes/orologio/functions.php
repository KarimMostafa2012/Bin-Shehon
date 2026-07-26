<?php
/**
 * Theme Functions
 *
 * @author Gogoneata Cristian <cristian.gogoneata@gmail.com>
 * @package WordPress
 * @subpackage orologio
 */

$theme = wp_get_theme();
if ( is_child_theme() ) {
    $theme = wp_get_theme( $theme->get( 'Template' ) );
}
$theme_version = $theme->get( 'Version' );

define("OROLOGIO_VERSION", $theme_version);

// Load the main classes
require get_template_directory() . '/lib/class-helper.php';
require get_template_directory() . '/lib/class-orologio.php';
require get_template_directory() . '/lib/orologio-functions.php';
require get_template_directory() . '/lib/orologio-template-functions.php';

if ( orologio_is_okt_toolkit_activated() ) {
    require get_template_directory() . '/lib/class-dynamic-css.php';

    /**
     * Elementor compatibility
     */
    if ( orologio_is_elementor_activated() ) {
        require_once get_template_directory() . '/lib/elementor/el-custom-header-typography.php';
        require get_template_directory() . '/lib/class-orologio-elementor.php';
    }
}

/**
 * Load the plugins class
 */
if ( is_admin() ) {
    require get_template_directory() . '/admin/class-theme-setup.php';    
}

/**
 * Load woocommerce functions
 */
if ( orologio_is_wc_activated() ) {
    require get_template_directory() . '/lib/woocommerce/class-orologio-woocommerce.php';
    require get_template_directory() . '/lib/woocommerce/orologio-woocommerce-template-hooks.php';
    require get_template_directory() . '/lib/woocommerce/orologio-woocommerce-template-functions.php';
}

/**
 * Maximum allowed width of content within the theme.
 */
if (!isset($content_width)) {
    $content_width = 1500;
}