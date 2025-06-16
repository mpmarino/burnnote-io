<?php
/*
Plugin Name: BurnNote.io
Description: Create private, self-destructing notes with one-time-view links.
Version: 1.3.8
Author: Your Name
*/

add_shortcode('burnnote_form', 'burnnote_form_shortcode');
add_action('init', 'burnnote_view_note');

function burnnote_form_shortcode() {
    ob_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['burnnote_message'])) {
        global $wpdb;
        $table = $wpdb->prefix . 'burnnotes';
        $message = sanitize_textarea_field($_POST['burnnote_message']);
        $token = wp_generate_password(32, false);

        $password = !empty($_POST['new_burnnote_password']) ? sanitize_text_field($_POST['new_burnnote_password']) : null;
        $password_hash = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

        $wpdb->insert($table, [
            'token' => $token,
            'message' => $message,
            'password' => $password_hash,
            'viewed' => 0,
            'expires_at' => null
        ]);

        $redirect_url = add_query_arg('burnnote_token', $token, get_permalink());
        wp_safe_redirect($redirect_url);
        exit;
    }

    if (isset($_GET['burnnote_token'])) {
        global $wpdb;
        $token = sanitize_text_field($_GET['burnnote_token']);
        $table = $wpdb->prefix . 'burnnotes';
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE token = %s", $token));

        if (!$row) {
            include plugin_dir_path(__FILE__) . 'templates/invalid.php';
        } elseif ($row->viewed) {
            include plugin_dir_path(__FILE__) . 'templates/already-viewed.php';
        } else {
            $link = site_url("?burnnote_view=" . $token);
            include plugin_dir_path(__FILE__) . 'templates/link-display.php';
        }
        return ob_get_clean();
    }

    include plugin_dir_path(__FILE__) . 'templates/form.php';
    return ob_get_clean();
}

function burnnote_view_note() {
    if (!isset($_GET['burnnote_view'])) return;

    global $wpdb;
    $table = $wpdb->prefix . 'burnnotes';
    $token = sanitize_text_field($_GET['burnnote_view']);
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE token = %s", $token));

    if (!$row) {
        include plugin_dir_path(__FILE__) . 'templates/invalid.php';
        exit;
    }

    if ($row->viewed) {
        include plugin_dir_path(__FILE__) . 'templates/already-viewed.php';
        exit;
    }

    if (!empty($row->password)) {
        if (!isset($_POST['burnnote_password'])) {
            include plugin_dir_path(__FILE__) . 'templates/password-form.php';
            exit;
        }

        $submitted_password = sanitize_text_field($_POST['burnnote_password']);
        if (!password_verify($submitted_password, $row->password)) {
            include plugin_dir_path(__FILE__) . 'templates/incorrect-password.php';
            exit;
        }
    }

    $wpdb->update($table, ['viewed' => 1], ['token' => $token]);
    $message = esc_html($row->message);
    include plugin_dir_path(__FILE__) . 'templates/message-view.php';
    exit;
}

register_activation_hook(__FILE__, 'burnnote_create_table');
function burnnote_create_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'burnnotes';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table (
        id BIGINT NOT NULL AUTO_INCREMENT,
        token VARCHAR(64) NOT NULL UNIQUE,
        message TEXT NOT NULL,
        password VARCHAR(255) DEFAULT NULL,
        viewed TINYINT(1) DEFAULT 0,
        expires_at DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('wp_enqueue_scripts', 'burnnote_enqueue_styles');
function burnnote_enqueue_styles() {
    if (!is_admin()) {
        wp_enqueue_style('burnnote-style', plugins_url('burnnote-io.css', __FILE__));
    }
}