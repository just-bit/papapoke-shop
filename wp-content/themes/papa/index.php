<?php
/* 
Template Name: Archives
*/
get_header();

?>

<section class="blog_section">

	<div class="homemage__categories blog__categories wow fadeIn" data-wow-delay=".05s" style="visibility: hidden;">

		<div class="container">
			
	        <ul class="blog__categorieslist flex">
	         
	            <li class="active"><a href="/blog/" class="wow fadeIn" data-wow-delay=".05s" style="visibility: hidden;">All</a></li>
				<?php
					$categories = get_terms( array(
					'taxonomy'   => 'category',
					'hide_empty' => false,
					'exclude'    => 1
				) );
				if ( $categories ) {
					$time = .1;
					foreach ( $categories as $category ) { ?>
	                    <li<?php if ( $category->term_id === $this_id ) {
									echo ' class="active"';
								} ?>>
	                        <a href="<?php echo get_category_link( $category->term_id ); ?>" class="wow fadeIn"
	                           data-wow-delay="<?php echo $time; ?>s"
	                           style="visibility: hidden;"><?php echo $category->name; ?></a>
	                    </li>
						<?php $time += 0.05;
					}
					wp_reset_postdata();
				} ?>

	        </ul>

	   </div>

	</div>


	<div class="container">



		<?php if ( have_posts() ) : ?>

		   <div class="blog_list flex">
		                
				<?php

					$exclude_ids_for_related = wp_cache_get( 'exclude_ids_for_related' );

					if ( false === $exclude_ids_for_related ) {
						$exclude_ids_for_related = array();
						wp_cache_set( 'exclude_ids_for_related', $exclude_ids_for_related );
					}

					while ( have_posts() ) : the_post();
						$exclude_ids_for_related[] = $post->ID;
						wp_cache_set( 'exclude_ids_for_related', $exclude_ids_for_related );
						get_template_part( 'template-parts/content', 'blog' );
					endwhile; ?>

			</div>

				<?php
						the_posts_pagination( array(
								'prev_text' => '←',
								'next_text' => '→',
							) );

					endif;
				?>
		    
		
			</div>


</section>

<?php get_footer(); ?>