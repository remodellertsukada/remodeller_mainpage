<?php
/**
 * Config File of Lightning Header Top
 *
 * @package Lightning Pro
 */

if ( ! class_exists( 'Lightning_Header_Top' ) ) {

	global $vk_header_top_prefix;
	$vk_header_top_prefix = lightning_get_prefix_customize_panel();

	$skin = get_option( 'lightning_design_skin' );
	if ( 'jpnstyle' !== $skin && 'charm' !== $skin ) {
		require_once dirname( __FILE__ ) . '/package/class-lightning-header-top.php';
	}

	// 問い合わせ電話番号/ボタンの非表示.
	$contact_exclude_array = array( 'fort', 'fort2', 'fort-bs4', 'fort-bs4-footer-light', 'pale', 'pale-bs4' );
	if ( in_array( $skin, $contact_exclude_array, true ) ) {
		/**
		 * 問い合わせボタンを非表示.
		 */
		function ltg_header_top_hidden_contact() {
			return false;
		}
		add_filter( 'header-top-contact', 'ltg_header_top_hidden_contact' );

		/**
		 * 問い合わせ電話番号を非表示.
		 */
		function ltg_header_top_hidden_tel() {
			return false;
		}
		add_filter( 'header-top-tel', 'ltg_header_top_hidden_tel' );
	}
}
