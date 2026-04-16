<?php
namespace WPX\Karambit\Core;
use WPX\Karambit\Manifest;

// use WPX\Karambit\Core\Options\WP_Options;
// use WPX\Karambit\Core\Options\OptionsManager;
use WPX\Karambit\Core\Hooks\HooksManager;
use WPX\Karambit\Core\Shortcodes\ShortcodeManager;
use WPX\Karambit\Core\API\ApiManager;
use WPX\Karambit\Core\Debug;
use WPX\Karambit\Core\Hooks\Actions;
use WPX\Karambit\Core\Diagnostics\SiteHealth;
use WPX\Karambit\Core\CPT\PostTypeManager;

use WPX\Karambit\Pages\SettingsPage;


abstract class Plugin implements Actions {

    
    public $options;

	// public $settings;

	
	protected $hooks;

	
	protected $shortcodes;

	
	protected $post_types;

	
	protected $rest_routes;

    
	protected $plugin_name;

    
	protected $version;





    

    public function __construct() {
		$this->version = Manifest::VERSION;
		$this->plugin_name = Manifest::NAME;
		
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	

	
    public function init() {
        // $this->options = new WP_Options();
		
		$this->rest_routes = new ApiManager();
		$this->hooks = new HooksManager();
		
		$this->shortcodes = new ShortcodeManager();
        
		$this->hooks->load();
		$this->hooks->register( $this );
		
		$this->hooks->register( new API\SettingsController() );
		$this->shortcodes->register_all();
		
		$this->register_pages();
		// $this->rest_routes->register_endpoints();

        new SiteHealth();
    }


	public function get_actions():array {
		$actions = [
			 //'plugins_loaded' => array( 'init' ) ,
			 
			//  'action' => array( 'method' ) ,

		];
		return $actions;
	}

	public function register_endpoints() {
		$this->rest_routes->register_endpoints();
	}

	public function register_post_types() {
		$this->post_types = new PostTypeManager();
		$this->post_types->register_all();
	}

	abstract protected function add_pages();

	private function register_pages() {
		foreach ( $this->add_pages() as $page ) {
			$this->hooks->register( $page );
		}
	}

}