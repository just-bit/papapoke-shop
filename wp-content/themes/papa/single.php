<?php
/* 
Template Name: Archives
*/
get_header();
?>

<div class="single-post-aboutpost wow fadeIn" data-wow-delay=".05s" style="visibility: hidden;">
	<div class="container flex">

	    <div class="single-post-aboutpost-cat"><?php the_category(' '); ?></div>

        <div class="single-post-aboutpost-minread"><?php echo gp_read_time(); ?> мин чтения</div>
            
        <div class="single-post-aboutpost-time">Update: <time datetime="<?php echo the_modified_time( 'Y-m-d\TH:i:s' )?>"><?php echo the_modified_time( 'Y-m-d' )?></time></div>

    </div>
</div>

<section class="singlepost">

	<div class="singlepost-fullwimg wow fadeIn" data-wow-delay=".09s" style="visibility: hidden;">
		<img src="<?php the_field('fullsize_image'); ?>" alt="fullbg">
	</div>
	
	<div class="container"> 

	    <main id="main" class="site-main" role="main">
	  
	        <?php
	        // Start the loop.
	        while ( have_posts() ) : the_post();
	  
	            /*
	             * Include the post format-specific template for the content. If you want to
	             * use this in a child theme, then include a file called called content-___.php
	             * (where ___ is the post format) and that will be used instead.
	             */
	        ?>

	            <div class="post-text content-with-wow">
		            <?php the_content () ?>
	  			</div>

	            <?php // If comments are open or we have at least one comment, load up the comment template.
	            // if ( comments_open() || get_comments_number() ) :
	            //    comments_template();
	            // endif;
	  
	         	  
		        // End the loop.
		        endwhile;
	        ?>
	  
	    </main><!-- .site-main -->


	    	<div class="single-menu">
				<a href="javascript: history.go(-1)" class="item back"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 20 18" fill="none" class="svg replaced-svg">
				<path d="M0.121981 8.70063L8.0385 0.6496C8.89017 -0.216533 10.276 -0.216533 11.1276 0.6496C11.9793 1.51573 11.9793 2.92507 11.1276 3.79121L8.08936 6.88109H17.9167C19.065 6.88109 20 7.83196 20 8.99981C20 10.1677 19.065 11.1185 17.9167 11.1185H8.08936L11.1276 14.2084C11.9793 15.0745 11.9793 16.4838 11.1276 17.35C10.7018 17.783 10.1427 18 9.58266 18C9.02267 18 8.46436 17.7839 8.03768 17.35L0.121121 9.29894C-0.0405168 9.13368 -0.0405167 8.86589 0.121981 8.70063Z" fill="#1A482D"></path>
				</svg></a>

				<a href="#header" class="item up"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20" fill="none" class="svg replaced-svg">
				<path opacity="0.2" d="M9.29937 0.121981L17.3504 8.0385C18.2165 8.89017 18.2165 10.276 17.3504 11.1276C16.4843 11.9793 15.0749 11.9793 14.2088 11.1276L11.1189 8.08936L11.1189 17.9167C11.1189 19.065 10.168 20 9.00019 20C7.83235 20 6.88152 19.065 6.88152 17.9167V8.08936L3.79163 11.1276C2.9255 11.9793 1.51616 11.9793 0.650026 11.1276C0.21698 10.7018 0 10.1427 0 9.58266C0 9.02267 0.216105 8.46436 0.650026 8.03768L8.70106 0.121121C8.86632 -0.0405168 9.13411 -0.0405167 9.29937 0.121981Z" fill="#333333"></path>
				</svg></a>

				<!-- <a href="#wpd-post-rating" class="item comment">Comments</a> -->

			</div>


	</div><!-- .content-area -->



</section>


<?php get_footer(); ?>