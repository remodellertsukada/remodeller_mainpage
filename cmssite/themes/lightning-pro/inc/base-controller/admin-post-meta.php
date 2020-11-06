<?php
/*
  入力フィールドの生成
/*-------------------------------------------*/
add_action( 'lightning_design_setting_meta_fields', 'lightning_design_setting_base_section_meta_fields' );
function lightning_design_setting_base_section_meta_fields() {

	// CSRF対策の設定（フォームにhiddenフィールドとして追加するためのnonceを「'noncename__lightning_design」として設定）
	wp_nonce_field( wp_create_nonce( __FILE__ ), 'noncename__lightning_design' );

	global $post;

	/*
	  セクションベース
	/*-------------------------------------------*/
	$form = '<h4>' . __( 'Section base setting', 'lightning-pro' ) . '</h4>';

	$id              = '_lightning_design_setting[section_base]';
	$saved_post_meta = get_post_meta( $post->ID, '_lightning_design_setting', true );

	if ( ! empty( $saved_post_meta['section_base'] ) ) {
		$saved = $saved_post_meta['section_base'];
	} else {
		$saved = '';
	}

	$options = array(
		'default' => __( 'Design skin default', 'lightning-pro' ),
		'no'      => __( 'No section base', 'lightning-pro' ),
		'use'     => __( 'Use section base', 'lightning-pro' ),
	);

	$form .= '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '">';
	foreach ( $options as $key => $value ) {
		$selected = '';
		if ( $key === $saved ) {
			$selected = ' selected';
		}
		$form .= '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $value ) . '</option>';
	}
	$form .= '</select>';
	echo $form;
}
