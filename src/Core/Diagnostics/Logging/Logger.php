<?php 
namespace WPX\Karambit\Core\Diagnostics\Logging;

use WPX\Karambit\Manifest;

class Logger {
    private static function get_log_path() {
        $upload_dir = wp_upload_dir();
        $path = $upload_dir['basedir'] . '/' . Manifest::PREFIX . '-logs';
        
        if ( ! file_exists( $path ) ) {
            wp_mkdir_p( $path );
            // Add an index.php and .htaccess for security
            file_put_contents( $path . '/index.php', '' );
            file_put_contents( $path . '/.htaccess', 'Deny from all' );
        }
        
        return $path . '/debug.log';
    }

    public static function log( $message, $title = 'LOG' ) {
        $timestamp = current_time( 'mysql' );
        $formatted = sprintf( "[%s] [%s]: %s\n", $timestamp, $title, self::format_message( $message ) );
        
        error_log( $formatted, 3, self::get_log_path() );
    }

    public static function clear() {
        if ( file_exists( self::get_log_path() ) ) {
            unlink( self::get_log_path() );
        }
    }

    public static function get_contents() {
        $path = self::get_log_path();
        return file_exists( $path ) ? file_get_contents( $path ) : 'No log entries found.';
    }

    private static function format_message( $message ) {
        if ( is_array( $message ) || is_object( $message ) ) {
            return print_r( $message, true );
        }
        return $message;
    }
}