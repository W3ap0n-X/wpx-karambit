<?php
namespace WPX\Karambit\Core;

use WPX\Karambit\Core\Diagnostics\Logging\Logger;
/**
 * Activator Class
 * 
 * @since     1.0.0
 */
class Debug {
// class Debug implements Actions {

    private $options;
    private $hooks;
    public function __construct($options, $hooks) {
        $this->options = $options;
        $this->hooks = $hooks;
    }

    // private function get_actions() {
	// 	$actions = [

	// 	];
	// 	return $actions;
	// }

	public static function easydump( $var, $label = null) {
		return (isset($label) ? '<h4>' . $label . '</h4>' : '') . '<pre>' . print_r($var, true) . '</pre>';
	}

    public static function logDump($var, $label = 'FeedEngine') {
        Logger::log( $label . "\n" . print_r($var, true));
    }
}