<?php
namespace WPX\Karambit\Core\Pages\Components;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Options\Options;
use WPX\Karambit\Core\Pages\Components\Sections\MetaSection;
use WPX\Karambit\Core\Pages\Components\Sections\Fields\MetaField;
use WPX\Karambit\Core\Debug;
use WPX\Karambit\Core\Pages\Components\SettingBuilder;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Metabox {

    
    protected $sections = array();

    
    public $options;

    
    private $title;

    
    private $id;

    public function get_id(){
        return $this->id;
    }

    
    private $page;

    
    private $description;


    public $callback;
    public $context;
    public $priority;

    
    public function __construct( $section_id, $page, $properties = array() ) {
        
        // $dump_me = ['section_id'=>$section_id, 'page'=>$page,'properties'=>$properties, 'options'=>$options_instance];
        // \WPX\Karambit\Core\Debug::logDump($dump_me, __METHOD__);
        $properties = wp_parse_args(
            $properties,
            array(
                'title'       => __( $section_id, Manifest::PREFIX ),
                'description' => ''
            )
        );

        // $this->options = $options_instance;

        $this->title       = $properties['title'];
        $this->description = $properties['description'];
        $this->page        = $page;
        $this->id          = $section_id;
        $this->priority          = $properties['priority'];
        $this->context          = $properties['context'];
        $this->callback          = $properties['callback'];

        // \WPX\Karambit\Core\Debug::logDump($this, __METHOD__ . "FIELD CHECK");
        // add_settings_section(
        //     $section_id,
        //     $this->title,
        //     array( $this, 'print_description' ),
        //     $page
        // );
        
    }

    public function register(){
        add_meta_box(
            $this->id,
            $this->title,
            $this->callback,
            $this->page,
            $this->context ,
            $this->priority,
        );

    }

    
    public function add_section( $properties ) {
        $section = new MetaSection( $this->id, $this->page, $properties );

        $this->sections[] = $section;

        return $section;
    }

    public function render($post, $settings) {
        
        $html =  '';
        // $html .= wp_nonce_field('_' . Manifest::PREFIX . '_' . $settings->get_name() . '_action', $settings->get_name() . '_nonce');
        // $values = get_post_meta($post->ID, '_' . Manifest::PREFIX . '_' . $settings->get_name(), true) ?: [];
        $html .= '<div class="qckfe-metabox-wrapper">';
        foreach ($this->sections as $section) {
            $section = SettingBuilder::build_ui_from_metabox($post->ID, $section, $settings);
            $html .= $section->render();
        }
        $html .= '</div>';
        // \WPX\Karambit\Core\Debug::logDump( $html, __METHOD__);
        return $html;
    }
}