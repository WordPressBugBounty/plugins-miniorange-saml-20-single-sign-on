<?php
/**
 * This file contains the class Mo_SAML_Replayed_Response_Exception
 * This exception is thrown when a SAML Assertion that has already been consumed is received again.
 *
 * @package miniorange-saml-20-single-sign-on/exception
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * This exception indicates that the SAML Assertion was already used for a login (replay attempt).
 */
class Mo_SAML_Replayed_Response_Exception extends Exception {
	/**
	 * Constructor function, which defines the `$code` and `$message` for
	 * the exception, and makes a call to the parent (`Exception`) constructor.
	 *
	 * @param mixed $message this contains the error message.
	 */
	public function __construct( $message ) {
		parent::__construct( $message, 16, null );
	}
}
