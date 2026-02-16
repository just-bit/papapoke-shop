<?php /* Template Name: Delivery */ ?>

<?php get_header(); ?>
<main>
<div class="page-content">
	<div class="container">
		<div class="page-delivery">
        <?php if (!empty(get_field('title'))): ?>
					<h1 class="page-title"><?= get_field('title') ?></h1>
        <?php endif; ?>
        <?php $list = get_field('list'); ?>
        <?php if (!empty($list)) { ?>
					<ul class="delivery-list">
              <?php foreach ($list as $item) { ?>
                  <?php if (!empty($item['link'])): ?>
									<li>
										<a href="<?= $item['link'] ?>" rel="nofollow" target="_blank">
											<img src="<?= $item['icon'] ?>" alt="logo">
											<span><?= $item['text'] ?></span>
										</a>
									</li>
                  <?php endif; ?>
              <?php } ?>
					</ul>
        <?php } ?>
		</div>
	</div>
</div>
</main>

<?php get_footer(); ?>
