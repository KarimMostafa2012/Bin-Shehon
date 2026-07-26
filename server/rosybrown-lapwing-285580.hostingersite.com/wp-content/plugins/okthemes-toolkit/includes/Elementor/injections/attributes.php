<?php
/**
 * Custom Attributes injection for all Elementor elements.
 * Adds an "Attributes" section in the Advanced tab of every widget,
 * section, column and container — mirroring Elementor PRO's feature.
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

// ── Control injection ─────────────────────────────────────────────────────────

function okt_add_custom_attributes_controls( \Elementor\Element_Base $element ) {

    $repeater = new Repeater();

    $repeater->add_control(
        'okt_attr_key',
        [
            'label'       => esc_html__( 'Attribute', 'okthemes-toolkit' ),
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'data-value',
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
        ]
    );

    $repeater->add_control(
        'okt_attr_value',
        [
            'label'       => esc_html__( 'Value', 'okthemes-toolkit' ),
            'type'        => Controls_Manager::TEXT,
            'placeholder' => esc_html__( 'my-value', 'okthemes-toolkit' ),
            'label_block' => true,
            'dynamic'     => [ 'active' => true ],
        ]
    );

    $element->start_controls_section(
        'okt_section_custom_attributes',
        [
            'label' => esc_html__( 'OKThemes Attributes', 'okthemes-toolkit' ),
            'tab'   => Controls_Manager::TAB_ADVANCED,
        ]
    );

    $element->add_control(
        'okt_attributes_info',
        [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => '<div style="line-height:1.7;font-size:12px">
                <strong>' . esc_html__( 'Scroll Motion', 'okthemes-toolkit' ) . '</strong><br>
                <span style="opacity:.75">' . esc_html__( 'Add any of these as Attribute + Value pairs:', 'okthemes-toolkit' ) . '</span><br><br>
                <code>data-scroll-y</code> &nbsp;<code>-20</code><br>
                <span style="opacity:.65;font-size:11px">' . esc_html__( 'Move Y by % of element height (↑ negative, ↓ positive)', 'okthemes-toolkit' ) . '</span><br><br>
                <code>data-scroll-x</code> &nbsp;<code>10</code><br>
                <span style="opacity:.65;font-size:11px">' . esc_html__( 'Move X by % of element width (← negative, → positive)', 'okthemes-toolkit' ) . '</span><br><br>
                <code>data-scroll-opacity</code> &nbsp;<code>true</code><br>
                <span style="opacity:.65;font-size:11px">' . esc_html__( 'Fade in 0→1 as element enters viewport', 'okthemes-toolkit' ) . '</span>
            </div>',
            'content_classes' => 'elementor-descriptor',
            'separator'       => 'after',
        ]
    );

    $element->add_control(
        'okt_attributes',
        [
            'label'       => esc_html__( 'Custom Attributes', 'okthemes-toolkit' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '<# print( okt_attr_key || "—" ); #>',
        ]
    );

    $element->end_controls_section();
}

// Widgets (all share the "common" base element)
add_action( 'elementor/element/common/_section_responsive/after_section_end',  'okt_add_custom_attributes_controls', 10 );

// Section (classic layout)
add_action( 'elementor/element/section/section_advanced/after_section_end',    'okt_add_custom_attributes_controls', 10 );

// Column (classic layout)
add_action( 'elementor/element/column/section_advanced/after_section_end',     'okt_add_custom_attributes_controls', 10 );

// Flexbox Container (Elementor 3.6+)
add_action( 'elementor/element/container/section_layout/after_section_end',    'okt_add_custom_attributes_controls', 10 );


// ── Frontend render ───────────────────────────────────────────────────────────

add_action(
    'elementor/frontend/before_render',
    function ( \Elementor\Element_Base $element ) {

        $settings = $element->get_settings_for_display();

        if ( empty( $settings['okt_attributes'] ) || ! is_array( $settings['okt_attributes'] ) ) {
            return;
        }

        foreach ( $settings['okt_attributes'] as $attr ) {

            $key = isset( $attr['okt_attr_key'] ) ? trim( $attr['okt_attr_key'] ) : '';
            if ( '' === $key ) {
                continue;
            }

            // Strip characters not valid in an HTML attribute name
            $key = preg_replace( '/[^a-zA-Z0-9_:\-.]/', '', $key );
            if ( '' === $key ) {
                continue;
            }

            // Block event-handler attributes (on*) to prevent XSS
            if ( preg_match( '/^on\w+$/i', $key ) ) {
                continue;
            }

            $value = isset( $attr['okt_attr_value'] ) ? trim( $attr['okt_attr_value'] ) : '';

            // Sanitize href / src values — reject javascript: URIs
            if ( in_array( strtolower( $key ), [ 'href', 'src', 'action' ], true ) ) {
                $value = esc_url( $value );
                if ( '' === $value ) {
                    continue;
                }
            } else {
                $value = esc_attr( $value );
            }

            $element->add_render_attribute( '_wrapper', $key, $value );
        }
    }
);
