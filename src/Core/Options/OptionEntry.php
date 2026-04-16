<?php


namespace WPX\Karambit\Core\Options;

class OptionEntry {

    public $key;
    public $label;
    public $type;
    public $default;
    public $description;
    public $placeholder;
    public $path;
    public $options;

    public $html;

    public function __construct( $key, $label, $type = 'text', $default = null, $path = [], $options = ['none'] , $description = '', $placeholder = '', $html = ''  ) {
        $this->key     = $key;
        $this->label   = $label;
        $this->type    = $type;
        $this->default = $default;
        $this->description = $description;
        $this->placeholder = $placeholder;
        $this->options = $options;
        $this->path    = $path;
        $this->html    = $html;
    }

    public function get_ui_name(){
        $path  = (! empty( $this->path ) ) ? $this->path : [];
        if (is_string($path) && !str_contains($path, '.')) {
            $path = is_array($path) ? $path : explode('.', $path);
        }
        $output = '';
        foreach ($path as $key) {
            $output .=  $key . '][';
        }
        $output .= "" . $this->key . '';
        return $output;
        
    }

    public function get_path(): string {
        // If there is no path, just return section[key]
        if (empty($this->path)) {
            return sprintf('[%s]', $this->key);
        }

        // Build the middle pieces: [sub][group]
        $mid_path = implode('][', $this->path);

        // Result: section_id[sub][group][key]
        return sprintf('[%s][%s]', $mid_path, $this->key);
    }
}