<?php
/**
 * Description: Default Index template to display loop of blog posts
 *
 * @package WordPress
 * @subpackage orologio
 */
use OrologioTheme\Classes\Orologio_Helper;

get_header();

    $blog_archive_post_layout       = Orologio_Helper::get_option( 'blog_archive_post_layout', 'list' );
    $blog_archive_post_list_style   = Orologio_Helper::get_option( 'blog_archive_post_list_style', 'block' );
    $blog_archive_post_grid_columns = Orologio_Helper::get_option( 'blog_archive_post_grid_columns', '1' );

    if ( $blog_archive_post_layout == 'list' ) {
        $blog_archive_post_grid_columns = '1';
    }    
?>

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

            <div class="gg_posts_grid">

            <?php
            // Get sticky posts
            $sticky_posts = get_option('sticky_posts');
            
            if (!empty($sticky_posts) && is_home() && !is_paged()) {
                // Query for sticky posts only
                $sticky_args = array(
                    'post__in' => $sticky_posts,
                    'ignore_sticky_posts' => 1
                );
                
                $sticky_query = new WP_Query($sticky_args);
                
                if ($sticky_query->have_posts()) : ?>
                    <div class="sticky-posts-section">
                        <div class="sticky-posts-wrapper">
                            <?php while ($sticky_query->have_posts()) : $sticky_query->the_post(); ?>
                                <div class="sticky-post">
                                    <?php get_template_part('parts/post-formats/part', 'sticky'); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php
                endif;
                wp_reset_postdata();
            }

            // Create a new query for regular posts, excluding sticky posts
            if (is_home() && !is_paged() && !empty($sticky_posts)) {
                $args = array(
                    'post__not_in' => $sticky_posts,
                    'posts_per_page' => get_option('posts_per_page'),
                    'paged' => get_query_var('paged') ? get_query_var('paged') : 1
                );
                $main_query = new WP_Query($args);
            } else {
                // On other pages, use the main query
                global $wp_query;
                $main_query = $wp_query;
            }
            ?>

            
                <?php if ($main_query->have_posts()) : ?>
                    
                    <ul 
                    class="el-grid" 
                    data-layout-mode="<?php echo esc_attr($blog_archive_post_layout); ?>" 
                    data-gap="gap" 
                    data-columns="<?php echo esc_attr($blog_archive_post_grid_columns); ?>"
                    <?php if ( $blog_archive_post_layout == 'list' ) : ?>
                    data-list-style="<?php echo esc_attr($blog_archive_post_list_style); ?>"
                    <?php endif; ?>
                    >
                    <?php while ($main_query->have_posts()) : $main_query->the_post(); ?>
                        <li><?php get_template_part('parts/post-formats/part'); ?></li>
                    <?php endwhile; ?>
                    </ul>

                    <?php 
                        if (function_exists("orologio_pagination")) {
                            orologio_pagination($main_query->max_num_pages);
                        }
                    ?>

                <?php else : ?>

                    <?php get_template_part('parts/post-formats/part', 'none'); ?>

                <?php endif; // end have_posts() check ?>
                
                <?php wp_reset_postdata(); ?>
            </div><!--/ .gg_posts_grid-->

            <?php orologio_page_sidebar(); ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>