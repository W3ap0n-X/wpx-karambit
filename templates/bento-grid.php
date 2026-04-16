<?php
/** @var string $content */
// $content= array_merge($content['manual'],$content['automatic']);

// $featured
$content= $content['manual'] . $content['automatic'];

$output .= <<<HTML
<div class="qckfe-trending-grid">
{$content}
    
</div>
HTML;
return $output;