<?php
/**
 * Fullscreen Search Form
 *
 * @package WordPress
 * @subpackage orologio
 */
?>

<div class="searchform-overlay-wrapper">
    <form method="get" id="searchform" class="" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="search" autofocus value="<?php the_search_query(); ?>" placeholder="<?php esc_attr_e( 'Type here', 'orologio' ); ?>" name="s" id="s" />
        <button type="submit" id="searchsubmit" class="btn btn-primary">
            <?php echo orologio_get_icons('search'); ?>
        </button>
    </form>
    <button class="close-overlay" title="Close"><span class="close-overlay-icon"><span></span><span></span></span></button>
</div>
