<?php

# EMAIL THE SUBMITTED FORM

function fcf_send() {
	// send the email if the submit button is clicked
	if ( isset( $_POST['fcf_submitted'] ) ) {

		// make sure the user has filled in all the fields including the reCAPTCHA, otherwise, display a warning message
		if ( !empty($_POST["fcf_contact_name"]) && !empty($_POST["fcf_contact_email"]) && !empty($_POST["fcf_contact_message"]) && !empty($_POST['g-recaptcha-response']) ) {

			// secret key for reCAPTCHA
			$secret = sanitize_text_field( get_option( 'fcf_recaptcha_secretkey' ) );
			// verify reCAPTCHA response
			$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
			// extract whether or not the reCAPTCHA response was successful
			$responseData = json_decode($verifyResponse);

			// get blog title to place in email
			$blog_title = get_bloginfo( 'name' );

			// sanitize form values and setup email message
			$name = sanitize_text_field( $_POST["fcf_contact_name"] );
			$email = sanitize_email( $_POST["fcf_contact_email"] );
			$subject = "[$blog_title]" . " New Inquiry From $name";
			$message = "\nName: $name\n" . "\nEmail: $email\n" . "\nMessage:\n\n" . implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['fcf_contact_message'] ) ) );

			// get the email address configured on the settings page, otherwise, fall back to the admin email address
			if ( get_option( 'fcf_admin_email' ) ) {
				$to = sanitize_email( get_option( 'fcf_admin_email' ) );
			} else {
				$to = sanitize_email( get_option( 'admin_email' ) );
			}

			// finally, attempt to send the email
			if ( $responseData->success ) {
				if ( wp_mail( $to, $subject, $message ) ) {
					echo '<div class="fcf-card">';
					echo '<div class="fcf-card-text">';
					echo '<p>Thanks for contacting us. We will respond to you as soon as possible.</p>';
					echo '</div>';
					echo '</div>';
				} else {
					echo '<div class="fcf-card">';
					echo '<div class="fcf-card-text">';
					echo 'Uh oh, something went wrong. Please check your entries and try again.';
					echo '</div>';
					echo '</div>';
				}
			} else {
				echo '<div class="fcf-card">';
				echo '<div class="fcf-card-text">';
				echo 'reCAPTCHA verification failed';
				echo '</div>';
				echo '</div>';
			}

		} else {
			echo '<div class="fcf-card">';
			echo '<div class="fcf-card-text">';
			echo 'Please fill out all the fields, including the reCAPTCHA.';
			echo '</div>';
			echo '</div>';
		}
	}
}