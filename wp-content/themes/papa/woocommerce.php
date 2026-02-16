<?php
 	get_header(); 
?>

<div class="container catalog flex">

		<?php

		if (is_shop() || is_product_tag() || is_product_category() ) {

		 if ( function_exists('dynamic_sidebar') ) 
				
			echo '<div class="filter-sidebar">';
				dynamic_sidebar('filer-sidebar'); 
			echo '</div>';

		}

		?>

	<div class="product-list">

		<?php if ( have_posts() ) : ?>

			<?php woocommerce_content(); ?>

		<?php endif; ?>

	</div>

</div>

<?php get_footer(); ?>