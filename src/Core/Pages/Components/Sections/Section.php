<?php
namespace WPX\Karambit\Core\Pages\Components\Sections;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Options\Options;
use WPX\Karambit\Core\Pages\Components\Sections\Fields\Field;
use WPX\Karambit\Core\Debug;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Section {

    
    protected $fields = array();

    
    public $options;

    
    protected $title;

    
    protected $id;

    public function get_id(){
        return $this->id;
    }

    
    protected $page;

    
    protected $description;

    
    public function __construct( $section_id, $page, $options_instance, $properties = array() ) {
        
        // $dump_me = ['section_id'=>$section_id, 'page'=>$page,'properties'=>$properties, 'options'=>$options_instance];
        // \WPX\Karambit\Core\Debug::logDump($dump_me, __METHOD__);
        $properties = wp_parse_args(
            $properties,
            array(
                'title'       => __( $section_id, Manifest::PREFIX ),
                'description' => ''
            )
        );

        $this->options = $options_instance;

        $this->title       = $properties['title'];
        $this->description = $properties['description'];
        $this->page        = $page;
        $this->id          = $section_id;
        // \WPX\Karambit\Core\Debug::logDump($this, __METHOD__ . "FIELD CHECK");
        add_settings_section(
            $section_id,
            $this->title,
            array( $this, 'print_description' ),
            $page
        );
    }

    
    public function print_description() {
        echo esc_html( $this->description ) . 'DESC';
    }

    
    public function add_field( $properties ) {
        $field = new Field( $this->id, $this->page, $properties );

        $this->fields[] = $field;

        return $field;
    }



}