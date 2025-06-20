<?php
/*
Plugin Name: BurnNote.io
Description: Create private, self-destructing notes with one-time-view links.
Version: 1.3.9
Author: Your Name
*/

// Encryption functions
function burnnote_encrypt_message($message) {
  if (!defined('BURNNOTE_SECRET_KEY')) {
      wp_die('BurnNote encryption key is not configured.');
  }

  $key = hash('sha256', BURNNOTE_SECRET_KEY, true);
  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

  $encrypted = openssl_encrypt($message, 'aes-256-cbc', $key, 0, $iv);
  if ($encrypted === false) {
      wp_die('Encryption failed');
  }

  // Combine IV and encrypted data
  return base64_encode($iv . $encrypted);
}

function burnnote_decrypt_message($encrypted_data) {
  if (!defined('BURNNOTE_SECRET_KEY')) {
      wp_die('BurnNote encryption key is not configured.');
  }

  $key = hash('sha256', BURNNOTE_SECRET_KEY, true);
  $data = base64_decode($encrypted_data);

  // Extract IV and encrypted message
  $iv_length = openssl_cipher_iv_length('aes-256-cbc');
  $iv = substr($data, 0, $iv_length);
  $encrypted = substr($data, $iv_length);

  $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
  if ($decrypted === false) {
      wp_die('Decryption failed');
  }

  return $decrypted;
}

add_shortcode('burnnote_form', 'burnnote_form_shortcode');
add_action('init', 'burnnote_view_note');

function burnnote_form_shortcode() {
    ob_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['burnnote_message'])) {
        // Cloudflare Turnstile validation
        if (!empty($_POST['cf-turnstile-response'])) {
            $turnstile_response = sanitize_text_field($_POST['cf-turnstile-response']);

            $verify_response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'body' => [
                    'secret'   => defined('CF_TURNSTILE_SECRET_KEY') ? CF_TURNSTILE_SECRET_KEY : '',
                    'response' => $turnstile_response,
                    'remoteip' => $_SERVER['REMOTE_ADDR'],
                ],
            ]);

            if (is_wp_error($verify_response)) {
                return '<div class="burnnote-error">Verification request failed. Please try again.</div>';
            }

            $body = json_decode(wp_remote_retrieve_body($verify_response), true);

            if (!isset($body['success']) || !$body['success']) {
                $error_codes = isset($body['error-codes']) ? implode(', ', $body['error-codes']) : 'Unknown error';
                return '<div class="burnnote-error">Security check failed: ' . esc_html($error_codes) . '</div>';
            }
        } else {
            return '<div class="burnnote-error">Turnstile token missing. Please refresh and try again.</div>';
        }

        // End
        global $wpdb;
        $table = $wpdb->prefix . 'burnnotes';
        $message = sanitize_textarea_field($_POST['burnnote_message']);
        $token = wp_generate_password(32, false);

        $password = !empty($_POST['new_burnnote_password']) ? sanitize_text_field($_POST['new_burnnote_password']) : null;
        $password_hash = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

        // Encrypt the message before storing
        $encrypted_message = burnnote_encrypt_message($message);

        $wpdb->insert($table, [
            'token' => $token,
            'message' => $encrypted_message,
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
    if (!isset($_GET['burnnote_view']) && !isset($_GET['burnnote_reveal'])) return;

    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    global $wpdb;
    $table = $wpdb->prefix . 'burnnotes';
    
    // Handle the reveal flow
    if (isset($_GET['burnnote_reveal'])) {
        $token = sanitize_text_field($_GET['burnnote_reveal']);
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
            // Check if password was already verified in this session
            $session_key = 'burnnote_verified_' . $token;
            if (!isset($_SESSION[$session_key])) {
                if (!isset($_POST['burnnote_password'])) {
                    include plugin_dir_path(__FILE__) . 'templates/password-form.php';
                    exit;
                }

                $submitted_password = sanitize_text_field($_POST['burnnote_password']);
                if (!password_verify($submitted_password, $row->password)) {
                    include plugin_dir_path(__FILE__) . 'templates/incorrect-password.php';
                    exit;
                }
                
                // Mark as verified in session
                $_SESSION[$session_key] = true;
            }
        }
        
        // Mark as viewed and delete after displaying
        $wpdb->update($table, ['viewed' => 1], ['token' => $token]);
        
        // If no expiration time is set, delete it
        if (empty($row->expires_at)) {
            $wpdb->delete($table, ['token' => $token]);
        }

        // Decrypt the message before displaying
        $message = esc_html(burnnote_decrypt_message($row->message));
        include plugin_dir_path(__FILE__) . 'templates/message-view.php';
        exit;
    }
    
    // Handle the initial view (show reveal prompt)
    if (isset($_GET['burnnote_view'])) {
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
            // Check if password was already verified in this session
            $session_key = 'burnnote_verified_' . $token;
            if (!isset($_SESSION[$session_key])) {
                if (!isset($_POST['burnnote_password'])) {
                    include plugin_dir_path(__FILE__) . 'templates/password-form.php';
                    exit;
                }

                $submitted_password = sanitize_text_field($_POST['burnnote_password']);
                if (!password_verify($submitted_password, $row->password)) {
                    include plugin_dir_path(__FILE__) . 'templates/incorrect-password.php';
                    exit;
                }
                
                // Mark as verified in session
                $_SESSION[$session_key] = true;
            }
        }
        
        // Show the reveal prompt
        include plugin_dir_path(__FILE__) . 'templates/reveal-prompt.php';
        exit;
    }
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

add_action('wp_enqueue_scripts', 'burnnote_enqueue_scripts');
function burnnote_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('burnnote-script', plugins_url('burnnote-io.js', __FILE__), array('jquery'), '1.0.0', true);
        wp_localize_script('burnnote-script', 'burnnote_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('burnnote_nonce')
        ));
    }
}

add_action('wp_enqueue_scripts', 'burnnote_enqueue_turnstile');
function burnnote_enqueue_turnstile() {
    $post = get_post();
    if ($post && has_shortcode($post->post_content, 'burnnote_form')) {
        wp_enqueue_script(
            'cf-turnstile',
            'https://challenges.cloudflare.com/turnstile/v0/api.js',
            [],
            null,
            true
        );
    }
}

add_action('wp_head', 'burnnote_preload_lock_icon');
function burnnote_preload_lock_icon() {
    $post = get_post();
    if ($post && is_page() && has_shortcode($post->post_content, 'burnnote_form')) {
        $attachment_id = 61; // your uploaded icon
        $img_url = wp_get_attachment_url($attachment_id);
        if ($img_url) {
            echo '<link rel="preload" as="image" href="' . esc_url($img_url) . '" type="image/png" fetchpriority="high">' . "\n";
        }
    }
}
