<?php
namespace WPX\Karambit\Core\Pages\Components\Utility;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SubmitButton {

	public $slug;
	public $text;
	public $styles;

	public function __construct($slug , $text = 'Save Options', $styles = 'primary large'){
		$this->slug = $slug;
		$this->text = $text;
		$this->styles = $styles;
		$this->render();
	}

	public function render() {
		$button = get_submit_button( __( $this->text, $this->slug ) , $this->styles , $this->slug );
		echo $button;
	}
}