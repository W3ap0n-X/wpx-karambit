<?php
namespace WPX\Karambit;

use WPX\Karambit\Manifest;


class Plugin extends Core\Plugin {
    private static $instance = null;
	public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function add_pages() {
		return [
			new \WPX\Karambit\Pages\SettingsPage( $this->hooks ),
			new \WPX\Karambit\Pages\BladeTest(  Manifest::PREFIX . '_settings' , $this->hooks ),
			new \WPX\Karambit\Pages\LogViewer(  Manifest::PREFIX . '_settings' , $this->hooks ),
		];
	}
	
	
	
}