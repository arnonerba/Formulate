<?php

/*
Plugin Name: Formulate
Description: Simple contact form for WordPress.
Version:     1.2 beta
Author:      Arnon Erba
Author URI:  https://www.arnonerba.com/
*/

defined( 'ABSPATH' ) OR exit;

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'settings.php';
}

require_once plugin_dir_path( __FILE__ ) . 'form.php';

require_once plugin_dir_path( __FILE__ ) . 'send.php';

function fcf_styles() {
	if (!is_admin()) {
		$stylesheet_setting = get_option('fcf_default_stylesheet');
		if ( $stylesheet_setting['stylesheet'] == 1 ) {
			wp_enqueue_style( 'cfc-light', plugin_dir_url( __FILE__ ) . 'css/light.css', '', '0.1' );
		} elseif ( $stylesheet_setting['stylesheet'] == 2 ) {
			wp_enqueue_style( 'cfc-dark', plugin_dir_url( __FILE__ ) . 'css/dark.css', '', '0.1' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'fcf_styles' );

# SHORTCODE

function fcf_shortcode() {
	ob_start();

	fcf_build();
	fcf_send();

	return ob_get_clean();
}

add_shortcode( 'formulate', 'fcf_shortcode' );

# ADD SETTINGS ACTION LINK ON PLUGINS PAGE

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'fcf_action_links' );
function fcf_action_links( $links ) {
	$links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=formulate') ) .'">Settings</a>';
	return $links;
}