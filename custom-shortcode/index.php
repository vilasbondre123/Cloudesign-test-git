<?php
/*
Plugin Name: Custom shortcode code
Description: This Plugin use for create custom shortcode use and using this shortcode display category post. And also create coupon code for perticular product
Version: 1.0
Author: Vilas bondre
*/
//For display category post using shortcode [myshortcode categories=”xyz” limit=”10” orderby=”DESC”]

function testShortcode( $atts ) { //print_r($atts);
	// for get value from shortcode
	$a = shortcode_atts( array(
		'categories' => 'default',
		'order' => 'ASC',
		'limit' => '5'
	), $atts );
	$cat = $a['categories'];
	$order = $a['order'];
	$limit = $a['limit'];
	$arg =array('category'=>$cat,'posts_per_page' =>$limit,'order'=>$order); // arguments for loop
	$catquery = new WP_Query( $arg ); ?>	
	<ul> 
		<?php while($catquery->have_posts()) : $catquery->the_post(); //start the loop ?> 
		<li><b><?php the_title(); ?></b>
		<li><?php the_content(); ?></li>	 
		<?php endwhile; ?>
	</ul>
	<?php wp_reset_postdata(); 
}
add_shortcode('myshortcode', 'testShortcode');
//To Apply discount coupon for perticular product. here is my poroduct id is "133" and coupon name is "FSCDMBUZ".
add_action( 'woocommerce_before_cart', 'my_speacial_coupons' );
function my_speacial_coupons() {
    global $woocommerce;
    $cw_coupon = 'FSCDMBUZ'; // This is a generated coupon code in admin pannel 
    if ( $woocommerce->cart->has_discount( $cw_coupon ) ){ //check the discount 
		return;
	}
    foreach ( $woocommerce->cart->cart_contents as $key => $values ) {
        $autocoupon = array( 133 ); // this is my product_id which want to use for this coupon code
        if( in_array( $values['product_id'], $autocoupon ) ) {
            $woocommerce->cart->add_discount( $cw_coupon );
            wc_print_notices();
        }
    }
}

?>