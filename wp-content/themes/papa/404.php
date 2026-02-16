<?php
/*
Template Name: Archives
*/
get_header();
?>

<section class="nf__container">

	<div class="container">

		<h1><span>4</span>0<span>4</span></h1>
		<p class="error-text">Page can’t be found</p>

		<div class="random-products">

			<h2 class="homepage-news" style="margin: 50px 0; font-size: 40px; line-height: 1; text-align:center;">It can be interesting for you</h2>

			<div>
				<?php echo do_shortcode('[products limit="4" columns="4" best_selling="true" ]'); ?>
			</div>
		</div>

	</div>

</section>


<?php get_footer(); ?>
