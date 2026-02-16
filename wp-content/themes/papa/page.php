<?php
 get_header(); 
?>

    <div class="container">

        <section class="page-maincontent">
            
            <?php

                if (have_posts()) :

                    while (have_posts()) :

                        the_post(); 

                        the_content();

                    endwhile;

                endif;

            ?>

        </section>

    </div>

<?php get_footer(); ?>