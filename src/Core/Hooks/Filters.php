<?php
namespace WPX\Karambit\Core\Hooks;

// Prevent direct access to files
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

interface Filters {
    /** @return array ['hook_name' => ['method', priority, args]] */
    public function get_filters(): array;
}