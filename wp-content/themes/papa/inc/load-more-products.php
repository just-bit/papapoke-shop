<?php
add_action('wp_ajax_papa_load_more_products', 'papa_load_more_products');
add_action('wp_ajax_nopriv_papa_load_more_products', 'papa_load_more_products');

function papa_load_more_products() {
    if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'papa_load_more_nonce')) {
        wp_die();
    }

    $tab = sanitize_text_field($_POST['tab'] ?? '');
    $page = intval($_POST['page'] ?? 2);
    
    if(!$tab) wp_die();

    $tabs = [
        'bowls' => 'menu_tabs_and_item_bowls',
        'rolls' => 'menu_tabs_and_item_rolls',
        'extras' => 'menu_tabs_and_item_extras',
        'drinks' => 'menu_tabs_and_item_drinks'
    ];

    if(!isset($tabs[$tab])) wp_die();

    $page_id = get_option('page_on_front');
    $product_ids = get_field($tabs[$tab], $page_id);
    
    if(empty($product_ids)) wp_die();

    $instock_ids = array_filter((array)$product_ids, function($id) {
        return get_post_meta($id, '_stock_status', true) === 'instock';
    });
    
    if(empty($instock_ids)) wp_die();

    $instock_ids = array_values($instock_ids);
    $batch = 12;
    $offset = ($page - 1) * $batch;
    
    $next_ids = array_slice($instock_ids, $offset, $batch);
    
    if(empty($next_ids)) wp_die();

    $query = new WP_Query([
        'post_type' => 'product',
        'post__in' => $next_ids,
        'posts_per_page' => $batch,
        'orderby' => 'post__in',
        'meta_query' => [[
            'key' => '_stock_status',
            'value' => 'instock'
        ]]
    ]);

    if($query->have_posts()) {
        while($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        wp_reset_postdata();
    }

    wp_die();
}
