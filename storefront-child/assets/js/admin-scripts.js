(function ($) {
	'use strict';
	$('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {
		if ($.fn.wpColorPicker) {
			$('input[name*="_prod_color_var"]').wpColorPicker();
		}
	});
	$('#variable_product_options').on('woocommerce_variations_added', function () {
		if ($.fn.wpColorPicker) {
			$('input[name*="_prod_color_var"]').wpColorPicker();
		}
	});
	
})(jQuery);
