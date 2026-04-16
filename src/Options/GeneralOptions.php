<?php

namespace WPX\Karambit\Options;

class GeneralOptions extends \WPX\Karambit\Core\Options\OptionSection {
    
    public function get_name(): string {
        return 'general_options'; // Will become qckfe_general_options
    }

    public function get_title(): string {
        return 'General Settings';
    }

    public function get_description(): string {
        return 'Section Description';
    }

    public function get_schema(): array {
        return [
            new \WPX\Karambit\Core\Options\OptionEntry(
                key: 'debug',
                label: 'Debug Mode',
                type: 'checkbox',
                default: false
            ),
            // Nested Example: qckfe_general_options[api][key]
            new \WPX\Karambit\Core\Options\OptionEntry(
                key: 'key',
                label: 'API Key',
                type: 'text',
                path: ['api'] 
            ),
            new \WPX\Karambit\Core\Options\OptionEntry(
                key: '213',
                label: 'New Setting',
                type: 'text',
                path: ['example'] 
            ),
        ];
    }
}