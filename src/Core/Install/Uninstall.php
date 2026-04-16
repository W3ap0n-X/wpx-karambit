<?php

namespace WPX\Karambit\Core\Install;

use WPX\Karambit\Manifest;

class Uninstall {

    /**
     * Fired when the "Delete" link is clicked in the WP Admin.
     */
    public static function cleanup() {
        \WPX\Karambit\Core\Debug::logDump('attempting uninstall', __METHOD__);
        // 1. Security check: Ensure WP is actually the one calling this
        if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
            exit;
        }

        // 2. Access the options (We use a standard delete_option here)

        // 3. Optional: Clean up any custom database tables or transients
        // delete_transient( Manifest::PREFIX . '_feed_cache' );
    }
}