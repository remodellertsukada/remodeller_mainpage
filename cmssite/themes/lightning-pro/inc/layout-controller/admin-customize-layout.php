<?php

/*
  customize_register
/*-------------------------------------------*/
add_action( 'customize_register', 'lightning_customize_register_layout' );
function lightning_customize_register_layout( $wp_customize ) {

	$wp_customize->add_section(
		'lightning_layout',
		array(
			'title'    => lightning_get_prefix_customize_panel() . __( 'Layout settings', 'lightning-pro' ),
			'priority' => 503,
		// 'panel'				=> 'lightning_setting',
		)
	);
	// Add setting
	$wp_customize->add_setting(
		'ltg_column_setting',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Custom_Html_Control(
			$wp_customize,
			'ltg_column_setting',
			array(
				'label'            => __( 'Column Setting', 'lightning-pro' ) . ' ( ' . __( 'PC mode', 'lightning-pro' ) . ' )',
				'section'          => 'lightning_layout',
				'type'             => 'text',
				'custom_title_sub' => '',
				'custom_html'      => '',
				// 'priority'         => 700,
			)
		)
	);

	$page_types = array(
		'front-page' => array(
			'label'       => __( 'Home page', 'lightning-pro' ),
			'description' => '',
		),
		'search'     => array(
			'label' => __( 'Search', 'lightning-pro' ),
		),
		'error404'   => array(
			'label' => __( '404 page', 'lightning-pro' ),
		),
		// If cope with custom post types that like a "archive-post" "single-post".
	);

	$get_post_types = get_post_types(
		array(
			'public'   => true,
		),
		'object'
	);

	foreach ( $get_post_types as $get_post_type ) {
		$archive_link = get_post_type_archive_link( $get_post_type->name );
		if ( $archive_link ){
			$page_types = $page_types + array(
				'archive-' . $get_post_type->name => array(
					'label' => __( 'Archive Page', 'lightning-pro' ). ' [' . esc_html( $get_post_type->label ) . ']',
				),
			);
		}
	}

	foreach ( $get_post_types as $get_post_type ) {
		$page_types = $page_types + array(
			'single-' . $get_post_type->name => array(
				'label' => __( 'Single Page', 'lightning-pro' ). ' [' . esc_html( $get_post_type->label ) . ']',
			),
		);
	}

	$choices = array(
		'default'               => __( 'Unspecified', 'lightning-pro' ),
		'col-two'               => __( '2 column', 'lightning-pro' ),
		'col-one'               => __( '1 column', 'lightning-pro' ),
		'col-one-no-subsection' => __( '1 column ( No sub section )', 'lightning-pro' ),
	);
	$choices = apply_filters( 'lighghtning_columns_setting_choice', $choices );

	$wp_customize->selective_refresh->add_partial(
		'lightning_theme_options[layout][front-page]',
		array(
			'selector'        => '.mainSection',
			'render_callback' => '',
		)
	);

	foreach ( $page_types as $key => $value ) {
		$wp_customize->add_setting(
			'lightning_theme_options[layout][' . $key . ']',
			array(
				'default'           => 'default',
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'lightning_theme_options[layout][' . $key . ']',
			array(
				'label'    => $value['label'],
				'section'  => 'lightning_layout',
				'settings' => 'lightning_theme_options[layout][' . $key . ']',
				'type'     => 'select',
				'choices'  => $choices,
				// 'priority' => 700,
			)
		);
	}

	$wp_customize->add_setting(
		'ltg_sidebar_setting',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Custom_Html_Control(
			$wp_customize,
			'ltg_sidebar_setting',
			array(
				'label'            => __( 'Sidebar Setting', 'lightning-pro' ),
				'section'          => 'lightning_layout',
				'type'             => 'text',
				'custom_title_sub' => '',
				'custom_html'      => '',
				// 'priority'         => 700,
			)
		)
	);

	// sidebar_position
	$wp_customize->add_setting(
		'lightning_theme_options[sidebar_position]',
		array(
			'default'           => 'right',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => array( 'VK_Helpers', 'sanitize_choice' ),
		)
	);
	$wp_customize->add_control(
		'lightning_theme_options[sidebar_position]',
		array(
			'label'    => __( 'Sidebar position ( PC mode )', 'lightning-pro' ),
			'section'  => 'lightning_layout',
			'settings' => 'lightning_theme_options[sidebar_position]',
			'type'     => 'radio',
			'choices'  => array(
				'right' => __( 'Right', 'lightning-pro' ),
				'left'  => __( 'Left', 'lightning-pro' ),
			),
		)
	);

	// sidebar_fix
	$wp_customize->add_setting(
		'ltg_sidebar_fix_setting_title',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Custom_Html_Control(
			$wp_customize,
			'ltg_sidebar_fix_setting_title',
			array(
				'label'            => '',
				'section'          => 'lightning_layout',
				'type'             => 'text',
				'custom_title_sub' => __( 'Sidebar fix', 'lightning-pro' ),
				'custom_html'      => '',
			)
		)
	);
	$wp_customize->add_setting(
		'lightning_theme_options[sidebar_fix]',
		array(
			'default'           => 'priority-top',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'lightning_theme_options[sidebar_fix]',
		array(
			'label'    => __( 'Sidebar fix setting', 'lightning-pro' ),
			'section'  => 'lightning_layout',
			'settings' => 'lightning_theme_options[sidebar_fix]',
			'type'     => 'select',
			'choices'  => array(
				'priority-top'		=> __( 'Fix top priority', 'lightning-pro' ),
				'priority-bottom'	=> __( 'Fix bottom priority', 'lightning-pro' ),
				'no-fix' 			=> __( 'Don\'t fix the sidebar', 'lightning-pro' ),
			)
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'lightning_theme_options[sidebar_fix]',
		array(
			'selector'        => '.sideSection',
			'render_callback' => '',
		)
	);

}
