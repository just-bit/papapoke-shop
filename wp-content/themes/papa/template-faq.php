<?php
 /*
 * Template name: FAQ Template
 */

 get_header(); 

?>

<div class="page-content">

    <div class="container">

        <section class="faq-section">

        <?php 
            $faq_heading = get_field('heading'); 
            if($faq_heading) {
        ?>
        <h2><?php echo $faq_heading; ?></h2>

        <?php }; ?>
        <?php if( have_rows('single_faq') ): ?>
            
            <div class="single__container">
                <?php while( have_rows('single_faq') ): the_row(); 
                    $single_faq_q = get_sub_field('single_faq_q');
                    $single_faq_a = get_sub_field('single_faq_a');
                ?>
                    <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">

                         <button itemprop="name" class="single__accordion">
                           <span class="faq-section-text"><?php echo $single_faq_q; ?></span>
                           <span class="faq-section-but"></span>
                         </button>
                         <div class="single__panel" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                            <p itemprop="text"><?php echo $single_faq_a; ?></p>
                         </div>
                    </div>

                <?php endwhile; ?>
                        
            </div>

        <?php endif; ?>
        
        </section>

    </div>

</div>

<?php get_footer(); ?>