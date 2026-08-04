<?php
/**
 * Plugin Name: Single Product Roller
 * Description: Adds repeatable image, title, and description rows to products with a shortcode renderer.
 * Version: 1.0.1
 * Author: Bin Shihon
 * Text Domain: single-product-roller
 */

defined('ABSPATH') || exit;

define('SPR_VERSION', '1.0.1');
define('SPR_PATH', plugin_dir_path(__FILE__));
define('SPR_URL', plugin_dir_url(__FILE__));
define('SPR_META_KEY', '_spr_items');

add_action('add_meta_boxes', 'spr_add_product_meta_box');
add_action('save_post_product', 'spr_save_product_meta_box');
add_action('admin_enqueue_scripts', 'spr_enqueue_admin_assets');
add_action('wp_enqueue_scripts', 'spr_register_frontend_assets');
add_shortcode('single_product_roller', 'spr_shortcode');
add_shortcode('binshihon_single_product_roller', 'spr_shortcode');

function spr_add_product_meta_box()
{
    add_meta_box(
        'spr_product_roller',
        esc_html__('Single Product Roller', 'single-product-roller'),
        'spr_render_product_meta_box',
        'product',
        'normal',
        'default'
    );
}

function spr_enqueue_admin_assets($hook)
{
    if (! in_array($hook, array('post.php', 'post-new.php'), true)) {
        return;
    }

    $screen = get_current_screen();

    if (! $screen || 'product' !== $screen->post_type) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style(
        'single-product-roller-admin',
        SPR_URL . 'assets/css/admin.css',
        array(),
        SPR_VERSION
    );
    wp_enqueue_script(
        'single-product-roller-admin',
        SPR_URL . 'assets/js/admin.js',
        array('jquery'),
        SPR_VERSION,
        true
    );
}

function spr_register_frontend_assets()
{
    wp_register_style(
        'single-product-roller',
        SPR_URL . 'assets/css/single-product-roller.css',
        array(),
        SPR_VERSION
    );
    wp_register_script(
        'single-product-roller',
        SPR_URL . 'assets/js/single-product-roller.js',
        array(),
        SPR_VERSION,
        true
    );
}

function spr_get_items($product_id)
{
    $items = get_post_meta($product_id, SPR_META_KEY, true);

    if (! is_array($items)) {
        return array();
    }

    return array_values(
        array_filter(
            array_map(
                function ($item) {
                    $image_id    = isset($item['image_id']) ? absint($item['image_id']) : 0;
                    $title       = isset($item['title']) ? sanitize_text_field($item['title']) : '';
                    $description = isset($item['description']) ? wp_kses_post($item['description']) : '';

                    if (! $image_id && '' === $title && '' === $description) {
                        return null;
                    }

                    return array(
                        'image_id'    => $image_id,
                        'title'       => $title,
                        'description' => $description,
                    );
                },
                $items
            )
        )
    );
}

function spr_render_product_meta_box($post)
{
    wp_nonce_field('spr_save_product_roller', 'spr_product_roller_nonce');

    $items = spr_get_items($post->ID);

    if (empty($items)) {
        $items = array(
            array(
                'image_id'    => 0,
                'title'       => '',
                'description' => '',
            ),
        );
    }
    ?>
    <div class="spr-admin" data-spr-admin>
        <div class="spr-admin__rows" data-spr-rows>
            <?php foreach ($items as $index => $item) : ?>
                <?php spr_render_admin_row($index, $item); ?>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button button-primary spr-admin__add" data-spr-add>
            <?php esc_html_e('Add row', 'single-product-roller'); ?>
        </button>
        <script type="text/html" data-spr-template>
            <?php
            spr_render_admin_row(
                '__INDEX__',
                array(
                    'image_id'    => 0,
                    'title'       => '',
                    'description' => '',
                )
            );
            ?>
        </script>
    </div>
    <?php
}

function spr_render_admin_row($index, $item)
{
    $image_id  = isset($item['image_id']) ? absint($item['image_id']) : 0;
    $image_src = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
    ?>
    <div class="spr-admin__row" data-spr-row>
        <div class="spr-admin__image">
            <input type="hidden" name="spr_items[<?php echo esc_attr($index); ?>][image_id]" value="<?php echo esc_attr($image_id); ?>" data-spr-image-id>
            <button type="button" class="spr-admin__preview" data-spr-select-image>
                <?php if ($image_src) : ?>
                    <img src="<?php echo esc_url($image_src); ?>" alt="">
                <?php else : ?>
                    <span><?php esc_html_e('Choose image', 'single-product-roller'); ?></span>
                <?php endif; ?>
            </button>
            <button type="button" class="button-link spr-admin__remove-image" data-spr-remove-image>
                <?php esc_html_e('Remove image', 'single-product-roller'); ?>
            </button>
        </div>
        <div class="spr-admin__fields">
            <label>
                <span><?php esc_html_e('Title', 'single-product-roller'); ?></span>
                <input type="text" name="spr_items[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr(isset($item['title']) ? $item['title'] : ''); ?>">
            </label>
            <label>
                <span><?php esc_html_e('Description', 'single-product-roller'); ?></span>
                <textarea name="spr_items[<?php echo esc_attr($index); ?>][description]" rows="4"><?php echo esc_textarea(isset($item['description']) ? $item['description'] : ''); ?></textarea>
            </label>
            <button type="button" class="button spr-admin__remove-row" data-spr-remove-row>
                <?php esc_html_e('Remove row', 'single-product-roller'); ?>
            </button>
        </div>
    </div>
    <?php
}

function spr_save_product_meta_box($post_id)
{
    if (
        ! isset($_POST['spr_product_roller_nonce'])
        || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['spr_product_roller_nonce'])), 'spr_save_product_roller')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    $raw_items = isset($_POST['spr_items']) && is_array($_POST['spr_items']) ? wp_unslash($_POST['spr_items']) : array();
    $items     = array();

    foreach ($raw_items as $item) {
        if (! is_array($item)) {
            continue;
        }

        $image_id    = isset($item['image_id']) ? absint($item['image_id']) : 0;
        $title       = isset($item['title']) ? sanitize_text_field($item['title']) : '';
        $description = isset($item['description']) ? wp_kses_post($item['description']) : '';

        if (! $image_id && '' === $title && '' === $description) {
            continue;
        }

        $items[] = array(
            'image_id'    => $image_id,
            'title'       => $title,
            'description' => $description,
        );
    }

    if (empty($items)) {
        delete_post_meta($post_id, SPR_META_KEY);
        return;
    }

    update_post_meta($post_id, SPR_META_KEY, $items);
}

function spr_shortcode($atts)
{
    $atts = shortcode_atts(
        array(
            'product_id' => 0,
            'class'      => '',
        ),
        $atts,
        'single_product_roller'
    );

    $product_id = absint($atts['product_id']);

    if (! $product_id && function_exists('is_product') && is_product()) {
        $product_id = get_queried_object_id();
    }

    if (! $product_id) {
        return '';
    }

    $items = spr_get_items($product_id);

    if (empty($items)) {
        return '';
    }

    wp_enqueue_style('single-product-roller');
    wp_enqueue_script('single-product-roller');

    $classes = trim('spr-roller ' . sanitize_html_class($atts['class']));
    $slide_count = count($items);

    ob_start();
    ?>
    <section class="<?php echo esc_attr($classes); ?>" data-spr-roller data-spr-index="0" style="--spr-slide-count: <?php echo esc_attr($slide_count); ?>; --spr-rotation: 0deg;">
        <div class="spr-roller__visual">
            <div class="spr-roller__circle" data-spr-circle>
                <?php foreach ($items as $index => $item) : ?>
                    <figure class="spr-roller__media<?php echo 0 === $index ? ' is-active' : ''; ?>" data-spr-media="<?php echo esc_attr($index); ?>">
                        <?php if (! empty($item['image_id'])) : ?>
                            <?php echo wp_get_attachment_image($item['image_id'], 'large'); ?>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="spr-roller__body">
            <div class="spr-roller__slides">
                <?php foreach ($items as $index => $item) : ?>
                    <article class="spr-roller__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-spr-slide="<?php echo esc_attr($index); ?>">
                        <?php if ('' !== $item['title']) : ?>
                            <h3 class="spr-roller__title"><?php echo esc_html($item['title']); ?></h3>
                        <?php endif; ?>
                        <?php if ('' !== $item['description']) : ?>
                            <div class="spr-roller__description"><?php echo wp_kses_post(wpautop($item['description'])); ?></div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php if ($slide_count > 1) : ?>
                <div class="spr-roller__controls">
                    <button type="button" class="spr-roller__arrow" data-spr-prev aria-label="<?php esc_attr_e('Previous slide', 'single-product-roller'); ?>">&larr;</button>
                    <div class="spr-roller__dots" role="tablist">
                        <?php foreach ($items as $index => $item) : ?>
                            <button type="button" class="spr-roller__dot<?php echo 0 === $index ? ' is-active' : ''; ?>" data-spr-dot="<?php echo esc_attr($index); ?>" aria-label="<?php echo esc_attr(sprintf(__('Show slide %d', 'single-product-roller'), $index + 1)); ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="spr-roller__arrow" data-spr-next aria-label="<?php esc_attr_e('Next slide', 'single-product-roller'); ?>">&rarr;</button>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php

    return ob_get_clean();
}
