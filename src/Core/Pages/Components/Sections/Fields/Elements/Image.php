<?php
namespace WPX\Karambit\Core\Pages\Components\Sections\Fields\Elements;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Image extends Element implements SettingsInterface {

    


    public function render() {
        $prefix = $this->plugin_prefix;
        $val = $this->value;
        $image_url = $this->value ? wp_get_attachment_image_url($this->value, 'medium') : '';
        $display = $this->value ? 'block' : 'none';
        $name = json_encode(esc_attr( $this->name ));
        
        // ob_start();
        // checked( '1', $this->value , true);
        // $checked = ob_get_clean();
        $label = esc_html( $this->label );
        $html = <<<HTML
            <fieldset>
                <label>
                    <div class="{$prefix}-image-preview-wrapper">
                        <img class="{$prefix}-image-preview" src="{$image_url}" style="max-width:200px; display:{$display};">
                        <input type="hidden" name={$name}  class="{$prefix}-image-id" value="{$val}">
                        <button type="button" class="button {$prefix}-select-img upload-image-button">Select Image</button>
                        <button type="button" class="button {$prefix}-remove-img remove-image-button" style="display:{$display};">Remove</button>
                    </div>
                    {$label}
                </label>
                <p></p>
            </fieldset>
        HTML;
        return $html;
    }

    
    public function sanitize( $option_value ) {
        return ( '1' === (string) $option_value || true === $option_value );
    }

}