<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage orologio
 */

use OrologioTheme\Classes\Orologio_Helper;

get_header();

// Allow Elementor PRO to override the 404 page.
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'single' ) ) {
    get_footer();
    return;
}

// Retrieve options with fallbacks.
$orologio_404_title       = Orologio_Helper::get_option( 'error_title', esc_html__( '404 Error', 'orologio' ) );
$orologio_404_desc        = Orologio_Helper::get_option( 'error_desc', esc_html__( 'It seems we cannot find what you are looking for.', 'orologio' ) );
$orologio_404_button_text = Orologio_Helper::get_option( 'error_button_text', esc_html__( 'Return To Home', 'orologio' ) );
$home_url                 = esc_url( home_url( '/' ) );
?>

<section id="content" class="site-content">
    <div class="theme-container">
        <div class="page-content">
            <div class="not_found_wrapper">
                <div class="not_found_box">
                    <?php if ( $orologio_404_title ) : ?>
                        <h1><?php echo esc_html( $orologio_404_title ); ?></h1>
                    <?php endif; ?>

                    <?php if ( $orologio_404_desc ) : ?>
                        <p class="info-404"><?php echo esc_html( $orologio_404_desc ); ?></p>
                    <?php endif; ?>

                    <a class="btn btn-primary" href="<?php echo $home_url; ?>">
                        <?php echo esc_html( $orologio_404_button_text ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
