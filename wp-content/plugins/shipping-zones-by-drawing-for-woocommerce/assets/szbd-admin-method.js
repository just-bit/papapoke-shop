jQuery(document).ready(function($) {
    
	$('#szbd_exclude_shipping_methods').attr('disabled', 'disabled');
	jQuery(document).on('wc_backbone_modal_loaded', function() {
		$('.szbd-enhanced-select').selectWoo();
		$('#woocommerce_szbd-shipping-method_title').parents('fieldset').hide();
		if ($('#woocommerce_szbd-shipping-method_map').find('option:selected').attr("value") !== 'radius') {
			$('#woocommerce_szbd-shipping-method_max_radius').parents('fieldset').hide();
            $('#woocommerce_szbd-shipping-method_max_radius').parents('fieldset').prev('label').hide();
		}
		

		if ($('#woocommerce_szbd-shipping-method_rate_mode').find('option:selected').attr("value") == 'flat') {
			$('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').hide();
            $('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').prev('label').hide();
			$('#woocommerce_szbd-shipping-method_rate_distance').parents('fieldset').hide();
            $('#woocommerce_szbd-shipping-method_rate_distance').parents('fieldset').prev('label').hide();
		} else if ($('#woocommerce_szbd-shipping-method_rate_mode').find('option:selected').attr("value") == 'distance') {
			$('#woocommerce_szbd-shipping-method_rate').parents('fieldset').hide();
            $('#woocommerce_szbd-shipping-method_rate').parents('fieldset').prev('label').hide();
			$('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').hide();
            $('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').prev('label').hide();
		}else{
			$('#woocommerce_szbd-shipping-method_rate').parents('fieldset').hide();
            $('#woocommerce_szbd-shipping-method_rate').parents('fieldset').prev('label').hide();
			
		}
		$('#woocommerce_szbd-shipping-method_rate_mode').on('change',function() {
			if ($(this).find('option:selected').attr("value") == 'flat') {
				$('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').fadeOut();
            $('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').prev('label').fadeOut();
			$('#woocommerce_szbd-shipping-method_rate_distance').parents('fieldset').fadeOut();
            $('#woocommerce_szbd-shipping-method_rate_distance').parents('fieldset').prev('label').fadeOut();
				$('#woocommerce_szbd-shipping-method_rate').parents('fieldset').fadeIn();
                $('#woocommerce_szbd-shipping-method_rate').parents('fieldset').prev('label').fadeIn();

			} else if ($(this).find('option:selected').attr("value") == 'distance') {
				$('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').fadeOut();
                $('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').prev('label').fadeOut();
				$('#woocommerce_szbd-shipping-method_rate_distance').parents('fieldset').fadeIn();
                $('#woocommerce_szbd-shipping-method_rate_distance').parents('fieldset').prev('label').fadeIn();
			} else {
				$('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').fadeIn();
                $('#woocommerce_szbd-shipping-method_rate_fixed').parents('fieldset').prev('label').fadeIn();
				$('#woocommerce_szbd-shipping-method_rate_distance').parents('fieldset').fadeIn();
                $('#woocommerce_szbd-shipping-method_rate_distance').parents('fieldset').prev('label').fadeIn();
			}
		});
		$('#woocommerce_szbd-shipping-method_map').on('change', function() {
			if ($(this).find('option:selected').attr("value") == 'radius') {
				
				$('#woocommerce_szbd-shipping-method_max_radius').parents('fieldset').fadeIn();
                $('#woocommerce_szbd-shipping-method_max_radius').parents('fieldset').prev('label').fadeIn();
			} else if ($(this).find('option:selected').attr("value") == 'none') {
				$('#woocommerce_szbd-shipping-method_max_radius').parents('fieldset').fadeOut();
                $('#woocommerce_szbd-shipping-method_max_radius').parents('fieldset').prev('label').fadeOut();
				
			} else {
				$('#woocommerce_szbd-shipping-method_max_radius').parents('fieldset').fadeOut();
                $('#woocommerce_szbd-shipping-method_max_radius').parents('fieldset').prev('label').fadeOut();
				
			}
		});
		
		

		
		
		$('#woocommerce_szbd-shipping-method_rate_mode').children('option').each(function() {
		if ($(this).val() === 'distance') {
			$(this).attr('disabled', true);
		}
		else if ($(this).val() === 'fixed_and_distance') {
			$(this).attr('disabled', true);
		}
	});
    // Insert links buttons to premium
    jQuery('.wc-modal-shipping-method-settings').find('.in_premium').parents('fieldset').prev('label:visible').after('<span class="premium_link" ><a  target="_blank" class="premium_link_ref" href="https://shippingzonesplugin.com/">Premium</a></span>');

	});

    
});
