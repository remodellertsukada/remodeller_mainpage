<?php

require 'plugin-update-checker.php';

/*-------------------------------------------*/
/*	License Key Configuration
/*-------------------------------------------*/
$license_slug = 'lightning-pro';
$license_name = $license_slug . '-license-key';
$license      = get_option( $license_name );

/*-------------------------------------------*/
/*	License Key Configuration
/*-------------------------------------------*/
function wsh_filter_update_checks( $queryArgs ) {
	global $license;
	global $license_name;

	if ( ! empty( $license ) ) {
		$queryArgs[ $license_name ] = $license;
	}

	return $queryArgs;
}

/*-------------------------------------------*/
/*	Customizer To Save License Key
/*-------------------------------------------*/
add_action( 'customize_register', 'vk_product_updater_customize_register' );
function vk_product_updater_customize_register( $wp_customize ) {
	global $license_name;
	$wp_customize->add_section(
		'license-section', array(
			'title'    => lightning_get_prefix_customize_panel() . __( 'License key', 'lightning-pro' ),
			'priority' => 400,
		)
	);

	$wp_customize->add_setting(
		$license_name, array( //add_settingのidが、wp_optionの保存名になる。
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		$license_name, array(
			'label'       => __( 'License key', 'lightning-pro' ),
			'section'     => 'license-section',
			'settings'    => $license_name,
			'description' => __( 'Once you enter the license key you will be able to do a one click update from the administration screen.', 'lightning-pro' ),
		)
	);
}
