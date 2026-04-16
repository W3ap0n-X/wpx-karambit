<?php
namespace WPX\Karambit;
if ( ! defined( 'WPINC' ) ) { die; }






/**
 * Plugin Meta Registry
 */
final class Manifest {
    // Static meta
    public const VERSION = '0.0.0.1';
    public const PREFIX  = 'krmbt';
    public const NAME    = 'Karambit';
    public const SLUG    = 'wpx-karambit';
    public const MENU_SLUG    = 'wpx_karambit';

    // Dynamic paths (Handled via methods to stay reliable)
    public static function path(string $path = ''): string {
        // We go up one level because this file is in /src/
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
    }

    public static function url(string $path = ''): string {
        return plugins_url(ltrim($path, '/'), self::path(self::SLUG . '.php'));
    }

    public static function details(){
        $details = array(
            'Name' => self::NAME , 
            'Slug' => self::SLUG , 
            'Version' => self::VERSION , 
            'Prefix' => self::PREFIX , 
        );
        return $details;
    }
}