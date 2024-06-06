<?php
function trufi_cache_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    ?>
    <div class="wrap">
        <p>Click the button below to clear all route caches:</p>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('clear_trufi_caches', 'clear_trufi_caches_nonce'); ?>
            <input type="hidden" name="action" value="clear_trufi_caches">
            <input type="submit" class="button button-primary" value="Clear Route Cache">
        </form>
    </div>
    <?php
}

function clear_trufi_caches() {
    if (!isset($_POST['clear_trufi_caches_nonce']) || !wp_verify_nonce($_POST['clear_trufi_caches_nonce'], 'clear_trufi_caches')) {
        return;
    }

    global $wpdb;

    // Delete all Trufi route transients
    $wpdb->query("DELETE FROM {$wpdb->options} 
                        WHERE option_name LIKE '_transient_trufi_route%'
                        OR  option_name LIKE '_transient_timeout_trufi_route%';");

    wp_redirect(add_query_arg('cache_cleared', 'true', admin_url('options-general.php?page=trufi-cache-settings')));
    exit;
}

add_action('admin_post_clear_trufi_caches', 'clear_trufi_caches');


function display_trufi_cache_cleared_notice() {
    if (isset($_GET['cache_cleared']) && $_GET['cache_cleared'] === 'true') {
        $message = 'Route cache has been cleared.';
        printf('<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html($message));
    }
}

add_action('admin_notices', 'display_trufi_cache_cleared_notice');


function trufi_add_cache_settings_page() {
    add_options_page(
        __('Trufi Route Cache Settings', 'TrufiApi-routes'),
        __('Trufi Route Cache', 'TrufiApi-routes'),
        'manage_options',
        'trufi-cache-settings',
        'trufi_cache_settings_page'
    );
}

add_action('admin_menu', 'trufi_add_cache_settings_page');
