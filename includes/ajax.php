<?php
/**
 * Ajax
 *
 * @package     EDD\FESRegistrationButton\Ajax
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_edd_fes_registration_button', 'edd_fes_registration_button_ajax' );
function edd_fes_registration_button_ajax() {
	$form_id   = !empty( $values ) && isset( $values['form_id'] )   ? absint( $values['form_id'] )   : ( isset( $_REQUEST['form_id'] )   ? absint( $_REQUEST['form_id'] )   : EDD_FES()->helper->get_option( 'fes-login-form', false ) );
	$user_id   = !empty( $values ) && isset( $values['user_id'] )   ? absint( $values['user_id'] )   : ( isset( $_REQUEST['user_id'] )   ? absint( $_REQUEST['user_id'] )   : get_current_user_id() );
	$vendor_id = !empty( $values ) && isset( $values['vendor_id'] ) ? absint( $values['vendor_id'] ) : ( isset( $_REQUEST['vendor_id'] ) ? absint( $_REQUEST['vendor_id'] ) : -2 );

	$user_data = get_userdata( $user_id );
	$values['first_name'] = empty($values['first_name']) ? $user_data->user_login : $values['first_name'];
	$values['last_name'] = empty($values['last_name']) ? $user_data->user_login : $values['last_name'];
	$values['display_name'] = empty($values['display_name']) ? $user_data->user_login : $values['display_name'];

	$values    = !empty( $values ) ? $values : $_POST;
	// Make the FES Form
	$form      = new FES_Registration_Form( $form_id, 'id', $vendor_id );

	// Save the FES Form
	$form->save_form_frontend( $values, $user_id );
}