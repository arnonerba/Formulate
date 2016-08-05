<?php

# BUILD THE HTML FORM

function fcf_build() {
	global $configured;
	if ( $configured ) {
		echo '<script src="https://www.google.com/recaptcha/api.js"></script>
		<form class="fcf-card" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">
			<div class="fcf-card-title">Contact Us</div>
			<div class="fcf-card-text">
				<input type="text" id="fcf-contact-name" class="fcf-textfield" id="name" name="contact_name" pattern="[a-zA-Z0-9\. ]+" value="' . ( isset( $_POST["contact_name"] ) ? esc_attr( $_POST["contact_name"] ) : '' ) . '">
				<label class="fcf-textfield-label" for="name">Your Name</label>
				<input type="email" id="fcf-contact-email" class="fcf-textfield" id="email" name="contact_email" value="' . ( isset( $_POST["contact_email"] ) ? esc_attr( $_POST["contact_email"] ) : '' ) . '">
				<label class="fcf-textfield-label" for="email">Email Address</label>
				<textarea id="fcf-contact-message" class="fcf-textfield textarea" type="text" rows= "5" id="message" name="contact_message">' . ( isset( $_POST["contact_message"] ) ? esc_attr( $_POST["contact_message"] ) : '' ) . '</textarea>
				<label class="fcf-textfield-label" for="message">Message</label>
				
			</div>
			<div class="g-recaptcha"';
			global $theme;
			if ( $theme['stylesheet'] == 1 ) {
				echo 'data-theme="light"';
			} elseif ( $theme['stylesheet'] == 2 ) {
				echo 'data-theme="dark"';
			}
			echo 'data-sitekey="' . sanitize_text_field( get_option( 'fcf_recaptcha_sitekey' ) ) . '"></div>
			<div class="fcf-card-actions">
				<button id="fcf_submitted" type="submit" class="fcf-button" name="fcf_submitted">Send</button>
			</div>
		</form>';
	}
}