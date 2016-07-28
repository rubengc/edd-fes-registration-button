<?php
/**
 * Shortcodes
 *
 * @package     EDD\FESRegistrationButton\Shortcodes
 * @since       1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

add_shortcode( 'fes_registration_button' , 'edd_fes_registration_button_shortcode' );
function edd_fes_registration_button_shortcode( $atts ) {
	edd_fes_registration_button()->locate_template('frontend-registration-button', true);
}