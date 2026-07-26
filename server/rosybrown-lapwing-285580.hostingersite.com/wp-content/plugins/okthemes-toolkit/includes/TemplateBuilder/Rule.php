<?php
namespace OKThemes\Toolkit\TemplateBuilder;

defined('ABSPATH') || exit;

class Rule {

    private static $current_page_type = null;
    private static $current_page_data = [];

    public static function instance() {
        static $instance = null;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    public function parse_exclusion_condition($page_id, $rules) {
        $hide = false;

        if (is_array($rules) && !empty($rules)) {
            foreach ($rules as $rule) {
                $case = $rule['rule'] ?? '';

                switch ($case) {
                    case 'entire_website':
                        $hide = true;
                        break;
                    case 'all_pages':
                        $hide = is_page();
                        break;
                    case 'front_page':
                        $hide = is_front_page();
                        break;
                    case 'post_page':
                        $hide = is_home();
                        break;
                    case 'post_details':
                        $hide = is_singular('post');
                        break;
                    case 'all_archive':
                        $hide = is_archive();
                        break;
                    case 'date_archive':
                        $hide = is_date();
                        break;
                    case 'author_archive':
                        $hide = is_author();
                        break;
                    case 'search_page':
                        $hide = is_search();
                        break;
                    case '404_page':
                        $hide = is_404();
                        break;
                    case 'specific_pages':
                        $hide = in_array($page_id, $rule['page_ids'] ?? []);
                        break;
                    case 'specific_posts':
                        $hide = in_array($page_id, $rule['posts_ids'] ?? []);
                        break;
                    case 'shop_page':
                        $hide = function_exists('is_shop') && is_shop();
                        break;
                    case 'product_details':
                        $hide = is_singular('product');
                        break;
                    case 'specific_products':
                        $hide = in_array($page_id, $rule['product_ids'] ?? []);
                        break;
                }

                if ($hide) break;
            }
        }

        return $hide;
    }

    public function get_current_page_type() {
        if (null === self::$current_page_type) {
            $type = '';
            $id   = get_the_ID();

            if (is_front_page()) {
                $type = 'is_front_page';
            } elseif (is_home()) {
                $type = 'is_home';
            } elseif (is_page()) {
                $type = function_exists('is_shop') && is_shop() ? 'is_shop_page' : 'is_page';
            } elseif (is_date()) {
                $type = 'is_date';
            } elseif (is_author()) {
                $type = 'is_author';
            } elseif (is_archive()) {
                $type = 'is_archive';
            } elseif (is_search()) {
                $type = 'is_search';
            } elseif (is_404()) {
                $type = 'is_404';
            } elseif (is_singular('post')) {
                $type = 'is_single';
            } elseif (is_singular('product')) {
                $type = 'is_product';
            }

            self::$current_page_type            = $type;
            self::$current_page_data['page_id'] = $id;
        }

        return self::$current_page_type;
    }

    public function get_templates_by_condition() {
        global $wpdb;

        $key       = 'okthemes_tb_include';
        $post_type = 'okthemes_template';

        if (isset(self::$current_page_data[$post_type])) {
            return self::$current_page_data[$post_type];
        }

        $type = $this->get_current_page_type();
        $id   = self::$current_page_data['page_id'] ?? get_the_ID();

        $meta_args = [
            "pm.meta_value LIKE '%\"entire_website\"%'"
        ];

        switch ($type) {
            case 'is_page':
            case 'is_front_page':
            case 'is_single':
            case 'is_product':
                $meta_args[] = "pm.meta_value LIKE '%\"{$id}\"%'";
                break;
        }

        $type_map = [
            'is_page'        => 'all_pages',
            'is_front_page'  => 'front_page',
            'is_home'        => 'post_page',
            'is_single'      => 'post_details',
            'is_archive'     => 'all_archive',
            'is_date'        => 'date_archive',
            'is_author'      => 'author_archive',
            'is_search'      => 'search_page',
            'is_404'         => '404_page',
            'is_shop_page'   => 'shop_page',
            'is_product'     => 'product_details',
        ];

        if (isset($type_map[$type])) {
            $meta_args[] = "pm.meta_value LIKE '%\"{$type_map[$type]}\"%'";
        }

        $sql = $wpdb->prepare(
            "SELECT p.ID, p.post_title, pm.meta_value 
             FROM {$wpdb->postmeta} pm 
             JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
             WHERE pm.meta_key = %s 
             AND p.post_type = %s 
             AND p.post_status = 'publish' 
             AND (" . implode(' OR ', $meta_args) . ") 
             ORDER BY p.post_date DESC",
            $key,
            $post_type
        );

        $results = $wpdb->get_results($sql);
        self::$current_page_data[$post_type] = [];
        foreach ($results as $template) {
            $settings = get_post_meta($template->ID, 'okthemes_tb_settings', true);
            self::$current_page_data[$post_type][$template->ID] = [
                'id'       => $template->ID,
                'type'     => $settings['template_type'] ?? '',
                'location' => unserialize($template->meta_value),
            ];
        }

        $this->remove_exclusion_rule_templates($post_type, $id);

        return self::$current_page_data[$post_type];
    }

    public function remove_exclusion_rule_templates($post_type, $page_id) {
        foreach (self::$current_page_data[$post_type] as $id => $data) {
            $rules = get_post_meta($id, 'okthemes_tb_exclude', true);
            if ($this->parse_exclusion_condition($page_id, $rules)) {
                unset(self::$current_page_data[$post_type][$id]);
            }
        }
    }
}

Rule::instance();
