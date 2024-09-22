<?php
/**
 * This file initiates the display for all the tabs.
 *
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'mo-saml-import-export.php';
require_once 'class-mo-saml-logger.php';

foreach ( glob( plugin_dir_path( __FILE__ ) . 'views' . DIRECTORY_SEPARATOR . '*.php' ) as $filename ) {
	include_once $filename;
}
/**
 * The function displays the tabs in the plugin and then renders the associated data.
 */
function mo_saml_register_saml_sso() {

	Mo_SAML_Utilities::mo_saml_extension_disabled_modal();
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- reading tab name
	if ( isset( $_GET['tab'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- reading tab name
		$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
		if ( 'addons' === $active_tab ) {
			echo "<script type='text/javascript'>
            highlightAddonSubmenu();
            </script>";

		}
	} else {
		$active_tab = 'save';
	}
	?>
	<?php

	mo_saml_display_plugin_dependency_warning();

	?>
	<div id="mo_saml_settings" >
		<?php
		if ( ! Mo_SAML_Utilities::mo_saml_is_sp_configured() ) {
			mo_saml_display_welcome_page();
		}

		mo_saml_display_plugin_header();
		?>

	</div>

	<?php
	mo_saml_display_plugin_tabs( $active_tab );
}

/**
 * This function returns attribute mapping url.
 */
function mo_saml_get_attribute_mapping_url() {
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		return add_query_arg( array( 'tab' => 'opt' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
	} else {
			$server_url = '';
	}
}

/**
 * This function returns service provider url.
 */
function mo_saml_get_service_provider_url() {
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			return add_query_arg( array( 'tab' => 'save' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
	} else {
			$server_url = '';
	}
}
/**
 * This function returns redirection sso url.
 */
function mo_saml_get_redirection_sso_url() {
	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			return add_query_arg( array( 'tab' => 'general' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
	} else {
		$server_url = '';
	}
}
/**
 * This function returns test url.
 */
function mo_saml_get_test_url() {

		$url = site_url() . '/?option=testConfig';

	return $url;
}

/**
 * This function verifies the customers are registered or not.
 */
function mo_saml_is_customer_registered_saml() {

	$email        = get_option( Mo_Saml_Customer_Constants::ADMIN_EMAIL );
	$customer_key = get_option( Mo_Saml_Customer_Constants::CUSTOMER_KEY );

	if ( ! $email || ! $customer_key || ! is_numeric( trim( $customer_key ) ) ) {
		return 0;
	} else {
		return 1;
	}
}
/**
 * This function displays test configuration error.
 *
 * @param string $error_code error code .
 * @param string $display_metadata_mismatch The metadata recieved in SMAL response and stored in plugin if the error corresponds to a mismatched metadata .
 * @param string $status_message The status sent by Identity Provider.
 */
function mo_saml_display_test_config_error_page( $error_code, $display_metadata_mismatch = '', $status_message = '' ) {
	$error_fix     = $error_code['fix'];
	$error_cause   = $error_code['cause'];
	$error_message = ! empty( $error_code['testconfig_msg'] ) ? $error_code['testconfig_msg'] : '';
	if ( ob_get_level() > 0 ) {
		ob_end_clean();
	}

	echo '<div style="font-family:Calibri;padding:0 3%;">';
	echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR ' . esc_html( $error_code['code'] ) . '</div>
	<div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>' .  wp_kses_post( $error_cause )  . '</p>
	<p>Please contact your administrator and report the following error:</p>
	<p><strong>Possible Cause: </strong>' . esc_html( $error_message ) . '</p>';
	if ( ! empty( $status_message ) ) {
		echo '<p><strong>Status Message in the SAML Response:</strong> <br/>' . esc_html( $status_message ) . '</p><br>';
	}
	if ( ! empty( $display_metadata_mismatch ) ) {
		echo wp_kses(
			$display_metadata_mismatch,
			array(
				'p'      => array(
					'strong' => array(),
				),
				'strong' => array(),
			)
		);
	}
	echo '<p><strong>Solution:</strong></p>
		' . wp_kses_post( $error_fix ) . '
	</div>
	<div style="margin:3%;display:block;text-align:center;">';
		if ( 'WPSAMLERR010' === $error_code['code'] || 'WPSAMLERR004' === $error_code['code'] || 'WPSAMLERR012' === $error_code['code'] ) {
			$option_id = '';
		switch ( $error_code['code'] ) {
			case 'WPSAMLERR004':
				$option_id = 'mo_fix_certificate';
				break;
			case 'WPSAMLERR010':
				$option_id = 'mo_fix_entity_id';
				break;
			case 'WPSAMLERR012':
				$option_id = 'mo_fix_iconv_cert';
				break;
		}
		echo '<div>
			    <ol style="text-align: center">
                    <form method="post" action="">';
		wp_nonce_field( $option_id );
		echo '<input type="hidden" name="option" value="' . esc_attr( $option_id ) . '" />
                <input type="submit" class="miniorange-button" style="width: 15%" value="' . esc_attr( 'Fix Issue' ) . '">
                <br>
                </ol>      
            </form>      
          </div>';
	} else {
		echo '<div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Done" onClick="self.close();"></div>';
	}
	echo '</div>';
	mo_saml_download_logs( $error_message, $error_cause );
	exit;
}
/**
 * This function renders the error log download section.
 *
 * @param string $error_msg error message.
 *
 * @param string $cause_msg casuse message.
 */
function mo_saml_download_logs( $error_msg, $cause_msg ) {

	echo '<div style="font-family:Calibri;padding:0 3%;">';
	echo '<hr class="header"/>';
	echo '          <p style="font-size: larger ;color: #a94442     ">' . wp_kses(
		__( 'You can check out the Troubleshooting section provided in the plugin to resolve the issue.<br> If the problem persists, mail us at <a href="mailto:samlsupport@xecurify.com">samlsupport@xecurify.com</a>' ),
		array(
			'br' => array(),
			'a'  => array( 'href' => array() ),
		)
	) . '.</p>
                   
                    </div>
                    <div style="margin:3%;display:block;text-align:center;">
                   
				<input class="miniorange-button" type="button" value="' . esc_attr_x( 'Close', '', 'miniorange-saml-20-single-sign-on' ) . '" onclick="self.close()"></form>            
                </div>    ';
	echo '&nbsp;&nbsp;';

	$saml_response = '';
	//phpcs:ignore WordPress.Security.NonceVerification.Missing 
	if ( ! empty( $_POST['SAMLResponse'] ) ) {
		//phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotValidated -- reading SAML response.
		$saml_response = sanitize_text_field( wp_unslash( $_POST['SAMLResponse'] ) );
	}

	update_option( Mo_Saml_Options_Test_Configuration::SAML_RESPONSE, $saml_response );
	$error_array = array(
		'Error' => $error_msg,
		'Cause' => $cause_msg,
	);
	update_option( Mo_Saml_Options_Test_Configuration::TEST_CONFIG_ERROR_LOG, $error_array );
	update_option( Mo_Saml_Sso_Constants::MO_SAML_TEST_STATUS, 0 );
	?>

	<style>
		.miniorange-button {
			padding:1%;
			background: linear-gradient(0deg,rgb(14 42 71) 0,rgb(26 69 138) 100%)!important;
			cursor: pointer;font-size:15px;
			border-width: 1px;border-style: solid;
			border-radius: 3px;white-space: nowrap;
			box-sizing: border-box;
			box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;
			margin: 22px;
		}
	</style>
	<?php

	exit();
}
/**
 * This function adds a query argument in the passed URL.
 *
 * @param array  $query_arg query argument.
 * @param string $url URL.
 *
 * @return string $url URL with $query_arg appended.
 */
function mo_saml_add_query_arg( $query_arg, $url ) {
	if ( strpos( $url, 'mo_saml_enable_debug_logs' ) !== false ) {
		$url = str_replace( 'mo_saml_enable_debug_logs', 'mo_saml_settings', $url );
	}
	$url = add_query_arg( $query_arg, $url );
	return $url;
}
?>
