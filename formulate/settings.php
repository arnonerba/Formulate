<?php

# PLUGIN SETTINGS ADMIN PAGE

add_action( 'admin_menu', 'fcf_options_page' );
function fcf_options_page() {
	add_options_page( 'Formulate Settings', 'Formulate', 'administrator', 'formulate', 'build_fcf_options_page' );
}
function build_fcf_options_page() { ?>
	<div class="wrap">
		<h1>Formulate Settings</h1>
		<div class="notice notice-info">
			<p>Please note that this plugin is still under active development.</p>
		</div>
		<?php if ( empty(get_option( 'fcf_recaptcha_sitekey' )) && empty(get_option( 'fcf_recaptcha_secretkey' )) ) { ?>
		<div class="notice notice-warning">
			<p>You must enter a valid reCAPTCHA Site Key and Secret Key before the contact form will display on your site.</p>
		</div>
		<?php } ?>
		<form action="options.php" method="post">
			<?php settings_fields( 'fcf_settings' ); ?>
			<?php do_settings_sections( 'formulate' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
<?php }

add_action( 'admin_init', 'fcf_settings_init' );
function fcf_settings_init() {
	register_setting( 'fcf_settings', 'fcf_admin_email', 'validate_fcf_admin_email' );
	register_setting( 'fcf_settings', 'fcf_recaptcha_sitekey', 'validate_fcf_recaptcha_sitekey' );
	register_setting( 'fcf_settings', 'fcf_recaptcha_secretkey', 'validate_fcf_recaptcha_secretkey' );
	register_setting( 'fcf_settings', 'fcf_default_stylesheet' );

	add_settings_field( 'fcf_admin_email', 'Admin Email', 'fcf_admin_email_callback', 'formulate', 'fcf_general_settings' );
	add_settings_field( 'fcf_recaptcha_sitekey', 'reCAPTCHA Site Key', 'fcf_recaptcha_sitekey_callback', 'formulate', 'fcf_recaptcha_settings' );
	add_settings_field( 'fcf_recaptcha_secretkey', 'reCAPTCHA Secret Key', 'fcf_recaptcha_secretkey_callback', 'formulate', 'fcf_recaptcha_settings' );
	add_settings_field( 'fcf_stylesheet_settings', 'Theme', 'fcf_stylesheet_settings_callback', 'formulate', 'fcf_advanced_settings' );

	add_settings_section( 'fcf_general_settings', 'General Settings', 'fcf_general_settings_callback', 'formulate' );
	add_settings_section( 'fcf_recaptcha_settings', 'reCAPTCHA Settings', 'fcf_recaptcha_settings_callback', 'formulate' );
	add_settings_section( 'fcf_advanced_settings', 'Advanced Settings', 'fcf_advanced_settings_callback', 'formulate' );
}

// generates the settings sections
function fcf_general_settings_callback() {

}
function fcf_recaptcha_settings_callback() {
	echo '<p>Go <a href="https://www.google.com/recaptcha">here</a> to setup your reCAPTCHA.</p>';
}
function fcf_advanced_settings_callback() {

}

// generates the settings fields
function fcf_admin_email_callback() {
	if ( current_user_can( 'administrator' ) ) {
		// calls the custom setting in the correct place
		$fcf_admin_email = sanitize_email( get_option( 'fcf_admin_email' ) );
		echo '<input type="email" name="fcf_admin_email" value="' . $fcf_admin_email . '" />';
		echo '<p class="description">If left empty, the WordPress admin email address will be used.</p>';
	} else {
		echo '<p>You don\'t have sufficient privileges to edit this setting</p>';
	}
}
function fcf_recaptcha_sitekey_callback() {
	if ( current_user_can( 'administrator' ) ) {
		// calls the custom setting in the correct place
		$fcf_recaptcha_sitekey = sanitize_text_field( get_option( 'fcf_recaptcha_sitekey') );
		echo '<input type="text" name="fcf_recaptcha_sitekey" value="' . $fcf_recaptcha_sitekey . '" />';
	} else {
		echo '<p>You don\'t have sufficient privileges to edit this setting</p>';
	}
}
function fcf_recaptcha_secretkey_callback() {
	if ( current_user_can( 'administrator' ) ) {
		// calls the custom setting in the correct place
		$fcf_recaptcha_secretkey = sanitize_text_field( get_option( 'fcf_recaptcha_secretkey') );
		echo '<input type="text" name="fcf_recaptcha_secretkey" value="' . $fcf_recaptcha_secretkey . '" />';
	} else {
		echo '<p>You don\'t have sufficient privileges to edit this setting</p>';
	}
}
function fcf_stylesheet_settings_callback() {
	$options = get_option( 'fcf_default_stylesheet' );

	echo '<input checked type="radio" id="stylesheet_option_one" name="fcf_default_stylesheet[stylesheet]" value="1"' . checked( 1, $options['stylesheet'], false ) . '/>
	<label for="stylesheet_option_one">Light Theme</label>
	<br />
	<input type="radio" id="stylesheet_option_two" name="fcf_default_stylesheet[stylesheet]" value="2"' . checked( 2, $options['stylesheet'], false ) . '/>
	<label for="stylesheet_option_two">Dark Theme</label>';
}

// validates the settings options
function validate_fcf_admin_email( $input ) {
	$output = sanitize_email( get_option( 'fcf_admin_email' ) );
	if ( !empty( $input ) ) {
		if ( filter_var($input, FILTER_VALIDATE_EMAIL) ) {
			$output = $input;
		} else {
			add_settings_error( 'fcf_admin_email', 'invalid-email', 'Please enter a valid email address.' );
		}
		return $output;
	}
}
function validate_fcf_recaptcha_sitekey( $input ) {
	$output = sanitize_text_field( get_option( 'fcf_recaptcha_sitekey' ) );
	if ( !empty( $input ) ) {
		if ( preg_match("/^[0-9a-zA-Z\-\_]*$/", $input) ) {
			$output = $input;
		} else {
			add_settings_error( 'fcf_recaptcha_sitekey', 'invalid-sitekey', 'Please enter a valid reCAPTCHA Site Key.' );
		}
		return $output;
	}
}
function validate_fcf_recaptcha_secretkey( $input ) {
	$output = sanitize_text_field( get_option( 'fcf_recaptcha_secretkey' ) );
	if ( !empty( $input ) ) {
		if ( preg_match("/^[0-9a-zA-Z\-\_]*$/", $input) ) {
			$output = $input;
		} else {
			add_settings_error( 'fcf_recaptcha_secretkey', 'invalid-secretkey', 'Please enter a valid reCAPTCHA Secret Key.' );
		}
		return $output;
	}
}