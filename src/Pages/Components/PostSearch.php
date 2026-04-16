<?php 

namespace WPX\Karambit\Pages\Components;
use WPX\Karambit\Core\Pages\Components\Interfaces\HTML;
class PostSearch implements HTML {


    public function get_html($value = []) : string {
        $value = (array) $value;
        // \WPX\Karambit\Core\Debug::logDump( $value, __METHOD__ . ' $value');
        $content = '';

        foreach ($value as $post_id) {
            $title = get_the_title( $post_id );
            $content .= <<<HTML
            <li>
                <span class="dashicons dashicons-menu"></span> {$title}
                <input class="qckfe-post-id" type="hidden" name="_qckfe_feed_settings[manual_ids][]" value="{$post_id}">
                <button type="button" class="remove-btn">-</button>
            </li>
            HTML;
        }


        $html = <<<HTML
            <div class="qckfe-manual-order-wrapper">
    
                <div class="qckfe-search-col">
                    <input type="text" id="qckfe-post-search" placeholder="Search by title...">
                    <ul id="qckfe-search-results">
                        </ul>
                </div>

                <div class="qckfe-feed-col">
                    <label>Current Feed Order (Drag to reorder):</label>
                    <ul id="qckfe-manual-list" class="ui-sortable">
                        {$content}
                        </ul>
                </div>
            </div>
        HTML;

        return $html;
    }
}