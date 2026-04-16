<?php
namespace WPX\Karambit\Hooks;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Hooks\HookInterface;

class PublicAssets implements HookInterface {

    public function is_filter(): bool { return false ; }
    public function get_hook(): string { return 'wp_enqueue_scripts'; }
    public function get_priority(): int { return 10; }
    public function get_args_count(): int { return 1; }

    public function get_callback(): callable {
        return function() {
            // \WPX\Karambit\Core\Debug::logDump( Manifest::url('src/assets/css/public.css' ), __METHOD__ . ' Manifest::url(\'src/assets/css/public.css\' )');
        wp_enqueue_style( Manifest::PREFIX . '-main', Manifest::url('src/assets/css/public.css' ));
            wp_enqueue_style( Manifest::PREFIX . '-old', Manifest::url('src/assets/css/public_old.css')  );
            wp_enqueue_script( 
                Manifest::PREFIX . '-main',
                Manifest::url('src/assets/js/public.js'), 
                ['jquery'], // Added jquery as a dependency since your script uses it
                Manifest::VERSION, 
                true // Move to footer for better performance
            );
        };
    }
}