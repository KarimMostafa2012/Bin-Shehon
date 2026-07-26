<?php
namespace OKThemes\Toolkit\Elementor\Widgets;

use OKThemes\Toolkit\Functions\Helpers as OKT_Helpers;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Navigation extends Widget_Base {

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
        return 'okthemes-navigation-widget';
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
        return esc_html__( 'OKT - Navigation widget', 'okthemes-toolkit' );
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
        return 'eicon-mega-menu';
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
            'menu_type',
            [
                'label'   => esc_html__( 'Menu', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'theme-default' => esc_html__( 'Theme Default', 'okthemes-toolkit' ),
                    'custom'        => esc_html__( 'Custom Menu', 'okthemes-toolkit' ),
                ],
                'default' => 'theme-default',
            ]
        );

        $menus = $this->get_all_menus();

        if ( ! empty( $menus ) ) {
            $this->add_control(
                'selected_menu',
                [
                    'label'     => esc_html__( 'Select Menu', 'okthemes-toolkit' ),
                    'type'      => Controls_Manager::SELECT,
                    'options' => $menus,
                    'default' => array_keys( $menus )[0],
                    'save_default' => true,
                    'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menus Screen</a> to manage your menus.', 'neuron-builder'), admin_url('nav-menus.php')),
                    'condition' => [
                        'menu_type' => 'custom',
                    ],
                ]
            );

            $this->add_control(
                'selected_mobile_menu',
                [
                    'label'     => esc_html__( 'Select Mobile Menu', 'okthemes-toolkit' ),
                    'type'      => Controls_Manager::SELECT,
                    'options' => $menus,
                    'default' => array_keys( $menus )[0],
                    'save_default' => true,
                    'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menus Screen</a> to manage your menus.', 'neuron-builder'), admin_url('nav-menus.php')),
                ]
            );
        } else {
            $this->add_control(
                'nav_menu_notice',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => sprintf(__('<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus Screen</a> to create one.', 'neuron-builder'), admin_url('nav-menus.php?action=edit&menu=0')),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }

        $this->add_control(
            'menu_style',
            [
                'label'   => esc_html__( 'Menu style', 'okthemes-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__( 'Horizontal', 'okthemes-toolkit' ),
                    'vertical'   => esc_html__( 'Vertical', 'okthemes-toolkit' ),
                ],
                'default' => 'vertical',
            ]
        );

        $this->add_control(
            'mobile_open_label',
            [
                'label'       => esc_html__( 'Mobile: Open label', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Menu', 'okthemes-toolkit' ),
            ]
        );
        $this->add_control(
            'mobile_close_label',
            [
                'label'       => esc_html__( 'Mobile: Close label', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__( 'Close', 'okthemes-toolkit' ),
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
            'link_url',
            [
                'label'       => esc_html__( 'Link URL', 'okthemes-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'http://your-link.com',
                'label_block' => true,
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
            'widget_style',
            [
                'label' => esc_html__( 'Menu Items', 'okthemes-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'nav_item_align',
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
                    '{{WRAPPER}} .main-menu ul' => 'align-items: {{VALUE}};',
                ],

            ]
        );


        $this->add_responsive_control(
            'nav_item_padding',
            [
                'label'      => esc_html__( 'Item Padding', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .main-navigation-wrapper ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'menu_item_typography',
                'selector' => '{{WRAPPER}} .main-navigation-wrapper ul li a',
            ]
        );

        $this->add_control(
            'submenu_heading',
            [
                'label'     => esc_html__( 'Submenu', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'submenu_bg',
            [
                'label'     => esc_html__( 'Submenu Background', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    'body:not(.primary-navigation-open) {{WRAPPER}} .main-navigation-wrapper ul li ul' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'submenu_shadow',
                'selector' => 'body:not(.primary-navigation-open) {{WRAPPER}} .main-navigation-wrapper ul li ul',
            ]
        );

        $this->add_control(
            'submenu_item_divider',
            [
                'label'     => esc_html__( 'Item Divider', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    'body:not(.primary-navigation-open) {{WRAPPER}} .main-navigation-wrapper ul li ul li:not(:last-child)' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submenu_item_padding',
            [
                'label'      => esc_html__( 'Item Padding', 'okthemes-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    'body:not(.primary-navigation-open) {{WRAPPER}} .main-navigation-wrapper ul li ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'submenu_item_typography',
                'selector' => '{{WRAPPER}} .main-navigation-wrapper ul li ul.sub-menu li a',
            ]
        );

        $this->start_controls_tabs( 'nav-menu-tab' );

        $this->start_controls_tab(
            'menu_item_normal',
            [
                'label' => esc_html__( 'Normal', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'menu_item_color',
            [
                'label'     => esc_html__( 'Item Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .main-navigation-wrapper ul li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_item_color',
            [
                'label'     => esc_html__( 'Submenu Item Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .main-navigation-wrapper ul li ul.sub-menu li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'menu_item_hover',
            [
                'label' => esc_html__( 'Hover/Current', 'okthemes-toolkit' ),
            ]
        );

        $this->add_control(
            'menu_item_hover_color',
            [
                'label'     => esc_html__( 'Item Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .main-navigation-wrapper ul li a:hover'               => 'color: {{VALUE}};',
                    '{{WRAPPER}} .main-navigation-wrapper ul li.current_page_item > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_item_hover_color',
            [
                'label'     => esc_html__( 'Submenu Item Color', 'okthemes-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .main-navigation-wrapper ul li ul.sub-menu li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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

        $available_menus = $this->get_all_menus();

		if ( ! $available_menus ) {
			return;
		}

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute(
            'wrapper',
            [
                'class' => [ 'navigation-widget' ]
            ]
        );

        $link_url = $settings['link_url'];        

        ?>

        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> >

            <?php if ($settings['menu_style'] != 'vertical') :?>
            <div class="menu-button-container">
                <button id="primary-mobile-menu" class="button" aria-controls="primary-menu-list" aria-expanded="false">
                    <span class="dropdown-icon open"><?php echo esc_html($settings['mobile_open_label']); ?>
                        <?php echo OKT_Helpers::get_theme_icon('mobile-menu-toggle');?>
                    </span>
                    <span class="dropdown-icon close"><?php echo esc_html($settings['mobile_close_label']); ?>
                        <?php echo OKT_Helpers::get_theme_icon('mobile-menu-toggle-close');?>
                    </span>
                </button>
            </div>
            <?php endif; ?>

            <div class="main-navigation-wrapper" id="main-navbar">
                <?php

                $args = [
                    'container_class' => 'main-menu '.esc_attr($settings['menu_style']),
                    'menu_class'      => 'main-menu-regular '.esc_attr($settings['menu_style']),
                    'show_toggles'   => true,
                ];

                if ( 'custom' == $settings['menu_type'] && ! empty( $settings['selected_menu'] ) ) {
                    $args['menu'] = $settings['selected_menu'];
                } elseif ( has_nav_menu( 'main-menu' ) ) {
                    $args['theme_location'] = 'main-menu';
                }

                wp_nav_menu( $args );
                ?>
            </div>

        </div>
        <?php
    }
    
    /**
     * Get Menus List
     *
     * @since 1.0.0
     */

    private function get_all_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}
    

}