<?php
namespace WPX\Karambit\Core\API;

interface Endpoint {
    public function get_route(): string;
    public function get_methods(): array; // e.g., ['GET']
    public function handle( \WP_REST_Request $request );
    public function get_args(): array;
    public function get_permission_callback(): callable;
}