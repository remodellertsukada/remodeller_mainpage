<?php
/*-------------------------------------------*/
/*  Load modules
/*-------------------------------------------*/
if ( ! class_exists( 'Lightning_Copyright_Custom' ) ) {
	require get_parent_theme_file_path( 'inc/copyright-customizer/package/class-copyright-customizer.php' );
	global $vk_copyright_customizer_prefix;
	$vk_copyright_customizer_prefix = lightning_get_prefix_customize_panel();

	global $vk_copyright_customizer_priority;
	$vk_copyright_customizer_priority = 543;
}
