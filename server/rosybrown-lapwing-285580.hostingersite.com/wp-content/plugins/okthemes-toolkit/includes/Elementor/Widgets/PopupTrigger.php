<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined('ABSPATH') || exit;

class PopupTrigger extends Widget_Base {

    /**
     * Retrieve the widget name.
     */
    public function get_name() {
        return 'okthemes-popup-trigger-widget';
    }

    /**
     * Retrieve the widget title.
     */
    public function get_title() {
        return esc_html__('OKT - Popup Trigger Widget', 'okthemes-toolkit');
    }

    /**
     * Retrieve the widget icon.
     */
    public function get_icon() {
        return 'eicon-off-canvas';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     */
    public function get_categories() {
        return ['okthemes_elements'];
    }

    public function get_script_depends() {
        return [ 'okthemes-popup-trigger' ];
    }
    
    public function get_style_depends() {
        return [ 'okthemes-popup-trigger' ];
    }


    /**
     * Register the widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'widget_content',
            [
                'label' => esc_html__('Content', 'okthemes-toolkit'),
            ]
        );

        $this->add_control(
            'popup_template',
            [
                'label' => esc_html__('Select Popup Template', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_popup_templates(),
                'description' => esc_html__('Select the popup template from the list of available templates.', 'okthemes-toolkit'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'trigger_action',
            [
                'label' => esc_html__('Trigger Action', 'okthemes-toolkit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'open',
                'options' => [
                    'open' => esc_html__('Open Popup', 'okthemes-toolkit'),
                    'close' => esc_html__('Close Popup', 'okthemes-toolkit'),
                ],
                'description' => esc_html__('Choose whether this trigger should open or close the popup.', 'okthemes-toolkit'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_svg',
            [
                'label' => esc_html__('Style', 'okthemes-toolkit'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => esc_html__( 'Alignment', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start'   => [
                        'title' => esc_html__( 'Left', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'flex-end'  => [
                        'title' => esc_html__( 'Right', 'okthemes-toolkit' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .popup-trigger' => 'justify-content: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'svg_fill_color',
            [
                'label' => esc_html__('SVG Fill Color', 'okthemes-toolkit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} svg rect' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'svg_width',
            [
                'label' => esc_html__('SVG Width', 'okthemes-toolkit'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 70,
                ],
                'size_units' => ['px', 'vw', '%'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'svg_height',
            [
                'label' => esc_html__('SVG Height', 'okthemes-toolkit'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'size_units' => ['px', 'vw', '%'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Retrieve popup templates for the dropdown control.
     *
     * @since 1.0.0
     * @access protected
     */
    private function get_popup_templates() {
        $args = [
            'post_type'   => 'okthemes_template',
            'numberposts' => -1,
            'orderby'     => 'title',
            'order'       => 'ASC',
        ];

        $query_query = get_posts( $args );

        $posts = [];

        if ( $query_query ) {
            foreach ( $query_query as $query ) {
                if ( 'popup' === $this->template_type( $query->ID ) ) {
                    $posts[$query->ID] = $query->post_title;
                }
            }
        } else {
            $posts[0] = esc_html__( 'No Template found', 'okthemes-toolkit' );
        }

        return $posts;
    }

    /**
     * Template Type
     */
    protected function template_type( $post_id ) {

        $meta = get_post_meta( $post_id, 'okthemes_tb_settings', true );

        if ( isset( $meta['template_type'] ) ) {
            $template_type = $meta['template_type'];
        } else {
            $template_type = '';
        }

        return $template_type;
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'popup-trigger-widget-wrapper');
        $popup_id = !empty($settings['popup_template']) ? $settings['popup_template'] : '';
        $trigger_action = !empty($settings['trigger_action']) ? $settings['trigger_action'] : 'open';

        // Create a CSS class based on the trigger action
        $action_class = 'popup-' . $trigger_action . '-trigger';

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <a href="javascript:void(0);" class="popup-trigger <?php echo esc_attr($action_class); ?>" 
               data-popup="popup-<?php echo esc_attr($popup_id); ?>" 
               data-action="<?php echo esc_attr($trigger_action); ?>">
                <?php if ($trigger_action === 'open') : ?>
                    <svg class="nav-btn__svg nav-btn__open" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                        width="70px" height="20px" viewBox="0 0 70 20" enable-background="new 0 0 70 20" xml:space="preserve">
                        <rect class="nav-rect top-line" x="20" width="50" height="1"/>
                        <rect class="nav-rect middle-line" y="9.5" width="40" height="1"/>
                        <rect class="nav-rect bottom-line" x="20" y="19" width="50" height="1"/>
                    </svg>
                <?php else : ?>
                    <svg class="nav-btn__svg nav-btn__close" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#fff" viewBox="0 0 65 65"><path d="M47.782 49.285 15.813 17.316M15.809 48.229l31.97-31.968"></path></svg>
                <?php endif; ?>
            </a>
        </div>
        <?php
    }

    /**
     * Render the widget output in the editor.
     */
    protected function content_template() {
        ?>
        <#
            view.addRenderAttribute('wrapper', 'class', 'popup-trigger-widget-wrapper');
            var actionClass = 'popup-' + settings.trigger_action + '-trigger';
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <a href="javascript:void(0);" class="popup-trigger {{ actionClass }}" 
               data-action="{{ settings.trigger_action }}"
               data-popup="popup-{{ settings.popup_template }}">
                <# if (settings.trigger_action === 'open') { #>
                    <svg class="nav-btn__svg nav-btn__open" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                        width="70px" height="20px" viewBox="0 0 70 20" enable-background="new 0 0 70 20" xml:space="preserve">
                        <rect class="nav-rect top-line" x="20" width="50" height="1"/>
                        <rect class="nav-rect middle-line" y="9.5" width="40" height="1"/>
                        <rect class="nav-rect bottom-line" x="20" y="19" width="50" height="1"/>
                    </svg>
                <# } else { #>
                    <svg class="nav-btn__svg nav-btn__close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                        width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve">
                        <rect class="nav-rect close-line-1" x="0" y="14" width="30" height="2" transform="rotate(45, 15, 15)"/>
                        <rect class="nav-rect close-line-2" x="0" y="14" width="30" height="2" transform="rotate(-45, 15, 15)"/>
                    </svg>
                <# } #>
            </a>
        </div>
        <?php
    }
}