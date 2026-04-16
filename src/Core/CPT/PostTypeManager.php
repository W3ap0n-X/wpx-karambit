<?php 
namespace WPX\Karambit\Core\CPT;

use WPX\Karambit\Manifest;


class PostTypeManager {
    protected $post_types = [];

    public function register_all() {
        $dir = Manifest::path() . 'src/CPT/';
        if ( ! is_dir( $dir ) ) return;
        $files = glob( $dir . '*.php' );
        foreach ( $files as $file ) {
            // \WPX\Karambit\Core\Debug::logDump( $file, __METHOD__);
            $class_name = basename( $file, '.php' );
            $full_class = "\\WPX\\Karambit\\CPT\\" . $class_name;
            if (class_exists($full_class)) {
                $this->post_types[] = new $full_class();
                
                // $instance->register( $class_name );
            }
        }

        foreach ($this->post_types as $post_type) {
            # code...
            // \WPX\Karambit\Core\Debug::logDump( $post_type->get_args(), __METHOD__);
            $post_type->register( );
            foreach ($post_type->get_metaboxes() as $metabox) {
                $metabox->register();
            }
        }
    }
}