<?php


/*
  Load modules
-------------------------------------------*/
// ページ下部に固定表示するメニュー
if ( ! class_exists( 'Vk_Mobile_Fix_Nav' ) ) {
	require_once 'package/class-vk-mobile-fix-nav.php';

	global $vk_mobile_fix_nav_prefix;
	$vk_mobile_fix_nav_prefix = lightning_get_prefix_customize_panel();

	global $vk_mobile_fix_nav_priority;
	$vk_mobile_fix_nav_priority = 550;

	add_action(
		'after_setup_theme',
		function() {
			// CSSはcommon.cssでビルドしているので読み込まないように外す
			$hook_point = apply_filters( 'vk_mobile_fix_nav_enqueue_point', 'wp_enqueue_scripts' );
			remove_action( $hook_point, array( 'Vk_Mobile_Fix_Nav', 'add_style' ) );
		}
	);
}
