<?php

/**
 * Plugin Name:       Gutenberg Blocks Disabler
 * Plugin URI:        https://github.com/heybran/wordpress-breeze-menu
 * Description:       A WordPress plugin to allow website admin to disable core Gutenberg blocks.
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            Brandon Zhang
 * Author URI:        https://github.com/heybran
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:           1.0.0
 * Text Domain:       Gutenberg Blocks Disabler
 * Domain Path:       /languages
 */

namespace GutenbergBlocksDisabler;

// If this file is called directly, abort.
if ( ! defined('WPINC') ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'GBD_VERSION', '1.0.0' );
define( 'GBD_PLUGIN_DIR', plugin_dir_path(__FILE__) );
define( 'GBD_SLUG', 'gutenberg-blocks-disabler' );
define( 'GBD_PLUGIN_NAME', 'Gutenberg Blocks Disabler' );

require_once GBD_PLUGIN_DIR . 'includes/block-types.php';

function admin_menu() {
	add_submenu_page(
		'tools.php',
		GBD_PLUGIN_NAME,
		GBD_PLUGIN_NAME,
		'manage_options',
		GBD_SLUG,
		__NAMESPACE__ . '\submenu_page_callback' 
	);
}
add_action('admin_menu', __NAMESPACE__ . '\admin_menu');

function allowed_block_types ( $block_editor_context, $editor_context ) {
	if ( ! empty( $editor_context->post ) ) {
		return [
			GBD_ALL_BLOCK_TYPES[0]
		];
	}

	return $block_editor_context;
}
add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\allowed_block_types', 10, 2 );

function submenu_page_callback() {
	?>
	<div class="wrap">
		<div class="gbd-header">
			<h3><?php echo GBD_PLUGIN_NAME; ?></h3>
			<form action="POST" action="options.php">
			</form>
		</div>
	</div>
	<?php
}
