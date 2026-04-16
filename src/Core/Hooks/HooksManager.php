<?php
namespace WPX\Karambit\Core\Hooks;


// Prevent direct access to files
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use WPX\Karambit\Manifest;

class HooksManager {

    
    public function load() {
        $dir = Manifest::path() . 'src/Hooks/';
        if ( ! is_dir( $dir ) ) return;

        $files = glob( $dir . '*.php' );

        foreach ( $files as $file ) {
            $class_name = basename( $file, '.php' );
            $full_class = "\\WPX\\Karambit\\Hooks\\" . $class_name;
            
            if ( class_exists( $full_class ) ) {
                // \WPX\Karambit\Core\Debug::logDump( $full_class, __METHOD__ . ' $full_class');
                // Instantiate and hand off to your existing register logic
                $this->register( new $full_class() );
            }
        }
    }

    
    public function register( $object ) {
        if ( $object instanceof HookInterface ) {
            $this->mutate_user_hook( $object );
            return;
        }
        
        if ( $object instanceof Actions ) {
            $this->register_actions( $object );
        }

        if ( $object instanceof Filters ) {
            $this->register_filters( $object );
        }
    }

    private function mutate_user_hook( HookInterface $object ) {
        $priority      = self::default_value( $object->get_priority(), 10 );
        $accepted_args      = self::default_value( $object->get_args_count(), 10 );
        // \WPX\Karambit\Core\Debug::logDump( $object->get_hook(), __METHOD__ . ' $object->get_hook()');

        if( $object->is_filter() ) {
            // \WPX\Karambit\Core\Debug::logDump( $object->get_hook(), __METHOD__ . ' $object->get_hook()');
            add_filter(
                $object->get_hook(),
                $object->get_callback(),
                $priority,
                $accepted_args
            );
        }
        
        else {
            
            add_action(
                $object->get_hook(),
                $object->get_callback(),
                $priority,
                $accepted_args
            );
        }
    }

    
    private function register_actions( $object ) {
        // \WPX\Karambit\Core\Debug::logDump($object->get_actions(), __METHOD__);
        $actions = $object->get_actions();

        foreach ( $actions as $action_name => $action_details ) {

            
            // if ()

            $method        = $action_details[0];
            $priority      = self::default_value( !empty($action_details[1])?$action_details[1]:null, 10 );
            $accepted_args = self::default_value( !empty($action_details[1])?$action_details[2]:null, 1 );

            add_action(
                $action_name,
                array( $object, $method ),
                $priority,
                $accepted_args
            );
        }
    }

    private function register_action( $action_name, $action_details ) {

    }

    
    private function register_filters( $object ) {
        $filters = $object->get_filters();

        foreach ( $filters as $filter_name => $filter_details ) {
            $method        = $filter_details[0];
            $priority      = self::default_value( $filter_details[1], 10 );
            $accepted_args = self::default_value( $filter_details[2], 1 );

            add_filter(
                $filter_name,
                array( $object, $method ),
                $priority,
                $accepted_args
            );
        }
    }

	
    public static function default_value( $value, $default ) {
        if ( isset( $value ) ) {
            return $value;
        }

        if ( isset( $default ) ) {
            return $default;
        }

        return null;
    }

}