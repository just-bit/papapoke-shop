<!-- OBS -->
<footer class="footer" id="contacts">

	<div class="container">
		<div class="footer-wrapper">
			<div class="footer-logo">
          <?php
				 $image = get_field('footer_logo', 'option');
				 $size = 'full';

				 if ($image) {
					 echo wp_get_attachment_image($image, $size, false, array('loading' => 'lazy'));
				 }
				 ?>
			</div>
			<div class="footer-green">
				<div class="footer-menu footer-menu-col">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main_menu',
                'container_class' => 'main-menu',
                'container' => 'nav'
            ));
            ?>
				</div>
				<div class="footer-menu footer-menu-col">
            <?php dynamic_sidebar('sidebar-footer-area-1'); ?>
				</div>
			</div>
		</div>
		<div class="footer-copyrights">
			<p>©<?= date('Y') ?> Papa Poke. All rights reserved</p>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer_bottom_menu',
            'container_class' => 'footer-bottom-menu',
            'container' => 'nav'
        ));
        ?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>

<div class="mfp-hide">
	<!-- popup-content -->
	<div class="popup popup-content" id="popup-content">
		<button type="button" class="mfp-close" aria-label="Close popup">
		</button>
		<div class="content">
			<div class="popup-content-title">Choose where to order</div>
			<div class="popup-content-inner">
				<a href="<?= is_front_page() ? '#menu' : '/#menu' ?>" class="popup-content-btn popup-content-btn-collect">Pick Up</a>
				<a href="/delivery/" class="popup-content-btn popup-content-btn-delivery">Delivery</a>
			</div>
		</div>
	</div>
</div>

</body>

</html>
