<?php
 /*
 * Template name: Contact us Template
 */

 get_header(); 

?>

<div class="page-content">

    <div class="container">

        <section class="section-contact">

            <div class="container flex">
                
                <div class="contact-left">
                    
                    <?php

                        $form_code = get_field('contact_form');

                        echo do_shortcode($form_code);

                    ?>

                </div>

                <div class="contact-right">
                    
                    <?php

                        if (have_posts()) :

                            while (have_posts()) :

                                the_post();

                                the_content();

                            endwhile;

                        endif;

                    ?>

                </div>

            </div>
        
        </section>

    </div>

</div>

<?php get_footer(); ?>