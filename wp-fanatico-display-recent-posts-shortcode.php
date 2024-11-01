<?php
/**
 * Plugin Name: WP Fanatico display recent posts shortcode
 * Plugin URI: https://wordpress.org/plugins/wp-fanatico-display-recent-posts-shortcode/
 * Description: Display or listing recent posts the diferents ways
 * Version: 1.0.6
 * Author: WPFANATICO.COM
 * Author URI: http://wpfanatico.com
 *
 * @package Display Posts
 * @version 1.0.6
 * @author Jose Daboin <contacto@wpfanatico.com>
 * @copyright Copyright (c) 2017, Jose Daboin
 * @link http://wpfanatico.com/wpfanatico-display-recent-posts-shortcode.html
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
 
function load_custom_style_wpfanatico() {
        wp_register_style( 'simple_grid_css', plugins_url() . '/wp-fanatico-display-recent-posts-shortcode/css/simple-grid.css', false, '1.0.0' );
        wp_enqueue_style( 'simple_grid_css' );
}
add_action( 'wp_enqueue_scripts', 'load_custom_style_wpfanatico' );


function wpfanatico_list_post_function( $atts ) {
    $attr = 
	shortcode_atts( 
	array(
        'number' => '3',
        'category' => '',
		'colum' => '3',
		'date' => '0'
		), 
	$atts );
	
	if( is_numeric($attr['number']) ) {	$number = sanitize_text_field( $attr['number'] ); }
	else {	$number = 3; }
	
	$category = sanitize_text_field( $attr['category'] );
	
	if( is_numeric($attr['colum']) ) {	$colum = sanitize_text_field( $attr['colum'] ); }
	else {	$colum = 3;	}

	if( is_numeric($attr['date']) ) {	$date = sanitize_text_field( $attr['date'] ); }
	else {	$date = 0;	}	
	
	
	$args = array(
	'numberposts' => $number,
	'offset' => 0,
	'category' => $category,
	'orderby' => 'post_date',
	'order' => 'DESC',
	'include' => '',
	'exclude' => '',
	'meta_key' => '',
	'meta_value' =>'',
	'post_type' => 'post',
	'post_status' => 'publish',
	'suppress_filters' => true
	);
	global $post;	
	$myposts = get_posts( $args );
	$ct=count($myposts);
	$size = 'post-thumbnail';
	$count=0;
	$postsl="";
	
	?>
	<div class="wpf-container">
	<?php
	$html='';
	foreach ( $myposts as $post ) : setup_postdata( $post ); 
		
		if($colum=='3')
		{
			$url=esc_attr(get_permalink( $post->ID ));
			$title=get_the_title();
			$excerpt=get_the_excerpt();
			if($count==0){$html.='<div class="wpf-row">';} 
			$html.='<div class="wpf-col-4">';
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium', 'post');
			$thumb_f=esc_attr($thumb[0]);
			$html.='<a href="'.$url.'" class="cont-img"><img class="prueba" src="'.$thumb_f.'"> </a>
			<a href="'.$url.'"><h2 class="wpf-post-title">'.$title.'</h2></a>			
			';			
			$html.='<p>'.$excerpt.'</p>';
			if($date==1){	$html.='<div class="date-post">'.get_the_date( get_option('date_format') ).'</div>'; }
			$html.='</div>';
			if($count==2){ 
			$html.='</div>';
			}		
			$count++;	
			if($count==3){ $count=0; }			
		} 
		
		
		if($colum=='2')	
		{
			$url=esc_attr(get_permalink( $post->ID ));
			$title=get_the_title();
			$excerpt=get_the_excerpt();
			if($count==0){	$html.='<div class="wpf-row">';  } 			
			$html.='<div class="wpf-col-6">';
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium', 'post'); $thumb_f=esc_attr($thumb[0]);	
			$html.='<a href="'.$url.'"><img src="'.$thumb_f.'"></a>';
			$html.='<a href="'.$url.'"><h2 class="wpf-post-title">'.$title.'</h2></a>';
			$html.='<p>'.$excerpt.'</p>'; 
			if($date==1){	$html.='<div class="date-post">'.get_the_date( get_option('date_format') ).'</div>'; }
			$html.='</div>';
			if($count==1){ $html.='</div>';  }	
			$count++;			
			if($count==2){$count=0;}				
		}
	endforeach; 
	
	wp_reset_postdata();
	if($ct==1) { $html.='</div>'; }
	$html.='</div><!--/.end-->';
	return $html;
	?>
	
	<?php	
}
add_shortcode( 'wpf-list-post', 'wpfanatico_list_post_function' );