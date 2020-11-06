<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.5.2
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'lightning_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function lightning_register_required_plugins() {

	$is_vk_blocks_free = is_plugin_active( 'vk-blocks/vk-blocks.php' );
	$is_vk_blocks_pro  = is_plugin_active( 'vk-blocks-pro/vk-blocks.php' );
	$is_vkExUnit       = is_plugin_active( 'vk-all-in-one-expansion-unit/vkExUnit.php' );

	$plugins = array(
		array(
			'name'     => 'VK Blocks Pro', // The plugin name.
			'slug'     => 'vk-blocks-pro', // The plugin slug (typically the folder name).
			'required' => false, // If false, the plugin is only 'recommended' instead of required.
			'source'   => 'https://vws.vektor-inc.co.jp/wp-content/themes/lightning-pro-child-vws/updates/?action=download&slug=vk-blocks-pro',
		),
	);

	if ( !$is_vk_blocks_pro && !$is_vkExUnit ) {
		/*
		* Array of plugin arrays. Required keys are name and slug.
		* If the source is NOT from the .org repo, then source is also required.
		*/
		$plugins = array(

			// This is an example of how to include a plugin bundled with a theme.
			array(
				'name'     => 'VK All in One Expansion Unit (Free)', // The plugin name.
				'slug'     => 'vk-all-in-one-expansion-unit', // The plugin slug (typically the folder name).
				'required' => false, // If false, the plugin is only 'recommended' instead of required.
			),
			array(
				'name'     => 'VK Blocks Pro', // The plugin name.
				'slug'     => 'vk-blocks-pro', // The plugin slug (typically the folder name).
				'required' => false, // If false, the plugin is only 'recommended' instead of required.
				'source'   => 'https://vws.vektor-inc.co.jp/wp-content/themes/lightning-pro-child-vws/updates/?action=download&slug=vk-blocks-pro',
			),
		);
	}elseif (!$is_vk_blocks_pro && $is_vkExUnit || !$is_vk_blocks_pro && $is_vk_blocks_free || !$is_vk_blocks_pro ){

		$plugins = array(

			// This is an example of how to include a plugin bundled with a theme.
			array(
				'name'     => 'VK Blocks Pro', // The plugin name.
				'slug'     => 'vk-blocks-pro', // The plugin slug (typically the folder name).
				'required' => false, // If false, the plugin is only 'recommended' instead of required.
				'source'   => 'https://vws.vektor-inc.co.jp/wp-content/themes/lightning-pro-child-vws/updates/?action=download&slug=vk-blocks-pro',
			),
		);

	}elseif ($is_vk_blocks_pro && !$is_vkExUnit){
		$plugins = array(

			// This is an example of how to include a plugin bundled with a theme.
			array(
				'name'     => 'VK All in One Expansion Unit (Free)', // The plugin name.
				'slug'     => 'vk-all-in-one-expansion-unit', // The plugin slug (typically the folder name).
				'required' => false, // If false, the plugin is only 'recommended' instead of required.
			),
		);
	}

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'lightning-pro' ),
			'menu_title'                      => __( 'Install Plugins', 'lightning-pro' ),
			'installing'                      => __( 'Installing Plugin: %s', 'lightning-pro' ), // %s = plugin name.
			'oops'                            => __( 'Something went wrong with the plugin API.', 'lightning-pro' ),
			'notice_can_install_required'     => _n_noop(
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'lightning'
			), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop(
				'This theme recommends the following plugin: %1$s.<br>Many additional functions are available for free.',
				'This theme recommends the following plugins: %1$s.<br>Many additional functions are available for free.',
				'lightning'
			), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop(
				'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
				'lightning'
			), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop(
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'lightning'
			), // %1$s = plugin name(s).
			'notice_ask_to_update_maybe'      => _n_noop(
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'lightning'
			), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop(
				'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
				'lightning'
			), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop(
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'lightning'
			), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop(
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'lightning'
			), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop(
				'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
				'lightning'
			), // %1$s = plugin name(s).
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'lightning'
			),
			'update_link'                     => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'lightning'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'lightning'
			),
			'return'                          => __( 'Return to Required Plugins Installer', 'lightning-pro' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'lightning-pro' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'lightning-pro' ),
			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'lightning-pro' ),  // %1$s = plugin name(s).
			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'lightning-pro' ),  // %1$s = plugin name(s).
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'lightning-pro' ), // %s = dashboard link.
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'lightning-pro' ),

			'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		),
	);

	tgmpa( $plugins, $config );
}
