<?php /* Template Name: Sitemap */ ?>

<?php get_header(); ?>

<div class="page-content">
	<div class="container">
		<div class="page-delivery page-sitemap">
            <h1 class="page-title">Sitemap</h1>
            <?php the_content(); ?>
        </div>

	</div>
</div>

<style>
    .wsp-container h2 {
      font-size: 30px;
      font-weight: 700;
      color: #3E6352;
      margin-bottom: 20px;
    }
    .contact-info-item p, .page-sitemap a {
     display: block;
			padding-bottom: 5px;
     font-size: 16px;
     line-height: 110%;
     font-weight: 400;
     color: #3E6352;
     text-align: left;
    }
    .contact-info-item p, .page-sitemap a:hover {
  text-decoration: underline;
    }

    .wsp-products-list {
     column-count: 3;
    }
    @media (max-width: 768px) {
     .wsp-products-list {
      column-count: 2;
     }
    }
    @media (max-width: 574px) {
     .wsp-products-list {
      column-count: 2;
     }
    }

</style>

<?php get_footer(); ?>
