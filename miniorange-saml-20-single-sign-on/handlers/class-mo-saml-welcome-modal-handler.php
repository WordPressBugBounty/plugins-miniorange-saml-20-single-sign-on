<?php
/**
 * This file contains functions which handles the welcome modal view.
 *
 * @package miniorange-20-single-sign-on\handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class handles Welcome Modal.
 */
class Mo_Saml_Welcome_Modal_Handler {

	/**
	 * This function dismisses the welcome modal by setting an option in database.
	 *
	 * @return void
	 */
	public static function mo_saml_dismiss_modal() {
		update_option( Mo_Saml_Options_Enum::NEW_USER, 1 );
	}
}
