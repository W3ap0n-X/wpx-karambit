<?php

namespace WPX\Karambit\Shortcodes;
// use WPX\Karambit\Core\Debug;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Shortcodes\Shortcode;

class Debug implements Shortcode {

    private $prefix = Manifest::PREFIX . '_';

    private $atts = array(
        'plugin_info' => false,
        'label' => null,
        'message' => 'null',

    );

    public function get_tag(): string { return $this->prefix . 'debug'; }

    public function render( $atts, $content = null ): string {
        // This looks exactly like a normal WP callback
        $a = shortcode_atts( $this->atts, $atts );
        $output = '';
        if($a['plugin_info'] !== false){
            $output .= \WPX\Karambit\Core\Debug::easydump( Manifest::details(), 'Plugin Details',);
        }
        if(isset($a['message'])){
            $output .= \WPX\Karambit\Core\Debug::easydump( $a['message'], $a['label']);
        }
        return $output ;


    }

    public function get_name(): string {
        return "Debug";
    }
    public function get_description(): string {
        return "Testing";
    }
    public function get_example(): string {
        return "Testing";
    }
}