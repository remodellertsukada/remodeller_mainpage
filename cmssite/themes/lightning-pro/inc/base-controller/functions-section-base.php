<?php

function lightning_is_base_active_by_skin(){

	$base = false;

	/*  Base setting of skin
	/*-------------------------------------------*/
	$skin     = get_option( 'lightning_design_skin' );
	$base_use_skins = array(
		'charm-bs4',
		'pale-bs4',
		'variety-bs4',
	);
	if ( in_array( $skin, $base_use_skins ) ){
		$base = true;
	}
	return $base;
}

function lightning_is_base_active(){

	$base = lightning_is_base_active_by_skin();

	/*  Base setting of site general
	/*-------------------------------------------*/
	$options = get_option('lightning_theme_options');
	if ( isset($options['section_base']) && $options['section_base'] === 'use' ){
		$base = true;
	} else if ( isset($options['section_base']) && $options['section_base'] === 'no' ){
		$base = false;
	}

	/*  Base setting of specific page 
	/*-------------------------------------------*/
	if ( is_singular() ){
		global $post;
		$cf = $post->_lightning_design_setting;
		if ( isset( $cf['section_base'] ) ){
			if ( $cf['section_base'] == 'no' ){
				$base = false;
			} else if ( $cf['section_base'] == 'use' ){
				$base = true;
			}
		}
	}

	$base = apply_filters( 'lightning_is_base_active', $base );

	return $base;
}

add_filter( 'lightning_get_the_class_names', 'lightning_add_class_baseSection',15 );
function lightning_add_class_baseSection( $class_names ) {
	if ( lightning_is_base_active() ){
		$class_names['siteContent'] = $class_names['siteContent'] . ' siteContent-base-on';
		$class_names['mainSection'] = $class_names['mainSection'] . ' mainSection-base-on';
		$class_names['sideSection'] = $class_names['sideSection'] . ' sideSection-base-on';
	} else {
		$class_names['siteContent'] = str_replace( ' siteContent-base-on', '', $class_names['siteContent'] );
		$class_names['mainSection'] = str_replace( ' mainSection-base-on', '', $class_names['mainSection'] );
		$class_names['sideSection'] = str_replace( ' sideSection-base-on', '', $class_names['sideSection'] );
	}
	return $class_names;
}