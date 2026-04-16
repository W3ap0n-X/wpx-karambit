<?php
namespace WPX\Karambit\Core\Hooks;

interface HookInterface {
    public function is_filter(): bool ;
    public function get_hook(): string;      // e.g., 'wp_enqueue_scripts'
    public function get_callback(): callable;
    public function get_priority(): int;
    public function get_args_count(): int;
}