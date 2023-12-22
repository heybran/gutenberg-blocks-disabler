<?php

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clean up option created by plugin
delete_option( 'gutenberg_blocks_disabler' );
