<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'fcf_admin_email' );
delete_option( 'fcf_form_title' );
delete_option( 'fcf_display_tel' );
delete_option( 'fcf_recaptcha_secretkey' );
delete_option( 'fcf_recaptcha_sitekey' );
delete_option( 'fcf_default_stylesheet' );
