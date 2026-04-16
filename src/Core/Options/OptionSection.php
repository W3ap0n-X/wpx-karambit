<?php
namespace WPX\Karambit\Core\Options;

use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Options\Options;

abstract class OptionSection implements Options {

    
    abstract public function get_name(): string;

    
    abstract public function get_title(): string;

    abstract public function get_description(): string;

    
    abstract public function get_schema(): array;

    
    public function get_db_row(): string {
        return Manifest::PREFIX . '_' . $this->get_name();
    }

    
    public function get_values(): array {
        return get_option( $this->get_db_row(), [] );
    }

    
    public function get_field_definition( string $key ): ?OptionEntry {
        $fields = $this->get_schema();
        return $fields[$key] ?? null;
    }

    public function set_defaults() {
        $fields = $this->get_schema();
        foreach ($fields as $field) {
            \WPX\Karambit\Core\Debug::logDump($field, __METHOD__);
        }
    }

    
    protected function deep_get(array $data, $path, $default = null) {
        // If it's a simple string, just return the top-level value
        if (is_string($path) && !str_contains($path, '.')) {
            return $data[$path] ?? $default;
        }

        // Convert dot notation 'services.google' to ['services', 'google']
        $keys = is_array($path) ? $path : explode('.', $path);

        foreach ($keys as $key) {
            if (is_array($data) && array_key_exists($key, $data)) {
                $data = $data[$key];
            } else {
                return $default;
            }
        }

        return $data;
    }

    

    public function get($name) {
        $values = $this->get_values(); // The raw DB row
        $entry = $this->get_field_definition($name);

        if ($entry && !empty($entry->path)) {
            // Build the full path: [path_segment, key]
            $full_path = array_merge($entry->path, [$entry->key]);
            return $this->deep_get($values, $full_path, $entry->default);
        }

        // Fallback to top-level or default
        return $values[$name] ?? ($entry->default ?? null);
    }

    public function set($name, $setValue) {
        $data  = $this->get_values();
        $entry = $this->get_field_definition( $name );
        $path  = ( $entry && ! empty( $entry->path ) ) ? $entry->path : [];

        // Run the mutation
        $this->deep_set( $data, $path, $name, $setValue );

        // Persistence: This triggers the WordPress update_option filter stack
        return update_option( $this->get_db_row(), $data );
    }

    
    protected function deep_set( array &$data, array $path, string $key, $value ) {
        $temp = &$data;

        foreach ( $path as $step ) {
            // If the step isn't an array, make it one so we can keep drilling.
            if ( ! isset( $temp[$step] ) || ! is_array( $temp[$step] ) ) {
                $temp[$step] = [];
            }
            $temp = &$temp[$step];
        }

        $temp[$key] = $value;
    }

    
    public function remove( $name) {
        $data  = $this->get_values();
        $entry = $this->get_field_definition( $name );
        $path  = ( $entry && ! empty( $entry->path ) ) ? $entry->path : [];

        $this->deep_unset( $data, $path, $name );

        return update_option( $this->get_db_row(), $data );
    }

    
    protected function deep_unset( array &$data, array $path, string $key ) {
        $temp = &$data;

        foreach ( $path as $step ) {
            if ( ! isset( $temp[$step] ) || ! is_array( $temp[$step] ) ) {
                return; // The path doesn't exist, our work here is done.
            }
            $temp = &$temp[$step];
        }

        unset( $temp[$key] );
    }

    public function get_value_for_entry(OptionEntry $entry) {
        $all_data = $this->get_values(); // Fetches the whole DB row
        
        // If there's no path, just grab the key from the top level
        if (empty($entry->path)) {
            return $all_data[$entry->key] ?? $entry->default;
        }

        // Walk the path
        $current = $all_data;
        foreach ($entry->path as $step) {
            if (isset($current[$step]) && is_array($current[$step])) {
                $current = $current[$step];
            } else {
                return $entry->default; // Path broken, return default
            }
        }

        return $current[$entry->key] ?? $entry->default;
    }
}