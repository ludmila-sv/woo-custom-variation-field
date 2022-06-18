jQuery(document).ready( function($) {
	$( 'a[href="#variable_product_options"]' ).on( 'click', function() {
		setTimeout( () => {
			$('input[name*="_prod_color_var"]').wpColorPicker();
		}, 1000);
	} );
} );
