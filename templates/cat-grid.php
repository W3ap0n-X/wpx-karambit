<?php
/** @var string $content */

$content= $content['manual'] . $content['automatic'];
$output .= <<<HTML
<div class="category-grid">

    
    {$content}
</div>
HTML;
return $output;