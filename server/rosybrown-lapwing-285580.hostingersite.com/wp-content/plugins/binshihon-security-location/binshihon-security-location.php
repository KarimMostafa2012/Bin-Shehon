<?php
/**
 * Plugin Name: Bin Shihon Security Location
 * Description: Logs user login security data and stores precise browser location only after explicit user permission.
 * Version: 1.0.0
 * Author: Bin Shihon
 * Text Domain: binshihon-security-location
 */

defined('ABSPATH') || exit;

define('BSL_VERSION', '1.0.0');
define('BSL_PATH', plugin_dir_path(__FILE__));
define('BSL_URL', plugin_dir_url(__FILE__));
define('BSL_TABLE', 'binshihon_security_location_logs');

register_activation_hook(__FILE__, 'bsl_activate');
add_action('wp_login', 'bsl_log_login', 10, 2);
add_action('wp_enqueue_scripts', 'bsl_enqueue_assets');
add_action('admin_enqueue_scripts', 'bsl_enqueue_assets');
add_action('wp_ajax_bsl_save_location', 'bsl_save_location');
add_action('admin_menu', 'bsl_admin_menu');

function bsl_activate()
{
    global $wpdb;

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $table = bsl_table_name();
    $charset_collate = $wpdb->get_charset_collate();

    dbDelta(
        "CREATE TABLE {$table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL DEFAULT 0,
            event_type varchar(40) NOT NULL DEFAULT 'login',
            ip_address varchar(100) NOT NULL DEFAULT '',
            user_agent text NULL,
            latitude decimal(10,7) NULL,
            longitude decimal(10,7) NULL,
            accuracy decimal(10,2) NULL,
            location_status varchar(40) NOT NULL DEFAULT 'not_requested',
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY event_type (event_type),
            KEY created_at (created_at)
        ) {$charset_collate};"
    );
}

function bsl_table_name()
{
    global $wpdb;

    return $wpdb->prefix . BSL_TABLE;
}

function bsl_get_client_ip()
{
    $keys = array(
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR',
    );

    foreach ($keys as $key) {
        if (empty($_SERVER[$key])) {
            continue;
        }

        $value = sanitize_text_field(wp_unslash($_SERVER[$key]));
        $ip = trim(explode(',', $value)[0]);

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    return '';
}

function bsl_get_user_agent()
{
    return isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_textarea_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
}

function bsl_insert_log($user_id, $event_type, $location_status = 'not_requested')
{
    global $wpdb;

    $now = current_time('mysql');

    $wpdb->insert(
        bsl_table_name(),
        array(
            'user_id'         => absint($user_id),
            'event_type'      => sanitize_key($event_type),
            'ip_address'      => bsl_get_client_ip(),
            'user_agent'      => bsl_get_user_agent(),
            'location_status' => sanitize_key($location_status),
            'created_at'      => $now,
            'updated_at'      => $now,
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s', '%s')
    );

    return absint($wpdb->insert_id);
}

function bsl_log_login($user_login, $user)
{
    if (! $user instanceof WP_User) {
        return;
    }

    $log_id = bsl_insert_log($user->ID, 'login', 'not_requested');

    if ($log_id) {
        update_user_meta($user->ID, '_bsl_pending_log_id', $log_id);
    }
}

function bsl_enqueue_assets()
{
    if (! is_user_logged_in()) {
        return;
    }

    wp_enqueue_style(
        'binshihon-security-location',
        BSL_URL . 'assets/security-location.css',
        array(),
        BSL_VERSION
    );

    wp_enqueue_script(
        'binshihon-security-location',
        BSL_URL . 'assets/security-location.js',
        array(),
        BSL_VERSION,
        true
    );

    wp_localize_script(
        'binshihon-security-location',
        'BinShihonSecurityLocation',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('bsl_location_nonce'),
            'userId'  => get_current_user_id(),
            'prompt'  => array(
                'title'       => __('Secure this login?', 'binshihon-security-location'),
                'description' => __('Allow location only if you want this site to save your precise login location for account security.', 'binshihon-security-location'),
                'allow'       => __('Allow location', 'binshihon-security-location'),
                'dismiss'     => __('Not now', 'binshihon-security-location'),
            ),
        )
    );
}

function bsl_save_location()
{
    if (! is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Not logged in.'), 403);
    }

    check_ajax_referer('bsl_location_nonce', 'nonce');

    $user_id = get_current_user_id();
    $status = isset($_POST['status']) ? sanitize_key(wp_unslash($_POST['status'])) : 'unknown';
    $latitude = isset($_POST['latitude']) ? (float) wp_unslash($_POST['latitude']) : null;
    $longitude = isset($_POST['longitude']) ? (float) wp_unslash($_POST['longitude']) : null;
    $accuracy = isset($_POST['accuracy']) ? (float) wp_unslash($_POST['accuracy']) : null;
    $log_id = absint(get_user_meta($user_id, '_bsl_pending_log_id', true));

    if (! $log_id) {
        $log_id = bsl_insert_log($user_id, 'location', $status);
    }

    global $wpdb;

    $data = array(
        'location_status' => $status,
        'updated_at'      => current_time('mysql'),
    );
    $formats = array('%s', '%s');

    if ('granted' === $status && null !== $latitude && null !== $longitude) {
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['accuracy'] = $accuracy;
        $formats[] = '%f';
        $formats[] = '%f';
        $formats[] = '%f';
    }

    $wpdb->update(
        bsl_table_name(),
        $data,
        array(
            'id'      => $log_id,
            'user_id' => $user_id,
        ),
        $formats,
        array('%d', '%d')
    );

    delete_user_meta($user_id, '_bsl_pending_log_id');
    update_user_meta($user_id, '_bsl_location_prompted_at', time());

    wp_send_json_success(array('message' => 'Saved.'));
}

function bsl_admin_menu()
{
    add_users_page(
        __('Security Locations', 'binshihon-security-location'),
        __('Security Locations', 'binshihon-security-location'),
        'list_users',
        'binshihon-security-locations',
        'bsl_render_admin_page'
    );
}

function bsl_render_admin_page()
{
    if (! current_user_can('list_users')) {
        wp_die(esc_html__('You do not have permission to view this page.', 'binshihon-security-location'));
    }

    global $wpdb;

    $rows = $wpdb->get_results(
        "SELECT l.*, u.user_login, u.user_email, u.display_name
        FROM " . bsl_table_name() . " l
        LEFT JOIN {$wpdb->users} u ON u.ID = l.user_id
        ORDER BY l.created_at DESC
        LIMIT 200"
    );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Security Locations', 'binshihon-security-location'); ?></h1>
        <p><?php esc_html_e('Precise latitude/longitude appears only when the logged-in user explicitly allows browser location access.', 'binshihon-security-location'); ?></p>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Time', 'binshihon-security-location'); ?></th>
                    <th><?php esc_html_e('User', 'binshihon-security-location'); ?></th>
                    <th><?php esc_html_e('IP', 'binshihon-security-location'); ?></th>
                    <th><?php esc_html_e('Location', 'binshihon-security-location'); ?></th>
                    <th><?php esc_html_e('Accuracy', 'binshihon-security-location'); ?></th>
                    <th><?php esc_html_e('Status', 'binshihon-security-location'); ?></th>
                    <th><?php esc_html_e('Browser', 'binshihon-security-location'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)) : ?>
                    <tr><td colspan="7"><?php esc_html_e('No logs yet.', 'binshihon-security-location'); ?></td></tr>
                <?php else : ?>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <td><?php echo esc_html($row->created_at); ?></td>
                            <td>
                                <?php echo esc_html($row->display_name ?: $row->user_login); ?><br>
                                <small><?php echo esc_html($row->user_email); ?></small>
                            </td>
                            <td><?php echo esc_html($row->ip_address); ?></td>
                            <td>
                                <?php if (null !== $row->latitude && null !== $row->longitude) : ?>
                                    <a href="<?php echo esc_url('https://www.google.com/maps?q=' . rawurlencode($row->latitude . ',' . $row->longitude)); ?>" target="_blank" rel="noopener">
                                        <?php echo esc_html($row->latitude . ', ' . $row->longitude); ?>
                                    </a>
                                <?php else : ?>
                                    &mdash;
                                <?php endif; ?>
                            </td>
                            <td><?php echo null !== $row->accuracy ? esc_html($row->accuracy . ' m') : '&mdash;'; ?></td>
                            <td><?php echo esc_html($row->location_status); ?></td>
                            <td><small><?php echo esc_html(wp_trim_words($row->user_agent, 18, '...')); ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
