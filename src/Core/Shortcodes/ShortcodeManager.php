<?php



namespace WPX\Karambit\Core\Shortcodes;

use WPX\Karambit\Manifest;

if ( ! defined( 'WPINC' ) ) { die; }
class ShortcodeManager {

    /** @var Shortcode[] List of registered shortcode objects */
    private static $registry = [];
    
    public function register_all() {

        // Scan the Shortcodes directory
        $dir = Manifest::path() . 'src/Shortcodes/';
        $files = glob( $dir . '*.php' );

        foreach ( $files as $file ) {
            $class_name = basename( $file, '.php' );
            $full_class = "\\WPX\\Karambit\\Shortcodes\\" . $class_name;

            if ( class_exists( $full_class ) ) {
                $instance = new $full_class();
                
                // Ensure it implements our Interface
                if ( $instance instanceof Shortcode ) {
                    add_shortcode( $instance->get_tag(), [ $instance, 'render' ] );

                    self::$registry[ $instance->get_tag() ] = $instance;
                }
            }
        }
    }

    /**
     * Returns the full list of shortcodes for UI/Documentation purposes.
     */
    public static function get_registered_shortcodes(): array {
        return self::$registry;
    }
}