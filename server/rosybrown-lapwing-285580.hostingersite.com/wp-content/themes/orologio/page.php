<?php
/**
 * Default Page
 * Description: Page template with a content container and right sidebar.
 *
 * @package WordPress
 * @subpackage orologio
 */
get_header(); ?>

<?php
    /**
     * Functions hooked into orologio_subheader action
     *
     * @hooked orologio_page_header                      - 0
     */
    do_action( 'orologio_subheader' );
?>

<section id="content" class="site-content">
    <div class="theme-container">
        <div class="page-content">
            <?php
            while ( have_posts() ) : the_post();
                get_template_part( 'parts/part', 'page' );
            endwhile;
            ?>

            <?php orologio_page_sidebar(); ?>
        </div><!-- end page container -->
    </div>
</section>

<?php get_footer(); ?>