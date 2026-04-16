<?php
/** @var \WPX\Karambit\Engine\Data\FeedItem $item */
$image_url =esc_url( $item->image_url );
$source = esc_html( $item->source );
$title = esc_html( $item->title );
$type = esc_html( $item->type );
$url = esc_url( $item->url );
$output = <<<HTML
<div class="qckfe-card">
    <div class="qckfe-card-media">
        <img src="{$image_url}" alt="">
    </div>
    <div class="qckfe-card-content">
        <span class="badge">{$source}</span>
        <h3>{$title}</h3>
        <p>{$type}</p>
        <a href="{$url}" class="btn">Read More</a>
    </div>
</div>
HTML;

return $output;