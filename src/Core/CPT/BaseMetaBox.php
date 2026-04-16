<?php
namespace WPX\Karambit\Core\CPT;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Pages\Components\Metabox;
use WPX\Karambit\Core\Pages\Components\SettingBuilder;
use \WPX\Karambit\Core\Options\OptionEntry;
abstract class BaseMetaBox {

    public $metabox;

    abstract public function get_name(): string;
    abstract public function get_title(): string;
    abstract public function get_screen(): string|array; // e.g., 'qckfe_feed'
    abstract public function get_schema(): array;

    public function get_context(): string {
        return 'normal' ;
    }

    public function get_priority(): string {
        return 'default' ;
    }

    public function register() {
        $this->metabox = new Metabox(
                $this->get_name(), 
                $this->get_screen(), 
                [
                    'title' => $this->get_title(),
                    'context' => $this->get_context(),
                    'priority' => $this->get_priority(),
                    'callback' => [$this, 'render_wrapper'],
                ]
            );

            
        add_action('add_meta_boxes', function() {
            $this->metabox->register();
            $section = $this->metabox->add_section('_' . Manifest::PREFIX . '_' . $this->get_name());
        });
        
        add_action('save_post', [$this, 'save_data']);
    }

    public function render_wrapper($post) {
        echo '<div class="data-wrap" data-prefix="' . Manifest::PREFIX . '">';
        $html = $this->metabox->render($post, $this);
        // \WPX\Karambit\Core\Debug::logDump( $html, __METHOD__);
        $name = '_' . Manifest::PREFIX . '_' . $this->get_name();
        wp_nonce_field($name  . '_action', $name . '_nonce');
        // \WPX\Karambit\Core\Debug::logDump( $nonce, __METHOD__ . ' $nonce');
        echo $html;
        echo '</div>';
        // // 1. Security Nonce
        // wp_nonce_field($this->get_name() . '_action', $this->get_name() . '_nonce');

        // // 2. Fetch existing values
        // $values = get_post_meta($post->ID, '_qckfe_settings', true) ?: [];

        // // 3. Reuse your SettingsBuilder!
        // // $builder = new SettingBuilder($this->get_schema(), $values);
        // echo '<div class="qckfe-metabox-wrapper">';
        // echo '<h1>test</h1>';
        // SettingBuilder::build_ui_from_metabox($post->ID, $this->metabox, $this->get_schema());
        // echo '<h2>' . $post->ID . '</h2>';
        // echo '<h2>' . count($values) . '</h2>';
        // echo '<h2>' . count($this->metabox->fields) . '</h2>';
        // // echo $content;
        // // $metabox->render();
        // // \WPX\Karambit\Core\Debug::logDump( $content, __METHOD__);
        // // $builder->render();
        // echo '</div>';
    }

    public function save_data($post_id) {
        // Security checks
        
        if (!isset($_POST['_' . Manifest::PREFIX . '_' . $this->get_name() . '_nonce'])) {
            \WPX\Karambit\Core\Debug::logDump( 'nonce missing', __METHOD__);
            return;
        }
        if (!wp_verify_nonce($_POST['_' . Manifest::PREFIX . '_' . $this->get_name() . '_nonce'], '_' . Manifest::PREFIX . '_' . $this->get_name() . '_action')) {
            \WPX\Karambit\Core\Debug::logDump( 'nonce unverified', __METHOD__);
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        // Save the whole array as one row (The "Rhyme" strategy)
        if (isset($_POST['_' . Manifest::PREFIX . '_' . $this->get_name()])) {
            // \WPX\Karambit\Core\Debug::logDump( $_POST, __METHOD__);
            update_post_meta($post_id, '_' . Manifest::PREFIX . '_' . $this->get_name(), $_POST['_' . Manifest::PREFIX . '_' . $this->get_name()]);
        }
    }

    public function get_values($post_id): array|string {
        return get_post_meta( $post_id , '_' . Manifest::PREFIX . '_' . $this->get_name(), true);
    }

    public function get_defaults(){
        $defaults = [];
        foreach ($this->get_schema() as $entry) {

            $defaults = $this->set($defaults, $entry->key , $entry->default);
        }
        // \WPX\Karambit\Core\Debug::logDump( $defaults, __METHOD__);
        return $defaults;
    }

    /**
     * Helper to find a specific field definition by its key.
     */
    public function get_field_definition( string $key ): ?OptionEntry {

        $fields = $this->get_schema();
        // \WPX\Karambit\Core\Debug::logDump( $fields, __METHOD__ . ' $fields');
        // \WPX\Karambit\Core\Debug::logDump( $key, __METHOD__ . ' $key');
        foreach ($fields as $entry) {
            if($entry->key == $key) {
                return $entry;
            }
        }
        return $fields[$key] ?? null;
    }

        /**
     * Deep-searches an array using a path of keys.
     *
     * @param array $data The nested settings array.
     * @param array|string $path The path to the value (e.g., ['services', 'google', 'api_key']).
     * @param mixed $default What to return if the path doesn't exist.
     * @return mixed
     */
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

    

    public function get($values, $name) {
        
        $entry = $this->get_field_definition($name);

        if ($entry && !empty($entry->path)) {
            // Build the full path: [path_segment, key]
            $full_path = array_merge($entry->path, [$entry->key]);
            return $this->deep_get($values, $full_path, $entry->default);
        }

        // Fallback to top-level or default
        return $values[$name] ?? ($entry->default ?? null);
    }

    public function set($data, $name, $setValue) {
        $entry = $this->get_field_definition( $name );
        // \WPX\Karambit\Core\Debug::logDump( $name, __METHOD__ . ' $name');
        // \WPX\Karambit\Core\Debug::logDump( $entry, __METHOD__ . ' $entry');
        $path  = ( $entry && ! empty( $entry->path ) ) ? $entry->path : [];

        // Run the mutation
        $this->deep_set( $data, $path, $name, $setValue );

        // Persistence: This triggers the WordPress update_option filter stack
        return $data;
    }

    /**
     * Sets a value deep within an array based on a path.
     * * @param array &$data The array to modify (passed by reference).
     * @param array $path  The nesting path, e.g., ['services', 'google'].
     * @param string $key  The actual setting key.
     * @param mixed $value The new value.
     */
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

    /**
     * Implements remove() from the Options interface.
     */
    public function remove($data, $name) {
        
        $entry = $this->get_field_definition( $name );
        $path  = ( $entry && ! empty( $entry->path ) ) ? $entry->path : [];

        $this->deep_unset( $data, $path, $name );
        
        // return update_option( $this->get_db_row(), $data );
    }

    /**
     * The recursive helper to kill a key deep in the nest.
     */
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

    // public function get_value_for_entry(OptionEntry $entry) {
    //     $all_data = $this->get_values(); // Fetches the whole DB row
        
    //     // If there's no path, just grab the key from the top level
    //     if (empty($entry->path)) {
    //         return $all_data[$entry->key] ?? $entry->default;
    //     }

    //     // Walk the path
    //     $current = $all_data;
    //     foreach ($entry->path as $step) {
    //         if (isset($current[$step]) && is_array($current[$step])) {
    //             $current = $current[$step];
    //         } else {
    //             return $entry->default; // Path broken, return default
    //         }
    //     }

    //     return $current[$entry->key] ?? $entry->default;
    // }

    public function get_value_for_entry($post_id, OptionEntry $entry) {
        $all_data = $this->get_values($post_id ); // Fetches the whole DB row
        // \WPX\Karambit\Core\Debug::logDump( $all_data, __METHOD__);
        // \WPX\Karambit\Core\Debug::logDump( $entry->key . '= ' . $this->get($all_data , $entry->key), __METHOD__ . ' $this->get($all_data , $entry->key)');
        if( !empty($all_data) ) {
            if (empty($entry->path)) {
                // \WPX\Karambit\Core\Debug::logDump( $all_data[$entry->key], __METHOD__ . ' $all_data[$entry->key]');
                return $all_data[$entry->key] ?? $entry->default;
            }
            // \WPX\Karambit\Core\Debug::logDump( $all_data, __METHOD__ . ' $all_data');
            // Walk the path
            $current = $all_data;
            foreach ($entry->path as $step) {
                // \WPX\Karambit\Core\Debug::logDump( $entry->path, __METHOD__ . ' $entry->path');
                if (isset($current[$step]) && is_array($current[$step])) {
                    $current = $current[$step];
                } else {
                    // \WPX\Karambit\Core\Debug::logDump( $current, __METHOD__ . ' $current');
                    // \WPX\Karambit\Core\Debug::logDump( $step, __METHOD__ . ' $step');
                    $updated_data = $this->set($all_data, $entry->key, $entry->default);
                    // \WPX\Karambit\Core\Debug::logDump( $entry->default, __METHOD__ . ' $entry->default');
                    // \WPX\Karambit\Core\Debug::logDump( $updated_data, __METHOD__ . ' $updated_data');
                    update_post_meta($post_id, '_' . Manifest::PREFIX . '_' . $this->get_name(), $updated_data);
                    // \WPX\Karambit\Core\Debug::logDump( $this->get($updated_data , $entry->key), __METHOD__ . ' $this->get($updated_data , $entry->key)');
                    return $entry->default; // Path broken, return default
                }
            }
            return $current[$entry->key] ?? $entry->default;
        } else {

            $meta = add_post_meta($post_id, '_' . Manifest::PREFIX . '_' . $this->get_name(), $this->get_defaults(), true);
            // \WPX\Karambit\Core\Debug::logDump( $this->get_defaults(), __METHOD__ . 'set_defaults');
            return esc_html($entry->default);
        }
    }
}