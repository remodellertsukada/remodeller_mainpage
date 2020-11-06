<?php

require get_parent_theme_file_path( '/plugin-support/woocommerce/customize.php' );

function lightning_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'lightning_add_woocommerce_support' );

/*
  Load CSS
/*-------------------------------------------*/
function lightning_woo_css() {
	wp_enqueue_style( 'lightning-woo-style', get_template_directory_uri() . '/plugin-support/woocommerce/css/woo.css', array( 'lightning-common-style' ), LIGHTNING_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'lightning_woo_css' );

function lightning_add_woocommerce_css_to_editor() {
	add_editor_style( '/plugin-support/woocommerce/css/woo.css' );
}
add_action( 'after_setup_theme', 'lightning_add_woocommerce_css_to_editor' );

/*
  WidgetArea initiate
/*-------------------------------------------*/

function lightning_widgets_init_product() {
	$plugin_path = 'woocommerce/woocommerce.php';
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( ! is_plugin_active( $plugin_path ) ) {
		return;
	}

	// Get post type name
	/*-------------------------------------------*/
	$post_type                = 'product';
	$woocommerce_shop_page_id = get_option( 'woocommerce_shop_page_id' );
	$label                    = get_the_title( $woocommerce_shop_page_id );
	$sidebar_description      = sprintf( __( 'This widget area appears on the %s contents page only.', 'lightning-pro' ), $label );

	// Set post type widget area
	register_sidebar(
		array(
			'name'          => sprintf( __( 'Sidebar(%s)', 'lightning-pro' ), $label ),
			'id'            => $post_type . '-side-widget-area',
			'description'   => $sidebar_description,
			'before_widget' => '<aside class="widget %2$s" id="%1$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title subSection-title">',
			'after_title'   => '</h1>',
		)
	);

}
add_action( 'widgets_init', 'lightning_widgets_init_product' );


/*
  Adding support for WooCommerce 3.0’s new gallery feature
-------------------------------------------*/
/*
https://woocommerce.wordpress.com/2017/02/28/adding-support-for-woocommerce-2-7s-new-gallery-feature-to-your-theme/
*/
add_action( 'after_setup_theme', 'lightning_woo_product_gallery_setup' );

function lightning_woo_product_gallery_setup() {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}



function lightning_woo_get_design_setting(){
	$shop_page_id = wc_get_page_id( 'shop' );
	$shop_page    = get_post( $shop_page_id );
	$option = get_post_meta( $shop_page_id, '_lightning_design_setting', true );
	return $option;
}
function lightning_woo_is_shop_page(){
	global $post;
	if ( 'product' === get_post_type( $post ) && ! is_singular() ) {
		return true;
	}
}

/**
 * 	カラム表示制御
 */
function lightning_woo_is_layout_onecolumn( $return ){
	if ( lightning_woo_is_shop_page() ) {
		$lightning_design_setting = lightning_woo_get_design_setting();

		if ( isset( $lightning_design_setting['layout'] ) ) {
			if ( 'col-two' === $lightning_design_setting['layout'] ) {
				$return = false;
			} elseif ( 'col-one-no-subsection' === $lightning_design_setting['layout'] ) {
				$return = true;
			} elseif ( 'col-one' === $lightning_design_setting['layout'] ) {
				$return = true;
			}
		}
		// ※ページ属性のテンプレート指定処理は非推奨項目につき非対応
	}
	return $return;
}
add_filter( 'lightning_is_layout_onecolumn', 'lightning_woo_is_layout_onecolumn' );

/**
 * 	サブサクション表示制御
 */
function lightning_woo_is_subsection_display( $return ){
	if ( lightning_woo_is_shop_page() ) {
		$lightning_design_setting = lightning_woo_get_design_setting();

		if ( isset( $lightning_design_setting['layout'] ) ) {
				
			if ( 'col-one-no-subsection' === $lightning_design_setting['layout'] ) {
				$return = false;
			} elseif ( 'col-two' === $lightning_design_setting['layout'] ) {
				$return = true;
			} elseif ( 'col-one' === $lightning_design_setting['layout'] ) {
				/* 1 column but subsection is exist */
				$return = true;
			}

		}
		// ※ページ属性のテンプレート指定処理は非推奨項目につき非対応
	}
	return $return;
}
add_filter( 'lightning_is_subsection_display', 'lightning_woo_is_subsection_display' );

/**
 * 	ページヘッダーとパンくずの表示制御
 */
function lightning_woo_is_page_header_and_breadcrumb( $return ){
	if ( lightning_woo_is_shop_page() ) {
		$lightning_design_setting = lightning_woo_get_design_setting();
		if ( ! empty( $lightning_design_setting['hidden_page_header_and_breadcrumb'] ) ) {
			$return = false;
		}
	}
	return $return;
}
add_filter( 'lightning_is_page_header_and_breadcrumb', 'lightning_woo_is_page_header_and_breadcrumb' );

/**
 * siteContent の上下余白
 */
function lightning_woo_is_siteContent_padding_off( $return ){
	if ( lightning_woo_is_shop_page() ) {
		$lightning_design_setting = lightning_woo_get_design_setting();
		if ( ! empty ( $lightning_design_setting['siteContent_padding'] ) ) {
			$return = true;
		}
	}
	return $return;
}
add_filter( 'lightning_is_siteContent_padding_off', 'lightning_woo_is_siteContent_padding_off' );

