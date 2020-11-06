<?php
/**
 * Config File of Lightning Header Trans
 *
 * @package Lightning Pro
 */

$current_skin = get_option( 'lightning_design_skin' );
if ( ! in_array( $current_skin, array( 'jpnstyle', 'jpnstyle-bs4', 'origin', 'variety', 'charm', 'fort', 'fort2', 'pale' ), true ) ) {
	require_once 'class-lightning_header_trans.php';
	require_once 'admin-post-meta.php';

	/**
	 * Header Trance
	 */
	function lightning_is_header_trans() {
		return Lightning_Header_Trans::is_header_trans();
	}

	Lightning_Header_Trans::init();
}

