<?php
$html = file_get_contents('resources/views/cycles/show.blade.php');
$html = preg_replace('/<!--.*?-->/s', '', $html);

$tags = ['div', 'ul', 'li', 'button', 'a', 'table', 'tr', 'td', 'th', 'thead', 'tbody', 'tfoot', 'span', 'h2', 'h3', 'h6', 'p', 'i'];

foreach ($tags as $tag) {
    preg_match_all("/<{$tag}[^>]*>/i", $html, $opens);
    // Ignore self-closing or empty for simplistic checking
    preg_match_all("/<\/{$tag}>/i", $html, $closes);
    if (count($opens[0]) !== count($closes[0])) {
        echo "Tag {$tag} is unbalanced! Opens: " . count($opens[0]) . ", Closes: " . count($closes[0]) . PHP_EOL;
    }
}
echo "Done checking tags.";
