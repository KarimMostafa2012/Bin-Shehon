<?php
//Inject custom controls in Button widget
add_action( 'elementor/element/button/section_style/after_section_end', function( $widget, $args ) {
    /** @var \Elementor\Widget_Base $widget */
    
    // Ensure we're modifying the correct widget
    if ( 'button' !== $widget->get_name() ) {
        return;
    }

    // Use the Elementor controls manager to get the existing control instance
    $control = $widget->get_controls( 'button_type' );
    
    // Verify we have the control and can modify its options
    if ( ! $control ) {
        return;
    }
    
    // Define the options you want to keep: Default and your custom option
    $new_options = [
        ''        => __( 'Default', 'okthemes-toolkit' ), 
        'outline' => __( 'Outline', 'okthemes-toolkit' )
    ];
    
    // Update the control's options directly
    $control['options'] = $new_options;
    
    // Update the control with the new set of options
    $widget->update_control( 'button_type', $control );
}, 10, 2 );