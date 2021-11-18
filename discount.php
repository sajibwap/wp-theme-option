<?php

/**
* Display Cross-Sell Products In Product Page;
*/

add_action('woocommerce_after_single_product', 'show_cross_sell_in_single_product', 30);
function show_cross_sell_in_single_product(){
    $crosssells = get_post_meta( get_the_ID(), '_crosssell_ids',true);

    // if(empty($crosssells)){
    //     return;
    // }

    $args = array( 
        'post_type' => 'product', 
        'posts_per_page' => -1, 
        'post__in' => $crosssells 
        );
    $products = new WP_Query( $args );
    if( $products->have_posts() ) : 
        echo '<div class="cross-sells"><h4>You may be interested in…</h4>';
        woocommerce_product_loop_start();
        while ( $products->have_posts() ) : $products->the_post();
            wc_get_template_part( 'content', 'product' );
        endwhile; // end of the loop.
        
        woocommerce_product_loop_end();
        echo '</div>';
        
    endif;
    wp_reset_postdata();
}

/**
* Changing Price for Frontend When A product id added to cart except the cross-sell product;
*/

add_filter('woocommerce_get_price_html', 'exc_discount', 10, 2);
function exc_discount( $price_html, $product ){
 

    //$crosssells = get_post_meta( get_the_ID(), '_crosssell_ids',true);
 
	/* if already has discount, do nothing */
	if( $product->is_on_sale() ) {
		return $price_html;
	}
	$item = get_option('exc_product');
	if( !is_admin() && count(WC()->cart->get_cross_sells())>0 && $product->get_id() == $item && (WC()->cart->get_cart_contents_count() >= 1)) {
			/* discount percentage */
        	$discount = 50;
         
        	return wc_format_sale_price(
        		wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ),
        		wc_get_price_to_display( $product, array( 'price' => ($product->get_regular_price() * ( 1 - $discount / 100 ) ) ) )
        	) . $product->get_price_suffix();
	}else{
	    return $price_html;
	    
	}
 
}

/**
* Changing Price for Cart When A product id added to cart except the cross-sell product;;
*/
    add_action( 'woocommerce_before_calculate_totals', 'exc_recalculate_price' );
    function exc_recalculate_price( $cart_object ) {
     
    	// you can always print_r() your object and look what's inside
    	//print_r( $cart_object->get_cart()); exit;
    	
     
	    // change prices
		foreach ( $cart_object->get_cart() as $hash => $value ) {
 			$item = get_option('exc_product');
			// and I  want to make discount for the product with ID 1228 - Magic Wand
			if( $value['product_id'] == $item && $value['quantity'] == 1 && WC()->cart->get_cart_contents_count() > 1 ) {
                $discount = 50;
				$newprice = $value['data']->get_regular_price() * ( 1 - $discount / 100 );
				$value['data']->set_price( $newprice );
			}
		}
    }
    
/**
* Changing Heading text of cross sale product
*/

add_filter( 'gettext', 'exc_custom_related_products_text', 20, 3 );
function exc_custom_related_products_text( $translated_text, $text, $domain ) {
 
	if( $translated_text == 'You may be interested in&hellip;' && is_cart() ) { // for Cross-sells
		$translated_text = 'You have got discount to this Product'; // new title
	}
	return $translated_text;
 
}