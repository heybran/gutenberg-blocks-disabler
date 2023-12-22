<?php

/**
 * Plugin Name:       Gutenberg Blocks Disabler
 * Plugin URI:        https://github.com/heybran/gutenberg-blocks-disabler
 * Description:       A WordPress plugin to allow website admin to disable core Gutenberg blocks.
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Brandon Zhang
 * Author URI:        https://github.com/heybran
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:           1.0.0
 * Text Domain:       Gutenberg Blocks Disabler
 */

namespace GutenbergBlocksDisabler;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'GBD_VERSION', '1.0.0' );
define( 'GBD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GBD_PLUGIN_SLUG', 'gutenberg_blocks_disabler' );
define( 'GBD_PLUGIN_DOMAIN', 'gutenberg-blocks-disabler' );
define( 'GBD_PLUGIN_NAME', 'Gutenberg Blocks Disabler' );

require_once GBD_PLUGIN_DIR . 'includes/class-adminpage.php';

AdminPage::register( get_option( GBD_PLUGIN_SLUG ) );

function allowed_block_types( $allowed_block_types, $block_editor_context ) {
	$gutenberg_blocks_disabler_allowed_block_types = get_option( GBD_PLUGIN_SLUG );

	if ( ! $gutenberg_blocks_disabler_allowed_block_types ) {
		return $allowed_block_types;
	}

	return array_values( $gutenberg_blocks_disabler_allowed_block_types );
}
add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\allowed_block_types', 10, 2 );
