<?php
/**
 * Scripts
 *
 * @package     EDD\FESRegistrationButton\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Load frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function edd_fes_registration_button_scripts( $hook ) {
    // Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_register_script( 'edd_fes_registration_button', EDD_FES_REGISTRATION_BUTTON_URL . '/assets/js/edd-fes-registration-button' . $suffix . '.js', array( 'jquery' ) );
    wp_localize_script( 'edd_fes_registration_button', 'registration_button', array( 'ajax' => array( 'url' => admin_url( 'admin-ajax.php' ) ) ) );
}
add_action( 'wp_enqueue_scripts', 'edd_fes_registration_button_scripts' );
