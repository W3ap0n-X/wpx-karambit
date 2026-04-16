<?php
namespace WPX\Karambit\Core\Hooks;

// Prevent direct access to files
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

interface Actions {
    /** @return array ['hook_name' => ['method', priority, args]] */
    public function get_actions(): array;
}