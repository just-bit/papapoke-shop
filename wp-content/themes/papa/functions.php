<?php

// Exclude Stripe & WooCommerce Payments scripts from WP Rocket Delay JS
add_filter( 'rocket_delay_js_exclusions', function( $exclusions ) {
    $exclusions[] = 'js.stripe.com';
    $exclusions[] = 'wcpay-upe-checkout';
    $exclusions[] = 'wcpay-checkout';
    return $exclusions;
} );

// Exclude Stripe & WooCommerce Payments scripts from Autoptimize JS defer
add_filter( 'autoptimize_filter_js_exclude', function( $exclude ) {
    return $exclude . ', js.stripe.com, wcpay-upe-checkout, wcpay-checkout';
} );

// Preload stripe.js on checkout so browser fetches it early (before footer scripts)
add_action( 'wp_head', function() {
    if ( is_checkout() ) {
        echo '<link rel="preload" href="https://js.stripe.com/v3/" as="script" crossorigin>';
    }
}, 1 );

// Move stripe.js from footer to head for faster loading on checkout
add_action( 'wp_enqueue_scripts', function() {
    if ( is_checkout() ) {
        wp_script_add_data( 'stripe', 'group', 0 );
    }
}, 20 );

add_image_size('hero_pic', 600, 600, false);
add_image_size('hero_pic_mob', 325, 325, false);
add_image_size('sect_pic', 605, 605, false);
add_image_size('product_pic', 600, 600, false);
add_image_size('product_pic_mob', 382, 382, false);
add_image_size('product_preview_pic', 300, 300, false);

//Styles and scrypts
function add_theme_scripts()
{
    wp_enqueue_style('main-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_style('main-style-magnific-popup', get_stylesheet_directory_uri() . '/assets/css/magnific-popup.css', array(), '1.0.0');
    wp_enqueue_style('datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css', array(), '2.5.20');
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('magnific-popup-js', get_stylesheet_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js', array('jquery'), '2.5.20', true);
    wp_enqueue_script('loadmore-products', get_stylesheet_directory_uri() . '/assets/js/loadmore-products.js', array('jquery'), '2.0.3', true);
    wp_localize_script('loadmore-products', 'papaLoadMore', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('papa_load_more_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'add_theme_scripts');
add_filter('style_loader_tag', 'add_async_to_specific_css', 10, 4);

function add_async_to_specific_css($html, $handle, $href, $media)
{

    $defer_handles = array(
        'main-style-magnific-popup',
        'datetimepicker',
        'wp-block-library',
        'wc-blocks-style'
    );

    if (in_array($handle, $defer_handles)) {
        return "<link rel='stylesheet' id='{$handle}-css' href='{$href}' media='print' onload=\"this.media='all'\"><noscript><link rel='stylesheet' href='{$href}'></noscript>";
    }

    return $html;
}















// Menus
function wpb_custom_new_menu() {
    register_nav_menus(array(
        'main_menu'          => __( 'Header Menu' ),
        'footer_bottom_menu' => __( 'Footer Bottom Menu' ),
    ));
}
add_action( 'init', 'wpb_custom_new_menu' );

// Title teme support
add_theme_support( 'title-tag' );

//Add thumbnail support
add_theme_support( 'post-thumbnails' );

//add logo support
add_theme_support( 'custom-logo' );

//disable gutenberg
add_filter('use_block_editor_for_post', '__return_false', 10);

//add navigation menu placement
add_filter('navigation_markup_template', 'my_navigation_template', 10, 2 );
function my_navigation_template( $template, $class ){

    return '
    <nav class="navigation %1$s" role="navigation">
        <div class="nav-links">%3$s</div>
    </nav>    
    ';
}

//add special classes for navigation menu
add_filter( 'nav_menu_css_class', 'special_nav_class', 10, 2 );
function special_nav_class($classes, $item){
    if( is_single() && $item->title == "Blog" ){
        $classes[] = "special-class";
    }

    return $classes;
}

// ACF THEME OPTIONS
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

}

// Woocommerce setup
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
   add_theme_support( 'woocommerce' );
}

if (class_exists('Woocommerce')){
    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
}

function mytheme_add_woocommerce_support() {
add_theme_support( 'woocommerce', array(
/*'thumbnail_image_width' => 150,
'single_image_width'    => 300,*/
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 3,
            'min_columns'     => 2,
            'max_columns'     => 3,
        ),
) );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

//
add_action( 'after_setup_theme', 'yourtheme_setup' );
function yourtheme_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

// Add additionals menu in footer
function wpb_custom_footer_menu() {
  register_nav_menus(
    array(
      'footer-categories' => __( 'Footer categories' ),
      'footer-information' => __( 'Footer info menu' )
    )
  );
}
add_action( 'init', 'wpb_custom_footer_menu' );

// hide tab description
add_filter('woocommerce_product_tabs', 'remove_product_tabs', 98);
function remove_product_tabs($tabs)
{
    unset($tabs['description']);  // Удалить описание
    return $tabs;
}

// WOOCOMMERCE SALE BADGE
add_action( 'woocommerce_sale_flash', 'pancode_echo_sale_percent' );

// CART MENU Shortcode
add_shortcode ('woo_cart_but', 'woo_cart_but' );
function woo_cart_but() {
    ob_start();

        $cart_count = WC()->cart->cart_contents_count; // Set variable for cart item count
        $cart_url = wc_get_checkout_url();  // Set Cart URL

        ?>
            <a class="menu-item cart-contents" href="<?php echo $cart_url; ?>" title="My Basket">
                <?php
                    if ( $cart_count > 0 ) {
                ?>
                    <span class="cart-contents-count"><?php echo $cart_count; ?></span>
                <?php

}                ?>
            </a>
        <?php

    return ob_get_clean();

}

// Add AJAX Shortcode when cart contents update
add_filter( 'woocommerce_add_to_cart_fragments', 'woo_cart_but_count' );
function woo_cart_but_count( $fragments ) {

    ob_start();

    $cart_count = WC()->cart->cart_contents_count;
    // $cart_url = wc_get_cart_url();
    $cart_url = wc_get_checkout_url();

    ?>
    <a class="cart-contents<?= $cart_count == 0 ? ' cart-contents-empty' : '' ?> menu-item" href="<?php echo $cart_url; ?>" title="<?php _e( 'View your shopping cart' ); ?>">
    <?php
    if ( $cart_count > 0 ) {
        ?>
        <span class="cart-contents-count"><?php echo $cart_count; ?></span>
        <?php
    }
        ?></a>
    <?php

    $fragments['a.cart-contents'] = ob_get_clean();

    return $fragments;
}

// Remove woocommerce archive page title
add_filter( 'woocommerce_show_page_title', '__return_false' );

/**
 * Echo discount percent badge html.
 *
 * @param string $html Default sale html.
 *
 * @return string
 */
function pancode_echo_sale_percent( $html ) {
  global $product;

  /**
   * @var WC_Product $product
   */

  // label - onsale
  if ( function_exists('get_field') ) {
    $product_id = $product ? $product->get_id() : get_the_ID();
    $label_value = get_field('label', $product_id);
    if ( !empty($label_value) && $label_value !== 'None' ) {
      return '';
    }
  }

  $regular_max = 0;
  $sale_min    = 0;
  $discount    = 0;

  if ( 'variable' === $product->get_type() ) {
    $prices      = $product->get_variation_prices();
    $regular_max = max( $prices['regular_price'] );
    $sale_min    = min( $prices['sale_price'] );
  } else {
    $regular_max = $product->get_regular_price();
    $sale_min    = $product->get_sale_price();
  }

  if ( ! $regular_max && $product instanceof WC_Product_Bundle ) {
    $bndl_price_data = $product->get_bundle_price_data();
    $regular_max     = max( $bndl_price_data['regular_prices'] );
    $sale_min        = max( $bndl_price_data['prices'] );
  }

  if ( floatval( $regular_max ) ) {
    $discount = round( 100 * ( $regular_max - $sale_min ) / $regular_max );
  }

  return '<span class="onsale">-&nbsp;' . esc_html( $discount ) . '%</span>';
}


// disable src set for google
function meks_disable_srcset( $sources ) {
    return false;
}
add_filter( 'wp_calculate_image_srcset', 'meks_disable_srcset' );

// change h2 to p - product title
function woocommerce_template_loop_product_title() {
   echo '<p class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</p>';
}

// Removes sku
function sv_remove_product_page_skus( $enabled ) {
    if ( ! is_admin() && is_product() ) {
        return false;
    }

    return $enabled;
}
add_filter( 'wc_product_sku_enabled', 'sv_remove_product_page_skus' );

//  REGISTER SIDEBARS
function filter_widgets_sidebar(){
    register_sidebar( array(
        'name' => "Filter sidebar",
        'id' => 'filer-sidebar',
        'description' => 'Shop sidebar',
        'before_title' => '<button class="single__accordion widget-title">',
        'after_title' => '</button><div class="single__panel">',
        'before_widget' => '<div class="catalog-widget-block">',
        'after_widget' => '</div></div>',
    ) );

    register_sidebar(
        array(
            'name'          => 'Footer area 1',
            'id'            => 'sidebar-footer-area-1',
            'description'   => '',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

    register_sidebar(
        array(
            'name'          => 'Footer area 2',
            'id'            => 'sidebar-footer-area-2',
            'description'   => '',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

    register_sidebar(
        array(
            'name'          => 'Aside Social',
            'id'            => 'sidebar-footer-area-3',
            'description'   => '',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );


}
add_action( 'widgets_init', 'filter_widgets_sidebar' );

//Configure woocommerce profile page
add_filter ( 'woocommerce_account_menu_items', 'wc_remove_my_account_links' );
function wc_remove_my_account_links( $menu_links ){

    //unset( $menu_links['edit-address'] ); // Addresses
    //unset( $menu_links['dashboard'] ); // Remove Dashboard
    //unset( $menu_links['payment-methods'] ); // Remove Payment Methods
    //unset( $menu_links['orders'] ); // Remove Orders
    unset( $menu_links['downloads'] ); // Disable Downloads
    //unset( $menu_links['edit-account'] ); // Remove Account details tab
    //unset( $menu_links['customer-logout'] ); // Remove Logout link

    return $menu_links;

}

//Excerp max symbols
function get_excerpt($limit, $source = null){
    $excerpt = $source == "content" ? get_the_content() : get_the_excerpt();
    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, $limit);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
    $excerpt = $excerpt.'...';
    return $excerpt;
}

//Blog Pagination
/*add_filter( 'navigation_markup_template', 'my_navigation_template', 10, 2 );
function my_navigation_template( $template, $class ) {
    return '<div class="b-pagination wow fadeIn" data-wow-delay=".05s" style="visibility: hidden;">%3$s</div>';
}*/


//Global blocks - activate if needed
/*add_action('init', function(){
    register_post_type( 'global_block', [
        'label'  => null,
        'labels' => [
            'name'               => 'Global blocks', // основное название для типа записи
            'singular_name'      => 'Global block', // название для одной записи этого типа
            'add_new'            => 'Add Global block', // для добавления новой записи
            'add_new_item'       => 'Adding Global block', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Editing Global block', // для редактирования типа записи
            'new_item'           => 'New Global block', // текст новой записи
            'view_item'          => 'Look Global block', // для просмотра записи этого типа.
            'search_items'       => 'Search Global block', // для поиска по этим типам записи
            'not_found'          => 'Not found', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Not found in trash', // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => 'Global blocks', // название меню
        ],
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => true, // показывать ли в меню адмнки
        'show_in_rest'        => null, // добавить в REST API. C WP 4.7
        'rest_base'           => null, // $post_type. C WP 4.7
        'menu_position'       => null,
        'menu_icon'           => null,
        'hierarchical'        => false,
        'supports'            => [ 'title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
        'taxonomies'          => [],
        'has_archive'         => false,
        'rewrite'             => true,
        'query_var'           => true,
    ] );
});*/

// Templates for global blocks
require get_template_directory() . '/inc/html-parts.php';
require get_template_directory() . '/inc/load-more-products.php';


//move short description after product meta
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 45 );

// Remove the product description Title
add_filter( 'woocommerce_product_description_heading', '__return_null' );

//disable product image zoom
function remove_image_zoom_support() {
    remove_theme_support( 'wc-product-gallery-zoom' );
}
add_action( 'wp', 'remove_image_zoom_support', 100 );


// Our hooked in function - $fields is passed via the filter!
// Action: remove label from $fields
function custom_wc_checkout_fields_no_label($fields) {
    // loop by category
    foreach ($fields as $category => $value) {
        // loop by fields
        foreach ($fields[$category] as $field => $property) {
            // remove label property
            unset($fields[$category][$field]['label']);
        }
    }
     return $fields;
}

// Out of stock at end of list
add_action( 'pre_get_posts', function( $query ) {
    if ( $query->is_main_query() && function_exists('is_woocommerce') && is_woocommerce() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        if( $query->get( 'orderby' ) == 'menu_order title' ) {  // only change default sorting
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'order', 'ASC' );
            $query->set( 'meta_key', '_stock_status' );
        }
    }
});

//hide out of stock on related products
function hide_out_of_stock_option( $option ){
    return 'yes';
}

add_action( 'woocommerce_before_template_part', function( $template_name ) {
if( $template_name !== "single-product/related.php" ) {
return;
}
add_filter( 'pre_option_woocommerce_hide_out_of_stock_items', 'hide_out_of_stock_option' );
} );


add_filter( 'woocommerce_product_query_tax_query', 'filter_product_query_tax_query', 10, 2 );
function filter_product_query_tax_query( $tax_query, $query ) {
    // On woocommerce home page only
    if( is_front_page() ){
        // Exclude products "out of stock"
        $tax_query[] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => array('outofstock'),
            'operator' => 'NOT IN'
        );
    }
    return $tax_query;
}

// Widgets container

// if no title then add widget content wrapper to before widget
add_filter( 'dynamic_sidebar_params', 'check_sidebar_params' );
function check_sidebar_params( $params ) {
    global $wp_registered_widgets;

    $settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
    $settings = $settings_getter->get_settings();
    $settings = $settings[ $params[1]['number'] ];

    if ( $params[0][ 'after_widget' ] == '</div></div>' && isset( $settings[ 'title' ] ) && empty( $settings[ 'title' ] ) )
        $params[0][ 'before_widget' ] .= '<div class="content">';

    return $params;
}

/**
 * Allow HTML in term (category, tag) descriptions
 */
foreach ( array( 'pre_term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_filter_kses' );
    if ( ! current_user_can( 'unfiltered_html' ) ) {
        add_filter( $filter, 'wp_filter_post_kses' );
    }
}

foreach ( array( 'term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_kses_data' );
}

/**
 *  Hide additional information on product page
 */
add_filter( 'woocommerce_product_tabs', 'njengah_remove_product_tabs', 9999 );

  function njengah_remove_product_tabs( $tabs ) {

    unset( $tabs['additional_information'] );

    return $tabs;

}

/**
 * Add title and alt to product images
 */

add_filter('wp_get_attachment_image_attributes', 'change_attachement_image_attributes', 20, 2);

function change_attachement_image_attributes( $attr, $attachment ){

    // Get post parent
    $parent = get_post_field( 'post_parent', $attachment);

    // Get post type to check if it's product
    $type = get_post_field( 'post_type', $parent);
    if( $type != 'product' ){
        return $attr;
    }

    /// Get title
    $title = get_post_field( 'post_title', $parent);

    $attr['alt'] = $title;
    $attr['title'] = $title;

    return $attr;
}

/**
 * Remove setting from woocommerce menu
 */

add_action( 'admin_menu', 'remove_wc_settings', 999);
function remove_wc_settings() {

    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    if($user_role == "shop_manager") {
        $remove_submenu = remove_submenu_page('woocommerce', 'wc-settings');
        $remove_submenu = remove_submenu_page('woocommerce', 'wc-addons');
        $remove_submenu = remove_submenu_page('woocommerce', 'agy_settings');
        $remove_submenu = remove_submenu_page('woocommerce', 'marketing');
        $remove_submenu = remove_submenu_page('popup', 'popup');
        $remove_submenu = remove_menu_page('popup', 'popup');
        $remove_submenu = remove_menu_page('wpcf7', 'wpcf7');
        $remove_submenu = remove_menu_page('popup_theme', 'popup_theme');
        $remove_submenu = remove_menu_page('theme-general-settings', 'theme-general-settings');
        $remove_submenu = remove_menu_page('tools.php');
        $remove_submenu = remove_menu_page('edit.php');
        $remove_submenu = remove_menu_page( 'edit.php?post_type=popup' );
    }

}


/**
 * Out of stock at the end of list
*/

add_filter('posts_clauses', 'order_by_stock_status', 999 );
function order_by_stock_status($posts_clauses) {
    global $wpdb;
    // only change query on WooCommerce loops
    if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())) {
        $posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
        $posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
        $posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
    }
    return $posts_clauses;
}

/**
 * Move category desciprtion under pagination
 */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 100 );

/**
 * Change echo of category description
 */
function woocommerce_taxonomy_archive_description() {
  if ( is_tax( array( 'product_cat', 'product_tag' ) ) && get_query_var( 'paged' ) == 0 ) {
    $description = wpautop( do_shortcode( term_description() ) );
    if ( $description ) {
      echo '<div class="term-description">' . $description . '</div>';
    }
  }
}

/*
* Delete shortlink
*/
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

/*
* delete some input fields on order page
*/
add_filter( 'woocommerce_checkout_fields', 'dw_remove_fields', 9999 );

function dw_remove_fields( $woo_checkout_fields_array ) {

    // she wanted me to leave these fields in checkout
    // unset( $woo_checkout_fields_array['billing']['billing_first_name'] );
    // unset( $woo_checkout_fields_array['billing']['billing_last_name'] );
    // unset( $woo_checkout_fields_array['billing']['billing_phone'] );
    // unset( $woo_checkout_fields_array['billing']['billing_email'] );
    // unset( $woo_checkout_fields_array['order']['order_comments'] ); // remove order notes

    // and to remove the billing fields below
    unset( $woo_checkout_fields_array['billing']['billing_company'] ); // remove company field
    // unset( $woo_checkout_fields_array['billing']['billing_country'] );
    // unset( $woo_checkout_fields_array['billing']['billing_address_1'] );
    unset( $woo_checkout_fields_array['billing']['billing_address_2'] );
    unset( $woo_checkout_fields_array['billing']['billing_city'] );
    unset( $woo_checkout_fields_array['billing']['billing_state'] ); // remove state field
    // unset( $woo_checkout_fields_array['billing']['billing_postcode'] ); // remove zip code field

    return $woo_checkout_fields_array;
}

add_filter( 'woocommerce_checkout_fields' , 'dw_not_required_fields', 9999 );

function dw_not_required_fields( $f ) {

    unset( $f['billing']['billing_company']['required'] ); // that's it
    // unset( $f['billing']['billing_phone']['required'] );

    // the same way you can make any field required, example:
    // $f['billing']['billing_company']['required'] = true;

    return $f;
}

// Add custom text field to checkout
add_filter( 'woocommerce_checkout_fields', 'add_custom_checkout_text_field' );
function add_custom_checkout_text_field( $fields ) {
    $fields['order']['checkout_custom_text'] = array(
        'type'        => 'text',
        'label'       => 'Choose a time',
        'required'    => true,
        'class'       => array( 'form-row-wide form-row-time visually-hidden' ),
        'placeholder' => 'Choose a time',
        'priority'    => 10
    );
    return $fields;
}

/**
 * Show product weight on archive pages
 */
add_action( 'woocommerce_after_shop_loop_item', 'rs_show_weights', 4 );

function rs_show_weights() {

    global $product;
    $weight = $product->get_weight();

    if ( $product->has_weight() ) {
        echo '<div class="product-meta-weight">' . $weight . get_option('woocommerce_weight_unit') . '</div>';
    }
}

// Product quantity on catalog page
/**
 * Display QTY Input before add to cart link.
 */
function custom_wc_template_loop_quantity_input() {
    // Global Product.
    global $product;

    // Check if the product is not null, is purchasable, is a simple product, is in stock, and not sold individually.
    if ( $product && $product->is_purchasable() && $product->is_type( 'simple' ) && $product->is_in_stock() && ! $product->is_sold_individually() ) {
        woocommerce_quantity_input(
            array(
                'min_value' => 1,
                'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
            )
        );
    }
}
add_action( 'woocommerce_after_shop_loop_item', 'custom_wc_template_loop_quantity_input', 9 );

/*
* Short description on product page
*/
function tutsplus_excerpt_in_product_archives() {

    echo '<div class="short-catalog">';
    the_excerpt();
    echo '</div>';

}

add_action( 'woocommerce_after_shop_loop_item_title', 'tutsplus_excerpt_in_product_archives', 9 );


/**
* Add type of product before image in loop
*/
// add_action( 'woocommerce_after_shop_loop_item_title', 'badge_new_acf', 2 );
// function badge_new_acf(){
//     global $product;

//     $product_new_type = get_field_object('product_type');
//     $product_new_type_value = $product_new_type['value'];

//     if( $product_new_type && in_array('showblock', $product_new_type) ) {
//        echo '<div class="product-type-img"><img src=';
//        echo $product_new_type['value'];
//        echo ' alt="product type image">';
//        echo '</div>';
//      }

// }

add_action( 'woocommerce_after_shop_loop_item_title', 'badge_new_acf', 2 );
function badge_new_acf(){
    if( !function_exists('get_field') ) {
        return;
    }
    
    global $product;
    
    // Используем get_the_ID() для текущего поста в цикле
    $product_id = $product ? $product->get_id() : get_the_ID();
    $product_new_type_value = get_field('product_type', $product_id);
    
    // Не отображаем если None или пусто
    if( empty($product_new_type_value) || $product_new_type_value === 'None' ) {
        return;
    }
    
    echo '<div class="product-type-img"><img src="' . esc_url($product_new_type_value) . '" alt="product type image" width="85" height="85" loading="lazy"></div>';
}

// label
add_action( 'woocommerce_after_shop_loop_item_title', 'badge_label_acf', 3 );
function badge_label_acf(){
    if( !function_exists('get_field') ) {
        return;
    }
    
    global $product;
    
    $product_id = $product ? $product->get_id() : get_the_ID();
    $label_value = get_field('label', $product_id);
    
    if( empty($label_value) || $label_value === 'None' ) {
        return;
    }
    
    echo '<div class="product-label-img"><img src="' . esc_url($label_value) . '" alt="product label image" width="85" height="85" loading="lazy"></div>';
}

/**
* Onepage checkout
*/

add_action( 'woocommerce_before_checkout_form', 'bbloomer_cart_on_checkout_page', 11 );

function bbloomer_cart_on_checkout_page() {
   echo do_shortcode( '[woocommerce_cart]' );
}

// On checkout page
/*add_action( 'woocommerce_checkout_order_review', 'remove_checkout_totals', 1 );
function remove_checkout_totals(){
    $cart_total = WC()->cart->get_cart_total();
    if ( $cart_total == 0 ) {
            // Remove cart totals block
            remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
    }
}*/

//Change the 'Billing details' checkout label to 'Contact Information'
function wc_billing_field_strings( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
case 'Billing &amp; Shipping' :
$translated_text = __( 'DELIVERY', 'woocommerce' );
break;
}
return $translated_text;
}
add_filter( 'gettext', 'wc_billing_field_strings', 20, 3 );


/**
* AJAX handler for updating cart item quantity in checkout
*/
add_action( 'wp_ajax_update_cart_item_quantity', 'handle_update_cart_item_quantity' );
add_action( 'wp_ajax_nopriv_update_cart_item_quantity', 'handle_update_cart_item_quantity' );

function handle_update_cart_item_quantity() {
    // Проверяем nonce для безопасности
    if ( ! wp_verify_nonce( $_POST['nonce'], 'update-order-review' ) ) {
        wp_die( 'Security check failed' );
    }

    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );
    $quantity = absint( $_POST['quantity'] );

    if ( $quantity <= 0 ) {
        WC()->cart->remove_cart_item( $cart_item_key );
    } else {
        WC()->cart->set_quantity( $cart_item_key, $quantity );
    }

    // Пересчитываем корзину
    WC()->cart->calculate_totals();

    // Получаем обновленный HTML таблицы чекаута
    ob_start();
    ?>
    <table class="shop_table woocommerce-checkout-review-order-table">
        <thead>
            <tr>
                <th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                <th class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    ?>
                    <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                        <td class="product-name">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
                            <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
                            <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                        </td>
                        <td class="product-total">
                            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="cart-subtotal">
                <th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                <td><?php wc_cart_totals_subtotal_html(); ?></td>
            </tr>

            <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                    <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                    <td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                <?php wc_cart_totals_shipping_html(); ?>
            <?php endif; ?>

            <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                <tr class="fee">
                    <th><?php echo esc_html( $fee->name ); ?></th>
                    <td><?php wc_cart_totals_fee_html( $fee ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                    <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                        <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                            <th><?php echo esc_html( $tax->label ); ?></th>
                            <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="tax-total">
                        <th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
                        <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>

            <tr class="order-total">
                <th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                <td><?php wc_cart_totals_order_total_html(); ?></td>
            </tr>
        </tfoot>
    </table>
    <?php
    $checkout_html = ob_get_clean();

    // Получаем обновленные итоги корзины
    $cart_totals = array(
        'subtotal' => WC()->cart->get_cart_subtotal(),
        'total' => WC()->cart->get_total()
    );

    // Получаем обновленный счетчик товаров
    $cart_count = WC()->cart->cart_contents_count;
    ob_start();
    ?>
    <a class="cart-contents<?= $cart_count == 0 ? ' cart-contents-empty' : '' ?> menu-item" href="<?php echo wc_get_checkout_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
    <?php
    if ( $cart_count > 0 ) {
        ?>
        <span class="cart-contents-count"><?php echo $cart_count; ?></span>
        <?php
    }
    ?></a>
    <?php
    $cart_contents_html = ob_get_clean();

    // Получаем обновленный список товаров для checkout-bottom__list
    ob_start();
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            echo '<div>';
            echo '<div>' . wp_kses_post( $_product->get_name() ) . ' <strong>× ' . $cart_item['quantity'] . '</strong></div>';
            echo '<div>' . apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) . '</div>';
            echo '</div>';
        }
    }
    $cart_items_html = ob_get_clean();

    wp_send_json_success( array(
        'checkout_html' => $checkout_html,
        'cart_totals' => $cart_totals,
        'cart_items_html' => $cart_items_html,
        'cart_contents_html' => $cart_contents_html
    ) );
}

/**
* Add checkout quantity update scripts
*/
add_action( 'wp_footer', 'add_checkout_quantity_update_scripts', 400 );
function add_checkout_quantity_update_scripts() {
    if ( is_checkout() ) { ?>
        <script type="text/javascript">
            var checkout_quantity_params = {
                'ajax_url': '<?php echo admin_url('admin-ajax.php'); ?>',
                'nonce': '<?php echo wp_create_nonce('update-order-review'); ?>'
            };

            // Fix: WCPay card fields not mounting on first page load.
            // Payment fields are rendered in checkout-bottom (outside #payment).
            // WCPay JS may miss them after update_checkout AJAX because it expects
            // elements inside #payment .payment_box. We wait for WCPay config to load,
            // then re-trigger updated_checkout (no AJAX) so WCPay re-scans the DOM.
            (function fixStripeMount() {
                var retries = 0;
                function checkAndRetrigger() {
                    if (retries >= 5) return;
                    var el = document.querySelector('.wcpay-upe-element');
                    if (el && el.children.length === 0 && typeof wcpay_upe_config !== 'undefined') {
                        retries++;
                        jQuery(document.body).trigger('updated_checkout');
                    }
                }
                // Check after initial page scripts finish
                var poll = setInterval(function() {
                    if (typeof wcpay_upe_config !== 'undefined' && typeof jQuery !== 'undefined') {
                        clearInterval(poll);
                        // Give WCPay time to do its initial mount attempt
                        setTimeout(checkAndRetrigger, 1500);
                        setTimeout(checkAndRetrigger, 3000);
                        setTimeout(checkAndRetrigger, 5000);
                    }
                }, 200);
                // Safety: stop polling after 15s
                setTimeout(function() { clearInterval(poll); }, 15000);
            })();
        </script>
    <?php }
}

/**
* Close information popup on btn click (check your postcode popup)
*/
add_action( 'wp_footer', 'my_custom_popup_scripts', 500 );
function my_custom_popup_scripts() { ?>
    <script type="text/javascript">
        (function ($, document, undefined) {

            $('#pum-183') // Change 123 to your popup ID number.
                .on('pumAfterOpen', function () {
                    var $popup = $(this);
                        $( "#popclose" ).click(function() {
                        $popup.popmake('close');
                    });
                });

        }(jQuery, document))
    </script><?php
}

// Custom checkout fields - save Bag and Cutlery checkboxes
add_action( 'woocommerce_checkout_process', 'process_checkout_extras' );
function process_checkout_extras() {
    // Validation for checkboxes if needed - checkboxes are optional so no validation required
    // Text field validation is handled automatically by WooCommerce
}

// Save checkout extras to order meta
add_action( 'woocommerce_checkout_update_order_meta', 'save_checkout_extras_to_order' );
function save_checkout_extras_to_order( $order_id ) {
    if ( ! empty( $_POST['checkout_bag'] ) ) {
        update_post_meta( $order_id, '_checkout_bag', sanitize_text_field( $_POST['checkout_bag'] ) );
    }

    if ( ! empty( $_POST['checkout_cutlery'] ) ) {
        update_post_meta( $order_id, '_checkout_cutlery', sanitize_text_field( $_POST['checkout_cutlery'] ) );
    }

    if ( ! empty( $_POST['checkout_custom_text'] ) ) {
        update_post_meta( $order_id, '_checkout_custom_text', sanitize_textarea_field( $_POST['checkout_custom_text'] ) );
    }
}

// Display checkout extras in admin order details
add_action( 'woocommerce_admin_order_data_after_billing_address', 'display_checkout_extras_in_admin' );
function display_checkout_extras_in_admin( $order ) {
    $bag = get_post_meta( $order->get_id(), '_checkout_bag', true );
    $cutlery = get_post_meta( $order->get_id(), '_checkout_cutlery', true );
    $custom_text = get_post_meta( $order->get_id(), '_checkout_custom_text', true );

    if ( $bag || $cutlery || $custom_text ) {
        echo '<h4>Additional options:</h4>';
        if ( $bag ) {
            echo '<p><strong>Bag:</strong> Yes</p>';
        }
        if ( $cutlery ) {
            echo '<p><strong>Cutlery:</strong> Yes</p>';
        }
        if ( $custom_text ) {
            echo '<p><strong>Choose a time:</strong> ' . esc_html( $custom_text ) . '</p>';
        }
    }
}

// Display checkout extras in customer order details (frontend)
add_action( 'woocommerce_order_details_after_order_table', 'display_checkout_extras_in_order_details' );
function display_checkout_extras_in_order_details( $order ) {
    $bag = get_post_meta( $order->get_id(), '_checkout_bag', true );
    $cutlery = get_post_meta( $order->get_id(), '_checkout_cutlery', true );
    $custom_text = get_post_meta( $order->get_id(), '_checkout_custom_text', true );

    if ( $bag || $cutlery || $custom_text ) {
        echo '<h3>Additional options</h3>';
        echo '<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">';
        if ( $bag ) {
            echo '<tr><td><strong>Bag:</strong></td><td>Yes</td></tr>';
        }
        if ( $cutlery ) {
            echo '<tr><td><strong>Cutlery:</strong></td><td>Yes</td></tr>';
        }
        if ( $custom_text ) {
            echo '<tr><td><strong>Choose a time:</strong></td><td>' . esc_html( $custom_text ) . '</td></tr>';
        }
        echo '</table>';
    }
}

// Display checkout extras in order emails
add_action( 'woocommerce_email_order_details', 'display_checkout_extras_in_emails', 20, 4 );
function display_checkout_extras_in_emails( $order, $sent_to_admin, $plain_text, $email ) {
    $bag = get_post_meta( $order->get_id(), '_checkout_bag', true );
    $cutlery = get_post_meta( $order->get_id(), '_checkout_cutlery', true );
    $custom_text = get_post_meta( $order->get_id(), '_checkout_custom_text', true );

    if ( $bag || $cutlery || $custom_text ) {
        if ( $plain_text ) {
            echo "\n" . "Additional options:" . "\n";
            if ( $bag ) {
                echo "Bag: Yes" . "\n";
            }
            if ( $cutlery ) {
                echo "Cutlery: Yes" . "\n";
            }
            if ( $custom_text ) {
                echo "Choose a time: " . $custom_text . "\n";
            }
        } else {
            echo '<h3>Additional options</h3>';
            echo '<ul>';
            if ( $bag ) {
                echo '<li><strong>Bag:</strong> Yes</li>';
            }
            if ( $cutlery ) {
                echo '<li><strong>Cutlery:</strong> Yes</li>';
            }
            if ( $custom_text ) {
                echo '<li><strong>Choose a time:</strong> ' . esc_html( $custom_text ) . '</li>';
            }
            echo '</ul>';
        }
    }
}

// Add JavaScript for checkbox synchronization
add_action( 'wp_footer', 'checkout_extras_sync_script' );
function checkout_extras_sync_script() {
    if ( is_cart() || is_checkout() ) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Load saved values from localStorage
            var savedBag = localStorage.getItem('checkout_bag');
            var savedCutlery = localStorage.getItem('checkout_cutlery');

            if (savedBag === '1') {
                $('input[name="checkout_bag"]').prop('checked', true);
            }
            if (savedCutlery === '1') {
                $('input[name="checkout_cutlery"]').prop('checked', true);
            }

            // Sync checkboxes on change
            $('input[name="checkout_bag"]').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('input[name="checkout_bag"]').prop('checked', isChecked);
                localStorage.setItem('checkout_bag', isChecked ? '1' : '0');
            });

            $('input[name="checkout_cutlery"]').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('input[name="checkout_cutlery"]').prop('checked', isChecked);
                localStorage.setItem('checkout_cutlery', isChecked ? '1' : '0');
            });

            // Datetimepicker settings
            // ========== НАСТРОЙКИ datetimepicker ==========
            var startTime = '11:00';    // Начальное время (формат: 'HH:MM')
            var endTime = '19:00';      // Конечное время (формат: 'HH:MM')
            var stepMinutes = 30;       // Шаг в минутах (30 = полчаса)
            var disablePastTime = true; // Блокировать прошедшее время (true/false)

            // Парсинг времени
            function parseTime(timeStr) {
                var parts = timeStr.split(':');
                return { hour: parseInt(parts[0]), minute: parseInt(parts[1]) };
            }

            var start = parseTime(startTime);
            var end = parseTime(endTime);

            // Генерация списка доступных времен (24-часовой формат для корректной работы списка)
            var allowedTimes = [];
            var startTotalMin = start.hour * 60 + start.minute;
            var endTotalMin = end.hour * 60 + end.minute;

            for (var min = startTotalMin; min <= endTotalMin; min += stepMinutes) {
                var h = Math.floor(min / 60);
                var m = min % 60;
                var timeStr24 = (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
                allowedTimes.push(timeStr24);
            }

            // Функция для отключения прошедших времен и форматирования списка
            function disablePastTimes(ct) {
                if (!disablePastTime) return;

                var now = new Date();
                var currentTotalMinutes = now.getHours() * 60 + now.getMinutes();

                setTimeout(function() {
                    $('.xdsoft_datetimepicker:visible .xdsoft_time').each(function() {
                        var $time = $(this);
                        var timeText = $time.text().trim();
                        var h, m;

                        // 1. Пытаемся распарсить как 24ч (H:i) - это исходный формат списка
                        var match24 = timeText.match(/^(\d{1,2}):(\d{2})$/);
                        // 2. Пытаемся распарсить как 12ч (g:i A) - если уже отформатировали
                        var match12 = timeText.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);

                        if (match24) {
                            h = parseInt(match24[1]);
                            m = parseInt(match24[2]);
                            
                            // ВИЗУАЛЬНОЕ ФОРМАТИРОВАНИЕ: меняем текст на 12-часовой формат
                            var h12 = h % 12 || 12;
                            var ampm = h < 12 ? 'AM' : 'PM';
                            // g:i A (без ведущего нуля в часах, как в инпуте)
                            var newText = h12 + ':' + (m < 10 ? '0' : '') + m + ' ' + ampm;
                            $time.text(newText);
                            
                        } else if (match12) {
                            h = parseInt(match12[1]);
                            m = parseInt(match12[2]);
                            var ampm = match12[3].toUpperCase();
                            
                            if (ampm === 'PM' && h !== 12) h += 12;
                            if (ampm === 'AM' && h === 12) h = 0;
                        } else {
                            return; // Неизвестный формат
                        }

                        // ПРОВЕРКА НА ПРОШЕДШЕЕ ВРЕМЯ
                        var timeTotalMinutes = h * 60 + m;
                        if (timeTotalMinutes <= currentTotalMinutes) {
                            $time.addClass('xdsoft_disabled');
                        } else {
                            $time.removeClass('xdsoft_disabled');
                        }
                    });
                }, 0);
            }

            // Флаг для предотвращения рекурсивной синхронизации
            var isSyncing = false;

            // Инициализация datetimepicker для checkout_field
            if ($('#checkout_field').length) {
                $('#checkout_field').datetimepicker({
                    datepicker: false,
                    format: 'g:i A', // В поле ввода: 12-часовой
                    formatTime: 'H:i', // В списке (логика): 24-часовой
                    allowTimes: allowedTimes,
                    validateOnBlur: false,
                    scrollInput: false,
                    onGenerate: disablePastTimes,
                    onShow: disablePastTimes,
                    onChangeDateTime: function(dp, $input) {
                        if (isSyncing) return;
                        isSyncing = true;
                        var value = $input.val();
                        $('#checkout_custom_text').val(value);
                        localStorage.setItem('checkout_custom_time', value);
                        
                        // Hide error message
                        var errorSpan = $input.siblings('.checkout-bottom__field-error');
                        if (value.trim() !== '') {
                            errorSpan.attr('style', 'display: none !important;');
                        }
                        isSyncing = false;
                    }
                });
            }

            // Инициализация для checkout_custom_text
            if ($('#checkout_custom_text').length) {
                $('#checkout_custom_text').datetimepicker({
                    datepicker: false,
                    format: 'g:i A',
                    formatTime: 'H:i',
                    allowTimes: allowedTimes,
                    validateOnBlur: false,
                    scrollInput: false,
                    onGenerate: disablePastTimes,
                    onShow: disablePastTimes,
                    onChangeDateTime: function(dp, $input) {
                        if (isSyncing) return;
                        isSyncing = true;
                        var value = $input.val();
                        $('#checkout_field').val(value);
                        localStorage.setItem('checkout_custom_time', value);
                        
                        // Hide error message
                        var errorSpan = $('#checkout_field').siblings('.checkout-bottom__field-error');
                        if (value.trim() !== '') {
                            errorSpan.attr('style', 'display: none !important;');
                        }
                        isSyncing = false;
                    }
                });
            }

            // Валидация на пустое поле
            $('#checkout_field').on('blur input change', function() {
                var val = $(this).val();
                var errorSpan = $(this).siblings('.checkout-bottom__field-error');
                if(val.trim() === '') {
                    errorSpan.attr('style', 'display: block !important;');
                } else {
                    errorSpan.attr('style', 'display: none !important;');
                }
            });

            // Загрузка сохраненного значения ПОСЛЕ инициализации
            var savedTime = localStorage.getItem('checkout_custom_time');
            if (savedTime) {
                $('#checkout_field').val(savedTime);
                $('#checkout_custom_text').val(savedTime);
            }

            // Clear localStorage when order is placed
            $('body').on('checkout_place_order', function() {
                localStorage.removeItem('checkout_bag');
                localStorage.removeItem('checkout_cutlery');
                localStorage.removeItem('checkout_custom_time');
            });
        });
        </script>


        <?php
    }
}

// add aria-label
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
add_action('woocommerce_before_shop_loop_item', 'custom_product_link_open', 10);

function custom_product_link_open()
{
    global $product;
    $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
    $title = $product->get_name();

    echo '<a href="' . esc_url($link) . '" class="woocommerce-loop-product__link-wrapper" aria-label="' . esc_attr($title) . '"></a>';
    echo '<a href="' . esc_url($link) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" aria-label="' . esc_attr($title) . '">';
}

// add loading lazy
add_filter('wp_get_attachment_image_attributes', 'add_lazy_to_wc_thumbnails', 10, 3);
function add_lazy_to_wc_thumbnails($attr, $attachment, $size)
{
    if ($size === 'woocommerce_thumbnail' || $size === 'shop_catalog') {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}

// preload product picture

add_action('wp_head', 'preload_main_product_image', 1);

function preload_main_product_image()
{
    if (is_product() && has_post_thumbnail()) {
        $attachment_id = get_post_thumbnail_id();
        $size = 'product_preview_pic';

        $image_src = wp_get_attachment_image_url($attachment_id, $size);
        $image_srcset = wp_get_attachment_image_srcset($attachment_id, $size);
        $image_sizes = wp_get_attachment_image_sizes($attachment_id, $size);

        if ($image_src) {
            echo '<link rel="preload" as="image" href="' . esc_url($image_src) . '"';
            if ($image_srcset) {
                echo ' imagesrcset="' . esc_attr($image_srcset) . '"';
            }
            if ($image_sizes) {
                echo ' imagesizes="' . esc_attr($image_sizes) . '"';
            }

            echo ' fetchpriority="high">';
            echo "\n";
        }
    }
}

add_image_size('product_preview_pic', 300, 300, false);



//Disable State/County required
 
add_filter('woocommerce_default_address_fields', function($fields){
    if (isset($fields['state'])) {
        $fields['state']['required'] = false;
    }
    return $fields;
}, 999);


add_filter('woocommerce_get_country_locale', function($locale){
    foreach ($locale as $country => $fields) {
        if (isset($locale[$country]['state'])) {
            $locale[$country]['state']['required'] = false;
        }
    }
    return $locale;
}, 999);