<?php
namespace OKThemes\Toolkit\TemplateBuilder;

defined('ABSPATH') || exit;

class Admin {
    private static $instance = null;

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_filter('manage_okthemes_template_posts_columns', [$this, 'custom_columns']);
        add_filter('manage_okthemes_template_posts_custom_column', [$this, 'display_custom_columns']);

        add_filter('wp_setup_nav_menu_item', function ($menu_item) {
            if ($menu_item->object === 'okthemes_template') {
                $menu_item->type_label = __('Theme Builder Mega Menu', 'okthemes-toolkit');
            }
            return $menu_item;
        });

        add_filter('nav_menu_items_okthemes_template', [$this, 'filter_template_in_menu']);
        add_filter('nav_menu_items_okthemes_template_recent', [$this, 'filter_template_in_menu']);
    }

    public function admin_menu() {
        add_menu_page(
            __('Template Builder', 'okthemes-toolkit'),
            __('Template Builder', 'okthemes-toolkit'),
            'manage_options',
            'edit.php?post_type=okthemes_template',
            '',
            'dashicons-layout',
            2
        );
    }

    public function custom_columns($columns) {
        $columns['type'] = __('Type', 'okthemes-toolkit');
        $columns['info'] = __('Info', 'okthemes-toolkit');
        return $columns;
    }

    public function display_custom_columns($name) {
        global $post;

        switch ($name) {
            case 'type':
                echo ucwords(str_replace('_', ' ', $this->get_template_type($post->ID)));
                break;
            case 'info':
                echo $this->get_item_info($post->ID);
                break;
        }
    }

    public function get_template_type($post_id) {
        $meta = get_post_meta($post_id, 'okthemes_tb_settings', true);
        return $meta['template_type'] ?? '';
    }

    public function get_item_info($post_id) {
        $type = $this->get_template_type($post_id);
        $info = '';

        if ($type === 'block') {
            $info = '<input class="wp-ui-text-highlight code widefat" type="text" onfocus="this.select();" readonly="readonly" value="[okthemes-tb-block id=&quot;' . $post_id . '&quot;]">';
        } elseif ($type === 'mega_menu') {
            $settings = get_post_meta($post_id, 'okthemes_tb_settings', true);
            $info = '<b>' . esc_html__('Width:', 'okthemes-toolkit') . '</b> ' . ucfirst($settings['mega_menu_width']);
            if ($settings['mega_menu_width'] === 'custom') {
                $info .= ' (' . $settings['mega_menu_custom_width']['width'] . 'px)';
            }
        } elseif ($type === 'offcanvas') {
            $settings = get_post_meta($post_id, 'okthemes_tb_settings', true);
            $info .= '<b>' . esc_html__('Width:', 'okthemes-toolkit') . '</b> ' . $settings['offcanvas_width']['width'] . 'px';
        } else {
            $info = $this->get_pretty_condition('include', $post_id) . '<br>' . $this->get_pretty_condition('exclude', $post_id);
        }

        return $info;
    }

    public function get_pretty_condition($type, $post_id) {
        $info = '';
        $include = get_post_meta($post_id, 'okthemes_tb_' . $type, true);

        if (is_array($include)) {
            $info .= '<b>' . ucfirst($type) . ': </b>';
            $index = 0;

            foreach ($include as $rule) {
                if ($index > 0) {
                    $info .= ', ';
                }
                $info .= ucwords(str_replace('_', ' ', $rule['rule']));
                $index++;
            }
        }

        return $info;
    }

    public function filter_template_in_menu($menu_item) {
        $new_items = [];
        foreach ($menu_item as $item) {
            if ($this->get_template_type($item->ID) === 'mega_menu') {
                $new_items[] = $item;
            }
        }
        return $new_items;
    }
}

Admin::instance();
