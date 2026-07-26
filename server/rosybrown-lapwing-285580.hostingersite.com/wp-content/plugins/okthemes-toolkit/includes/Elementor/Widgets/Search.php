<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use OKThemes\Toolkit\Functions\Helpers as OKT_Helpers;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class Search extends Widget_Base {

    public function get_script_depends() {
		return [ 'okthemes-search-widget' ];
	}

    public function get_style_depends() {
		return [ 'okthemes-search-widget' ];
	}

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'okthemes-search-widget';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'OKT - Search widget', 'okthemes-toolkit' );
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-site-logo';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['okthemes_elements'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return ['okthemes', 'toolkit', 'header', 'footer', 'logo', 'site'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'widget_content',
            [
                'label' => esc_html__( 'General', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'icon_type',
            [
                'label'   => esc_html__( 'Icon Type', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::CHOOSE,
                'toggle'  => false,
                'default' => 'theme_default',
                'options' => [
                    'theme_default' => [
                        'title' => esc_html__( 'Theme default', 'okthemes-toolkit' ),
                        'icon'  => 'far fa-image',
                    ],
                    'icon'  => [
                        'title' => esc_html__( 'Icon', 'okthemes-toolkit' ),
                        'icon'  => 'fas fa-star',
                    ],
                ],
            ]
        );

        $this->add_control(
            'link_type',
            [
                'label'   => esc_html__( 'Link Type', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'theme_default'   => esc_html__( 'Theme Default', 'okthemes-toolkit' ),
                    'simple_link'   => esc_html__( 'Simple Link', 'okthemes-toolkit' ),
                    'popup'   => esc_html__( 'Popup', 'okthemes-toolkit' ),
                ],
                'default' => 'theme_default',
                'toggle'  => false,
            ]
        );

        $this->add_control(
            'link_url',
            [
                'label'       => esc_html__( 'Link URL', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'condition'   => [
                    'link_type' => 'simple_link',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'template_id',
            [
                'label'   => esc_html__( 'Select Template', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => $this->select_saved_template(),
                'condition'   => [
                    'link_type' => 'popup',
                ],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'            => esc_html__( 'Icon', 'okthemes-toolkit' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'          => [
                    'value'   => 'fas fa-search',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    'icon_type' => 'icon',
                ],
                'label_block'      => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__( 'Style', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label'     => esc_html__( 'Icon Color(Hover)', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label'          => esc_html__( 'Width', 'okthemes-toolkit' ),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => 'px',
                    'size' => '20',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => '20',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => '20',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} a svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label'          => esc_html__( 'Height', 'okthemes-toolkit' ),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => 'px',
                    'size' => '20',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => '20',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => '20',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} a svg' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute(
            'wrapper',
            [
                'class' => [ 'site-search-widget' ]
            ]
        );

        $link_type = $settings['link_type'];
        $link_url = $settings['link_url'];
        $popup_id = $settings['template_id'];
        $link_data = '';
        $href = '#';
        $include_search_form_overlay = false;
        $class = '';
        
        if ( $link_type =='simple_link' &&  ! empty( $link_url['url'] ) ) {
            $href = $link_url['url'];
        }

        if ( $link_type == 'popup' && ! empty( $popup_id ) ) {
            $class = 'popup-trigger';
            $link_data = 'data-popup="popup-' . esc_attr( $popup_id ) . '"';
        }

        if ( $link_type =='theme_default' ) {
            $class = "toggle-search-box";
            $include_search_form_overlay = true;
        }

        $class_attr = $class ? ' class="' . esc_attr( $class ) . '"' : '';

        echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
            // The rest of your link rendering as before
            echo '<a ' . $link_data . $class_attr . ' href="' . esc_url( $href ) . '" title="' . esc_attr__( 'Search products', 'okthemes-toolkit' ) . '">';
            if ('icon' == $settings['icon_type']) {
                \Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
            } else {
                echo OKT_Helpers::get_theme_icon('header-search');
            }
            if ($include_search_form_overlay) {
                // Directly include the search form overlay part
                get_template_part('parts/part', 'searchform-overlay');
            }
            echo '</a>';
        echo '</div>';
    }

    protected function content_template() {
        ?>

        <#
            var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' );
            var wrapper_class = 'site-search-widget';
            view.addRenderAttribute(
                'wrapper',
                {
                    'class': [ 'site-search-widget' ]
                }
            );
        #>

        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
            <a class="top-header-search toggle-search-box" id="trigger-header-search" href="#" title="<?php esc_attr_e('Search products', 'okthemes-toolkit'); ?>">
            <# if ( settings.selected_icon.value  && settings.icon_type == 'icon' ) { #>    
                {{{ iconHTML.value }}}
            <# } else { #>
                <span class="torac-icon icon-header-search"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>     
            <# } #>
            </a>
        </div>
        
        <?php
    }

    /**
     * Get ALl Elementor Saved Template
     *
     * @since 1.0.0
     * @access protected
     */
    protected function select_saved_template() {
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
}