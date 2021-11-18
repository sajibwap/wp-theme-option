<?php 

/**
* Plugin Name: Theme Option
* Plugin URI: http://fb.com/msh.sajib
* Description: Theme opiton settings
* Version: 1.0.0
* Author: Sajib
* Author URI: http://fb.com/msh.sajib
* License: GPL2
*/


function exc_scripts($screen){
	if ($screen == 'options-general.php') {
		wp_enqueue_style( 'toggle', plugin_dir_url( __FILE__ )."/assets/css/minitoggle.css" );
		wp_enqueue_script( 'toggle-js', plugin_dir_url( __FILE__ )."/assets/js/minitoggle.js", array( 'jquery' ), time(), true );
		wp_enqueue_script( 'main-js', plugin_dir_url( __FILE__ )."/assets/js/main.js", array( 'jquery' ), time(), true );
	}

}
add_action( 'admin_enqueue_scripts', 'exc_scripts' );



function theme_options(){
	add_settings_section( 'exc_section', 'Custom Setting section', 'setting_Cb', 'general' );

	add_settings_field( 'exc_name', 'Name', 'exc_settings_field_cb','general','exc_section',array('exc_name'));
	add_settings_field( 'exc_phone', 'Phone', 'exc_settings_field_cb','general','exc_section',array('exc_phone'));
	add_settings_field( 'exc_country', 'Country', 'exc_settings_countries_cb','general','exc_section');
	add_settings_field( 'exc_skill', 'Skills', 'exc_settings_skill_cb','general','exc_section');
	add_settings_field( 'exc_gender', 'Gender', 'exc_settings_gender_cb','general','exc_section');
	add_settings_field( 'exc_product', 'Product', 'exc_settings_product_cb','general','exc_section');
	add_settings_field( 'exc_toggle', 'Toggle', 'exc_settings_toggle_cb','general','exc_section');
	// add_settings_field( $id, $title, $callback, $page, 'default', array( '' ) );
	
	register_setting( 'general', 'exc_name', array( 'sanitize_callback' => 'esc_attr' ));
	register_setting( 'general', 'exc_phone', array( 'sanitize_callback' => 'esc_attr' ));
	register_setting( 'general', 'exc_country', array( 'sanitize_callback' => 'esc_attr' ));
	register_setting( 'general', 'exc_skill');
	register_setting( 'general', 'exc_gender', array( 'sanitize_callback' => 'esc_attr' ));
	register_setting( 'general', 'exc_product', array( 'sanitize_callback' => 'esc_attr' ));
	register_setting( 'general', 'exc_toggle', array( 'sanitize_callback' => 'esc_attr' ));
	// register_setting( $option_group, $option_name, array( '' ) );

}
add_action('admin_init','theme_options');
/*
** TOGGLE JS
**/
function exc_settings_toggle_cb(){
	echo '<div class="toggle"></div>';
	//printf("<input type='checkbox' id='exc_toggle' name='exc_toggle' value='0'/>");
}

/*
** Product : Select Options
**/
function exc_settings_product_cb(){
	$option = get_option( 'exc_product' );
	$args 	= array(
		'post_type'      => 'product',
		'posts_per_page' => 10
	);
	$products = new WP_Query( $args );

	printf("<select name='exc_product' id='exc_product'>");
	while ( $products->have_posts() ) : $products->the_post();
		$selected = '';
		if ( $option == get_the_ID() ) $selected = 'selected';
		global $product;
		printf("<option name='%s' value='%s' %s>%s</option>",$product->get_name(),$product->get_id(),$selected,get_the_title());
	endwhile;
	printf("</select>");
	wp_reset_query();
}


/**
*** Gender : Radio Checkbox
***/
function exc_settings_gender_cb(){
	$option	= get_option( 'exc_gender' );
	$genders= array('Male','Female');


	foreach ($genders as $gender) {
		$checked = '';
		if ( strtolower($option) == strtolower($gender)) {
			$checked = 'checked=\'checked\'';
		}
		printf("<input type='radio' name='exc_gender' value='%s' %s/> %s</br>",$gender,$checked,$gender);
	}
}


/*
** Skills : Multiple checkbox
**/
function exc_settings_skill_cb(){
	$option	= get_option( 'exc_skill' );
	$skills = array('Web Development','Graphics Desgin','Digital Marketing','Software Development');
	$skills = apply_filters( 'exc_skills_update', $skills );


	foreach ($skills as $skill) {
		$checked = '';
		if ( is_array($option) && in_array($skill, $option)) {
			$checked = 'checked=\'checked\'';
		}
		printf("<input type='checkbox' name='exc_skill[]' value='%s' %s/> %s</br>",$skill,$checked,$skill);
	}
}

/*
** Country : Select Option
**/
function exc_settings_countries_cb(){
	$option 	= get_option( 'exc_country' );
	$countries 	= array('US','UK','Bangladesh','Pakistan');

	printf("<select name='%s' id='%s'>",'exc_country','exc_country');
	foreach ($countries as $country) {
		$selected = '';
		if ( strtolower($option) == strtolower($country) ) $selected = 'selected';
		printf("<option value='%s' %s>%s</option>",$country,$selected,$country);
	}
	printf("</select>");
}


/*
** Name : Input Field
**/
function exc_settings_field_cb($arg){
	$name = get_option($arg[0]);
	printf("<input type='text' id='%s' name='%s' value='%s'/>",$arg[0],$arg[0],$name);
}
function setting_Cb(){
	echo "<p>This is description for the section</p>";
}


include 'discount.php';


add_filter( 'exc_skills_update', 'exc_skills_update_f' );
function exc_skills_update_f($skills){
	array_push($skills, 'Content Writting');
	return $skills;
}