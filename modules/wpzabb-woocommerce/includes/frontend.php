<?php
if ( post_type_exists( 'product' ) ) {
	$args = new stdClass();
	$args->post_type = 'product';
	$args->posts_per_page = (int)$settings->count > 0 ? (int)$settings->count : -1;
	$args->order = $settings->orderdir;
	$args->orderby = $settings->orderby == 'price' || $settings->orderby == 'sales' ? 'meta_value_num' : ( $settings->orderby == 'rand' ? 'rand' : 'date' );
	if ( $settings->orderby == 'price' || $settings->orderby == 'sales' ) $args->meta_key = $settings->orderby == 'price' ? '_price' : 'total_sales';
	if ( (int)$settings->category > 0 ) $args->tax_query = array( array( 'taxonomy' => 'product_cat', 'field' => 'id', 'terms' => (int)$settings->category ) );
	$query = FLBuilderLoop::query( $args );

	if ( $query->have_posts() ) :

?><div class="wpzabb-woocommerce-products">

	<?php
	woocommerce_product_loop_start();

	while ( $query->have_posts() ) {
		$query->the_post();
		$_product;
		if ( function_exists( 'wc_get_product' ) ) {
			$_product = wc_get_product( $query->post->ID );
		} else {
			$_product = new WC_Product( $query->post->ID );
		}

		?><li <?php post_class(); ?>>
			<?php
			/**
			 * woocommerce_before_shop_loop_item hook.
			 *
			 * @hooked woocommerce_template_loop_product_link_open - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item' );

			/**
			 * woocommerce_before_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );

			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );

			if ( $settings->showprice == 'true' ) {
				/**
				 * woocommerce_after_shop_loop_item_title hook.
				 *
				 * @hooked woocommerce_template_loop_rating - 5
				 * @hooked woocommerce_template_loop_price - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
			}

			if ( $settings->showcartbtn == 'true' ) {
				/**
				 * woocommerce_after_shop_loop_item hook.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item' );
			}
			?>
		</li><?php
	}

	woocommerce_product_loop_end();
	?>

</div><?php

	endif;

	wp_reset_postdata();
}