<?php

# BUILD THE HTML FORM

function fcf_build() {
	global $configured;
	if ( $configured ) {
		echo '<script src="https://www.google.com/recaptcha/api.js"></script>
		<form class="fcf-card" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">
			<div class="fcf-card-title">';
			if ( get_option( 'fcf_form_title' ) ) {
				$title = sanitize_text_field( get_option( 'fcf_form_title' ) );
			} else {
				$title = 'Contact Us';
			}
			echo $title . '</div>
			<div class="fcf-card-text">
				<input type="text" id="fcf-contact-name" class="fcf-textfield" name="fcf_contact_name" pattern="[a-zA-Z0-9\. ]+" value="' . ( isset( $_POST["fcf_contact_name"] ) ? esc_attr( $_POST["fcf_contact_name"] ) : '' ) . '">
				<label class="fcf-textfield-label" for="fcf-contact-name">Your Name</label>';
				global $displayTel;
				if ( $displayTel == 1 ) {
					echo '<input type="tel" id="fcf-phone-number" class="fcf-textfield" name="fcf_phone_number" pattern="[0-9\(\)\-\+\* ]+" value="' . ( isset( $_POST["fcf_phone_number"] ) ? esc_attr( $_POST["fcf_phone_number"] ) : '' ) . '">
					<label class="fcf-textfield-label" for="fcf-phone-number">Phone Number (optional)</label>';
				}
				echo '<input type="email" id="fcf-contact-email" class="fcf-textfield" name="fcf_contact_email" value="' . ( isset( $_POST["fcf_contact_email"] ) ? esc_attr( $_POST["fcf_contact_email"] ) : '' ) . '">
				<label class="fcf-textfield-label" for="fcf-contact-email">Email Address</label>
				<textarea id="fcf-contact-message" class="fcf-textfield textarea" type="text" rows= "5" name="fcf_contact_message">' . ( isset( $_POST["fcf_contact_message"] ) ? esc_attr( $_POST["fcf_contact_message"] ) : '' ) . '</textarea>
				<label class="fcf-textfield-label" for="fcf-contact-message">Message</label>
				
			</div>
			<div id="fcf-recaptcha">
				<div class="g-recaptcha"';
				global $theme;
				if ( $theme['stylesheet'] == 1 ) {
					echo ' data-theme="light"';
				} elseif ( $theme['stylesheet'] == 2 ) {
					echo ' data-theme="dark"';
				}
				echo ' data-sitekey="' . sanitize_text_field( get_option( 'fcf_recaptcha_sitekey' ) ) . '"></div>
			</div>
			<div class="fcf-card-actions">
				<button id="fcf_submitted" type="submit" class="fcf-button" name="fcf_submitted">Send</button>
			</div>
		</form>';
	}
}