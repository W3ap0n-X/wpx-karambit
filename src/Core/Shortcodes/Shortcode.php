<?php
namespace WPX\Karambit\Core\Shortcodes;
use WPX\Karambit\Manifest;
interface Shortcode {
    public function get_tag(): string;
    public function render( $atts, $content = null ): string;
    
    // New metadata for the "Library" page
    public function get_name(): string;
    public function get_description(): string;
    public function get_example(): string;
}