<?php

$theme_opt = wp_get_theme( get_template() );

define( 'LIGHTNING_THEME_VERSION', $theme_opt->Version );
define( 'LIGHTNING_SHORT_NAME', 'LTG THEME' );
/*
  Theme setup
/*
  Load JS
/*
  Load CSS
/*
  Load Theme Customizer additions.
/*
  Load Custom template tags for this theme.
/*
  Load widgets
/*
  Load designskin manager
/*
  Load tga(Plugin install)
/*
  Load Front PR Blocks
/*
  WidgetArea initiate
/*
  Year Artchive list 'year' and count insert to inner </a>
/*
  Category list 'count insert to inner </a>
/*
  Global navigation add cptions
/*
  headfix enable
/*
  Tag Cloud _ Change font size
/*
  HOME _ Default content hidden
/*
  Move jQuery to footer
/*
  disable_tgm_notification_except_admin
/*
  Add defer first aid
/*-------------------------------------------*/


/*
  Theme setup
/*-------------------------------------------*/
add_action( 'after_setup_theme', 'lightning_theme_setup' );
function lightning_theme_setup() {

	global $content_width;

	/*
	  Title tag
	/*-------------------------------------------*/
	add_theme_support( 'title-tag' );

	/*
	  editor-styles
	/*-------------------------------------------*/
	add_theme_support( 'editor-styles' );

	// When this support that printed front css and it's overwrite skin table style and so on
	// add_theme_support( 'wp-block-styles' );

	add_theme_support( 'align-wide' );

	/*
	  custom-background
	/*-------------------------------------------*/
	add_theme_support( 'custom-background' );

	// Block Editor line height @since WordPress 5.5
	add_theme_support( 'custom-line-height' );
	// Block Editor custom unit @since WordPress 5.5
	add_theme_support( 'custom-units', 'px', 'em', 'rem', 'vw', 'vh' );

	/*
	  cope with page excerpt
	/*-------------------------------------------*/
	add_post_type_support( 'page', 'excerpt' );

	/*
	  Admin page _ Eye catch
	/*-------------------------------------------*/
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 320, 180, true );

	/*
	  Custom menu
	/*-------------------------------------------*/
	register_nav_menus( array( 'Header' => 'Header Navigation' ) );
	register_nav_menus( array( 'Footer' => 'Footer Navigation' ) );

	load_theme_textdomain( 'lightning-pro', get_template_directory() . '/languages' );

	/*
	  Set content width
	/* 	(Auto set up to media max with.)
	/*-------------------------------------------*/
	if ( ! isset( $content_width ) ) {
		$content_width = 1140;
	}

	/*
	  Add theme support for selective refresh for widgets.
	/*-------------------------------------------*/
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	  Feed Links
	/*-------------------------------------------*/
	add_theme_support( 'automatic-feed-links' );

	/*
	  WooCommerce
	/*-------------------------------------------*/
	add_theme_support( 'woocommerce' );

	/*
	  Option init
	/*-------------------------------------------*/
	/*
	Save default option first time.
	When only customize default that, Can't save default value.
	*/
	$theme_options_default = lightning_theme_options_default();
	if ( ! get_option( 'lightning_theme_options' ) ) {
		add_option( 'lightning_theme_options', $theme_options_default );
		$lightning_theme_options = $theme_options_default;
	}

}

/*
  Load JS
/*-------------------------------------------*/

add_action( 'wp_enqueue_scripts', 'lightning_addJs' );
function lightning_addJs() {
	wp_register_script( 'lightning-js', get_template_directory_uri() . '/assets/js/lightning.min.js', array(), LIGHTNING_THEME_VERSION, true );
	wp_localize_script( 'lightning-js', 'lightningOpt', apply_filters( 'lightning_localize_options', array() ) );
	// jsのjQuery依存はもう無いが、一応追加しておく
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'lightning-js' );
}

add_action( 'wp_enqueue_scripts', 'lightning_commentJs' );
function lightning_commentJs() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/*
  Load CSS
/*-------------------------------------------*/
add_action( 'after_setup_theme', 'lightning_load_css_action' );
function lightning_load_css_action() {
	add_action( 'wp_enqueue_scripts', 'lightning_common_style' );
	add_action( 'wp_enqueue_scripts', 'lightning_theme_style' );
}

function lightning_common_style() {
	wp_enqueue_style( 'lightning-common-style', get_template_directory_uri() . '/assets/css/common.css', array(), LIGHTNING_THEME_VERSION );
}
function lightning_theme_style() {
	wp_enqueue_style( 'lightning-theme-style', get_stylesheet_uri(), array(), LIGHTNING_THEME_VERSION );
}

/*
  Load Editor CSS
/*-------------------------------------------*/
add_action( 'after_setup_theme', 'lightning_load_common_editor_css' );
function lightning_load_common_editor_css() {
	/*
	 Notice : Use url then if you use local environment https has error that bring to get css error and don't refrected */
	/* Notice : add_editor_style() is only one args. */
	add_editor_style( 'assets/css/common_editor.css' );
}

/*
Already add_editor_style() is used but reload css by wp_enqueue_style() reason is
use to wp_add_inline_style()
*/
add_action( 'enqueue_block_editor_assets', 'lightning_load_common_editor_css_to_gutenberg' );
function lightning_load_common_editor_css_to_gutenberg() {

	wp_enqueue_style(
		'lightning-common-editor-gutenberg',
		// If not full path that can't load in editor screen
		get_template_directory_uri() . '/assets/css/common_editor.css',
		array( 'wp-edit-blocks' ),
		LIGHTNING_THEME_VERSION
	);
}


require get_parent_theme_file_path( '/functions-compatible.php' );


/*
  Load tga(Plugin install)
/*-------------------------------------------*/
require get_parent_theme_file_path( '/inc/tgm-plugin-activation/tgm-config.php' );

/*
  Load Theme Customizer additions.
/*-------------------------------------------*/
require get_parent_theme_file_path( '/inc/customize/customize.php' );
require get_parent_theme_file_path( '/inc/customize/customize-design.php' );
require get_parent_theme_file_path( '/inc/customize/customize-top-slide.php' );
require get_parent_theme_file_path( '/inc/customize/customize-functions.php' );

/*
  Load allow customize modules
/*-------------------------------------------*/
get_template_part( 'inc/vk-mobile-nav/vk-mobile-nav-config' );

/*
  Load Custom template tags for this theme.
/*-------------------------------------------*/
require get_parent_theme_file_path( '/inc/template-tags.php' );
require get_parent_theme_file_path( '/inc/template-tags-old.php' );
require get_parent_theme_file_path( '/inc/class-vk-helpers.php' );

/*
  Load modules
/*-------------------------------------------*/
require get_parent_theme_file_path( '/inc/package-manager.php' );
require get_parent_theme_file_path( '/inc/class-design-manager.php' );
require get_parent_theme_file_path( '/inc/font-awesome/font-awesome-config.php' );
require get_parent_theme_file_path( '/inc/term-color/term-color-config.php' );
require get_parent_theme_file_path( '/inc/vk-components/vk-components-config.php' );
require get_parent_theme_file_path( '/inc/template-redirect.php' );
require get_parent_theme_file_path( '/inc/layout-controller/layout-controller.php' );
require get_parent_theme_file_path( '/inc/vk-footer-customize/vk-footer-customize-config.php' );
require get_parent_theme_file_path( '/inc/vk-old-options-notice/vk-old-options-notice-config.php' );
require get_parent_theme_file_path( '/inc/vk-css-optimize/vk-css-optimize-config.php' );


/*
  Plugin support
/*-------------------------------------------*/
// Load woocommerce modules
if ( class_exists( 'woocommerce' ) ) {
	require get_parent_theme_file_path( '/plugin-support/woocommerce/functions-woo.php' );
}
// Load polylang modules
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'polylang/polylang.php' ) ) {
	require get_parent_theme_file_path( '/plugin-support/polylang/functions-polylang.php' );
}
if ( is_plugin_active( 'bbpress/bbpress.php' ) ) {
	require get_parent_theme_file_path( '/plugin-support/bbpress/functions-bbpress.php' );
}

/*
  WidgetArea initiate
/*-------------------------------------------*/
if ( ! function_exists( 'lightning_widgets_init' ) ) {
	function lightning_widgets_init() {
		// sidebar widget area
		register_sidebar(
			array(
				'name'          => __( 'Sidebar(Home)', 'lightning-pro' ),
				'id'            => 'front-side-top-widget-area',
				'before_widget' => '<aside class="widget %2$s" id="%1$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h1 class="widget-title subSection-title">',
				'after_title'   => '</h1>',
			)
		);
			register_sidebar(
				array(
					'name'          => __( 'Sidebar(Common top)', 'lightning-pro' ),
					'id'            => 'common-side-top-widget-area',
					'before_widget' => '<aside class="widget %2$s" id="%1$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h1 class="widget-title subSection-title">',
					'after_title'   => '</h1>',
				)
			);
			register_sidebar(
				array(
					'name'          => __( 'Sidebar(Common bottom)', 'lightning-pro' ),
					'id'            => 'common-side-bottom-widget-area',
					'before_widget' => '<aside class="widget %2$s" id="%1$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h1 class="widget-title subSection-title">',
					'after_title'   => '</h1>',
				)
			);

		// Sidebar( post_type )

		$postTypes = get_post_types( array( 'public' => true ) );

		foreach ( $postTypes as $postType ) {

			// Get post type name
			/*-------------------------------------------*/
			$post_type_object = get_post_type_object( $postType );
			if ( $post_type_object ) {
				// Set post type name
				$postType_name = esc_html( $post_type_object->labels->name );

				$sidebar_description = '';
				if ( $postType == 'post' ) {

					$sidebar_description = __( 'This widget area appears on the Posts page only. If you do not set any widgets in this area, this theme sets the following widgets "Recent posts", "Category", and "Archive" by default. These default widgets will be hidden, when you set any widgets. <br><br> If you installed our plugin VK All in One Expansion Unit (Free), you can use the following widgets, "VK_Recent posts",  "VK_Categories", and  "VK_archive list".', 'lightning-pro' );

				} elseif ( $postType == 'page' ) {

					$sidebar_description = __( 'This widget area appears on the Pages page only. If you do not set any widgets in this area, this theme sets the "Child pages list widget" by default. This default widget will be hidden, when you set any widgets. <br><br> If you installed our plugin VK All in One Expansion Unit (Free), you can use the "VK_ child page list" widget for the alternative.', 'lightning-pro' );

				} elseif ( $postType == 'attachment' ) {

					$sidebar_description = __( 'This widget area appears on the Media page only.', 'lightning-pro' );

				} else {

					$sidebar_description = sprintf( __( 'This widget area appears on the %s contents page only.', 'lightning-pro' ), $postType_name );

				}

				// Set post type widget area
				register_sidebar(
					array(
						'name'          => sprintf( __( 'Sidebar(%s)', 'lightning-pro' ), $postType_name ),
						'id'            => $postType . '-side-widget-area',
						'description'   => $sidebar_description,
						'before_widget' => '<aside class="widget %2$s" id="%1$s">',
						'after_widget'  => '</aside>',
						'before_title'  => '<h1 class="widget-title subSection-title">',
						'after_title'   => '</h1>',
					)
				);
			} // if($post_type_object){

		} // foreach ($postTypes as $postType) {

		// Home content top widget area

			register_sidebar(
				array(
					'name'          => __( 'Home content top', 'lightning-pro' ),
					'id'            => 'home-content-top-widget-area',
					'before_widget' => '<div class="widget %2$s" id="%1$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="mainSection-title">',
					'after_title'   => '</h2>',
				)
			);

		// footer upper widget area

			register_sidebar(
				array(
					'name'          => __( 'Widget area of upper footer', 'lightning-pro' ),
					'id'            => 'footer-upper-widget-1',
					'before_widget' => '<aside class="widget %2$s" id="%1$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h1 class="widget-title subSection-title">',
					'after_title'   => '</h1>',
				)
			);

		// footer widget area

			$footer_widget_area_count = 3;
			$footer_widget_area_count = apply_filters( 'lightning_footer_widget_area_count', $footer_widget_area_count );

		for ( $i = 1; $i <= $footer_widget_area_count; ) {
			register_sidebar(
				array(
					'name'          => __( 'Footer widget area', 'lightning-pro' ) . ' ' . $i,
					'id'            => 'footer-widget-' . $i,
					'before_widget' => '<aside class="widget %2$s" id="%1$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h1 class="widget-title subSection-title">',
					'after_title'   => '</h1>',
				)
			);
			$i++;
		}

		// LP widget area

			$args  = array(
				'post_type'      => 'page',
				'post_status'    => 'publish,private,draft',
				'posts_per_page' => -1,
				'meta_key'       => '_wp_page_template',
				'meta_value'     => 'page-lp.php',
			);
			$posts = get_posts( $args );

			if ( $posts ) {
				foreach ( $posts as $key => $post ) {
					register_sidebar(
						array(
							/* Translators: %s: LP title */
							'name'          => sprintf( __( 'LP widget "%s"', 'lightning-pro' ), esc_html( $post->post_title ) ),
							'id'            => 'lp-widget-' . $post->ID,
							'before_widget' => '<div class="widget %2$s" id="%1$s">',
							'after_widget'  => '</div>',
							'before_title'  => '<h2 class="mainSection-title">',
							'after_title'   => '</h2>',
						)
					);
				}
			}
			wp_reset_postdata();
	}
} // if ( ! function_exists( 'lightning_widgets_init' ) ) {
add_action( 'widgets_init', 'lightning_widgets_init' );

/*
  Year Artchive list 'year' and count insert to inner </a>
/*-------------------------------------------*/
function lightning_archives_link( $html ) {
	return preg_replace( '@</a>(.+?)</li>@', '\1</a></li>', $html );
}
add_filter( 'get_archives_link', 'lightning_archives_link' );

/*
  Category list count insert to inner </a>
/*-------------------------------------------*/
function lightning_list_categories( $output, $args ) {
	$output = preg_replace( '/<\/a>\s*\((\d+)\)/', ' ($1)</a>', $output );
	return $output;
}
add_filter( 'wp_list_categories', 'lightning_list_categories', 10, 2 );

/*
  Global navigation add cptions
/*-------------------------------------------*/
class description_walker extends Walker_Nav_Menu {
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		$output     .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		$prepend     = '<strong class="gMenu_name">';
		$append      = '</strong>';
		$description = ! empty( $item->description ) ? '<span class="gMenu_description">' . esc_attr( $item->description ) . '</span>' : '';

		if ( $depth != 0 ) {
			$description = $append = $prepend = '';
		}

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $prepend . apply_filters( 'the_title', $item->title, $item->ID ) . $append;
		$item_output .= $description . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/*
  headfix enable
/*-------------------------------------------*/
add_filter( 'body_class', 'lightning_body_class' );
function lightning_body_class( $class ) {
	// header fix
	if ( apply_filters( 'lightning_headfix_enable', true ) ) {
		$class[] = 'headfix';
	}
	// header height changer
	if ( apply_filters( 'lightning_header_height_changer_enable', true ) ) {
		$class[] = 'header_height_changer';
	}
	return $class;
}

// lightning headfix disabel sample
/*
add_filter( 'lightning_headfix_enable', 'lightning_headfix_disabel');
function lightning_headfix_disabel(){
	return false;
}
*/

// lightning header height changer disabel sample
/*
add_filter( 'lightning_header_height_changer_enable', 'lightning_header_height_changer_disabel');
function lightning_header_height_changer_disabel(){
	return false;
}
*/

/*
  Tag Cloud _ Change font size
/*-------------------------------------------*/
function lightning_tag_cloud_filter( $args ) {
	$args['smallest'] = 10;
	$args['largest']  = 10;
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'lightning_tag_cloud_filter' );

/*
  HOME _ Default content hidden
/*-------------------------------------------*/
add_filter( 'is_lightning_home_content_display', 'lightning_home_content_hidden' );
function lightning_home_content_hidden( $flag ) {
	global $lightning_theme_options;
	if ( isset( $lightning_theme_options['top_default_content_hidden'] ) && $lightning_theme_options['top_default_content_hidden'] ) {
		$flag = false;
	}
	return $flag;
}

/*
  disable_tgm_notification_except_admin
/*-------------------------------------------*/
add_action( 'admin_head', 'lightning_disable_tgm_notification_except_admin' );
function lightning_disable_tgm_notification_except_admin() {
	if ( ! current_user_can( 'administrator' ) ) {
		$allowed_html = array(
			'style' => array( 'type' => array() ),
		);
		$text         = '<style>#setting-error-tgmpa { display:none; }</style>';
		echo wp_kses( $text, $allowed_html );
	}
}

/*
  Add defer first aid
// function lightning_add_defer_to_scripts( $tag, $handle ) {
// if ( ! preg_match( '/\b(async|defer)\b/', $tag ) ) {
// return str_replace( ' src', ' defer src', $tag );
// }
// return $tag;
// }
//
// if ( ! is_admin() ) {
// add_filter( 'script_loader_tag', 'lightning_add_defer_to_scripts', 10, 2 );
// }

*/


/*
  embed card
/*-------------------------------------------*/

remove_action( 'embed_footer', 'print_embed_sharing_dialog' );

function lightning_embed_styles() {
	wp_enqueue_style( 'wp-oembed-embed', get_template_directory_uri() . '/assets/css/wp-embed.css' );
}
add_action( 'embed_head', 'lightning_embed_styles' );
require get_parent_theme_file_path( './inc/vk-page-header/vk-page-header-config.php' );
require get_parent_theme_file_path( './inc/custom-field-builder/custom-field-builder-config.php' );
require get_parent_theme_file_path( './inc/vk-font-selector/vk-font-selector-config.php' );
require get_parent_theme_file_path( './inc/vk-mobile-fix-nav/vk-mobile-fix-nav-config.php' );
require get_parent_theme_file_path( './inc/copyright-customizer/copyright-customizer-config.php' );
require get_parent_theme_file_path( './inc/vk-google-tag-manager/vk-google-tag-manager-config.php' );
require get_parent_theme_file_path( './inc/vk-campaign-text/vk-campaign-text-config.php' );
require get_parent_theme_file_path( './inc/header-top/header-top-config.php' );
require get_parent_theme_file_path( './inc/headding-design/headding-design-config.php' );
require get_parent_theme_file_path( './inc/base-controller/base-controller.php' );
require get_parent_theme_file_path( './inc/header-trans/header-trans.php' );
require get_parent_theme_file_path( './inc/vk-footer-customize/vk-footer-customize-config.php' );
require get_parent_theme_file_path( './inc/vk-footer-style/vk-footer-style-config.php' );

$skin = Lightning_Design_Manager::get_current_skin();
if ( ! empty( $skin['bootstrap'] ) && $skin['bootstrap'] == 'bs4' ) {
	require get_parent_theme_file_path( './inc/media-posts-bs4/media-posts-bs4-config.php' );
}

/*-------------------------------------------*/
/*	Deactive Lightning Origin Pro
/*-------------------------------------------*/
add_action( 'init', 'lightning_deactive_origin_pro' );
function lightning_deactive_origin_pro() {
	$plugin_path = 'lightning-origin-pro/lightning_origin_pro.php';
	if ( function_exists( 'lightning_deactivate_plugin' ) ) {
		lightning_deactivate_plugin( $plugin_path );
	}
}

/*-------------------------------------------*/
/*  Move jQuery to footer
/*-------------------------------------------*/
// プラグインの js の　enque で jQuery を入れてないものがあり動かなかったりするので一旦停止
// add_action( 'init', 'lightning_move_jquery_to_footer' );
function lightning_move_jquery_to_footer() {
	if ( is_admin() || lightning_is_login_page() ) {
		return;
	}

	global $wp_scripts;
	$jquery     = $wp_scripts->registered['jquery-core'];
	$jquery_ver = $jquery->ver;
	$jquery_src = $jquery->src;

	wp_deregister_script( 'jquery' );
	wp_deregister_script( 'jquery-core' );

	wp_register_script( 'jquery', false, [ 'jquery-core' ], $jquery_ver, true );
	wp_register_script( 'jquery-core', $jquery_src, [], $jquery_ver, true );
}

function lightning_is_login_page() {
	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

/*-------------------------------------------*/
/*	Load updater
/*-------------------------------------------*/
require 'inc/plugin-update-checker/plugin-update-checker-config.php';
$checker = Puc_v4_Factory::buildUpdateChecker(
	'https://vws.vektor-inc.co.jp/updates/?action=get_metadata&slug=lightning-pro',
	__FILE__,
	$license_slug
);
$checker->addQueryArgFilter( 'wsh_filter_update_checks' );

if ( is_admin() && current_user_can( 'edit_theme_options' ) ) {
	$network_runnning_pro = false;

	if ( is_multisite() ) {
		$network_options = get_site_option( 'active_sitewide_plugins', array() );
		if ( isset( $network_options['lightning-original-brand-unit/lightning-original-brand-unit.php'] ) ) {
			$network_runnning_pro = true;
		}
	}

	$opt = get_option( 'active_plugins', array() );
	if ( !$network_runnning_pro && !in_array( 'lightning-original-brand-unit/lightning-original-brand-unit.php', $opt) ) {

		$state = $checker->getUpdateState();
		$update = $state->getUpdate();

		if ( empty( get_option( 'lightning-pro-license-key', false ) ) ) {
			add_action( 'admin_notices', function(){
				echo '<div class="error"><p>';
				echo __( 'License Key has no registerd.', 'lightning-pro' );
				echo __( 'You need register License Key at Themes > Customizer > Lightning License Key.', 'lightning-pro');
				echo '</p></div>';
			});
		} elseif (
				!empty( $update )
				&& version_compare($update->version, LIGHTNING_THEME_VERSION, '>')
				&& empty($update->download_url)
			) {

			add_action( 'admin_notices', function(){
				echo '<div class="error"><p>';
				echo __( 'Your Lightning Pro license key is expired.', 'lightning-pro' );
				echo __( 'If you need update. get <a href="https://vws.vektor-inc.co.jp/product/lightning-pro-update-license" target="_blank">Update License</a>.', 'lightning-pro');
				echo '</p></div>';
			});

			add_filter( 'wp_prepare_themes_for_js', function( $themes ) {
				foreach ( $themes as $key => $v ) {
					if ( $v['name'] == 'Lightning Pro' ) {
						$themes[ $key ][ 'update' ] .= '<p><strong>';
						$themes[ $key ][ 'update' ] .= __( 'Can\'t update Theme becouse Your Lightning Pro license key is expired.', 'lightning-pro' );
						$themes[ $key ][ 'update' ] .= ' ' . __( 'If you need update. get <a href="https://vws.vektor-inc.co.jp/product/lightning-pro-update-license" target="_blank">Update License</a>.', 'lightning-pro');
						$themes[ $key ][ 'update' ] .= '</strong></p>';
						break;
					}
				}
				return $themes;
			}, 10, 1 );
		}
	}
}

/*-------------------------------------------*/
/*	Old funnction packages
/*-------------------------------------------*/
add_filter( 'lightning_old_packages_array', 'lightning_old_packages_array_custom' );
function lightning_old_packages_array_custom( $packages ) {
	$packages_pro = array(
		'widget_pr_content' => array(
			'label'       => __( 'PR Content Widget', 'lightning-pro' ),
			'description' => __( 'You can use the same function by Outer Block and PR Content Block in Plugin VK Blocks.', 'lightning-pro' ),
			'path'        => get_parent_theme_file_path( '/inc/vk-widget-pr-content/vk-widget-pr-content-config.php' ),
		),
		'media_posts'       => array(
			'label'       => __( 'Media Posts', 'lightning-pro' ),
			'description' => __( 'You can use the same function by Archive Page Setting at customize screen and Media Posts BS4 Widget.', 'lightning-pro' ),
			'path'        => get_parent_theme_file_path( '/inc/media-posts/media-posts-config.php' ),
		),
	);
	$packages     = wp_parse_args( $packages, $packages_pro );
	return $packages;
}

/*-------------------------------------------*/
/*	モバイルでのページトップ処理
/*-------------------------------------------*/
add_action( 'init', 'lightning_pagetop_compatible' );
function lightning_pagetop_compatible(){
	// モバイル固定ナビが表示されている状態
	if ( class_exists( 'Vk_Mobile_Fix_Nav' ) && Vk_Mobile_Fix_Nav::is_fix_nav_enable() ) {
		$option = get_option( 'vkExUnit_pagetop' );
		// ページトップに戻るボタン機能で 非表示の設定値が存在しない場合
		if( ! isset( $option['hide_mobile'] ) ){
			if ( ! is_array( $option ) ) {
				$option = array();
			}
			// ベージトップに戻るの非表示をtrueにして保存
			$option['hide_mobile'] = true;
			update_option( 'vkExUnit_pagetop', $option );
		}
	}
}