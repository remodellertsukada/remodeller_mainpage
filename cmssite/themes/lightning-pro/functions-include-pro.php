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