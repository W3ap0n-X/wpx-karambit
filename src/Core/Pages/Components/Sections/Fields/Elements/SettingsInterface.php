<?php
namespace WPX\Karambit\Core\Pages\Components\Sections\Fields\Elements;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

interface SettingsInterface {
    /**
     * Sanitize the given option value.
     *
     * @param string $option_value
     *
     * @return mixed
     */
    public function sanitize( $option_value );
}