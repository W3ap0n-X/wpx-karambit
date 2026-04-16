<?php
namespace WPX\Karambit\Core\Pages\Components\Sections\Fields\Elements;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Pages\Components\Interfaces\UI;
use WPX\Karambit\Core\Options\Options;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

abstract class Element implements UI {
    const NUMBER_ELEMENT = 'Number';
    const TEXT_ELEMENT = 'Text';
    const RADIO_ELEMENT = 'Radio';
    const CHECKBOX_ELEMENT = 'Checkbox';
    const IMAGE_ELEMENT = 'Image';
    const CUSTOM_ELEMENT = 'Custom';
    private static $number_of_elements = 0;
    protected $label;
    protected $name;
    public $value;
    protected $option_name;
    private $validate;
    private $pre_write;
    protected $plugin_prefix;

    
    public function __construct( $section_id, $properties = array()) {
        self::$number_of_elements++;
        if ( $this instanceof SettingsInterface ) {
            $properties = wp_parse_args(
                $properties,
                array(
                    'label'     => sprintf(
                        
                        __( 'Element #%s', Manifest::PREFIX ),
                        self::$number_of_elements
                    ),
                    'name'      => 'element_' . self::$number_of_elements,
                    'validate'  => null,
                    'pre_write' => null,
                    'post_read' => null,
                    'prefix' => ''
                )
            );
            $this->plugin_prefix = Manifest::PREFIX;
            $this->label       = $properties['label'];
            $this->option_name = $properties['name'];
            $this->name        = sprintf( '%s%s_%s', $properties['prefix'], Manifest::PREFIX , $this->option_name );
            $this->validate    = $properties['validate'];
            $this->pre_write   = $properties['pre_write'];
            $this->value       = $properties['value'];
            if ( is_callable( $properties['post_read'] ) ) {
                $this->value = $properties['post_read']( $this->value );
            }
        }
    }

    public function get_option_name() {
        return $this->option_name;
    }

    public function get_validate() {
        return $this->validate;
    }

    public function get_value() {
        return $this->value;
    }

    public function get_pre_write() {
        return $this->pre_write;
    }

}