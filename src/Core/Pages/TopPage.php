<?php
namespace WPX\Karambit\Core\Pages;
use WPX\Karambit\Manifest;
abstract class TopPage extends Admin {

	/**
     * Add this page as a top-level menu page.
     */
    public function add_page() {
        add_menu_page(
            $this->get_page_title(),    // page_title
            $this->get_menu_title(),    // menu_title
            $this->get_capability(),    // capability
            $this->get_slug(),          // menu_slug
            array( $this, 'render' ),   // callback function
            $this->get_icon_url(),      // icon_url
            $this->get_position()       // position
        );
    }

    protected function get_page_prefix() {
        return 'toplevel_page_';
    }
}