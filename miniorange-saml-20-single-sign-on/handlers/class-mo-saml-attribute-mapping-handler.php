<?php
	/**
	 * Handles Form processing of the Attribute Mapping.
	 *
	 * @package  miniorange-saml-20-single-sign-on\handlers
	 */

if ( ! defined( 'ABSPATH' ) ) {
		exit();
}

/**
 * Handles Form Processing of Attribute Mapping section of "Attribute&Role Mapping" tab of the plugin.
 */
class Mo_SAML_Attribute_Mapping_Handler {
	/**
	 * Handles clearing attributes list shown in the attribute mapping of the plugin
	 *
	 * @return void
	 */
	public static function clear_attr_list() {
		delete_option( Mo_Saml_Options_Test_Configuration::TEST_CONFIG_ATTRS );
		$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::mo_saml_translate( 'ATTRIBUTES_CLEARED' ), 'CLEAR_ATTR_LIST' );
		$post_save->mo_saml_post_save_action();
	}

	/**
	 * Handles saving the "User Matching Preference" radio buttons (Username/Email) shown above the
	 * Attribute Mapping section. Stores the choice as a single option so it can be used to match the
	 * WordPress user during SSO login.
	 *
	 * @param array           $post_array Contains the values of $_POST.
	 * @param DatabaseHandler $db_handler Object for DatabaseHandler.
	 * @return void
	 */
	public static function mo_saml_update_identify_user( $post_array, $db_handler ) {
		// Anything other than exactly 'username' defaults to Email.
		$identify_user_by = isset( $post_array['saml_am_identify_user_by'] ) && Mo_Saml_Options_Enum_Attribute_Mapping::ACCOUNT_MATCHER_USERNAME === $post_array['saml_am_identify_user_by']
			? Mo_Saml_Options_Enum_Attribute_Mapping::ACCOUNT_MATCHER_USERNAME
			: Mo_Saml_Options_Enum_Attribute_Mapping::ACCOUNT_MATCHER_EMAIL;

		$db_handler->mo_saml_save_options( array( Mo_Saml_Options_Enum_Attribute_Mapping::ATTRIBUTE_ACCOUNT_MATCHER => $identify_user_by ) );

		$post_save = new Mo_SAML_Post_Save_Handler(
			Mo_Saml_Save_Status_Constants::SUCCESS,
			Mo_Saml_Messages::mo_saml_translate( 'UPDATED_IDENTIFY_USER' ),
			'IDENTIFY_USER_BY',
			array( 'identify_user_by' => $identify_user_by )
		);
		$post_save->mo_saml_post_save_action();
	}
}
