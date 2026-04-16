<?php
namespace WPX\Karambit\Core;

class Activator {

    
    public static function activate() {
		\WPX\Karambit\Core\Debug::logDump('activating', __METHOD__);
		// update_option( 'rewrite_rules', '' );
    }

	
	public static function deactivate() {
		\WPX\Karambit\Core\Debug::logDump('deactivating', __METHOD__);
		// flush_rewrite_rules();
        // unregister_post_type( 'glave-post' );
	}

}