<?php
/*
Plugin Name: Formulate
Description: Simple contact form for WordPress.
Version:     1.0 beta
Author:      Arnon Erba
Author URI:  https://www.arnonerba.com/
*/

defined( 'ABSPATH' ) OR exit;

####
####
## PART ONE: PLUGIN SETTINGS
####
####

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
			<?php settings_fields( 'fcf_general_settings' ); ?>
			<?php do_settings_sections( 'formulate' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
<?php }

add_action( 'admin_init', 'fcf_options_init' );
function fcf_options_init() {
	register_setting( 'fcf_general_settings', 'fcf_admin_email', 'validate_fcf_admin_email' );
	register_setting( 'fcf_general_settings', 'fcf_recaptcha_sitekey', 'validate_fcf_recaptcha_sitekey' );
	register_setting( 'fcf_general_settings', 'fcf_recaptcha_secretkey', 'validate_fcf_recaptcha_secretkey' );
	add_settings_field( 'fcf_admin_email', 'Admin Email', 'fcf_admin_email_callback', 'formulate', 'fcf_general_settings' );
	add_settings_field( 'fcf_recaptcha_sitekey', 'reCAPTCHA Site Key', 'fcf_recaptcha_sitekey_callback', 'formulate', 'fcf_recaptcha_settings' );
	add_settings_field( 'fcf_recaptcha_secretkey', 'reCAPTCHA Secret Key', 'fcf_recaptcha_secretkey_callback', 'formulate', 'fcf_recaptcha_settings' );
	add_settings_section( 'fcf_general_settings', 'General Settings', 'fcf_general_settings_callback', 'formulate' );
	add_settings_section( 'fcf_recaptcha_settings', 'reCAPTCHA Settings', 'fcf_recaptcha_settings_callback', 'formulate' );
}

// generates the settings sections
function fcf_general_settings_callback() {

}
function fcf_recaptcha_settings_callback() {
	echo '<p>Go <a href="https://www.google.com/recaptcha">here</a> to setup your reCAPTCHA.</p>';
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

// add settings action link on plugins page
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'fcf_action_links' );
function fcf_action_links( $links ) {
	$links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=formulate') ) .'">Settings</a>';
	return $links;
}

####
####
## PART TWO: SETUP THE HTML FORM
####
####

// build the form using HTML
function fcf_build() {
	// only build the form if the reCAPTCHA settings have been set
	if ( !empty(get_option( 'fcf_recaptcha_sitekey' )) && !empty(get_option( 'fcf_recaptcha_secretkey' )) ) {

		echo'<script src="https://www.google.com/recaptcha/api.js"></script>
		<form class="card wide" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">
			<div class="card-title">Contact Us</div>
			<div class="card-text">
				<input type="text" class="textfield" id="name" name="contact_name" pattern="[a-zA-Z0-9\. ]+" value="' . ( isset( $_POST["contact_name"] ) ? esc_attr( $_POST["contact_name"] ) : '' ) . '">
				<label class="textfield-label" for="name">Your Name</label>
				<input type="email" class="textfield" id="email" name="contact_email" value="' . ( isset( $_POST["contact_email"] ) ? esc_attr( $_POST["contact_email"] ) : '' ) . '">
				<label class="textfield-label" for="email">Email Address</label>
				<textarea class="textfield textarea" type="text" rows= "5" id="message" name="contact_message">' . ( isset( $_POST["contact_message"] ) ? esc_attr( $_POST["contact_message"] ) : '' ) . '</textarea>
				<label class="textfield-label" for="message">Message</label>
				<div class="g-recaptcha" data-sitekey="' . sanitize_text_field( get_option( 'fcf_recaptcha_sitekey' ) ) . '"></div>
			</div>
			<div class="card-actions">
				<button id="contact_submitted" type="submit" class="button" name="contact_submitted">Send</button>
			</div>
		</form>';
	}
}

####
####
## PART THREE: EMAIL THE SUBMITTED FORM
####
####

// send the form via email
function fcf_send() {
	// send the email if the submit button is clicked
	if ( isset( $_POST['contact_submitted'] ) ) {

		// make sure the user has filled in all the fields including the reCAPTCHA, otherwise, display a warning message
		if ( !empty($_POST["contact_name"]) && !empty($_POST["contact_email"]) && !empty($_POST["contact_message"]) && !empty($_POST['g-recaptcha-response']) ) {

			// secret key for reCAPTCHA
			$secret = sanitize_text_field( get_option( 'fcf_recaptcha_secretkey' ) );
			// verify reCAPTCHA response
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
			// extract whether or not the reCAPTCHA response was successful
			$responseData = json_decode($verifyResponse);

			// get blog title to place in email
			$blog_title = get_bloginfo( 'name' );

			// sanitize form values and setup email message
			$name = sanitize_text_field( $_POST["contact_name"] );
			$email = sanitize_email( $_POST["contact_email"] );
			$subject = "[$blog_title]" . " New Inquiry From $name";
			$message = "\nName: $name\n" . "\nEmail: $email\n" . "\nMessage:\n\n" . implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['contact_message'] ) ) );

			// get the email address configured on the settings page, otherwise, fall back to the admin email address
			if ( get_option( 'fcf_admin_email' ) ) {
				$to = sanitize_email( get_option( 'fcf_admin_email' ) );
			} else {
				$to = sanitize_email( get_option( 'admin_email' ) );
			}

			// finally, attempt to send the email
			if ( $responseData->success ) {
				if ( wp_mail( $to, $subject, $message ) ) {
					echo '<div class="card wide status">';
					echo '<div class="card-text">';
					echo '<p>Thanks for contacting us. We will respond to you as soon as possible.</p>';
					echo '</div>';
					echo '</div>';
				} else {
					echo '<div class="card wide status">';
					echo '<div class="card-text">';
					echo 'Uh oh, something went wrong. Please check your entries and try again.';
					echo '</div>';
					echo '</div>';
				}
			} else {
				echo '<div class="card wide status">';
				echo '<div class="card-text">';
				echo 'reCAPTCHA verification failed';
				echo '</div>';
				echo '</div>';
			}

		} else {
			echo '<div class="card wide status">';
			echo '<div class="card-text">';
			echo 'Please fill out all the fields, including the reCAPTCHA.';
			echo '</div>';
			echo '</div>';
		}
	}
}

####
####
## PART FOUR: CREATE SHORTCODE
####
####

function fcf_shortcode() {
	ob_start();

	fcf_build();
	fcf_send();

	return ob_get_clean();
}

add_shortcode( 'formulate', 'fcf_shortcode' );

####
####
## PART FIVE: UNINSTALL SETTINGS
####
####

register_uninstall_hook( __FILE__, 'fcf_uninstall' );

function fcf_uninstall() {
	delete_option( 'fcf_admin_email' );
	delete_option( 'fcf_recaptcha_secretkey' );
	delete_option( 'fcf_recaptcha_sitekey' );
}

?>