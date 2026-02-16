<?php 

function hublatestposts() { ?>

<div class="homemage__categories flex">
     
    <?php 
    // Define our WP Query Parameters
    $the_query = new WP_Query( 'posts_per_page=6' ); ?>
      
     
    <?php 
    // Start our WP Query
    while ($the_query -> have_posts()) : $the_query -> the_post(); 
    // Display the Post Title with Hyperlink
    ?>
      
     
    <article class="blog_item wow fadeIn" data-wow-delay=".07s" style="visibility: hidden; background-image: url();">

        <div class="blog_item_cont-cat flex">
                    <span><?php the_category(' '); ?></span>
                    <span class="time-read"><?php echo gp_read_time(); ?> min read</span>
        </div>    

        <a href="<?php the_permalink(); ?>" class="blog_thumblink" style="background-image: url(<?php if ( has_post_thumbnail()) { ?>
           
                 <?php the_post_thumbnail_url(); ?>

            <?php } ?>   );">

            <div class="blog_item_cont">

                <p class="blog_item-title">
                    <?php the_title() ?>
                </p>

                <p class="blog_item-text">
                    <?php echo get_excerpt(120); ?>
                </p>

            </div>     

        </a>    

    </article>
      
     
    <?php 
    // Repeat the process and reset once it hits the limit
    endwhile;
    wp_reset_postdata();
    ?>

</div>
    

<?php } ?>