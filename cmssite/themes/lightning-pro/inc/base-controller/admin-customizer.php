<?php

/*
  Customizer Setting
/*-------------------------------------------*/
add_action( 'customize_register', 'lightning_base_controll_customize_register' );
function lightning_base_controll_customize_register( $wp_customize ) {

	$wp_customize->add_setting(
		'section_base_setting',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Custom_Html_Control(
			$wp_customize,
			'section_base_setting',
			array(
				'label'            => __( 'Section base setting', 'lightning-pro' ),
				'section'          => 'lightning_layout',
				'type'             => 'text',
				'custom_title_sub' => '',
				'custom_html'      => '*' . __( 'Bootstrap4 skin only', 'lightning-pro' ),
				'priority'         => 700,
			)
		)
	);

	$choices = array(
		'default' => __( 'Design skin default', 'lightning-pro' ),
		'no'      => __( 'No section base', 'lightning-pro' ),
		'use'     => __( 'Use section base', 'lightning-pro' ),
	);

	$wp_customize->add_setting(
		'lightning_theme_options[section_base]',
		array(
			'default'           => 'default',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'lightning_theme_options[section_base]',
		array(
			'label'    => '',
			'section'  => 'lightning_layout',
			'settings' => 'lightning_theme_options[section_base]',
			'type'     => 'select',
			'choices'  => $choices,
			'priority' => 700,
		)
	);

}
