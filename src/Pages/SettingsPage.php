<?php
namespace WPX\Karambit\Pages;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Hooks\Actions;
use WPX\Karambit\Core\Pages\TopPage;
use WPX\Karambit\Core\Pages\Components\SettingBuilder;



class SettingsPage extends TopPage implements Actions {

    

    public function __construct(  $hooks) {
        parent::__construct(  $hooks );
    }



    
    protected function get_menu_title() {
        return __( Manifest::NAME, Manifest::SLUG );
    }

    
    protected function get_page_title() {
        return __( Manifest::NAME . ' Settings', Manifest::SLUG );
    }

    
    // protected function get_icon_url() {
    //     return 'dashicons-shield-alt';
    // }

    
    public function get_slug() {
        return Manifest::PREFIX . '_settings';
    }

    
    public function register_sections() {
        $this->add_section( new \WPX\Karambit\Options\GeneralOptions() );
        // $this->add_section( new \WPX\Karambit\Options\BentoOptions() );

        

        foreach ($this->sections as  $section) {
            SettingBuilder::build_ui_from_section($this->get_slug(), $section);
        }
    }

}
