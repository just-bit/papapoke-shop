<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
    return;
}
?>
<nav class="woocommerce-pagination">
    <?php
    $links = paginate_links(
        apply_filters(
            'woocommerce_pagination_args',
            array(
                'base'      => $base,
                'format'    => $format,
                'add_args'  => false,
                'current'   => max( 1, $current ),
                'total'     => $total,
                'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                'type'      => 'array', // массив вместо готовой разметки
                'end_size'  => 3,
                'mid_size'  => 3,
            )
        )
    );

    if ( ! empty( $links ) ) {
        $links = array_map( function( $link ) {
            return str_replace( '/page/1/', '/', $link );
        }, $links );

        echo '<ul class="page-numbers">';
        foreach ( $links as $link ) {
            echo '<li>' . $link . '</li>';
        }
        echo '</ul>';
    }
    ?>
</nav>
