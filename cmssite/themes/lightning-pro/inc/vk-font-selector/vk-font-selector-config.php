<?php
/*-------------------------------------------*/
/*  Load modules
/*-------------------------------------------*/
if ( ! class_exists( 'Vk_Font_Selector_Customize' ) ) {
	require_once( 'package/class-vk-font-selector.php' );
	global $vk_font_selector_prefix;
	$vk_font_selector_prefix = lightning_get_prefix_customize_panel();

	global $vk_font_selector_enqueue_handle_style;
	$vk_font_selector_enqueue_handle_style = 'lightning-design-style';

	global $vk_font_selector_editor_style;
	$vk_font_selector_editor_style = 'lightning-common-editor-gutenberg';

	global $vk_font_selector_priority;
	$vk_font_selector_priority = 502;

	add_filter( 'vk_font_target_array', 'lightning_font_target_change' );
	function lightning_font_target_change( $target_array ) {
		$target_array = array(
			'hlogo'            => array(
				'label'    => __( 'Header Logo', 'lightning-pro' ),
				'selector' => '.navbar-brand.siteHeader_logo',
			),
			'menu'             => array(
				'label'    => __( 'Global Menu / Mobile Menu', 'lightning-pro' ),
				'selector' => '.gMenu_name,.vk-mobile-nav .menu,.mobile-fix-nav-menu',
			),
			'menu_description' => array(
				'label'    => __( 'Global Menu Description', 'lightning-pro' ),
				'selector' => '.gMenu_description',
			),
			'title'            => array(
				'label'    => __( 'Title', 'lightning-pro' ),
				'selector' => 'h1,h2,h3,h4,h5,h6,dt,.page-header_pageTitle,.mainSection-title,.subSection-title,.veu_leadTxt,.lead',
			),
			'text'             => array(
				'label'    => __( 'Text', 'lightning-pro' ),
				'selector' => 'body',
			),
		);

		// セレクタを書き換えるだけなら以下のような記述でも可。
		// 今回は設定項目を任意の順番で挿入したかったので配列全体を差し替え
		// $target_array['title']['selector'] = 'h1,h2,h3,h4,h5,h6,dt,.page-header_pageTitle,.mainSection-title,.subSection-title,.veu_leadTxt,.lead';

		return $target_array;
	}

	add_filter( 'vk_font_editor_target_array', 'lightning_font_editor_target_change' );
	function lightning_font_editor_target_change( $editor_target_array ) {
		$editor_target_array = array(
			'title'            => array(
				'label'    => __( 'Title', 'lightning-pro' ),
				'selector' => '.edit-post-visual-editor.editor-styles-wrapper h1,
							.edit-post-visual-editor.editor-styles-wrapper h2,
							.edit-post-visual-editor.editor-styles-wrapper h3,
							.edit-post-visual-editor.editor-styles-wrapper h4,
							.edit-post-visual-editor.editor-styles-wrapper h5,
							.edit-post-visual-editor.editor-styles-wrapper h6,
							.edit-post-visual-editor.editor-styles-wrapper dt',
			),
			'text'             => array(
				'label'    => __( 'Text', 'lightning-pro' ),
				'selector' => '.edit-post-visual-editor.editor-styles-wrapper',
			),
		);

		// セレクタを書き換えるだけなら以下のような記述でも可。
		// 今回は設定項目を任意の順番で挿入したかったので配列全体を差し替え
		// $target_array['title']['selector'] = 'h1,h2,h3,h4,h5,h6,dt,.page-header_pageTitle,.mainSection-title,.subSection-title,.veu_leadTxt,.lead';

		return $editor_target_array;
	}
}
