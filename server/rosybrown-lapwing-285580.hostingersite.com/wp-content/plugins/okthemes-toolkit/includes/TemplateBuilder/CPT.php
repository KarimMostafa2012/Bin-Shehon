<?php
namespace OKThemes\Toolkit\TemplateBuilder;

defined('ABSPATH') || exit;

class CPT {
    private static $instance = null;

    private $type = 'okthemes_template';
    private $slug = 'okthemes_template';
    private $name;
    private $singular_name;
    private $plural_name;

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', [$this, 'setup_post_type']);
        add_filter('single_template', [$this, 'custom_templates']);
    }

    public function setup_post_type() {
        $this->name          = __('Template Builder', 'okthemes-toolkit');
        $this->singular_name = __('Template', 'okthemes-toolkit');
        $this->plural_name   = __('Templates', 'okthemes-toolkit');
        $this->register_post_type();

        if (get_option('okthemes_tb_flush_rewrite') === 'yes') {
            flush_rewrite_rules(false); // false = don't hard refresh .htaccess
            update_option('okthemes_tb_flush_rewrite', 'no');
        }
    }

    public function register_post_type() {
        $labels = [
            'name'               => $this->name,
            'singular_name'      => $this->singular_name,
            'add_new'            => sprintf(__('Add New %s', 'okthemes-toolkit'), $this->singular_name),
            'add_new_item'       => sprintf(__('Add New %s', 'okthemes-toolkit'), $this->singular_name),
            'edit_item'          => sprintf(__('Edit %s', 'okthemes-toolkit'), $this->singular_name),
            'new_item'           => sprintf(__('New %s', 'okthemes-toolkit'), $this->singular_name),
            'all_items'          => sprintf(__('All %s', 'okthemes-toolkit'), $this->plural_name),
            'view_item'          => sprintf(__('View %s', 'okthemes-toolkit'), $this->name),
            'search_items'       => sprintf(__('Search %s', 'okthemes-toolkit'), $this->name),
            'not_found'          => sprintf(__('No %s found', 'okthemes-toolkit'), strtolower($this->name)),
            'not_found_in_trash' => sprintf(__('No %s found in Trash', 'okthemes-toolkit'), strtolower($this->name)),
            'parent_item_colon'  => '',
            'menu_name'          => $this->name,
        ];

        $args = [
            'labels'              => $labels,
            'has_archive'         => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'show_in_nav_menu'    => true,
            'public'              => true,
            'rewrite'             => ['slug' => $this->slug],
            'show_in_rest'        => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'menu_icon'           => 'dashicons-layout',
            'supports'            => ['title', 'author', 'elementor'],
        ];

        register_post_type($this->type, $args);
    }

    public function custom_templates($single_template) {
        global $post;

        if ($post->post_type === $this->type) {
            $meta = get_post_meta($post->ID, 'okthemes_tb_settings', true);
            $template_type = $meta['template_type'] ?? '';

            if ( 'popup' === $template_type ) {
                $single_template = OKT_INCLUDES . '/TemplateBuilder/templates/popup.php';
            } elseif ( 'offcanvas' === $template_type ) {
                $single_template = OKT_INCLUDES . '/TemplateBuilder/templates/offcanvas.php';
            } else {
                $single_template = OKT_INCLUDES . '/TemplateBuilder/templates/canvas.php';
            }

        }

        return $single_template;
    }
}

CPT::instance();
