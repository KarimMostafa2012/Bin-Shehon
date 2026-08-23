<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage orologio
 */

    use OrologioTheme\Classes\Orologio_Helper;

    $single_post_meta                   = Orologio_Helper::get_option( 'single_post_meta', 'yes' );
    $single_post_meta_tags              = Orologio_Helper::get_option( 'single_post_meta_tags', 'yes' );
    $single_post_navigation             = Orologio_Helper::get_option( 'single_post_navigation', 'yes' );
    $single_post_navigation_next_button = Orologio_Helper::get_option( 'single_post_navigation_next_button', 'Next article' );
    $featured_image_url                 = get_the_post_thumbnail_url( get_the_ID(), 'full' );
    $post_description                   = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 34, '...' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	

        <header
            class="orologio-single-post-hero"
            <?php if ( $featured_image_url ) : ?>
            style="background-image: url('<?php echo esc_url( $featured_image_url ); ?>');"
            <?php endif; ?>
        >
            <div class="orologio-single-post-hero__inner">
                <time class="orologio-single-post-hero__date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
                    <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
                </time>
                <?php the_title( '<h1 class="orologio-single-post-hero__title">', '</h1>' ); ?>
                <?php if ( $post_description ) : ?>
                    <p class="orologio-single-post-hero__excerpt"><?php echo esc_html( $post_description ); ?></p>
                <?php endif; ?>
            </div>
        </header>

		<?php if ( '' !== get_the_post_thumbnail() ) : ?>
        <figure class="post-thumbnail orologio-single-post-featured">
            <?php the_post_thumbnail( 'full' ); ?>
        </figure><!-- .post-thumbnail -->
        <?php endif; ?>

        <div class="post-content-wrapper">
            <div class="entry-content">
                <?php
                /* translators: %s: Name of current post */
                the_content( sprintf(
                    esc_html__( 'Continue reading %s', 'orologio' ),
                    the_title( '<span class="screen-reader-text">', '</span>', false )
                ) );

                wp_link_pages( array(
                    'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'orologio' ) . '</span>',
                    'after'       => '</div>',
                    'link_before' => '<span>',
                    'link_after'  => '</span>',
                    'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'orologio' ) . ' </span>%',
                    'separator'   => '<span class="screen-reader-text">, </span>',
                ) );
            ?>
            </div><!-- .entry-content -->
        </div>

		<?php 
        if ( $single_post_meta == 'yes' && $single_post_meta_tags == 'yes'  ) {
            orologio_entry_footer();
        }
        ?>

        <?php if ( $single_post_navigation == 'yes') : ?>

        <?php orologio_next_post_nav($single_post_navigation_next_button); ?>

        <?php do_action( 'orologio_social_share' ); ?>

        <?php endif; ?>

        <?php comments_template( '', true ); ?>

</article><!-- article -->
