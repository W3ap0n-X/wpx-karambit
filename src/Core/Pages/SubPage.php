<?php
namespace WPX\Karambit\Core\Pages;
use WPX\Karambit\Manifest;
abstract class SubPage extends Admin {

	public $parent_slug;

	public function __construct($parent_slug, $hooks){
		$this->parent_slug = $parent_slug;
		parent::__construct($hooks);
	}

	/**
     * Add this page as a subpage
     */
    public function add_page() {
        // \WPX\Karambit\Core\Debug::logDump('parent_slug: ' . $this->parent_slug, __METHOD__);
        add_submenu_page(
            $this->parent_slug,   		// parent slug
            $this->get_page_title(),    // page_title
            $this->get_menu_title(),    // menu_title
            $this->get_capability(),    // capability
            $this->get_slug(),          // menu_slug
            array( $this, 'render' ),   // callback
            $this->get_position()       // position
        );
    }

    protected function get_page_prefix() {
        return '_page_';
    }

}