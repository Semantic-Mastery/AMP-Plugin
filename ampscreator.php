<?php
/**
 * Plugin Name: AMPs Creator
 * Description: Add AMPs Creator support to your WordPress site.
 * Author: 
 * Version: Beta v1.2
 * License: 
 */
 
  
define( 'AMPsCreator_QUERY_VAR', 'amps' );
if ( ! defined( 'AMPsCreator_DEV_MODE' ) ) {
	define( 'AMPsCreator_DEV_MODE', defined( 'WP_DEBUG' ) && WP_DEBUG );
}

require_once( dirname( __FILE__ ) . '/class-ampscreator-post.php' );

register_activation_hook( __FILE__, 'amps_activate' );
function amps_activate(){
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'amps_deactivate' );
function amps_deactivate(){
	flush_rewrite_rules();
}

add_action( 'init', 'amps_init' );
function amps_init() {
	add_rewrite_endpoint( AMPsCreator_QUERY_VAR, EP_PERMALINK | EP_PAGES );
}

add_action( 'wp', 'amps_add_actions' );
remove_action( 'wp_head', 'rel_canonical' ); //remove wp canonical
add_action( 'wp_head', 'my_rel_canonical');  // add my custon canonical url


function amps_add_actions() {
	if ( ! is_singular() ) {
		return;
	}

	if ( false !== get_query_var( AMPsCreator_QUERY_VAR, false ) ) {
		// TODO: check if post_type supports amps
		add_action( 'template_redirect', 'amps_template_redirect' );
		
		
	} else {
		add_action( 'wp_head', 'amps_canonical' );
		
	}
}
// my canonical remplace canoncial wp site for custom canoncial
function my_rel_canonical() {
	
	
   
$link = get_permalink( $id );
  if ( $page = get_query_var('cpage') )
    $link = get_comments_pagenum_link( $page );

$my_canonical = str_replace ( "/amp" , "" , $link );

  echo "<link rel='canonical' href='$my_canonical' />\n";
	
}






function amps_template_redirect() {
	amps_render( get_queried_object_id() );
	exit;
}


 
  
function amps_get_url( $post_id ) {
	if ( '' != get_option( 'permalink_structure' ) ) {
		$amps_url = trailingslashit( get_permalink( $post_id ) ) . user_trailingslashit( AMPsCreator_QUERY_VAR, 'single_amps' );
	} else {
		$amps_url = add_query_arg( AMPsCreator_QUERY_VAR, absint( $post_id ), home_url() );
	}

	return apply_filters( 'amps_get_url', $amps_url, $post_id );
}

function amps_canonical() {
	if ( false === apply_filters( 'show_amps_canonical', true ) ) {
		return;
	}

	$amps_url = amps_get_url( get_queried_object_id() );
	printf( '<br><link rel="amphtml" href="%s" />', esc_url( $amps_url ) );
}

function amps_render( $post_id ) {
	do_action( 'pre_amps_render', $post_id );
	$amps_post = new AMPsCreator_Post( $post_id );
	include( dirname( __FILE__ ) . '/template.php' );
}

