<?php
get_header();
?>

<!-- Banner -->
<main>
<section class="home-banner">

  <?php
  $banner_image_id = get_field('heading_banner_photo');
  $banner_link = get_field('heading_banner_link');

  if ($banner_image_id) {
      $banner_desktop_url = wp_get_attachment_image_url($banner_image_id, 'full');
      $banner_mobile_url = wp_get_attachment_image_url($banner_image_id, 'large');

      if ($banner_desktop_url) {
          ?>
          <div class="home-banner-image">
              <?php if (!empty($banner_link['url'])): ?>
                  <a href="<?php echo esc_url($banner_link['url']); ?>" <?php echo !empty($banner_link['target']) ? 'target="' . esc_attr($banner_link['target']) . '"' : ''; ?> class="home-banner-link">
              <?php endif; ?>
              <img src="<?php echo esc_url($banner_desktop_url); ?>"
                  <?php if ($banner_mobile_url): ?>
                  srcset="<?php echo esc_url($banner_mobile_url); ?> 768w, <?php echo esc_url($banner_desktop_url); ?> 1280w"
                  sizes="100vw"
                  <?php endif; ?>
                  alt="<?php echo esc_attr(get_field('heading_h1') ?: 'Banner'); ?>" 
                  class="hp-banner" 
                  fetchpriority="high" 
                  loading="eager"
                  decoding="async">
              <?php if (!empty($banner_link['url'])): ?>
                  </a>
              <?php endif; ?>
          </div>
          <?php
      }
  }
  ?>
	<div class="container flex home-banner-cont">

		<div class="home-banner-cont-left">
			<h1><?php the_field('heading_h1'); ?></h1>
			<div class="home-banner-cont-left-text">
          <?php the_field('heading_text'); ?>
			</div>
			<a href="#menu" class="action-button" data-target="sides"><span class="btn-star"></span> TRY IT <span class="btn-star"></span> TRY IT <span class="btn-star"></span> TRY IT <span class="btn-star"></span></a>

		</div>

   <div class="home-banner-cont-right">

<?php
$image_id = get_field('heading_bowl_photo');

if ($image_id) {
    $desktop_url = wp_get_attachment_image_url($image_id, 'hero_pic');
    $mobile_url = wp_get_attachment_image_url($image_id, 'hero_pic_mob');

    if ($desktop_url && $mobile_url) {
        ?>
        <img src="<?php echo esc_url($desktop_url); ?>"
            srcset="<?php echo esc_url($mobile_url); ?> 412w, <?php echo esc_url($desktop_url); ?> 600w"
            sizes="(max-width: 767px) 95vw, 600px" alt="SPICY SHRIMP BOWL" class="hp-bowl" fetchpriority="high" loading="eager"
            width="600" height="600" decoding="async">
        <?php
    }
}
?>

    </div>
	</div>

</section>

<!-- Menu -->
<section class="menu-sec" id="menu">

	<div class="container">
      <?php if (!empty(get_field('menu_heading'))): ?>
				<h2><?php the_field('menu_heading'); ?></h2>
      <?php endif; ?>

		<div class="menu-sec-tabs">

			<div class="menu-sec-bar m-black">
				<button class="menu-sec-item menu-sec-button-bowl tabbtn active-red" onclick="openCity(event,'bowls')">Bowls</button>
				<button class="menu-sec-item menu-sec-button-rolls tabbtn" onclick="openCity(event,'rolls')">Rolls</button>
				<button class="menu-sec-item menu-sec-button-sushi-sets tabbtn" onclick="openCity(event,'sushi-sets')">Sushi sets</button>
				<button class="menu-sec-item menu-sec-button-sides tabbtn" onclick="openCity(event,'sides')">Sides</button>
				<button class="menu-sec-item menu-sec-button-drinks tabbtn" onclick="openCity(event,'drinks')">Drinks</button>
			</div>


			<div id="bowls" class="menu-sec-container menu-sec-border papabowls has-filter" data-page="1">

          <?php
          $args = array(
              'post_type' => 'product',
              'posts_per_page' => 1
          );
          $products = get_posts($args);

          if ($products) {
              $product_id = $products[0]->ID;
              $field_object = get_field_object('filters', $product_id);

              if ($field_object && isset($field_object['choices'])) {
                  echo '<div class="menu-filter-wrapper">';
//                  echo '<div class="menu-filter-title">Preferences:</div>';
                  echo '<ul class="menu-filter">';
                  foreach ($field_object['choices'] as $value => $label) {
                      echo '<li data-filter="' . esc_attr($value) . '"><span></span>' . esc_html($label) . '</li>';
                  }
                  echo '</ul>';
                  echo '</div>';
              }
          }
          ?>

				<div class="woocommerce columns-3">
					<ul class="products columns-3">
              <?php
              $ids_raw = get_field('menu_tabs_and_item_bowls');

              // DEBUG: Get ALL bowls from category
              $all_bowls_query = new WP_Query(array(
                  'post_type' => 'product',
                  'posts_per_page' => -1,
                  'fields' => 'ids',
                  'tax_query' => array(
                      array(
                          'taxonomy' => 'product_cat',
                          'field' => 'slug',
                          'terms' => 'bowls',
                      )
                  ),
                  'meta_query' => array(
                      array(
                          'key' => '_stock_status',
                          'value' => 'instock',
                          'compare' => '='
                      )
                  )
              ));
              $all_bowls_count = count($all_bowls_query->posts);
              wp_reset_postdata();

              $ids_instock = array_filter((array)$ids_raw, function ($pid) {
                  return get_post_meta($pid, '_stock_status', true) === 'instock';
              });
              echo '<!-- DEBUG: ACF IDs: ' . count((array)$ids_raw) . ', In stock from ACF: ' . count($ids_instock) . ', Total in category: ' . $all_bowls_count . ' -->';
              if (!empty($ids_instock)) {
                  $args = array(
                      'post_type' => 'product',
                      'post__in' => $ids_instock,
                      'posts_per_page' => 12,
                      'orderby' => 'post__in',
                      'meta_query' => array(
                          array(
                              'key' => '_stock_status',
                              'value' => 'instock',
                              'compare' => '='
                          )
                      )
                  );
              } else {
                  $args = array(
                      'post_type' => 'product',
                      'posts_per_page' => 12,
                      'orderby' => 'date',
                      'order' => 'DESC',
                      'tax_query' => array(
                          array(
                              'taxonomy' => 'product_cat',
                              'field' => 'slug',
                              'terms' => 'bowls',
                          )
                      ),
                      'meta_query' => array(
                          array(
                              'key' => '_stock_status',
                              'value' => 'instock',
                              'compare' => '='
                          )
                      )
                  );
              }
              $loop = new WP_Query($args);

              if ($loop->have_posts()) {

                  while ($loop->have_posts()) : $loop->the_post();

                      wc_get_template_part('content', 'product');

                  endwhile;
              }

              wp_reset_postdata();
              ?>
					</ul><!--/.products-->
				</div>

			</div>

			<div id="rolls" class="menu-sec-container menu-sec-border papabowls has-filter" style="display:none" data-page="1">
          <?php
          $args = array(
              'post_type' => 'product',
              'posts_per_page' => 1
          );
          $products = get_posts($args);

          if ($products) {
              $product_id = $products[0]->ID;
              $field_object = get_field_object('filters', $product_id);

              if ($field_object && isset($field_object['choices'])) {
                  echo '<div class="menu-filter-wrapper">';
//                  echo '<div class="menu-filter-title">Preferences:</div>';
                  echo '<ul class="menu-filter">';
                  foreach ($field_object['choices'] as $value => $label) {
                      echo '<li data-filter="' . esc_attr($value) . '"><span></span>' . esc_html($label) . '</li>';
                  }
                  echo '</ul>';
                  echo '</div>';
              }
          }
          ?>
				<div class="woocommerce columns-3 ">
					<ul class="products columns-3">
              <?php
              $ids_raw = get_field('menu_tabs_and_item_rolls');
              $ids_instock = array_filter((array)$ids_raw, function ($pid) {
                  return get_post_meta($pid, '_stock_status', true) === 'instock';
              });
              echo '<!-- DEBUG: Total rolls IDs: ' . count((array)$ids_raw) . ', In stock: ' . count($ids_instock) . ' -->';
              if (!empty($ids_instock)) {
                  $args = array(
                      'post_type' => 'product',
                      'post__in' => $ids_instock,
                      'posts_per_page' => 12,
                      'orderby' => 'post__in',
                      'meta_query' => array(
                          array(
                              'key' => '_stock_status',
                              'value' => 'instock',
                              'compare' => '='
                          )
                      )
                  );
              } else {
                  $args = array(
                      'post_type' => 'product',
                      'posts_per_page' => 12,
                      'orderby' => 'date',
                      'order' => 'DESC',
                      'tax_query' => array(
                          array(
                              'taxonomy' => 'product_cat',
                              'field' => 'slug',
                              'terms' => 'rolls',
                          )
                      ),
                      'meta_query' => array(
                          array(
                              'key' => '_stock_status',
                              'value' => 'instock',
                              'compare' => '='
                          )
                      )
                  );
              }
              $loop = new WP_Query($args);

              if ($loop->have_posts()) {

                  while ($loop->have_posts()) : $loop->the_post();

                      wc_get_template_part('content', 'product');

                  endwhile;
              }

              wp_reset_postdata();
              ?>
					</ul><!--/.products-->
				</div>
			</div>

			<div id="sushi-sets" class="menu-sec-container menu-sec-border papabowls has-filter" style="display:none" data-page="1">
				<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => 1
				);
				$products = get_posts($args);

				if ($products) {
					$product_id = $products[0]->ID;
					$field_object = get_field_object('filters', $product_id);

					if ($field_object && isset($field_object['choices'])) {
						echo '<div class="menu-filter-wrapper">';
//                  echo '<div class="menu-filter-title">Preferences:</div>';
						echo '<ul class="menu-filter">';
						foreach ($field_object['choices'] as $value => $label) {
							echo '<li data-filter="' . esc_attr($value) . '"><span></span>' . esc_html($label) . '</li>';
						}
						echo '</ul>';
						echo '</div>';
					}
				}
				?>
				<div class="woocommerce columns-3">
					<ul class="products columns-3">
						<?php
						$ids_raw = get_field('menu_tabs_and_item_sides');
						$ids_instock = array_filter((array)$ids_raw, function ($pid) {
							return get_post_meta($pid, '_stock_status', true) === 'instock';
						});
						echo '<!-- DEBUG: Total sides IDs: ' . count((array)$ids_raw) . ', In stock: ' . count($ids_instock) . ' -->';
						if (!empty($ids_instock)) {
							$args = array(
								'post_type' => 'product',
								'post__in' => $ids_instock,
								'posts_per_page' => 12,
								'orderby' => 'post__in',
								'meta_query' => array(
									array(
										'key' => '_stock_status',
										'value' => 'instock',
										'compare' => '='
									)
								)
							);
						} else {
							$args = array(
								'post_type' => 'product',
								'posts_per_page' => 12,
								'orderby' => 'date',
								'order' => 'DESC',
								'tax_query' => array(
									array(
										'taxonomy' => 'product_cat',
										'field' => 'slug',
										'terms' => 'sushi-sets',
									)
								),
								'meta_query' => array(
									array(
										'key' => '_stock_status',
										'value' => 'instock',
										'compare' => '='
									)
								)
							);
						}
						$loop = new WP_Query($args);

						if ($loop->have_posts()) {

							while ($loop->have_posts()) : $loop->the_post();

								wc_get_template_part('content', 'product');

							endwhile;
						}

						wp_reset_postdata();
						?>
					</ul><!--/.products-->
				</div>
			</div>

			<div id="sides" class="menu-sec-container menu-sec-border papabowls has-filter" style="display:none" data-page="1">
          <?php
          $args = array(
              'post_type' => 'product',
              'posts_per_page' => 1
          );
          $products = get_posts($args);

          if ($products) {
              $product_id = $products[0]->ID;
              $field_object = get_field_object('filters', $product_id);

              if ($field_object && isset($field_object['choices'])) {
                  echo '<div class="menu-filter-wrapper">';
//                  echo '<div class="menu-filter-title">Preferences:</div>';
                  echo '<ul class="menu-filter">';
                  foreach ($field_object['choices'] as $value => $label) {
                      echo '<li data-filter="' . esc_attr($value) . '"><span></span>' . esc_html($label) . '</li>';
                  }
                  echo '</ul>';
                  echo '</div>';
              }
          }
          ?>
				<div class="woocommerce columns-3">
					<ul class="products columns-3">
              <?php
              $ids_raw = get_field('menu_tabs_and_item_sides');
              $ids_instock = array_filter((array)$ids_raw, function ($pid) {
                  return get_post_meta($pid, '_stock_status', true) === 'instock';
              });
              echo '<!-- DEBUG: Total sides IDs: ' . count((array)$ids_raw) . ', In stock: ' . count($ids_instock) . ' -->';
              if (!empty($ids_instock)) {
                  $args = array(
                      'post_type' => 'product',
                      'post__in' => $ids_instock,
                      'posts_per_page' => 12,
                      'orderby' => 'post__in',
                      'meta_query' => array(
                          array(
                              'key' => '_stock_status',
                              'value' => 'instock',
                              'compare' => '='
                          )
                      )
                  );
              } else {
                  $args = array(
                      'post_type' => 'product',
                      'posts_per_page' => 12,
                      'orderby' => 'date',
                      'order' => 'DESC',
                      'tax_query' => array(
                          array(
                              'taxonomy' => 'product_cat',
                              'field' => 'slug',
                              'terms' => 'sides',
                          )
                      ),
                      'meta_query' => array(
                          array(
                              'key' => '_stock_status',
                              'value' => 'instock',
                              'compare' => '='
                          )
                      )
                  );
              }
              $loop = new WP_Query($args);

              if ($loop->have_posts()) {

                  while ($loop->have_posts()) : $loop->the_post();

                      wc_get_template_part('content', 'product');

                  endwhile;
              }

              wp_reset_postdata();
              ?>
					</ul><!--/.products-->
				</div>
			</div>

			<div id="drinks" class="menu-sec-container menu-sec-border papabowls has-filter" style="display:none" data-page="1">
          <?php
          $args = array(
              'post_type' => 'product',
              'posts_per_page' => 1
          );
          $products = get_posts($args);

          if ($products) {
              $product_id = $products[0]->ID;
              $field_object = get_field_object('filters', $product_id);

              if ($field_object && isset($field_object['choices'])) {
                  echo '<div class="menu-filter-wrapper">';
//                  echo '<div class="menu-filter-title">Preferences:</div>';
                  echo '<ul class="menu-filter">';
                  foreach ($field_object['choices'] as $value => $label) {
                      echo '<li data-filter="' . esc_attr($value) . '"><span></span>' . esc_html($label) . '</li>';
                  }
                  echo '</ul>';
                  echo '</div>';
              }
          }
          ?>
				<div class="woocommerce columns-3">
					<ul class="products columns-3">
              <?php
              $ids_raw = get_field('menu_tabs_and_item_drinks');
              $ids_instock = array_filter((array)$ids_raw, function ($pid) {
                  return get_post_meta($pid, '_stock_status', true) === 'instock';
              });
              echo '<!-- DEBUG: Total drinks IDs: ' . count((array)$ids_raw) . ', In stock: ' . count($ids_instock) . ' -->';
              if (!empty($ids_instock)) {
                  $args = array(
                      'post_type' => 'product',
                      'post__in' => $ids_instock,
                      'posts_per_page' => 12,
                      'orderby' => 'post__in',
                      'meta_query' => array(
                          array(
                              'key' => '_stock_status',
                              'value' => 'instock',
                              'compare' => '='
                          )
                      )
                  );
              } else {
                  $args = array(
                      'post_type' => 'product',
                      'posts_per_page' => 12,
                      'orderby' => 'date',
                      'order' => 'DESC',
                      'tax_query' => array(
                          array(
                              'taxonomy' => 'product_cat',
                              'field' => 'slug',
                              'terms' => 'drinks',
                          )
                      ),
                      'meta_query' => array(
                          array(
                              'key' => '_stock_status',
                              'value' => 'instock',
                              'compare' => '='
                          )
                      )
                  );
              }
              $loop = new WP_Query($args);

              if ($loop->have_posts()) {

                  while ($loop->have_posts()) : $loop->the_post();

                      wc_get_template_part('content', 'product');

                  endwhile;
              }

              wp_reset_postdata();
              ?>
					</ul><!--/.products-->
				</div>
			</div>
		</div>

	</div>

	</div>

	</div>

</section>

<!-- About us -->
<section class="team-sec" style="display: none;">

	<div class="container team-sec-up">

		<div class="team-sec-up-wrap">

			<h2><?php the_field('about_us_heading'); ?></h2>

			<div class="flex team-sec-up-text">

				<p><?php the_field('about_us_left_text'); ?></p>
				<p><?php the_field('about_us_right_text'); ?></p>

			</div>

		</div>

	</div>

	<div class="container flex team-sec-down">

		<img src="<?php the_field('about_us_photo'); ?>" alt="team" width="897" height="588">
		<div class="team-sec-down-right">
			<h2><?php the_field('about_us_heading2'); ?></h2>
			<p><?php the_field('about_us_text'); ?></p>
		</div>
	</div>
</section>

<section class="ph-sect" id="story">
	<div class="container">
		<div class="ph-sect__wrapper">
			<div class="ph-sect__text">
          <?php $philosophy = get_field('philosophy'); ?>
          <?php if (!empty($philosophy)): ?>
              <?= $philosophy['text'] ?>
          <?php endif; ?>
			</div>
			<div class="ph-sect__pic">
        <?php if (!empty($philosophy['picture']['ID'])):
            $image_id = $philosophy['picture']['ID'];
            echo wp_get_attachment_image($image_id, 'sect_pic', false, [
                'loading' => 'lazy',
                'alt' => 'Out Philosophy'
            ]);
        endif; ?>
			</div>
		</div>
	</div>
</section>
</main>
<?php get_footer(); ?>
