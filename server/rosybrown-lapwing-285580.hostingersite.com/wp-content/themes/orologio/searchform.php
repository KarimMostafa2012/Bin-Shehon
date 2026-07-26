<?php
/**
 * Search Form Template
 *
 * @package WordPress
 * @subpackage orologio
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="<?php echo esc_attr( uniqid( 'search-form-' ) ); ?>">
        <span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'label', 'orologio' ); ?></span>
    </label>
    <input type="search" id="<?php echo esc_attr( uniqid( 'search-form-' ) ); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'orologio' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit"><?php echo esc_html_x( 'Search', 'submit button', 'orologio' ); ?></button>
</form>


