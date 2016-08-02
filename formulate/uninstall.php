<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'fcf_admin_email' );
delete_option( 'fcf_recaptcha_secretkey' );
delete_option( 'fcf_recaptcha_sitekey' );