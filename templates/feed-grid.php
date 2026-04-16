<?php 
/** @var string $content */
$content= $content['manual'] . $content['automatic'];
$output .= <<<HTML
    <div class="qckfe-feed-grid">
        {$content}
    </div>
HTML;

return $output;