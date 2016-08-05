<?php

# BUILD THE HTML FORM

function fcf_build() {
	// only build the form if the reCAPTCHA settings have been set
	if ( !empty(get_option( 'fcf_recaptcha_sitekey' )) && !empty(get_option( 'fcf_recaptcha_secretkey' )) ) {

		global $theme;

		echo '<script src="https://www.google.com/recaptcha/api.js"></script>
		<form class="fcf-card" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">
			<div class="fcf-card-title">Contact Us</div>
			<div class="fcf-card-text">
				<input type="text" class="fcf-textfield" id="name" name="contact_name" pattern="[a-zA-Z0-9\. ]+" value="' . ( isset( $_POST["contact_name"] ) ? esc_attr( $_POST["contact_name"] ) : '' ) . '">
				<label class="fcf-textfield-label" for="name">Your Name</label>
				<input type="email" class="fcf-textfield" id="email" name="contact_email" value="' . ( isset( $_POST["contact_email"] ) ? esc_attr( $_POST["contact_email"] ) : '' ) . '">
				<label class="fcf-textfield-label" for="email">Email Address</label>
				<textarea class="fcf-textfield textarea" type="text" rows= "5" id="message" name="contact_message">' . ( isset( $_POST["contact_message"] ) ? esc_attr( $_POST["contact_message"] ) : '' ) . '</textarea>
				<label class="fcf-textfield-label" for="message">Message</label>
				
			</div>
			<div class="g-recaptcha"';

			if ( $theme['stylesheet'] == 1 ) {
				echo 'data-theme="light"';
			} elseif ( $theme['stylesheet'] == 2 ) {
				echo 'data-theme="dark"';
			}

			echo 'data-sitekey="' . sanitize_text_field( get_option( 'fcf_recaptcha_sitekey' ) ) . '"></div>
			<div class="fcf-card-actions">
				<button id="contact_submitted" type="submit" class="fcf-button" name="contact_submitted">Send</button>
			</div>
		</form>';

	}
}