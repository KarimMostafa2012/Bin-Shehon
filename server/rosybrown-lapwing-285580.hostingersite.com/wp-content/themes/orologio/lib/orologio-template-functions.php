<?php
use OrologioTheme\Classes\Orologio_Helper;
//Page layout
if (! function_exists('orologio_page_layout')) :
    function orologio_page_layout() {
        
        $post_id = Orologio_Helper::get_post_id();
        $page_layout = 'right-sidebar';

        //Get page layout from each individual page
        $page_layout_select = Orologio_Helper::get_meta( 'orologio_global_meta', 'page_layout_select', 'theme_default', $post_id);
        

        if ( is_home() ) {
            // This checks if the current page is the blog posts index, regardless of the show_on_front setting.
            if ( $page_layout_select == 'theme_default' ) {
                $page_layout = Orologio_Helper::get_option( 'blog_archive_sidebar', 'right-sidebar' );
            } else {
                $page_layout = Orologio_Helper::get_meta( 'orologio_global_meta', 'page_layout', 'right-sidebar', $post_id);
            }
        } elseif ( is_archive() || is_search() ){
            
            $page_layout = Orologio_Helper::get_option( 'blog_archive_sidebar', 'right-sidebar' );

            
            if (function_exists('is_shop') && is_shop() && $page_layout_select != 'theme_default' ) {
                $page_layout = Orologio_Helper::get_meta( 'orologio_global_meta', 'page_layout', 'right-sidebar', $post_id);
            }
        } else {
            
            if ( $page_layout_select == 'theme_default' ) {
                $page_layout = Orologio_Helper::get_option( 'page_global_sidebar', 'right-sidebar' );
            } else {
                $page_layout = Orologio_Helper::get_meta( 'orologio_global_meta', 'page_layout', 'right-sidebar', $post_id);
            }
        }      

        return $page_layout;

    }
endif;

//Page sidebar
if (! function_exists('orologio_page_sidebar')) :
function orologio_page_sidebar() {
    
    $page_layout = orologio_page_layout();
    if ($page_layout == 'no-sidebar') {
        return;
    }

  ?>
  <aside class="sidebar-nav">
      <?php get_sidebar(); ?>
  </aside>
  <!--/aside .sidebar-nav -->

<?php }
endif;

/**
 * Featured image 
 *
 */
if ( ! function_exists( 'orologio_page_header_featured_image' ) ) :

    function orologio_page_header_featured_image($post_id) {
        
        if ( has_post_thumbnail($post_id) && !is_singular('product') && !is_single() && !is_archive() && !is_post_type_archive() && !is_search() && !is_tax('product_cat') ) : ?>
            <div class="page-header-image">
            <?php echo get_the_post_thumbnail( $post_id, 'full' ); ?>
            </div>
        <?php endif;
    }
endif;

if ( ! function_exists( 'orologio_page_header_featured_image_overlay' ) ) :

    function orologio_page_header_featured_image_overlay($post_id) {
        
        if ( has_post_thumbnail($post_id) && (is_singular() || is_post_type_archive('product')) ) {
            echo 'style = "background-image: url('. get_the_post_thumbnail_url( $post_id, 'full' ) .');" ';
        }
    }
        
endif;

/**
 * Next post navigation with featured image
 *
 */
if ( ! function_exists( 'orologio_next_post_nav' ) ) :

    function orologio_next_post_nav( $button_name ) {

        if ( ! $button_name) {
            $button_name = esc_html_e('Next article', 'orologio');
        }

        if ( is_singular( 'post' ) ) :
            
            $next_post = get_previous_post();
        
            if ( is_a( $next_post , 'WP_Post' ) ) : ?>
            <div class="next-post-nav">
                <div class="next-article-title">
                    <span class="top-title"><?php echo esc_html($button_name); ?></span>
                    <span class="next-article"><?php echo get_the_title( $next_post->ID ); ?></span>
                </div>
                <div class="next-article-btns">
                    <a class="button outline primary" href="<?php echo get_permalink( $next_post->ID ); ?>">Read article</a>
                    <a class="button outline primary" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>">View all</a>
                </div>
            </div>
            <?php endif;
        endif;
    }
endif;


/**
 * Display site header
 *
 */
add_action( 'orologio_header', 'orologio_site_header', 5 );
if ( ! function_exists( 'orologio_site_header' ) ) : 
    /**
     * The global header
     */
    function orologio_site_header() {

        //Hook the builder header
        do_action('okthemes_builder_header');

        if( 'enabled' === Orologio_Helper::check_default_header() ) :
            
        ?>
        <header id="header" class="site-header">
            <div class="theme-container">
                <div class="header-wrapper">
                    <?php
                        orologio_site_branding();
                        orologio_primary_navigation_regular();
                    ?>
                </div>
            </div>
        </header>
    <?php
        endif;
    }
endif;

if ( ! function_exists( 'orologio_site_branding' ) ) {
    /**
     * Site branding wrapper and display
     *
     * @since  1.0.0
     * @return void
     */
    function orologio_site_branding() { ?>

        <div class="site-branding" id="main-logo">

            <?php
            $site_logo_type      = Orologio_Helper::get_option( 'site_logo_type', 'text' );
            $site_image_logo      = Orologio_Helper::get_option( 'site_image_logo', ['url' => get_template_directory_uri() . '/assets/images/logo.png'] );
            
            $sticky_header_logo_check      = Orologio_Helper::get_option( 'sticky_header_logo_check', 'no' );
            $sticky_site_image_logo      = Orologio_Helper::get_option( 'sticky_site_image_logo', ['url' => get_template_directory_uri() . '/assets/images/sticky-logo.png'] );
            
            //Normal logo
            if ( $site_logo_type == 'image' && $site_image_logo['url'] ) {
                $image = '<img src="'.esc_url( $site_image_logo['url'] ).'" alt="'.get_bloginfo('name', 'display').'">';
                $html = sprintf(
                    '<a href="%1$s" class="default-logo" rel="home">%2$s</a>',
                    esc_url( home_url( '/' ) ),
                    $image
                );
            } else {
                $html = '<div class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></div>';

                if ( '' !== get_bloginfo( 'description' ) ) {
                    $html .= '<p class="site-description">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';
                }
            }

            //Sticky logo
            if ( $sticky_header_logo_check == 'yes' &&  $sticky_site_image_logo['url'] ) {
                $sticky_image = '<img src="'.esc_url( $sticky_site_image_logo['url'] ).'" alt="'.get_bloginfo('name', 'display').'">';
                $html .= sprintf(
                    '<a href="%1$s" class="sticky-logo" rel="home">%2$s</a>',
                    esc_url( home_url( '/' ) ),
                    $sticky_image
                );
            }

            echo wp_kses($html,'logo'); // WPCS: XSS ok.
            ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'orologio_wpml_language_display' ) ) {
    /**
     * Display Primary Navigation
     *
     * @since  1.0.0
     * @return void
     */
    function orologio_wpml_language_display() {

        $wpml = defined('ICL_SITEPRESS_VERSION');

        if ( ! $wpml ) {
            return;
        }

        //get languages
        $languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );

        if(!empty($languages)){
            echo '<ul class="orologio-wpml-langs">';
            foreach( $languages as $l ) {
                if(!$l['active']){
                    echo '<li><a href="' . $l['url'] . '">' . $l['code'] . '</a></li>';
                }
            }
            echo '</ul>';
        }

    }
}

if ( ! function_exists( 'orologio_polylang_language_display' ) ) {
    /**
     * Display Primary Navigation
     *
     * @since  1.0.0
     * @return void
     */
    function orologio_polylang_language_display() {

        $polylang = defined('POLYLANG');

        if ( ! $polylang || ! function_exists( 'pll_the_languages' ) ) {
            return;
        }

        echo '<div class="multilanguage-switcher">';
        echo '<ul>';
        pll_the_languages( array('display_names_as' => 'slug' ));
        echo '</ul>';
        echo '</div>';

    }
}

if ( ! function_exists( 'orologio_primary_navigation_regular' ) ) {
    /**
     * Display Primary Navigation
     *
     * @since  1.0.0
     * @return void
     */
    function orologio_primary_navigation_regular() {
        $menu_open_label = Orologio_Helper::get_option( 'header_menu_open_text', esc_html__('Menu', 'orologio') );
        $menu_close_label = Orologio_Helper::get_option( 'header_menu_close_text', esc_html__('Close', 'orologio') );
        ?>

        <!-- primary-mobile-menu -->
        <div class="menu-button-container">
            <button id="primary-mobile-menu" class="button" aria-controls="primary-menu-list" aria-expanded="false">
                <span class="dropdown-icon open"><?php echo esc_html($menu_open_label); ?>
                    <?php echo orologio_get_icons('mobile-menu-toggle');?>
                </span>
                <span class="dropdown-icon close"><?php echo esc_html($menu_close_label); ?>
                <?php echo orologio_get_icons('mobile-menu-toggle-close');?>
                </span>
            </button>
        </div>

        <div class="main-navigation-wrapper" id="main-navbar">

            <?php
            if(has_nav_menu('main-menu')){
                wp_nav_menu(
                    array(
                        'theme_location'  => 'main-menu',
                        'container_class' => 'main-menu',
                        'menu_class'      => 'main-menu-regular',
                        'show_toggles'   => true,
                    )
                );
            }
            else{
                wp_nav_menu( [
                    'theme_location' => 'main-menu',
                    'container'      => 'div',
                    'menu_class'     => 'main-menu',
                    'walker'      => new Orologio_Page_Walker(),
                    'show_toggles'   => true,
                ] );
            }

            orologio_wpml_language_display();
            orologio_polylang_language_display();
            ?>

            <div class="main-header-extras">
            <?php
                /**
                 * Functions hooked in to orologio_header_extras action
                 *
                 * @hooked orologio_header_search - 10
                 * @hooked orologio_header_my_account - 20
                 * @hooked orologio_header_minicart_hook - 30
                 */
                do_action( 'orologio_header_extras' );
            ?>
            </div>

        </div><!-- .main-navigation-wrapper -->

        <?php
    }
}

function orologio_sub_menu_toggle($depth) {
    // Add toggle button.
    $output = '<button class="sub-menu-toggle depth-'.$depth.'" aria-expanded="false" onClick="OrologioExpandSubMenu(this)">';
    $output .= '<span class="icon-plus">'.orologio_get_icons('menu-toggle-plus').'</span>';
    $output .= '<span class="icon-minus">'.orologio_get_icons('menu-toggle-minus').'</span>';
    $output .= '<span class="screen-reader-text">' . esc_html__( 'Open menu', 'orologio' ) . '</span>';
    $output .= '</button>';
    return $output;
}

function orologio_add_sub_menu_toggle( $output, $item, $depth, $args ) {
    if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
        // Add toggle button.
        $output .= orologio_sub_menu_toggle($depth);
    }
    return $output;
}
add_filter( 'walker_nav_menu_start_el', 'orologio_add_sub_menu_toggle', 10, 4 );

// Custom walker for wp_page_menu to include submenu toggle buttons and add main-menu-regular class to the ul element
class Orologio_Page_Walker extends Walker_Page {
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='children sub-menu'>\n";
    }

    function start_el(&$output, $page, $depth = 0, $args = array(), $current_page = 0) {
        if ( isset($args['show_toggles']) && $args['show_toggles'] ) {
            $output .= '<li class="page_item page-item-' . $page->ID;
            if (isset($args['pages_with_children'][$page->ID])) {
                $output .= ' page_item_has_children';
            }
            $output .= '">';

            $output .= '<a href="' . get_permalink($page->ID) . '">' . apply_filters('the_title', $page->post_title, $page->ID) . '</a>';

            if (isset($args['pages_with_children'][$page->ID])) {
                $output .= orologio_sub_menu_toggle($depth);
            }
        } else {
            parent::start_el($output, $page, $depth, $args, $current_page);
        }
    }
}

/**
 * Display page header
 *
 */
add_action( 'orologio_subheader', 'orologio_page_header', 0 );
if ( ! function_exists( 'orologio_page_header' ) ) :

	function orologio_page_header() {
		$post_id = Orologio_Helper::get_post_id();

        //Bail if on any of these pages
        if (is_front_page() || is_404() || is_singular('product')) {
            return; 
        }

		// Theme Options
		$page_header = Orologio_Helper::get_option( 'page_header', 'enabled' );
		$page_title  = Orologio_Helper::get_option( 'page_title', 'enabled' );

		// Single post options
		$single_post_meta          = Orologio_Helper::get_option( 'single_post_meta', 'yes' );
		$single_post_meta_category = Orologio_Helper::get_option( 'single_post_meta_category', 'yes' );
		$single_post_meta_date     = Orologio_Helper::get_option( 'single_post_meta_date', 'yes' );

		// Page Options (overwrite)
		$page_header          = Orologio_Helper::get_meta( 'orologio_global_meta', 'meta_page_header', 'enabled');
		$page_title           = Orologio_Helper::get_meta( 'orologio_global_meta', 'meta_page_title', 'enabled');
		$page_top_title       = Orologio_Helper::get_meta( 'orologio_global_meta', 'page_header_top_title', '');
		$page_custom_title    = Orologio_Helper::get_meta( 'orologio_global_meta', 'page_header_custom_title', '');
		$page_description     = Orologio_Helper::get_meta( 'orologio_global_meta', 'page_header_description', '');

		// WooCommerce pages handling
		if ( function_exists( 'is_product_category' ) && is_product_category() || function_exists( 'is_product_tag' ) && is_product_tag() ) {
			$page_description = wc_format_content( term_description() );
		}

		// Page header conditions
		if ($page_header == 'enabled') {
			?>
            <!-- Page meta -->
            <section id="subheader" class="site-subheader">
                <div class="theme-container">
                    <div class="page-meta" <?php echo orologio_page_header_featured_image_overlay($post_id); ?>>
                        <div class="page-meta-wrapper">
                            <?php
                            // Display for singular 'post'
                            if (is_singular('post') && $single_post_meta === 'yes' && $single_post_meta_category === 'yes') {
                                echo orologio_entry_header();
                            }

                            // Top title
                            if ($page_top_title != '') {
                                echo '<h6 class="page-header-toptitle">' . esc_html($page_top_title) . '</h6>';
                            }

                            // Page title display logic
                            if ($page_title == 'enabled') {
                                // Check if a custom title is set
                                if (!empty($page_custom_title)) {
                                    echo '<h1 class="entry-title">' . wp_kses_post($page_custom_title) . '</h1>';
                                } else {
                                    orologio_display_page_title(); // Use the default title function
                                }
                            }

                            // Meta for posts
                            if (is_singular('post') && $single_post_meta === 'yes' && $single_post_meta_date === 'yes') {
                                echo '<div class="entry-meta">' . orologio_posted_on() . '</div>';
                            }
                            

                            // Description
                            if ($page_description != '') {
                                echo '<div class="header-page-description">' . wp_kses($page_description, 'page_description') . '</div>';
                            }
                            ?>
                        </div><!-- .page-meta-wrapper -->
                    </div><!-- .page-meta -->
                </div><!-- .theme-container -->
            </section>
            <!-- End Page meta -->
			<?php
		}
	}
endif;

function orologio_display_page_title() {
	if (is_archive()) {
		the_archive_title('<h1 class="entry-title">', '</h1>');
	} elseif (is_search()) {
		echo '<h1 class="page-title">' . sprintf(esc_html__('Results for "%s"', 'orologio'), '<span class="page-description search-term">' . esc_html(get_search_query()) . '</span>') . '</h1>';
	} elseif (is_home()) {
		echo '<h1 class="entry-title">' . get_the_title(get_option('page_for_posts', true)) . '</h1>';
	} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() || function_exists( 'is_shop' ) && is_shop() ) {
		woocommerce_page_title('<h1 class="entry-title">', '</h1>');
	} else {
		the_title('<h1 class="entry-title">', '</h1>');
	}
}

/**
 * Display template for post footer information (in single.php).
 *
 */
if (!function_exists('orologio_posted_in')) :
    function orologio_posted_in() {

    // Translators: used between list items, there is a space after the comma.
    $tag_list = get_the_tag_list('<ul class="list-inline post-tags"><li>','</li><li>','</li></ul>');

    // Translators: 1 is the tags
    if ( $tag_list ) {
        $utility_text = esc_html__( '%1$s', 'orologio' );
    } 

    printf($tag_list);

}
endif;

/**
 * Outputs the posted-on information for a post.
 */
function orologio_posted_on() {
    global $post;

    // Get the author ID and name
    $author_id = $post->post_author;
    $author = get_the_author_meta('display_name', $author_id);

    // Generate the "byline" string
    $byline = sprintf(
        /* translators: %s: post author */
        esc_html__('by %s', 'orologio'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url($author_id)) . '">' . esc_html($author) . '</a></span>'
    );

    // Generate the "posted-on" string and combine it with the byline
    $output = '<span class="posted-on">' . orologio_time_link() . '</span><span class="byline"> ' . $byline . '</span>';

    return $output;
}

/**
 * Generates and returns a time link for the post.
 *
 * @return string HTML markup for the time link.
 */
function orologio_time_link() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );

    return sprintf(
        /* translators: %s: post date */
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );
}


if ( ! function_exists( 'orologio_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function orologio_entry_footer() {

    // Get Tags for posts.
    $tags_list = get_the_tag_list( '' );

    // We don't want to output .entry-footer if it will be empty, so make sure its not.
    if ( $tags_list || get_edit_post_link() ) {

        echo '<footer class="entry-footer">';

            if ( 'post' === get_post_type() ) {
                if ( $tags_list ) {
                    echo '<span class="cat-tags-links">';

                        if ( $tags_list ) {
                            echo '<span class="tags-links"><span class="screen-reader-text">' . esc_html__( 'Tags', 'orologio' ) . '</span>' . $tags_list . '</span>';
                        }

                    echo '</span>';
                }
            }

        echo '</footer> <!-- .entry-footer -->';
    }
}
endif;

if ( ! function_exists( 'orologio_entry_header' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function orologio_entry_header() {

    // Get Categories for posts.
    $categories_list = get_the_category_list();

    // We don't want to output .entry-footer if it will be empty, so make sure its not.
    if ( $categories_list || get_edit_post_link() ) {

        echo '<div class="entry-meta-header">';

            if ( 'post' === get_post_type() ) {
                if ( $categories_list ) {
                    echo '<span class="cat-tags-links">';

                        // Make sure there's more than one category before displaying.
                        if ( $categories_list ) {
                            echo '<span class="cat-links"><span class="screen-reader-text">' . esc_html__( 'Categories', 'orologio' ) . '</span>' . $categories_list . '</span>';
                        }

                    echo '</span>';
                }
            }

        echo '</div>';
    }
}
endif;


/**
 * Excerpt read more
 *
 */

function orologio_excerpt_more( $more ) {
    return '<p class="more-link-wrapper"><a class="btn btn-primary" href="'. get_permalink( get_the_ID() ) . '">' . esc_html__('Read More', 'orologio') . '</a></p>';
}
add_filter( 'excerpt_more', 'orologio_excerpt_more' );

/**
 * Customize Continue reading
 *
 */

add_filter( 'the_content_more_link', 'orologio_read_more_link' );
function orologio_read_more_link() {
    return '<p class="more-link-wrapper"><a class="btn btn-primary" href="' . get_permalink() . '">' . esc_html__('Read More', 'orologio') . '</a></p>';
}

/**
 * Display numeric pagination
 *
 * @param int $max_num_pages The total number of pages
 * @return void
 */
function orologio_pagination($max_num_pages = null) {
    global $wp_query;
    
    // If $max_num_pages is not provided, use the main query's max_num_pages
    if (null === $max_num_pages) {
        $max_num_pages = $wp_query->max_num_pages;
    }
    
    // Don't print empty markup if there's only one page
    if ($max_num_pages < 2) {
        return;
    }
    
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    
    $links = paginate_links(array(
        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format'    => '?paged=%#%',
        'current'   => max(1, $paged),
        'total'     => $max_num_pages,
        'prev_text' => orologio_get_icons('arrow-left'), 
        'next_text' => orologio_get_icons('arrow-right'),
        'type'      => 'list',
        'end_size'  => 3,
        'mid_size'  => 3,
    ));
    
    if ($links) :
    ?>
    <nav class="pagination" role="navigation" aria-label="<?php esc_attr_e('Posts Pagination', 'orologio'); ?>">
        <?php echo wp_kses_post($links); ?>
    </nav>
    <?php
    endif;
}


/**
 * Display template for comments and pingbacks.
 *
 */
if (!function_exists('orologio_comment')) :
    function orologio_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' : ?>

                <li <?php comment_class('media, comment'); ?> id="comment-<?php comment_ID(); ?>">
                    <div class="comments-body">
                        <p>
                            <?php esc_html_e('Pingback:', 'orologio'); ?> <?php comment_author_link(); ?>
                        </p>
                    </div><!--/.media-body -->
                <?php
                break;
            default :
                // Proceed with normal comments.
                global $post; ?>

                <li <?php comment_class('media'); ?> id="comment-<?php comment_ID(); ?>">
                        
                        <div class="comments-body">
                            <div class="comments-body-header">
                                <a href="<?php echo esc_url($comment->comment_author_url); ?>" class="avatar-holder">
                                    <?php echo get_avatar($comment, 70); ?>
                                </a>
                                <div class="vcard">
                                    <h4 class="comment-author ">
                                        <?php
                                        printf('<cite class="fn">%1$s %2$s</cite>',
                                            get_comment_author_link(),
                                            // If current post author is also comment author, make it known visually.
                                            ($comment->user_id === $post->post_author) ? '<span class="label"> ' . esc_html__(
                                                'Post author',
                                                'orologio'
                                            ) . '</span> ' : ''); ?>
                                    </h4>
                                    <p class="meta">
                                        <?php printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                                                esc_url(get_comment_link($comment->comment_ID)),
                                                get_comment_time('c'),
                                                sprintf(
                                                    esc_html__('%1$s at %2$s', 'orologio'),
                                                    get_comment_date(),
                                                    get_comment_time()
                                                )
                                            ); ?>
                                    </p>
                                </div>
                                <?php comment_reply_link( array_merge($args, array(
                                            'reply_text' => esc_html__('Reply', 'orologio'),
                                            'depth'      => $depth,
                                            'max_depth'  => $args['max_depth']
                                        )
                                    )); ?>
                            </div>
                            
                            <div class="comments-body-content">
                                <?php if ('0' == $comment->comment_approved) : ?>
                                    <p class="comment-awaiting-moderation"><?php esc_html_e(
                                        'Your comment is awaiting moderation.',
                                        'orologio'
                                    ); ?></p>
                                <?php endif; ?>

                                <?php comment_text(); ?>
                            </div>
                                                    
                        </div>
                        <!--/.comments-body -->
                <?php
                break;
        endswitch;
    }
endif;


/**
 * Header extra - Header search widget
 **/
add_action( 'orologio_header_extras', 'orologio_header_search', 10 );
if ( ! function_exists('orologio_header_search') ) { 
    function orologio_header_search() {
    $site_header_search = Orologio_Helper::get_option( 'site_header_search', 'disabled' );
    if ( $site_header_search == 'enabled' ) {
    ?>
        <div class="header-extra header-search-widget">
            <a class="top-header-search toggle-search-box" id="trigger-header-search" href="#" title="<?php esc_attr_e('Search products', 'orologio'); ?>"><?php echo orologio_get_icons('header-search');?></a>
            <?php get_template_part( 'parts/part','searchform-overlay' ); ?>
        </div>
        
    <?php

        

    }
    } 
}



if ( ! function_exists( 'orologio_site_preloader' ) ) {
    /**
     * Site preloader
     *
     * @since  1.0.0
     * @return void
     */
    function orologio_site_preloader() { ?>
        <div class="preloader"></div>
    <?php }
}

add_action( 'orologio_footer', 'orologio_site_footer', 5 );
if ( ! function_exists( 'orologio_site_footer' ) ) {
    /**
     * The global footer
     */
    function orologio_site_footer() {
        
        //Hook the builder footer
        do_action('okthemes_builder_footer'); 

        if( 'enabled' === Orologio_Helper::check_default_footer() ) :
        ?>
        <footer id="footer" class="site-footer">
            <div class="theme-container">
                <?php
                    orologio_footer_widgets();
                    orologio_footer_newsletter();
                    orologio_footer_credit();
                ?>
            </div>
        </footer>
        
    <?php
        endif;
    }
}

/**
 * Footer credit 
 */
if (!function_exists('orologio_footer_credit')) :
    function orologio_footer_credit() {
        
        //Default value 
        $copyright      = Orologio_Helper::get_option( 'copyright', 'enabled' );
        $copyright_text = Orologio_Helper::get_option( 'copyright_text', 'Copyright © 2024. All rights reserved.' );
        ?>
        
        <div class="footer-credit-wrapper">
            <?php if( $copyright == 'enabled' ) : ?>
            <div class="footer-credit">
                <?php echo wp_kses_post( $copyright_text ); ?>
            </div><!-- /footer-credit -->
            <?php endif; ?>

            <?php orologio_footer_navigation(); ?>
        </div>

    <?php }
endif;


/**
 * Footer newsletter 
 */
if (!function_exists('orologio_footer_newsletter')) :
    function orologio_footer_newsletter() { ?>
        
        <?php if( function_exists('mc4wp_show_form') ) : ?>
        <div class="footer-newsletter-wrapper">
            <?php mc4wp_show_form(); ?>
        </div>
        <?php endif; ?>

    <?php }
endif;


/**
 * Footer widgets
 */
if (!function_exists('orologio_footer_widgets')) :
    function orologio_footer_widgets() {
        if ( is_active_sidebar('footer-widgets-area') ) : ?>
    
            <div class="footer-widgets">
                <?php get_sidebar("footer"); ?>
            </div>
        
        <?php endif;
    }
endif;

if ( ! function_exists( 'orologio_footer_navigation' ) ) {
    /**
     * Display footer Navigation
     *
     * @since  1.0.0
     * @return void
     */
    function orologio_footer_navigation() { ?>

        <div class="footer-navigation" id="footer-nav-content">
            <?php
            if ( has_nav_menu( 'footer-menu' ) ) {
                wp_nav_menu(
                    array(
                        'theme_location'  => 'footer-menu',
                        'container_class' => '',
                        'fallback_cb'     => false,
                        'depth'          => 1
                    )
                );
            }
            ?>
        </div><!-- .footer-navigation -->
        <?php
    }
}

/**
 * Scroll to top functionality
 */
$back_to_top = Orologio_Helper::get_option('back_to_top', 'enabled');

if ($back_to_top == 'enabled') {
    add_action('wp_footer', 'orologio_back_to_top', 25);
    add_action('wp_body_open', 'orologio_add_top_anchor', 10);
}

if (!function_exists('orologio_back_to_top')) {
    function orologio_back_to_top() {       
        ?>
        <a href="#orologio-top" class="scrollup">
            <?php echo orologio_get_icons('scroll-up'); ?>
        </a>
        <?php
    }
}

if (!function_exists('orologio_add_top_anchor')) {
    function orologio_add_top_anchor() {
        echo '<div id="orologio-top"></div>';
    }
}

/**
 * Smooth scrolling functionality
 */
$site_smooth_scroll = Orologio_Helper::get_option('site_smooth_scroll', 'disabled');

if ($site_smooth_scroll == 'enabled') {
    add_action('wp_body_open', 'orologio_open_site_wrapper');
    add_action('okthemes_builder_footer', 'orologio_close_site_wrapper', 26);
}

if (!function_exists('orologio_open_site_wrapper')) {
    function orologio_open_site_wrapper() {
        echo '<div class="site-wrapper inertia">';
    }
}

if (!function_exists('orologio_close_site_wrapper')) {
    function orologio_close_site_wrapper() {
        echo '</div>';
    }
}
