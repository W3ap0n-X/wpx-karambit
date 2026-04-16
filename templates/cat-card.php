<?php
/** @var \WPX\Karambit\Engine\Data\FeedItem $item */





$output .= <<<HTML
        <a href='{$item->url}' class='cat-card'>
            
            <div class='cat-info'>
                <span class='cat-name'>{$item->title}</span>
                <span class='cat-count'>{$item->source}</span>
            </div>
            <div class='cat-icon' style='background: {$icon_color};'>
                <img src="{$item->image_url}" alt="">
            </div>
        </a>
        HTML;

        return $output;