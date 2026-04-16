<?php

namespace WPX\Karambit\Core\Pages\Components;
use WPX\Karambit\Core\Pages\Components\Sections\Fields\Elements\Element;

class SettingBuilder {
    public static function build_ui_from_section($page, $section_object ) {
        

        foreach ($section_object->options->get_schema() as $entry) {
            // THE FACTORY LOGIC: Map Type to Element
            $element = match($entry->type) {
                'boolean' => Element::CHECKBOX_ELEMENT,
                'checkbox' => Element::CHECKBOX_ELEMENT,
                'string'  => Element::TEXT_ELEMENT,
                'number'  => Element::NUMBER_ELEMENT,
                'select'  => Element::RADIO_ELEMENT,
                'image'  => Element::IMAGE_ELEMENT,
                'custom'  => Element::CUSTOM_ELEMENT,
                default   => Element::TEXT_ELEMENT,
            };
            $html_name = $section_object->options->get_name() . $entry->get_path();
            
            $section_object->add_field([
                'id' => $entry->key, 
                'label' => $entry->label, 
                'description' => $entry->description
                ])->add_element($element, [
                    'label' => $entry->label, 
                    'description' => $entry->description, 
                    'name' => $html_name,
                    'value' => $section_object->options->get_value_for_entry($entry),
                ]);
        }

        return $section_object;
    }

    public static function build_ui_from_metabox($post, $section_object, $settings) {
        // \WPX\Karambit\Core\Debug::logDump( [$post, $section_object, $settings ], __METHOD__);
        // \WPX\Karambit\Core\Debug::logDump( $values, __METHOD__ . ' VALUES');
        $fields = [];
        foreach ($settings->get_schema() as $key => $entry) {
            // THE FACTORY LOGIC: Map Type to Element
            $element = match($entry->type) {
                'boolean' => Element::CHECKBOX_ELEMENT,
                'checkbox' => Element::CHECKBOX_ELEMENT,
                'string'  => Element::TEXT_ELEMENT,
                'number'  => Element::NUMBER_ELEMENT,
                'select'  => Element::RADIO_ELEMENT,
                'image'  => Element::IMAGE_ELEMENT,
                'custom'  => Element::CUSTOM_ELEMENT,
                default   => Element::TEXT_ELEMENT,
            };
            // \WPX\Karambit\Core\Debug::logDump( $entry, __METHOD__ . ' $entry');
            $html_name = $settings->get_name() . $entry->get_path();
            $value = $settings->get_value_for_entry($post,$entry);
            // if($entry->type == 'custom'){
            //     \WPX\Karambit\Core\Debug::logDump( [ $entry, $value], __METHOD__ . ' ' . $html_name . '->get_value_for_entry($post,$entry)');
            // }
            // \WPX\Karambit\Core\Debug::logDump( $html_name, __METHOD__ . ' $html_name');
            $fields[] = $section_object->add_field([
                'id' => $entry->key, 
                'label' => $entry->label, 
                'description' => $entry->description
                ])->add_element($element, [
                    'label' => $entry->label, 
                    'description' => $entry->description, 
                    'name' => $html_name,
                    'value' => $value,
                    'prefix' => '_',
                    'options' => $entry->options, 
                    'html' => $entry->html, 
                ]);
        }
        // \WPX\Karambit\Core\Debug::logDump( $fields, __METHOD__);
        return $section_object;
    }
}