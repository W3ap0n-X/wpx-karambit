<?php
namespace WPX\Karambit\Core\Pages\Components\Sections;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Options\Options;
use WPX\Karambit\Core\Pages\Components\Sections\Fields\MetaField;
use WPX\Karambit\Core\Debug;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class MetaSection extends Section {

        public function __construct( $section_id, $page, $options_instance, $properties = array() ) {
        
        // $dump_me = ['section_id'=>$section_id, 'page'=>$page,'properties'=>$properties, 'options'=>$options_instance];
        // \WPX\Karambit\Core\Debug::logDump($dump_me, __METHOD__);
        $properties = wp_parse_args(
            $properties,
            array(
                'title'       => __( $section_id, Manifest::PREFIX ),
                'description' => null
            )
        );

        $this->options = $options_instance;

        $this->title       = $properties['title'];
        $this->description = $properties['description'];
        $this->page        = $page;
        $this->id          = $section_id;
    }

    
    public function add_field( $properties ) {
        $field = new MetaField( $this->id, $this->page, $properties );

        $this->fields[] = $field;

        return $field;
    }

    public function render() {
        $html = '';
        $html .= '<div class="qckfe_meta_section">';
        $html .= '<h4>' . (isset($this->title) ? $this->title : 'SECTION TITLE')  . '</h4>';
        $html .= '<p>' . (isset($this->description) ? $this->description : 'SECTION DESCRIPTION')  . '</p>';
        foreach ($this->fields as $field) {
            $html .= $field->render();
        }
        $html .= '</div>';
        // \WPX\Karambit\Core\Debug::logDump( $html, __METHOD__);
        return $html;
    }
}