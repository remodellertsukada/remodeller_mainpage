<?php

/*-------------------------------------------*/
/*  Load modules
/*-------------------------------------------*/
if ( ! class_exists( 'VK_Widget_Pr_Content' ) ) {
	require_once( 'package/class-vk-widget-pr-content.php' );

	// PR Contet ウィジェットのCSSファイルを単独で読み込まないように外す
	global $pr_content_dont_load_css;
	$pr_content_dont_load_css = true;

	// global $vk_page_header_output_class;
	// $vk_page_header_output_class = '.page-header';
	//
	// global $customize_setting_prefix;
	// $customize_setting_prefix = 'Lightning';

}
