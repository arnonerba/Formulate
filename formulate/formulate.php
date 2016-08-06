<?php

/*
Plugin Name: Formulate
Description: Simple contact form for WordPress.
Version:     2.0
Author:      Arnon Erba
Author URI:  https://www.arnonerba.com/
*/

defined( 'ABSPATH' ) OR exit;

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'settings.php';
}

$theme = get_option('fcf_default_stylesheet');
$configured = False;
if ( !empty(get_option( 'fcf_recaptcha_sitekey' )) && !empty(get_option( 'fcf_recaptcha_secretkey' )) ) {
	global $configured;
	$configured = True;
}

// load stylesheets
if ( $configured ) {
	function fcf_styles() {
		if (!is_admin()) {
			global $theme;
			if ( $theme['stylesheet'] == 1 ) {
				wp_enqueue_style( 'fcf-roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400', '', null );
				wp_enqueue_style( 'fcf-material-light', plugin_dir_url( __FILE__ ) . 'css/light.css', '', '1.5' );
			} elseif ( $theme['stylesheet'] == 2 ) {
				wp_enqueue_style( 'fcf-roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400', '', null );
				wp_enqueue_style( 'fcf-material-dark', plugin_dir_url( __FILE__ ) . 'css/dark.css', '', '1.5' );
			}
		}
	}
	add_action( 'wp_enqueue_scripts', 'fcf_styles' );
}

require_once plugin_dir_path( __FILE__ ) . 'form.php';
require_once plugin_dir_path( __FILE__ ) . 'send.php';

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