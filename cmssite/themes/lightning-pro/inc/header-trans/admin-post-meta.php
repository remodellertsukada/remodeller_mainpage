<?php
/*
  入力フィールドの生成
/*-------------------------------------------*/
add_action( 'lightning_design_setting_meta_fields', 'lightning_design_setting_header_trans_meta_fields' );
function lightning_design_setting_header_trans_meta_fields() {

	// CSRF対策の設定（フォームにhiddenフィールドとして追加するためのnonceを「'noncename__lightning_design」として設定）
	// ※ 他でnonceを出力しているのでコメントアウト
	// wp_nonce_field( wp_create_nonce( __FILE__ ), 'noncename__lightning_design' );

	global $post;

	/*
	  セクションベース
	/*-------------------------------------------*/
	// $form = '<h4>' . __( 'Header trans', 'lightning-pro' ) . '</h4>';

	// $id              = '_lightning_design_setting[header_trans]';
	$saved_post_meta = get_post_meta( $post->ID, '_lightning_design_setting', true );

	// if ( ! empty( $saved_post_meta['header_trans'] ) ) {
	// 	$saved = $saved_post_meta['header_trans'];
	// } else {
	// 	$saved = '';
	// }

	/*
	  .Page Header
	/*-------------------------------------------*/
	$form = '<h4>' . __( 'Header transmission', 'lightning-pro' ) . '</h4>';

	$id    = '_lightning_design_setting[header_trans]';
	$name  = '_lightning_design_setting[header_trans]';
	$label = __( 'Enable header transmission', 'lightning-pro' );

	$form .= '<ul>';

	$checked = '';
	if ( ! empty( $saved_post_meta['header_trans'] ) ) {
		$checked = ' checked';
	}

	$form .= '<li class="vk_checklist_item vk_checklist_item-style-vertical">' . '<input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="true"' . $checked . '  class="vk_checklist_item_input"><label for="' . esc_attr( $name ) . '" class="vk_checklist_item_label">' . wp_kses_post( $label ) . '</label></li>';
	$form .= '</ul>';
	echo $form;
}
