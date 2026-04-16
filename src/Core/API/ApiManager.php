<?php
namespace WPX\Karambit\Core\API;

use WPX\Karambit\Manifest;
use WPX\Karambit\Core\API\Endpoint;
class ApiManager {
    public function register_endpoints() {
        // \WPX\Karambit\Core\Debug::logDump('registering routes', __METHOD__);
        $dir = Manifest::path() . 'src/API/';
        if ( ! is_dir( $dir ) ) return;

        $files = glob( $dir . '*.php' );
        foreach ( $files as $file ) {
            $class_name = basename( $file, '.php' );
            $full_class = "\\WPX\\Karambit\\API\\" . $class_name;

            if ( class_exists( $full_class ) ) {
                $endpoint = new $full_class();
                // \WPX\Karambit\Core\Debug::logDump('registering routes for: ' . $full_class, __METHOD__);
                if ( $endpoint instanceof Endpoint ) {
                    // \WPX\Karambit\Core\Debug::logDump(Manifest::PREFIX . '/v1' .  $endpoint->get_route(), __METHOD__);
                    register_rest_route( Manifest::PREFIX . '/v1', $endpoint->get_route(), [
                        'methods'             => $endpoint->get_methods(),
                        'callback'            => [ $endpoint, 'handle' ],
                        'args'                => $endpoint->get_args(),
                        'permission_callback' => $endpoint->get_permission_callback(),
                    ]);
                } else {
                    \WPX\Karambit\Core\Debug::logDump('ERROR: ' . $full_class, __METHOD__);
                }
            }
        }
    }
}