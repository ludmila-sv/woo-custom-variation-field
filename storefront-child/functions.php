<?php
/**
 * Returns version string for assets.
 *
 * @return null|string
 */
function _get_asset_version() {

	return defined( 'ENV_DEV' ) ? gmdate( 'YmdHis' ) : wp_get_theme()->get( 'Version' );

}

/**
 * Theme scripts and styles
 *
 * @return void
 */
function storefront_child_enqueue_styles_scripts() {

	wp_enqueue_style( 'storefront-child-style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0' );

	$asset_version = _get_asset_version();

	wp_enqueue_script(
		'storefront-child-scripts',
		get_stylesheet_directory_uri() . '/assets/js/scripts.js',
		array( 'jquery' ),
		$asset_version,
		true
	);

}
add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_styles_scripts' );

/**
 * Admin scripts and styles
 *
 * @return void
 */
function storefront_child_admin_scripts( $hook ) {
	$asset_version = _get_asset_version();

	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );

	wp_enqueue_script( 'storefront-child-admin-script', get_stylesheet_directory_uri() . '/assets/js/admin-scripts.js', array( 'wp-color-picker' ), $asset_version, true );
}
add_action( 'admin_enqueue_scripts', 'storefront_child_admin_scripts' );


/**
 * Add custom color field to variation
 *
 * @param string $loop
 * @param array $variation_data
 * @param object $variation
 */
function storefront_child_color_field( $loop, $variation_data, $variation ) {
	woocommerce_wp_text_input(
		array(
			'id'          => '_prod_color_var[' . $variation->ID . ']',
			'label'       => 'Button color',
			'description' => 'Color in hex',
			'desc_tip'    => true,
			'placeholder' => '#ff0000',
			'value'       => get_post_meta( $variation->ID, '_prod_color_var', true ),
		)
	);
}
add_action( 'woocommerce_product_after_variable_attributes', 'storefront_child_color_field', 10, 3 );

/**
 * Saves custom variation field value
 */
function storefront_child_save_variation_color_field( $post_id ) {
	$woocommerce__prod_color_var = $_POST['_prod_color_var'][ $post_id ];
	if ( isset( $woocommerce__prod_color_var ) && ! empty( $woocommerce__prod_color_var ) ) {
		update_post_meta( $post_id, '_prod_color_var', esc_attr( $woocommerce__prod_color_var ) );
	}
}
add_action( 'woocommerce_save_product_variation', 'storefront_child_save_variation_color_field', 10, 2 );

/**
 * Renders choose color buttons.
 */
function storefront_child_render_color_btns() {
	global $product;

	$default_attributes = $product->get_default_attributes();
	$default_color      = $default_attributes['pa_color'];

	$variations = $product->get_available_variations();
	?>

	<div class="choose-color">Choose color:</div>
	<div class="variation-colors">
		<?php
		foreach ( $variations as $variation ) {
			$variations_color = get_post_meta( $variation['variation_id'], '_prod_color_var', true );

			$attr_name = $variation['attributes']['attribute_pa_color'];
			$checked   = ( $attr_name === $default_color ) ? ' checked' : '';
			?>
			<div class="variation-color__btn">
				<input type="radio" name="variation_color" value="<?php echo esc_attr( $attr_name ); ?>" id="variation_color_<?php echo esc_attr( sanitize_title( $attr_name ) ); ?>"<?php echo esc_attr( $checked ); ?>>
				<label for="variation_color_<?php echo esc_attr( sanitize_title( $attr_name ) ); ?>"><span style="background-color: <?php echo $variations_color; ?>"><i class="fas fa-check"></i></span></label>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}
add_action( 'woocommerce_after_variations_table', 'storefront_child_render_color_btns' );
