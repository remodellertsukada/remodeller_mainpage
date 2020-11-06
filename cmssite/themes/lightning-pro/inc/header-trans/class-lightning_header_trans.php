<?php

class Lightning_Header_Trans {
	static function init() {
		add_action( 'customize_register', array( __CLASS__, 'resister_customize' ), 15, 1 );
		add_action( 'wp_head', array( __CLASS__, 'render_style' ) );
		// このタイミングで動かさないとカスタマイザーで反映されない
		add_action( 'wp', array( __CLASS__, 'self_disable' ) );
		add_filter( 'lightning_get_the_class_names', array( __CLASS__, 'class_filter' ), 10, 2 );
	}

	static function self_disable() {
		$current_skin = Lightning_Design_Manager::get_skins();
		if ( in_array( $current_skin, array( 'jpnstyle', 'jpnstyle-bs4', 'origin', 'variety', 'charm', 'fort', 'fort2', 'pale' ) ) ) {
			add_filter(
				'lightning_is_header_trans',
				function( $f ) {
					return false;
				}
			);
		}
	}

	/**
	 * convert html color code to rgb string
	 *
	 * @param   string $color   html color code (ex, '#abcde1'
	 * @return  string $color   rgb string (ex, '123, 42, 1'
	 */
	static function to_rgb( $color ) {
		if ( substr( $color, 0, 1 ) == '#' ) {
			$color = substr( $color, 1 );
		}
		$buf = array();
		if ( strlen( $color ) == 3 ) {
			$color = substr( $color, 0, 1 ) . substr( $color, 0, 1 )
				. substr( $color, 1, 1 ) . substr( $color, 1, 1 )
				. substr( $color, 2, 1 ) . substr( $color, 2, 1 );
		}
		for ( $i = 0; $i < 3; $i++ ) {
			$buf[ $i ] = substr( $color, $i * 2, 2 );
		}
		return implode( ',', array( hexdec( $buf[0] ), hexdec( $buf[1] ), hexdec( $buf[2] ) ) );
	}

	/**
	 * ヘッダー画像を書き換える
	 */
	static function rewrite_header_image( $url ) {
		$o = self::get_options();
		return $o['header_image'];
	}

	static function render_style() {
		$options = self::get_options();
		do_action( 'lightning_header_trans_render', $options );

		if ( ! apply_filters( 'lightning_header_trans_enable_default_render', true ) ) {
			return;
		}
		if ( ! self::is_header_trans() ) {
			return;
		}

		if ( $options['header_image'] ) {
			add_filter( 'lightning_head_logo_image_url', array( __CLASS__, 'rewrite_header_image' ) );
		}

		$head_rgba       = 'rgba(' . self::to_rgb( $options['background_color'] ) . ',' . $options['background_opacity'] . ')';
		$border_rgba     = 'rgba(' . self::to_rgb( $options['text_color'] ) . ',0.5)';
		$header_top_rgba = 'rgba(' . self::to_rgb( $options['background_color'] ) . ',' . $options['header_top_background_opacity'] . ')';

		$dynamic_css = '<style>';
		do_action( 'lightning_header_trans_pre_render_style', $options );

		$dynamic_css .= '.siteHeader-trans-true{position:absolute;}';
		$dynamic_css .= '.admin-bar .siteHeader-trans-true{margin-top:32px;}';
		$dynamic_css .= '@media screen and (max-width: 782px) {.admin-bar .siteHeader-trans-true{margin-top:46px;}}';

		$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true{position:absolute;top:0;background-color:' . $head_rgba . ';box-shadow:none;border-bottom:none;}';
		// $dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .headerTop{background:none;}';
		$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .headerTop_description,';
		$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .headerTop ul>li>a,';
		$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .headerTop ul>li>span,';
		$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .gMenu_name,';
		$dynamic_css .= "body:not(.header_scrolled) .siteHeader-trans-true .gMenu_description{color:{$options['text_color']};}";
		$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .gMenu_outer,';
		$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .gMenu > li{background:none;border:none;}';
		$dynamic_css .= "body:not(.header_scrolled) .siteHeader-trans-true .gMenu_outer .gMenu li{border-color:{$border_rgba};}";

		$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .headerTop { background-color:' . $header_top_rgba . ';border-bottom:none}';

		$dynamic_css     .= '@media (min-width: 768px) {';
			$dynamic_css .= 'body:not(.header_scrolled) .siteHeader-trans-true .gMenu > li:before { border-bottom:1px solid ' . $border_rgba . '; }';
			$dynamic_css .= '.gMenu>li.menu-item-has-children::after{ transition: all .5s ease-out; }';
		$dynamic_css     .= '}';

		// Touch device
		$mode = VK_Helpers::color_mode_check( $options['text_color'] );
		if ( $mode['mode'] === 'bright' ) {
			$icon_url = get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-acc-icon-open-white.svg';
		} else {
			$icon_url = get_template_directory_uri() . '/inc/vk-mobile-nav/package/images/vk-menu-acc-icon-open-black.svg';
		}

		$dynamic_css .= "body:not(.header_scrolled) .siteHeader-trans-true .gMenu > li > .acc-btn {
			border-color:{$options['text_color']};
			background-image:url(" . $icon_url . ');
		}';

		// 透過にした場合にjsを読み込んだ後で位置調整のスクリプトが走るため、あとからスライド上のテキストがガクっと落ちる。そのため、最初は文字をcssで透明にしている
		$dynamic_css .= '.slide-main .slide-text-set { opacity:0;transition: opacity 1s; }';

		/*
		ロゴが回り込みから中央になるタイプのスキンについて 中央にならないように補正
		/* --------------------------------------------------*/
		// Origin2 は lg サイズでロゴが中央でヘッダーがやたら高くなってしまうため
		// 透過の時は回り込みレイアウトに強制補正
		$skin            = get_option( 'lightning_design_skin' );
		$set_float_skins = array( 'origin2' );
		$set_float_skins = apply_filters( 'lightning_header_trans_float_adjust_skins', $set_float_skins );

		if ( in_array( $skin, $set_float_skins, true ) ) {
			$dynamic_css .= '
			@media( min-width:992px ) and ( max-width:1199.99px ){
			.siteHeader-trans-true .siteHeader_logo {
				float: left;
			}
			.siteHeader-trans-true .gMenu_outer {
				right: 0;
				float: right;
				width: auto;
				min-height: 4em;
				display: table;
			}
			.siteHeader-trans-true .gMenu {
				right: 0;
				margin: 0;
				float: right;
			}
			.siteHeader-trans-true .gMenu_outer nav {
				display: table-cell;
				vertical-align: middle;
			}
			.siteHeader-trans-true .siteHeader_logo {
				float: left;
				width:auto;
			}
			.header_scrolled .siteHeader-trans-true .gMenu_outer {
				width:100%;
				float:none;
				min-height: unset;
			}
			.header_scrolled .siteHeader-trans-true .gMenu_outer nav{
				display:block;
			}
			.header_scrolled .siteHeader-trans-true .gMenu{
				float: none;
			}
			}
			';
		}

		$dynamic_css .= '</style>';

				// delete before after space
				$dynamic_css = trim( $dynamic_css );
				// convert tab and br to space
				$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
				// Change multiple spaces to single space
				$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );
				// wp_add_inline_style( 'lightning-design-style', $dynamic_css );

		echo $dynamic_css;
		echo <<< EOL
<script type="text/javascript">;(function(w,d){
  var q=null,f=function(){
    var h=d.getElementsByClassName('siteHeader')[0].offsetHeight/2;
    Array.prototype.forEach.call(d.getElementsByClassName('slide-text-set'),function(v){
    // if(w.window.innerWidth<992){v.removeAttribute('style');return}
		v.style.top = 'calc(50% + '+h+'px)';
		v.style.opacity = 1;
    });
  };
  w.addEventListener('load',f,false);
  w.addEventListener('resize',function(){clearTimeout(q);q=setTimeout(f,300);},false);
})(window,document);</script>
EOL;
	}


	/*
		Customize
	/*-------------------------------------------*/

	static function resister_customize( $wp_customize ) {
		$default = self::option_default();

		// Add setting
		$wp_customize->add_setting(
			'ltg_trans_setting',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Custom_Html_Control(
				$wp_customize,
				'ltg_trans_setting',
				array(
					'label'            => __( 'Header Transmission', 'lightning-pro' ) . ' (Beta)',
					'section'          => 'lightning_header',
					'type'             => 'text',
					'custom_title_sub' => '',
					'custom_html'      => '',
					// 'priority'         => 700,
				)
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[enable]',
			array(
				'default'    => $default['enable'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[background_color]',
			array(
				'default'    => $default['background_color'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[background_opacity]',
			array(
				'default'    => $default['background_opacity'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[header_top_background_opacity]',
			array(
				'default'    => $default['header_top_background_opacity'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[text_color]',
			array(
				'default'    => $default['text_color'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'lightning_header_trans_options[header_image]',
			array(
				'default'    => $default['header_image'],
				'type'       => 'option',
				'capability' => 'edit_theme_options',
			)
		);

		global $vk_header_top_prefix;

		$wp_customize->add_section(
			'lightning_header',
			array(
				'title'    => $vk_header_top_prefix . __( 'Header settings', 'lightning-pro' ),
				'priority' => 511,
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'header_opacity',
				array(
					'label'    => __( 'Enable header transmission', 'lightning-pro' ),
					'section'  => 'lightning_header',
					'settings' => 'lightning_header_trans_options[enable]',
					'type'     => 'checkbox',
					'priority' => 300,
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'lightning_header_trans_options[background_color]',
				array(
					'label'    => __( 'Transmission mode Header Background Color', 'lightning-pro' ),
					'section'  => 'lightning_header',
					'settings' => 'lightning_header_trans_options[background_color]',
					'priority' => 300,
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'lightning_header_trans_options[background_opacity]',
				array(
					'label'       => __( 'Transmission mode Header Opacity', 'lightning-pro' ),
					'section'     => 'lightning_header',
					'settings'    => 'lightning_header_trans_options[background_opacity]',
					'type'        => 'range',
					'priority'    => 300,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'lightning_header_trans_options[header_top_background_opacity]',
				array(
					'label'       => __( 'Transmission mode Header Top Opacity', 'lightning-pro' ),
					'section'     => 'lightning_header',
					'settings'    => 'lightning_header_trans_options[header_top_background_opacity]',
					'type'        => 'range',
					'priority'    => 300,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'lightning_header_trans_options[text_color]',
				array(
					'label'    => __( 'Transmission mode Header Text Color', 'lightning-pro' ),
					'section'  => 'lightning_header',
					'settings' => 'lightning_header_trans_options[text_color]',
					'priority' => 300,
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'lightning_header_trans_options[header_image]',
				array(
					'label'       => __( 'Transmission mode Header Logo Image', 'lightning-pro' ),
					'section'     => 'lightning_header',
					'settings'    => 'lightning_header_trans_options[header_image]',
					'description' => __( 'Recommended image size : 280*60px', 'lightning-pro' ),
					'priority'    => 300,
				)
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'lightning_header_trans_options[header_image]',
			array(
				'selector'        => '.siteHeader_logo.siteHeader_logo-trans-true',
				'render_callback' => '',
			)
		);
	}

	static function option_default() {
		return array(
			'enable'                        => false,
			'background_color'              => '#ffffff',
			'background_opacity'            => 0.3,
			'header_top_background_opacity' => 0,
			'text_color'                    => '#333333',
			'header_image'                  => '',
		);
	}

	/**
	 * この機能に対するオプションの取得。未設定時はデフォルト値が返る
	 * get_option( 'lightning_header_trans_options',$default_array ) だけで処理すると、
	 * カスタマイザで一つの項目だけ変更して保存されると他の項目はデフォルト値で保存されず公開画面で Undefined になる。
	 * ちなみにカスタマイズ画面ではカスタマイザで指定したデフォルト値が入った状態で表示されるので Undefined にならずに気づきにくいので注意
	 * これを回避するために wp_parse_args() でデフォルト値の配列と結合してから返す
	 */
	static function get_options() {
		$options = get_option( 'lightning_header_trans_options' );
		$default = self::option_default();
		$o       = wp_parse_args( $options, $default );
		return $o;
	}

	static function is_enable_timing() {
		$return = false;
		if ( is_front_page() ) {
			$o = self::get_options();
			if ( ! empty( $o['enable'] ) ) {
				$return = true;
			}
		} elseif ( is_singular() ) {
			global $post;
			$meta = get_post_meta( $post->ID, '_lightning_design_setting', true );
			if ( ! empty( $meta['header_trans'] ) ) {
				$return = true;
			}
		}

		return $return;
	}

	static function is_header_trans() {
		return apply_filters( 'lightning_is_header_trans', self::is_enable_timing() );
	}

	/**
	 * lightning_get_the_class_namesに対するフィルターフック
	 * header_logo (.siteHeader_logo) に対する透過クラス名追加
	 */
	static function class_filter( $class_names, $position ) {
		// header_logo の場所のクラスをヘッダー透過が有効な時に書き換える
		if ( $position == 'header_logo' && self::is_header_trans() ) {
			$class_names[ $position ] .= ' siteHeader_logo-trans-true';
		}

		// headerかつ出力設定時にクラスを追加する
		if ( $position == 'header' && self::is_header_trans() ) {
			$class_names[ $position ] .= ' siteHeader-trans-true';
		}
		return $class_names;
	}
}
