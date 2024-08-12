<?php
/**
 * Plugin Name: Error Log Manager
 * Description: A simple plugin to view and clear the error.log file.
 * Version: 1.0
 * Author: Your Name
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class ErrorLogManager {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu() {
        add_menu_page(
            'Error Log Manager',
            'Error Log Manager',
            'manage_options',
            'error-log-manager',
            array($this, 'display_error_log_page')
        );
    }

    public function display_error_log_page() {
        ?>
        <div class="wrap">
            <h1>Error Log Manager</h1>
            <?php
            if (isset($_POST['clear_log'])) {
                $this->clear_error_log();
                echo '<div class="updated"><p>Error log cleared!</p></div>';
            }
            $this->display_error_log();
            ?>
            <form method="post">
                <input type="submit" name="clear_log" class="button button-primary" value="Clear Error Log">
            </form>
            <div class="notice notice-warning">
                <p><strong>Important:</strong> To ensure this plugin works correctly, please make sure that debugging is enabled in your <code>wp-config.php</code> file. Add or ensure the following lines are present:</p>
                <pre>
<code>define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);</code>
                </pre>
                <p>This configuration allows the plugin to access and manage the <code>debug.log</code> file in the <code>wp-content</code> directory.</p>
            </div>
        </div>
        <?php
    }

    private function display_error_log() {
        $log_file = WP_CONTENT_DIR . '/debug.log';
        
        if (file_exists($log_file)) {
            $log_contents = file_get_contents($log_file);
            echo '<textarea rows="20" cols="100" readonly>' . esc_textarea($log_contents) . '</textarea>';
        } else {
            echo '<p>No error log found. Ensure debugging is enabled in <code>wp-config.php</code>.</p>';
        }
    }

    private function clear_error_log() {
        $log_file = WP_CONTENT_DIR . '/debug.log';
        if (file_exists($log_file)) {
            file_put_contents($log_file, '');
        }
    }
}

new ErrorLogManager();
