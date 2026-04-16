<?php
namespace WPX\Karambit\Core\Pages\Components\Sections\Fields\Elements;

use WPX\Karambit\Core\Pages\Components\Interfaces\HTML;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Custom extends Element {

    
    private $html = null;

    
    public function render() {
        $content = ! empty( $this->html ) ? $this->html : '';
        $html = <<<HTML
            <div class="custom-element">
                {$content}

            </div>
        HTML;
        return $html;
    }

    public function set_html($html) {
        // \WPX\Karambit\Core\Debug::logDump( $this->value, __METHOD__ . ' $this->value');
        // \WPX\Karambit\Core\Debug::logDump( $this->get_value(), __METHOD__ . ' $this->get_value()');
        if ( $html instanceof HTML ) {
            $this->html = $html->get_html( $this->value );
        } else {
            \WPX\Karambit\Core\Debug::logDump( $html, __METHOD__ . ' HTML is not valid');
        }

    }

    
    public function __construct( $section_id, $properties = array() ) {
        parent::__construct( $section_id, $properties );
        $this->value = $properties['value'];
        // \WPX\Karambit\Core\Debug::logDump( $properties, __METHOD__ . ' $properties');
        $this->set_html( $properties['html'] );
    }

}