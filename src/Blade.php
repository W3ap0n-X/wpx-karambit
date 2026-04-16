<?php
namespace WPX\Karambit;

use Jenssegers\Blade\Blade as ViewEngine;
use Jenssegers\Blade\Container as ViewContainer;

class Blade extends ViewEngine
{

	public $viewPaths = array();

	public $cachePath;
	
	public $container;


	public function __construct($viewPaths, string $cachePath)
    {
		$this->cachePath = $cachePath;
		$this->viewPaths = $viewPaths;
		$this->container = new ViewContainer();
		ViewContainer::setInstance($this->container);
		
    }

	public function run(){
	    
		parent::__construct($this->viewPaths, $this->cachePath , $this->container);
	}

	public function add_view_path($path) {
		$this->viewPaths[] = $path;
	}

	public function get_view_paths() {
		return $this->viewPaths;
	}

}