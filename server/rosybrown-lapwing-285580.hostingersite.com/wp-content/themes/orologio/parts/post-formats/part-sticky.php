<?php
/**
 * Template part for displaying sticky posts
 *
 * @package WordPress
 * @subpackage orologio
 */

use OrologioTheme\Classes\Orologio_Helper;

$archive_post_featured_image   = Orologio_Helper::get_option('archive_post_featured_image', 'yes');
$archive_post_meta             = Orologio_Helper::get_option('archive_post_meta', 'yes');
$archive_post_meta_date        = Orologio_Helper::get_option('archive_post_meta_date', 'yes');
$archive_post_meta_category    = Orologio_Helper::get_option('archive_post_meta_category', 'yes');
$archive_post_content          = Orologio_Helper::get_option('archive_post_content', 'full_content');
$archive_post_excerpt_count    = Orologio_Helper::get_option('archive_post_excerpt_count', '12');
$archive_read_more_button      = Orologio_Helper::get_option('archive_read_more_button', 'no');
$archive_read_more_button_text = Orologio_Helper::get_option('archive_read_more_button_text', 'Read more');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('sticky-post-article'); ?>>
    <div class="sticky-post-inner">
        <?php if ('' !== get_the_post_thumbnail() && $archive_post_featured_image == 'yes') : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large'); ?>
            </a>
        </div><!-- .post-thumbnail -->
        <?php endif; ?>

        <div class="post-content-wrapper">
            
            <div class="entry-meta">
                <?php if ('post' === get_post_type() && $archive_post_meta == 'yes' && $archive_post_meta_date == 'yes') {
                    echo orologio_time_link();
                }; ?>
            </div><!-- .entry-meta -->

            <div class="entry-header">
                <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
            </div><!-- .entry-header -->

            <?php if ($archive_post_content == 'full_content') : ?>
                <div class="entry-content">
                    <?php
                    /* translators: %s: Name of current post */
                    the_content(sprintf(
                        esc_html__('Continue reading %s', 'orologio'),
                        the_title('<span class="screen-reader-text">', '</span>', false)
                    ));
                    ?>
                </div><!-- .entry-content -->
            <?php elseif (has_excerpt() && $archive_post_content == 'excerpt') : ?>
                <div class="entry-summary">
                    <?php echo wp_trim_words(get_the_excerpt(), $archive_post_excerpt_count['number']); ?>
                </div><!-- .entry-summary -->
            <?php endif; ?>

            <?php if ($archive_read_more_button == 'yes') : ?>
                <a class="okthemes-simple-link" href="<?php echo get_permalink(); ?>">
				    <span class="simple-link-text"><?php echo esc_html($archive_read_more_button_text); ?></span>
                    <?php echo orologio_get_icons('theme-next-arrow'); ?>
                </a>
            <?php endif; ?>
            
            
        </div>
    </div>
</article><!-- article -->