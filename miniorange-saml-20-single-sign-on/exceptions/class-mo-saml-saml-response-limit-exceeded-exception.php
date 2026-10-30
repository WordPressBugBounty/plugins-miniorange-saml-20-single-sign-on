<?php
/**
 * Thrown when a posted SAMLResponse exceeds configured size limits.
 *
 * @package miniorange-saml-20-single-sign-on/exception
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SAML Response exceeded inbound size limits before XML parsing.
 */
class Mo_SAML_Saml_Response_Limit_Exceeded_Exception extends Exception {

	/**
	 * Sets the message and the plugin error code (40, WPSAMLERR040).
	 *
	 * @param mixed $message This contains the error message.
	 */
	public function __construct( $message ) {
		$code = 40;
		parent::__construct( $message, $code, null );
	}
}
