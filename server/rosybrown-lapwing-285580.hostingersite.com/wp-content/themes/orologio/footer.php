<?php
/**
 * Footer
 *
 * @package WordPress
 * @subpackage orologio
 */
?>

<?php
    /**
     * Functions hooked in to orologio_after_content action
     *
     * @hooked orologio_end_content - 10
     */
    do_action( 'orologio_after_content' );


    /**
     * Functions hooked into orologio_footer action
     *
     * @hooked orologio_footer_wrapper_open            - 5
     * @hooked orologio_footer_widgets                 - 10
     * @hooked orologio_footer_newsletter              - 20
     * @hooked orologio_footer_credit                  - 30
     * @hooked orologio_footer_wrapper_close           - 60
     */
    do_action( 'orologio_footer' );
    
    wp_footer(); ?>
    </body>
</html>