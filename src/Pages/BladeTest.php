<?php
namespace WPX\Karambit\Pages;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Hooks\Actions;
use WPX\Karambit\Core\Pages\SubPage;
use WPX\Karambit\Core\Pages\Components\SettingBuilder;



class BladeTest extends SubPage implements Actions {

    

    public function __construct(  $parent_slug, $hooks) {
        parent::__construct(  $parent_slug, $hooks );
    }

    public function get_actions(): array {
        return array(
            'admin_menu'            => array( 'add_page' ),
            'admin_init'            => array( 'register_sections' ),
            'admin_notices'         => array( 'display_admin_notices' ),
            'admin_enqueue_scripts' => array( 'maybe_enqueue_stylesheets'),
            
        );
    }

    public function maybe_enqueue_stylesheets($hook_suffix ) {
        // \WPX\Karambit\Core\Debug::logDump( 'enqueue media', __METHOD__);
        parent::maybe_enqueue_stylesheets($hook_suffix );
        // wp_enqueue_media();

        $this->start_blade();
    }


    /**
     * Return the menu title.
     *
     * @return string
     */
    protected function get_menu_title() {
        return __( "Blade Page", Manifest::SLUG );
    }

    /**
     * Return the page title.
     *
     * @return string
     */
    protected function get_page_title() {
        return __( Manifest::NAME . ' Test', Manifest::SLUG );
    }

    /**
     * Return the menu icon as a dashicon.
     *
     * @link https://developer.wordpress.org/resource/dashicons/
     *
     * @return string
     */
    // protected function get_icon_url() {
    //     return 'dashicons-shield-alt';
    // }

    /**
     * Return page slug.
     *
     * @return string
     */
    public function get_slug() {
        return Manifest::PREFIX . '_blade';
    }

    /**
     * Register sections.
     */
    public function register_sections() {
        // $this->add_section( new \WPX\Karambit\Options\GeneralOptions() );
        

        

        foreach ($this->sections as  $section) {
            SettingBuilder::build_ui_from_section($this->get_slug(), $section);
        }
    }
    
    public function start_blade(){
	    $cache_path = Manifest::path('cache');
	    $views_path = Manifest::path('views');
	    \WPX\Karambit\Core\Debug::logDump( $cache_path, __METHOD__ . ' $cache_path');
	    \WPX\Karambit\Core\Debug::logDump( $views_path, __METHOD__ . ' $views_path');
	    $this->blade = new \WPX\Karambit\Blade([$views_path], $cache_path);
	    $this->blade->run();
	}
	
	/**
     * @var Blade
     */
    private $blade;

    /**
     * Render Custom HTML for plugin wp-admin page above options sections
     */
    public function content_top() {
        // $b_string = $this->blade->compiler()->compileString('{{ $test }}');
        // $html = $this->blade->evaluate($b_string, ['test' => 'Success']);
        $html = $this->blade->render('test', ['test' => 'Success']);
        return $html;
        
    
    }

    /**
     * Render Custom HTML for plugin wp-admin page above options sections
     */
    public function content_bottom() {
        
        $html = <<<HTML
            <div id="krmbt-blade-editor" name="krmbt-blade-editor" ></div>
            <input id="krmbt-blade-data" name="krmbt-blade-data" />
        HTML;
        // $html = $this->blade->render('test', ['test' => 'Success']);
        return $html;
        
    
    }

}
