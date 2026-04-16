<?php
namespace WPX\Karambit\Hooks;

use WPX\Karambit\Core\Hooks\Actions;

class AdminSetup implements Actions {

    public function get_actions(): array {
        return [
            // Hook Name => [ Method Name, Priority, Args ]
            'admin_menu' => [ 'register_settings_menu', 10, 1 ],
            'admin_init' => [ 'initialize_settings', 10, 1 ]
        ];
    }

    public function register_settings_menu() {
        \WPX\Karambit\Core\Debug::logDump('', __METHOD__);
        // add_menu_page logic goes here...
    }

    public function initialize_settings() {
        \WPX\Karambit\Core\Debug::logDump('', __METHOD__);
        // register_setting logic goes here...
    }
}