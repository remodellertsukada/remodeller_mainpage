<?php
/*
  Load modules ( master config )
/*-------------------------------------------*/
if ( ! class_exists( 'Vk_Page_Header' ) ) {

	$skin = get_option( 'lightning_design_skin' );
	if ( $skin == 'variety' || $skin == 'variety-bs4' ) {
		return;
	}

	require_once 'package/class-vk-page-header.php';
	require get_parent_theme_file_path( '/inc/custom-field-builder/custom-field-builder-config.php' );

	global $customize_setting_prefix;
	$customize_setting_prefix = lightning_get_prefix_customize_panel();

	global $customize_section_priority;
	$customize_section_priority = 530;

	global $vk_page_header_output_class;
	$vk_page_header_output_class = '.page-header';

	global $vk_page_header_inner_class;
	$vk_page_header_inner_class = '.page-header h1.page-header_pageTitle,.page-header div.page-header_pageTitle';

	/*
	CSS関連はCSSで指定するのでPHP側でデフォルトを指定する必要はない
	 */
	global $vk_page_header_default;
	$vk_page_header_default = array(
		'text_color'  => '#333',
		'image_basic' => get_template_directory_uri() . '/inc/vk-page-header/package/images/header-sample-biz.jpg',
	);

	global $vk_page_header_default_bg_url;
	// このファイルがテーマで使われた場合の例
	$vk_page_header_default_bg_url = get_template_directory_uri( '/inc/vk-page-header/package/images/header-sample.jpg' );
	// プラグインの場合の例
	// $vk_page_header_default_bg_url = plugins_url( '/images/header-sample.jpg', __FILE__ );

	global $vk_page_header_enqueue_handle_style;
	$vk_page_header_enqueue_handle_style = 'lightning-design-style';

}

/*
Sample Image
https://pixabay.com/ja/%E4%BA%AC-%E6%97%A5%E6%9C%AC-%E7%AB%B9-%E3%83%9C%E3%82%B1%E5%91%B3-%E5%86%92%E9%99%BA-%E6%A3%AE%E6%9E%97-%E6%97%85%E8%A1%8C-1860521/
 */
