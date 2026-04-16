<?php

namespace WPX\Karambit\Core\Options;

class OptionsManager {
    private $data = []; // Loaded from DB

    public function __construct() {
        $this->data = get_option( \WPX\Karambit\Manifest::PREFIX . '_settings', [] );
    }

    /**
     * The Public Window: Use this in your UI traces
     */
    public function get_section( $section_id ) {
        // Trace here to see if the UI is asking for the right key!
        // \WPX\Karambit\Core\Debug::logDump($section_id, __METHOD__ . ' :: Fetching Section');
        
        return $this->data[ $section_id ] ?? [];
    }
}