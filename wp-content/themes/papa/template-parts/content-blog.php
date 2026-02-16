<?php $id_post = get_the_ID(); ?>
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
