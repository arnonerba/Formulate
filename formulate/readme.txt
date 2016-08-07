=== Formulate ===
Tags: contact form, contact, form, feedback, email, captcha, recaptcha, material design, material, google
Requires at least: 2.7
Tested up to: 4.5.3
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html

Formulate is a simple contact form for WordPress.

== Description ==

Formulate allows you to add a contact form to your site to collect a visitor\'s name, email address, and message. A few key options are included on the easy-to-use admin settings page. Use of a Google reCAPTCHA is integrated and mandatory.

= Less is More =

Formulate takes a minimalistic approach to the contact form. Lengthy contact forms can scare away potential clients, while short forms seem less daunting to fill out.

= Modern Themes =

Formulate includes themes based on the [Material Design Guidelines](https://material.google.com/) for a clean, modern look.

= Development =

Follow development at [GitHub](https://github.com/arnonerba/formulate)

== Installation ==

1. Download the plugin files and upload the "formulate" directory to `/wp-content/plugins/` or wherever your WordPress installation stores plugins. You man also install the plugin through the WordPress plugins screen.
1. Active Formulate on the Plugins page.
1. Go to Settings > Formulate to setup your reCAPTCHA and configure how you want Formulate to look and behave.

== Frequently Asked Questions ==

= What options does Formulate provide? =

* Multiple responsive themes
* Optional phone number field
* Send contact form submissions to a custom email addres
* Ability to disable all themes and write your own CSS

= Why am I not getting emails? =

Formulate does not handle mail delivery. Instead, it uses the WordPress wp_mail function. If you see an "Uh oh, something went wrong" error, it is almost definitely a problem with your mail server or with your web host. You can check to see if WordPress is capable of sending email at all by requesting a password reset email from the login page. If your webhost does not natively support sending email, you may need to install an SMTP plugin to send email through another server.

= How do I uninstall Formulate? =

Simple deactivate Formulate and click on the "delete" button. Formulate will clean up after itself and WordPress will take care of the rest.