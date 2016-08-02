<?php

# BUILD THE HTML FORM

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