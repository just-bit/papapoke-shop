<?php
/**
 * Single Product Image
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
	return;
}

global $product;

$columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
		'woocommerce-product-gallery--columns-' . absint($columns),
		'images',
	)
);
?>
<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>"
	data-columns="<?php echo esc_attr($columns); ?>">

	<figure class="woocommerce-product-gallery__wrapper">
    <?php
		if ($post_thumbnail_id) {
			$src_desktop = wp_get_attachment_image_url($post_thumbnail_id, 'product_pic');     // 600x600
			$src_mobile = wp_get_attachment_image_url($post_thumbnail_id, 'product_pic_mob'); // 382x382
			$full_src = wp_get_attachment_image_src($post_thumbnail_id, 'full');
			$attributes = array(
				'title' => get_post_field('post_title', $post_thumbnail_id),
				'data-caption' => get_post_field('post_excerpt', $post_thumbnail_id),
				'data-src' => $src_desktop,
				'data-large_image' => $full_src[0],
				'data-large_image_width' => $full_src[1],
				'data-large_image_height' => $full_src[2],
				'fetchpriority' => 'high',
				'decoding' => 'async',
				'class' => 'wp-post-image',
				'loading' => 'eager',
		
				'srcset' => $src_mobile . ' 412w, ' . $src_desktop . ' 600w',
				'sizes' => '(max-width: 767px) 95vw, 600px',
			);
			$img_html = wp_get_attachment_image($post_thumbnail_id, 'product_pic', false, $attributes);
			$html = '<div data-thumb="' . esc_url($src_desktop) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url($full_src[0]) . '">' . $img_html . '</a></div>';

		} else {
			$html = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf(
				'<img src="%s" alt="%s" class="wp-post-image" fetchpriority="high" decoding="async">',
				esc_url(wc_placeholder_img_src('woocommerce_single')),
				esc_html__('Awaiting product image', 'woocommerce')
			);
			$html .= '</div>';
		}

		echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id);

		do_action('woocommerce_product_thumbnails');
		?>
	</figure>

    <?php
    $product_id = $product ? $product->get_id() : get_the_ID();
    $product_new_type_value = get_field('product_type', $product_id);
    if (empty($product_new_type_value) || $product_new_type_value != 'None') {
        echo '<div class="product-type-img"><img src="' . esc_url($product_new_type_value) . '" alt="product type image" width="85" height="85"></div>';
    } else{
        echo '';
    }

    // label icon
    $label_value = get_field('label', $product_id);
    if (!empty($label_value) && $label_value != 'None') {
        echo '<div class="product-label-img"><img src="' . esc_url($label_value) . '" alt="product label image" width="85" height="85"></div>';
    }
    ?>
</div>
