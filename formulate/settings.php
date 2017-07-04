<?php

# PLUGIN SETTINGS ADMIN PAGE

add_action( 'admin_menu', 'fcf_options_page' );
function fcf_options_page() {
	add_options_page( 'Formulate Settings', 'Formulate', 'administrator', 'formulate', 'build_fcf_options_page' );
}
function build_fcf_options_page() { ?>
	<div class="wrap">
		<h1>Formulate Settings</h1>
		<?php // The PHP < 5.5 empty() function workaround is also used here.
			global $fcf_recaptcha_sitekey_set;
			global $fcf_recaptcha_secretkey_set;
		?>
		<?php if ( empty( $fcf_recaptcha_sitekey_set ) && empty( $fcf_recaptcha_secretkey_set ) ) { ?>
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
	register_setting( 'fcf_settings', 'fcf_form_title', 'validate_fcf_form_title' );
	register_setting( 'fcf_settings', 'fcf_display_tel' );
	register_setting( 'fcf_settings', 'fcf_recaptcha_sitekey', 'validate_fcf_recaptcha_sitekey' );
	register_setting( 'fcf_settings', 'fcf_recaptcha_secretkey', 'validate_fcf_recaptcha_secretkey' );
	register_setting( 'fcf_settings', 'fcf_default_stylesheet' );

	add_settings_field( 'fcf_admin_email', 'Admin Email', 'fcf_admin_email_callback', 'formulate', 'fcf_general_settings' );
	add_settings_field( 'fcf_form_title', 'Contact Form Title', 'fcf_form_title_callback', 'formulate', 'fcf_general_settings' );
	add_settings_field( 'fcf_display_tel', 'Phone Number Field', 'fcf_display_tel_callback', 'formulate', 'fcf_general_settings' );
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
function fcf_form_title_callback() {
	if ( current_user_can( 'administrator' ) ) {
		// calls the custom setting in the correct place
		$fcf_form_title = sanitize_text_field( get_option( 'fcf_form_title' ) );
		echo '<input type="text" name="fcf_form_title" value="' . $fcf_form_title . '" />';
		echo '<p class="description">If left empty, the form will be titled "Contact Us".</p>';
	} else {
		echo '<p>You don\'t have sufficient privileges to edit this setting</p>';
	}
}
function fcf_display_tel_callback() {
	if ( current_user_can( 'administrator' ) ) {

		$options = get_option( 'fcf_display_tel' );

		echo '<input type="checkbox" id="display_tel_checkbox" name="fcf_display_tel" value="1"' . checked( 1, $options, false ) . '/>
		<label for="display_tel_checkbox">Include in form</label>';
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
	if ( current_user_can( 'administrator' ) ) {

		$options = get_option( 'fcf_default_stylesheet' );

		echo '<input checked type="radio" id="stylesheet_option_one" name="fcf_default_stylesheet" value="1"' . checked( 1, $options, false ) . '/>
		<label for="stylesheet_option_one">Material Light</label>
		<p class="description">Light theme based on the Material Design Guidelines.</p>
		<br />
		<input type="radio" id="stylesheet_option_two" name="fcf_default_stylesheet" value="2"' . checked( 2, $options, false ) . '/>
		<label for="stylesheet_option_two">Material Dark</label>
		<p class="description">Dark theme based on the Material Design Guidelines.</p>
		<br />
		<input type="radio" id="stylesheet_option_three" name="fcf_default_stylesheet" value="3"' . checked( 3, $options, false ) . '/>
		<label for="stylesheet_option_three">Windows 95</label>
		<p class="description">Authentic Windows 95 theme built with pure CSS.</p>
		<br />
		<input type="radio" id="stylesheet_option_four" name="fcf_default_stylesheet" value="4"' . checked( 4, $options, false ) . '/>
		<label for="stylesheet_option_four">iOS</label>
		<p class="description">A simple light theme based on iOS interface guidelines.</p>
		<br />
		<input type="radio" id="stylesheet_option_five" name="fcf_default_stylesheet" value="5"' . checked( 5, $options, false ) . '/>
		<label for="stylesheet_option_five">No Theme</label>
		<p class="description">Advanced option; no CSS will be loaded.</p>';
	} else {
		echo '<p>You don\'t have sufficient privileges to edit this setting</p>';
	}
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
function validate_fcf_form_title( $input ) {
	$output = sanitize_text_field( get_option( 'fcf_form_title' ) );
	if ( !empty( $input ) ) {
		if ( preg_match("/^[0-9a-zA-Z\'\"\- ]*$/", $input) ) {
			$output = $input;
		} else {
			add_settings_error( 'fcf_form_title', 'invalid-title', 'Please only use 0-9, a-z, and A-Z in the title.' );
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