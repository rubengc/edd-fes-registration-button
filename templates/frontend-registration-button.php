<?php

$current_user = wp_get_current_user();

if ( 0 == $current_user->ID ) {
	echo '<p class="edd-fes-registration-button-message">' . __( 'You must log in', 'edd-fes-registration-button' ) . '</p>';
} else if( EDD_FES()->vendors->user_is_vendor( $current_user->ID ) ) {
    echo '<p class="edd-fes-registration-button-message">' . __( 'You are already a vendor', 'edd-fes-registration-button' ) . '</p>';
} else {
	$color = edd_get_option( 'checkout_color', 'blue' );
	$color = ( $color == 'inherit' ) ? '' : $color;
	$style = edd_get_option( 'button_style', 'button' );
	?>
		<form action="" method="post" name="fes-registration-form" class="fes-registration-form edd-fes-registration-button-form">
			<input type="hidden" name="user_id" value="<?php echo $current_user->ID; ?>" />
			<input type="hidden" name="vendor_id" value="<?php echo $current_user->ID; ?>" />
			<input type="hidden" name="first_name" value="<?php echo $current_user->user_firstname; ?>" />
			<input type="hidden" name="last_name" value="<?php echo $current_user->user_lastname; ?>" />
			<input type="hidden" name="display_name" value="<?php echo $current_user->display_name; ?>" />
			<input type="hidden" name="action" value="edd_fes_registration_button" />
			<input type="hidden" name="form_id" value="<?php echo EDD_FES()->helper->get_option( 'fes-registration-form', false ); ?>" />
			<?php echo wp_nonce_field( 'fes-registration-form', 'fes-registration-form', true, false ) ?>
			<input type="submit" name="submit" value="<?php _e('Become a vendor', 'edd-fes-registration-button'); ?>" class="edd-submit edd-fes-registration-button <?php echo $color . ' ' . $style; ?>" />
		</form>
	<?php
}