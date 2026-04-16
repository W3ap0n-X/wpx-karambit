<?php
namespace WPX\Karambit\Core\Pages\Components\Sections\Fields\Elements;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Checkbox extends Element implements SettingsInterface {

    


    public function render() {
        $name = json_encode(esc_attr( $this->name ));
        ob_start();
        checked( '1', $this->value , true);
        $checked = ob_get_clean();
        $label = esc_html( $this->label );
        $html = <<<HTML
            <fieldset>
                <label>
                    <input 
                        type="hidden" 
                        name={$name} 
                        value="0" 
                    />
                    <input
                        type="checkbox"
                        name={$name} 
                        name={$name} 
                        value="1"
                        {$checked}
                    />
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