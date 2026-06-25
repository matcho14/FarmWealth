<?php
$html = file_get_contents('resources/views/cycles/show.blade.php');
$html = preg_replace('/<!--.*?-->/s', '', $html);

$lines = explode(PHP_EOL, $html);
foreach($lines as $i => $line) {
    $oth = substr_count(strtolower($line), '<th');
    $cth = substr_count(strtolower($line), '</th');
    if ($oth != $cth) echo 'Line ' . ($i+1) . ': TH Opens ' . $oth . ', Closes ' . $cth . PHP_EOL;

    $oli = substr_count(strtolower($line), '<li');
    $cli = substr_count(strtolower($line), '</li');
    if ($oli != $cli) echo 'Line ' . ($i+1) . ': LI Opens ' . $oli . ', Closes ' . $cli . PHP_EOL;
}
