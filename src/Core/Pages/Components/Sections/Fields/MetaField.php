<?php
namespace WPX\Karambit\Core\Pages\Components\Sections\Fields;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Options\Options;
use WPX\Karambit\Core\Pages\Components\Sections\Fields\Elements\Element;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class MetaField {

    
    protected static $number_of_fields = 0;

    
    protected $elements = array();

    
    private $options;

    
    protected $section_id;

    
    protected $description;

    /**
     * Render the UI element.
     *
     * @return string
     */
    public function render() {
        // \WPX\Karambit\Core\Debug::logDump('Rendering Field', __METHOD__);

        $html = '';
        $html .= '<div class="qckfe_meta_field">';
        // $html .= '<h4>' . (isset($this->title) ? $this->title : 'FIELD TITLE')  . '</h4>';
        $html .= '<p>' . (isset($this->description) ? $this->description : 'FIELD DESCRIPTION')  . '</p>';
        // if ( ! empty( $this->description ) ) {
        //     $html .= '<p class="description">' . esc_html($this->description)  . '</p>';
        // }

        

        foreach ( $this->elements as $key => $element ) {
            $html .= $element->render();
        }
        $html .= '</div>';
        // \WPX\Karambit\Core\Debug::logDump( $html, __METHOD__);
        return $html;
    }

    
    public function __construct( $section_id, $page, $properties = array() ) {

        $dump_me = ['id'=>$section_id, 'page'=>$page,'properties'=>$properties];
        // \WPX\Karambit\Core\Debug::logDump($dump_me, __METHOD__);
        self::$number_of_fields++;

        $properties = wp_parse_args(
            $properties,
            array(
                'label'       => sprintf(
                    
                    __( 'Field #%s', Manifest::PREFIX ),
                    self::$number_of_fields
                ),
                'id'          => 'field_' . self::$number_of_fields,
                'description' => '',
            )
        );

        $this->section_id  = $section_id;
        $this->description = $properties['description'];

        // add_settings_field(
        //     $properties['id'],
        //     $properties['label'],
        //     array( $this, 'render' ),
        //     $page,
        //     $section_id
        // );
    }

    
    public function add_element( $element_type, $properties ) {
        $element_type = __NAMESPACE__ . '\\Elements\\' . $element_type;

        if ( ! class_exists( $element_type ) ) {
            return;
        }

        $element = new $element_type( $this->section_id, $properties );
        if ( ! ( $element instanceof Element ) ) {
            return;
        }

        $this->elements[ $element->get_option_name() ] = $element;
        return $this;
    }

    
    public function get_elements() {
        return $this->elements;
    }

}