<?php
/** @var \WPX\Karambit\Engine\Data\FeedItem $item */

$output = <<<HTML
    <a href='{$item->url}' class='trending-card {$slot_class} {$accent}'>
        <div class='card-img' style='background-image: url({$item->image_url});'></div>
        <div class='card-overlay'>
            <h3>{$item->title}</h3>
            <span class='view-link'>View Review →</span>
        </div>
    </a>
HTML;

return $output;