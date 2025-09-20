<?php
/**
 * Plugin Name: Marquee Text Plugin
 * Description: Adds a marquee text right after the <body> tag when enabled from the settings.
 * Version: 1.1
 * Author: Ahmet Batuhan Yigit
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add settings menu
function marquee_plugin_menu() {
    add_menu_page('Marquee Settings', 'Marquee Settings', 'manage_options', 'marquee-settings', 'marquee_settings_page');
}
add_action('admin_menu', 'marquee_plugin_menu');

// Settings page
function marquee_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_admin_referer('marquee_settings_update')) {
        update_option('marquee_text', sanitize_text_field($_POST['marquee_text']));
        update_option('marquee_enabled', isset($_POST['marquee_enabled']) ? 1 : 0);
    }

    $marquee_text = get_option('marquee_text', '');
    $marquee_enabled = get_option('marquee_enabled', 0);
    ?>
    <div class="wrap">
        <h1>Marquee Settings</h1>
        <form method="POST">
            <?php wp_nonce_field('marquee_settings_update'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="marquee_text">Marquee Text</label></th>
                    <td><input type="text" id="marquee_text" name="marquee_text" value="<?php echo esc_attr($marquee_text); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="marquee_enabled">Enable Marquee</label></th>
                    <td><input type="checkbox" id="marquee_enabled" name="marquee_enabled" value="1" <?php checked($marquee_enabled, 1); ?> /></td>
                </tr>
            </table>
            <p><input type="submit" value="Save Settings" class="button button-primary" /></p>
        </form>
    </div>
    <?php
}

// Add marquee after body tag
function add_marquee_after_body() {
    if (get_option('marquee_enabled', 0)) {
        $marquee_text = esc_html(get_option('marquee_text', ''));

        wp_enqueue_script('marquee-script', plugins_url('marquee-script.js', __FILE__), array(), '1.0', true);
        wp_localize_script('marquee-script', 'marquee_params', array(
            'marqueeText' => $marquee_text,
        ));

        // Enqueue CSS (create marquee-styles.css in your plugin directory)
        wp_enqueue_style('marquee-styles', plugins_url('marquee-styles.css', __FILE__));
    }
}
add_action('wp_footer', 'add_marquee_after_body');

?>