<?php
/**
 * Lightning Header Top
 *
 * @package Lightning Pro
 */

if ( ! class_exists( 'Lightning_Header_Top' ) ) {
	/**
	 * Lightning Header Top
	 */
	class Lightning_Header_Top {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'resister_customize' ) );
			add_action( 'lightning_header_prepend', array( __CLASS__, 'header_top_prepend_item' ), 11 );
			add_action( 'after_setup_theme', array( __CLASS__, 'header_top_add_menu' ) );

			$options = get_option( 'lightning_theme_options' );
			add_action( 'wp_head', array( __CLASS__, 'render_style' ), 5 );

		}

		/**
		 * Default Option.
		 */
		public static function default_option() {
			$args = array(
				'header_top_hidden'                  => false,
				'header_top_hidden_menu_and_contact' => false,
				'header_top_contact_icon'            => '<i class="far fa-envelope"></i>',
				'header_top_contact_txt'             => '',
				'header_top_contact_url'             => '',
				'header_top_tel_icon'                => '<i class="fas fa-mobile-alt"></i>',
				'header_top_tel_number'              => '',
				'header_top_background_color'        => '',
				'header_top_text_color'              => '',
				'header_top_border_bottom_color'     => '',
			);
			return $args;
		}

		/**
		 * Color Setting Enale
		 */
		public static function is_color_setting_enable() {
			$header_trance_option = get_option( 'lightning_header_trans_options' );

			$plugin_path = 'lightning-header-color-manager/lightning-header-color-manager.php';
			// カラーマネージャーが有効化されている場合.
			if ( is_plugin_active( $plugin_path ) ) {
				// ... 別に上書き指定できてもよいので何も特別な処理しない.
			}

			// ヘッダー透過が機能が有効な設定＆ページの場合はヘッダー上部機能は無効化する.

			if ( class_exists( 'Lightning_Header_Trans' ) && Lightning_Header_Trans::is_header_trans() ) {
				return false;
			}

			return true;

		}

		/**
		 * Customizer.
		 *
		 * @param \WP_Customize_Manager $wp_customize Customizer.
		 */
		public static function resister_customize( $wp_customize ) {

			require_once 'class-custom-text-control-a.php';

			global $vk_header_top_prefix;

			// Add Section.
			$wp_customize->add_section(
				'lightning_header_top',
				array(
					'title'    => $vk_header_top_prefix . __( 'Header top settings', 'lightning-pro' ),
					'priority' => 510,
				)
			);

			// header_top_hidden.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_hidden]',
				array(
					'default'           => false,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					// 'transport'         => 'postMessage',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_checkbox' ),
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_hidden]',
				array(
					'label'    => __( 'Hide header top area', 'lightning-pro' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_hidden]',
					'type'     => 'checkbox',
				)
			);

			// header_top_hidden_menu_and_contact.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_hidden_menu_and_contact]',
				array(
					'default'           => false,
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					// 'transport'         => 'postMessage',
					'sanitize_callback' => array( 'VK_Helpers', 'sanitize_checkbox' ),
				)
			);
			$wp_customize->add_control(
				'lightning_header_top_options[header_top_hidden_menu_and_contact]',
				array(
					'label'    => __( 'Text align center and hide menu and contact button', 'lightning-pro' ),
					'section'  => 'lightning_header_top',
					'settings' => 'lightning_header_top_options[header_top_hidden_menu_and_contact]',
					'type'     => 'checkbox',
				)
			);

			// $wp_customize->selective_refresh->add_partial(
			// 'lightning_theme_options[header_top_hidden]', array(
			// 'selector'        => '.headerTop_description',
			// 'render_callback' => '',
			// )
			// );

			$icon_description_before  = __( 'To choose your favorite icon, and enter HTML.', 'lightning-pro' ) . '<br>';
			$icon_description_before .= '<strong>Font Awesome 5</strong><br>' . __( 'Ex ) ', 'lightning-pro' ) ;
			$icon_description_before .= '&lt;i class="';
			$icon_description_after = '"&gt;&lt;/i&gt;<br>';
			$icon_description_after .= '[ <a href="//fontawesome.com/icons?d=gallery&m=free" target="_blank">Icon list</a> ]';

			if ( apply_filters( 'header-top-contact', true ) ) {

				// header_top_contact_icon.
				$wp_customize->add_setting(
					'lightning_header_top_options[header_top_contact_icon]',
					array(
						'default'           => '<i class="far fa-envelope"></i>',
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						// 'transport'         => 'postMessage',
						'sanitize_callback' => 'wp_kses_post', // sake for use i tags.
					)
				);
				$wp_customize->add_control(
					new Custom_Text_Control(
						$wp_customize,
						'lightning_header_top_options[header_top_contact_icon]',
						array(
							'label'       => __( 'Contact button icon', 'lightning-pro' ),
							'section'     => 'lightning_header_top',
							'settings'    => 'lightning_header_top_options[header_top_contact_icon]',
							'type'        => 'text',
							'description' => $icon_description_before . 'far fa-envelope' . $icon_description_after,
						)
					)
				);

				// header_top_contact_txt.
				$wp_customize->add_setting(
					'lightning_header_top_options[header_top_contact_txt]',
					array(
						'default'           => '',
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						// 'transport'         => 'postMessage',
						'sanitize_callback' => 'wp_kses_post', // sake for use i tags.
					)
				);
				$wp_customize->add_control(
					'lightning_header_top_options[header_top_contact_txt]',
					array(
						'label'    => __( 'Contact button text', 'lightning-pro' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_contact_txt]',
						'type'     => 'text',
					)
				);

				// header_top_contact_url.
				$wp_customize->add_setting(
					'lightning_header_top_options[header_top_contact_url]',
					array(
						'default'           => '',
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						// 'transport'         => 'postMessage',
						'sanitize_callback' => 'esc_url_raw',
					)
				);
				$wp_customize->add_control(
					new Custom_Text_Control_A(
						$wp_customize,
						'lightning_header_top_options[header_top_contact_url]',
						array(
							'label'       => __( 'Contact button link url', 'lightning-pro' ),
							'section'     => 'lightning_header_top',
							'settings'    => 'lightning_header_top_options[header_top_contact_url]',
							'type'        => 'text',
							'description' => __( 'Ex : http:www.aaa.com/contact/', 'lightning-pro' ),
						)
					)
				);

				// header_top_contact_link_target.
				$wp_customize->add_setting(
					'lightning_header_top_options[header_top_contact_link_target]',
					array(
						'default'           => false,
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						'sanitize_callback' => array( 'VK_Helpers', 'sanitize_checkbox' ),
					)
				);
				$wp_customize->add_control(
					'lightning_header_top_options[header_top_contact_link_target]',
					array(
						'label'    => __( 'Open link target in new tab', 'lightning-pro' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_contact_link_target]',
						'type'     => 'checkbox',
					)
				);
			}

			if ( apply_filters( 'header-top-tel', true ) ) {

				// Header Top Tel Icon.
				$wp_customize->add_setting(
					'lightning_header_top_options[header_top_tel_icon]',
					array(
						'default'           => '<i class="fas fa-mobile-alt"></i>',
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						// 'transport'         => 'postMessage',
						'sanitize_callback' => 'wp_kses_post',
					)
				);
				$wp_customize->add_control(
					new Custom_Text_Control(
						$wp_customize,
						'lightning_header_top_options[header_top_tel_icon]',
						array(
							'label'       => __( 'Contact tel icon', 'lightning-pro' ),
							'section'     => 'lightning_header_top',
							'settings'    => 'lightning_header_top_options[header_top_tel_icon]',
							'type'        => 'text',
							'description' => $icon_description_before . 'fas fa-mobile-alt' . $icon_description_after,
						)
					)
				);

				// Header Top Tel Number.
				$wp_customize->add_setting(
					'lightning_header_top_options[header_top_tel_number]',
					array(
						'default'           => '',
						'type'              => 'option',
						'capability'        => 'edit_theme_options',
						// 'transport'         => 'postMessage',
						'sanitize_callback' => 'sanitize_text_field',
					)
				);
				$wp_customize->add_control(
					'lightning_header_top_options[header_top_tel_number]',
					array(
						'label'    => __( 'Contact tel number', 'lightning-pro' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_tel_number]',
						'type'     => 'text',
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'lightning_header_top_options[header_top_tel_number]',
					array(
						'selector'        => '.headerTop_tel_wrap',
						'render_callback' => '',
					)
				);
			}

			// Main Background Color.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_background_color]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'lightning_header_top_options[header_top_background_color]',
					array(
						'label'    => __( 'Background color', 'lightning-pro' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_background_color]',
					)
				)
			);

			// Main Text Color.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_text_color]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'lightning_header_top_options[header_top_text_color]',
					array(
						'label'    => __( 'Text color', 'lightning-pro' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_text_color]',
					)
				)
			);

			// Main Text Color.
			$wp_customize->add_setting(
				'lightning_header_top_options[header_top_border_bottom_color]',
				array(
					'default'           => '',
					'type'              => 'option',
					'capability'        => 'edit_theme_options',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'lightning_header_top_options[header_top_border_bottom_color]',
					array(
						'label'    => __( 'Border bottom color', 'lightning-pro' ),
						'section'  => 'lightning_header_top',
						'settings' => 'lightning_header_top_options[header_top_border_bottom_color]',
					)
				)
			);
		}

		/**
		 * Header Top Prepend Item.
		 */
		public static function header_top_prepend_item() {

			$options = get_option( 'lightning_header_top_options' );
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );

			$header_top_style = '';

			// ヘッダートップ非表示処理.
			if ( ! empty( $options['header_top_hidden'] ) ) {
				return;
			}

			$header_prepend  = '<div class="headerTop" id="headerTop"' . $header_top_style . '>';
			$header_prepend .= '<div class="container">';

			$is_hidden_menu_and_contact = false;
			if ( ! empty( $options['header_top_hidden_menu_and_contact'] ) ) {
				$is_hidden_menu_and_contact = true;
			}

			$text_center = '';
			if ( $is_hidden_menu_and_contact ) {
				$text_center = ' text-center';
			}

			$header_prepend .= '<p class="headerTop_description' . $text_center . '">' . get_bloginfo( 'description' ) . '</p>';

			if ( ! $is_hidden_menu_and_contact ) {
				if ( ! empty( $options['header_top_tel_number'] ) && $options['header_top_tel_number'] ) {
					$tel_number = mb_convert_kana( esc_attr( $options['header_top_tel_number'] ), 'n' );

					$tel_icon = $options['header_top_tel_icon'];

					/* ここで追加するHTMLは header-top-customizer.js でも修正する必要があるので注意 */
					$contact_tel = '';
					/* スキンによって使用しないものがある */
					if ( apply_filters( 'header-top-tel', true ) ) {

						if ( $tel_number ) {
							$contact_tel .= '<li class="headerTop_tel">';
							if ( wp_is_mobile() ) {
								$contact_tel .= '<a class="headerTop_tel_wrap" href="tel:' . $tel_number . '">' . $tel_icon . $tel_number . '</a>';
							} else {
								$contact_tel .= '<span class="headerTop_tel_wrap">' . $tel_icon . $tel_number . '</span>';
							}
							$contact_tel .= '</li>';
						}
					}
				} else {
					$contact_tel = '';
				}

				$args            = array(
					'theme_location' => 'header-top',
					'container'      => 'nav',
					'items_wrap'     => '<ul id="%1$s" class="%2$s nav">%3$s' . $contact_tel . '</ul>',
					'fallback_cb'    => '',
					'echo'           => false,
				);
				$header_top_menu = wp_nav_menu( $args );
				if ( $header_top_menu ) {
					$header_prepend .= apply_filters( 'Lightning_headerTop_menu', $header_top_menu );
				} elseif ( $contact_tel || is_customize_preview() ) {
					$header_prepend .= '<nav><ul id="%1$s" class="%2$s nav">' . $contact_tel . '</ul></nav>';
				}

				if ( apply_filters( 'header-top-contact', true ) ) {
					$header_prepend .= self::header_top_contact_btn();
				}
			} // if ( ! is_hidden_menu_and_contact( $options ) ) {

			$header_prepend .= '</div><!-- [ / .container ] -->';
			$header_prepend .= '</div><!-- [ / #headerTop  ] -->';
			echo $header_prepend;
		}

		/**
		 * Header Top Content Button
		 */
		public static function header_top_contact_btn() {

			$options = get_option( 'lightning_header_top_options' );
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );

			$contact_icon = $options['header_top_contact_icon'];

			$btn_txt = '';
			if ( ! empty( $options['header_top_contact_txt'] ) ) {
				$btn_txt = wp_kses_post( $options['header_top_contact_txt'] );
			}

			$link_url = '';
			if ( ! empty( $options['header_top_contact_url'] ) ) {
				$link_url = esc_url( $options['header_top_contact_url'] );
			}

			$link_target = '';
			if ( ! empty( $options['header_top_contact_link_target'] ) ) {
				$link_target = 'target="_blank"';
			}

			if ( ! empty( $btn_txt ) && $btn_txt && ! empty( $link_url ) && $link_url ) {

				$contact_btn_html = '<div class="headerTop_contactBtn"><a href="' . $link_url . '" class="btn btn-primary"' . $link_target . '>' . $contact_icon . $btn_txt . '</a></div>';
				return $contact_btn_html;
			}
		}

		/**
		 * Header Top Menu.
		 */
		public static function header_top_add_menu() {
			register_nav_menus( array( 'header-top' => 'Header Top Navigation' ) );
		}

		/**
		 * Render Style
		 */
		public static function render_style() {

			$options = get_option( 'lightning_header_top_options' );
			$default = self::default_option();
			$options = wp_parse_args( $options, $default );

			$dynamic_css = '';
			if ( true === self::is_color_setting_enable() ) {
				$text_color          = esc_html( $options['header_top_text_color'] );
				$bg_color            = esc_html( $options['header_top_background_color'] );
				$border_bottom_color = esc_html( $options['header_top_border_bottom_color'] );

				if ( $text_color || $bg_color || $border_bottom_color ) {
					$dynamic_css .= '.headerTop{';
					if ( $text_color ) {
						$dynamic_css .= 'color:' . $text_color . ';';
					}
					if ( $bg_color ) {
						$dynamic_css .= 'background-color:' . $bg_color . ';';
					}
					if ( $border_bottom_color ) {
						$dynamic_css .= 'border-bottom: 1px solid ' . $border_bottom_color . ';';
					}
					$dynamic_css .= '}';
				}

				if ( $text_color ) {
					$dynamic_css .= '.headerTop .nav li a{';
					$dynamic_css .= 'color:' . $text_color . ';';
					$dynamic_css .= '}';
				}

				if ( $dynamic_css ) {
					// delete before after space.
					$dynamic_css = trim( $dynamic_css );
					// convert tab and br to space.
					$dynamic_css = preg_replace( '/[\n\r\t]/', '', $dynamic_css );
					// Change multiple spaces to single space.
					$dynamic_css = preg_replace( '/\s(?=\s)/', '', $dynamic_css );

					wp_add_inline_style( 'lightning-design-style', $dynamic_css );

				}
			}
		}

	}
	$Lightning_header_top = new Lightning_Header_Top();

}
