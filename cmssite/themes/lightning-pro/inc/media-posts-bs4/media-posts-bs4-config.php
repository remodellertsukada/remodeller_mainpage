<?php
/**
 * VK Media Posts BS4 Config
 *
 * @package VK Media Posts BS4
 */

if ( ! class_exists( 'VK_MEDIA_POSTS_BS4' ) ) {

	define( 'VK_MEDIA_POSTS_BS4_URL', get_template_directory_uri() . '/inc/media-posts-bs4/package/' );
	define( 'VK_MEDIA_POSTS_BS4_DIR', dirname( __FILE__ ) );
	define( 'VK_MEDIA_POSTS_BS4_VERSION', '1.0' );

	global $system_name;
	$system_name = lightning_get_theme_name();

	global $customize_section_name;
	if ( function_exists( 'lightning_get_prefix_customize_panel' ) ) {
		// 空のパネル名を設定出来るように最後に空白は入れない.
		$customize_section_name = lightning_get_prefix_customize_panel();
	} else {
		$customize_section_name = 'Lightning ';
	}

	require_once dirname( __FILE__ ) . '/package/class-vk-media-posts-bs4.php';

	/**
	 * Column size setting
	 *
	 * @param array $sizes size of using on media post bs4.
	 */
	function lightning_media_posts_bs4_sizes( $sizes ) {
		unset( $sizes['xxl'] );
		return $sizes;
	}
	add_filter( 'vk_media_post_bs4_size', 'lightning_media_posts_bs4_sizes' );

	/**
	 * Default Options
	 *
	 * @param array $default_options default options of using on media post bs4.
	 */
	function lightning_media_posts_bs4_default_options( $default_options ) {
		unset( $default_options['col_xxl'] );
		return $default_options;
	}
	add_filter( 'vk_media_posts_bs4_default_options', 'lightning_media_posts_bs4_default_options' );

	/**
	 * Default Options of Widget
	 *
	 * @param array $default_options default options of using on media post bs4 widget.
	 */
	function lightning_media_posts_bs4_widget_default_options( $default_options ) {
		unset( $default_options['col_xxl'] );
		return $default_options;
	}
	add_filter( 'vk_media_posts_bs4_widget_default_options', 'lightning_media_posts_bs4_widget_default_options' );

	/**
	 * Archive Loop change
	 * アーカイブループのレイアウトを改変するかどうかの判定
	 *
	 * @param array   $post_type post type.
	 * @param boolean $flag Change layout or not.
	 */
	function lightning_is_loop_layout_change_bs4_flag_bs4( $post_type = 'post', $flag = false ) {
		$vk_post_type_archive = get_option( 'vk_post_type_archive' );
		// 指定の投稿タイプアーカイブのレイアウトに値が存在する場合.
		if ( ! empty( $vk_post_type_archive[ $post_type ]['layout'] ) ) {
			// デフォルトじゃない場合.
			if ( 'default' !== $vk_post_type_archive[ $post_type ]['layout'] ) {
				$flag = true;
			}
		}
		return $flag;
	}

	/**
	 * アーカイブループを改変するかどうかの指定
	 *
	 * @param boolean $flag Change archive loop or not.
	 */
	function lightning_is_loop_layout_change_bs4( $flag ) {
		$post_type_info = lightning_get_post_type();
		$post_type      = $post_type_info['slug'];

		if ( is_author() ) {
			$post_type = 'author';
		}

		$flag = lightning_is_loop_layout_change_bs4_flag_bs4( $post_type, $flag );
		return $flag;
	}
	add_filter( 'is_lightning_extend_loop', 'lightning_is_loop_layout_change_bs4' );

	/**
	 * ループ改変実行
	 */
	function lightning_do_loop_layout_change_bs4() {

		$vk_post_type_archive = get_option( 'vk_post_type_archive' );

		$post_type      = lightning_get_post_type();
		$post_type_slug = $post_type['slug'];
		$post_type_slug = ( is_author() ) ? 'author' : $post_type['slug'];

		$flag = lightning_is_loop_layout_change_bs4_flag_bs4( $post_type_slug );
		if ( $flag ) {

			$customize_options = $vk_post_type_archive[ $post_type_slug ];
			// Get default option.
			$customize_options_default = VK_Media_Posts_BS4::options_default();
			// Markge options.
			$options = wp_parse_args( $customize_options, $customize_options_default );

			global $wp_query;

			/*
			Lightning Pro のみ
			過去の不具合などの影響で col_xxlが保存されている事があるため削除
			削除しておかないと xxl のカラム数がコントロールできない
			*/
			unset($options['col_xxl']);

			VK_Component_Posts::the_loop( $wp_query, $options );
		}
	}
	add_action( 'lightning_extend_loop', 'lightning_do_loop_layout_change_bs4' );

	/**
	 * アーカイブページレイアウト
	 *
	 * @param object $query WP_Query.
	 */
	function lightning_posts_per_page_custom_bs4( $query ) {

		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		// アーカイブの時以外は関係ないので return.
		if ( ! $query->is_archive() && ! $query->is_home() ) {
			return;
		}

		// アーカイブページの表示件数情報を取得.
		$vk_post_type_archive = get_option( 'vk_post_type_archive' );
		// Post Type
		$post_type_info = lightning_get_post_type();
		$post_type = $post_type_info['slug'];

		if ( $query->is_home() && ! $query->is_front_page() && ! empty( $vk_post_type_archive['post']['count'] ) ) {
			$query->set( 'posts_per_page', $vk_post_type_archive['post']['count'] );
		}

		// authhor archive.
		if ( $query->is_author() && ! empty( $vk_post_type_archive['author']['count'] ) ) {
			$query->set( 'posts_per_page', $vk_post_type_archive['author']['count'] );
		}

		if ( $query->is_archive() || $query->is_home() ) {

			$page_for_posts['post_top_id'] = get_option( 'page_for_posts' );

			// post_type_archive & is_date and other.
			if ( ! empty( $query->query_vars['post_type'] ) ) {
				if ( isset( $vk_post_type_archive[ $post_type ]['count'] ) ) {
					$query->set( 'posts_per_page', $vk_post_type_archive[ $post_type ]['count'] );
				}
			}

			if ( isset( $vk_post_type_archive[ $post_type ]['orderby'] ) ) {
				$query->set( 'orderby', $vk_post_type_archive[ $post_type ]['orderby'] );
			}
			if ( isset( $vk_post_type_archive[ $post_type ]['order'] ) ) {
				$query->set( 'order', $vk_post_type_archive[ $post_type ]['order'] );
			}

			// カスタム分類アーカイブ.
			if ( ! empty( $query->tax_query->queries ) ) {
				$taxonomy  = $query->tax_query->queries[0]['taxonomy'];
				$post_type = get_taxonomy( $taxonomy )->object_type[0];
				if ( ! empty( $vk_post_type_archive[ $post_type ]['count'] ) ) {
					$query->set( 'posts_per_page', $vk_post_type_archive[ $post_type ]['count'] );
				}
			}
		}

		return $query;

	}
	add_action( 'pre_get_posts', 'lightning_posts_per_page_custom_bs4' );

	// プリフィックス.
	global $vk_media_post_prefix;
	$vk_media_post_prefix = lightning_get_prefix();
}
