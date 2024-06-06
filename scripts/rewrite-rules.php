<?php

function trufi_maps_add_rewrite_rules() {
    $map_page_id = get_option(TRUFI_MAP_PAGE_ID_OPTION);
    $base_path   = ($map_page_id)
        ? get_post_field('post_name', $map_page_id)
        : 'routes';
    add_rewrite_tag('%trufi_map_id%', '([^/]+)');
    add_rewrite_tag('%trufi_map_name%', '([^/]+)');
    add_rewrite_rule("^{$base_path}/([^/]+)/([^/]+)/?", 'index.php?trufi_map_name=$matches[1]&trufi_map_id=$matches[2]', 'top');
    add_rewrite_endpoint($base_path, EP_PERMALINK | EP_PAGES);
}

add_action('init', 'trufi_maps_add_rewrite_rules');
