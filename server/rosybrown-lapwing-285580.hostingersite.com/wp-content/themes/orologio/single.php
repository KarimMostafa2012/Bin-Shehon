<?php
/**
 * Default Post Template
 *
 * @package WordPress
 * @subpackage orologio
 */
get_header();
?>

    <section id="content" class="site-content">
        <div class="theme-container orologio-single-post-container">
            <div class="page-content">
                
                <?php 
                while (have_posts()) : the_post();
                    get_template_part( 'parts/post-formats/part', 'single' );
                endwhile; // end of the loop.
                ?>
                
            </div><!-- end page container -->
        </div>
    </section>
<?php get_footer(); ?>
