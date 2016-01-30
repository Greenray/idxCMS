<?php
# idxCMS Flat Files Content Management System v3.1
# Copyright (c) 2016 Victor Nabatov greenray.spb@gmail.com
# Parser of the browsers prefixes

$files = explode("|", $_GET["files"]);

$contents = '';
foreach ($files as $file) {
    $file = str_replace('./', '/', $file);
    $contents .= file_get_contents($_SERVER['DOCUMENT_ROOT'].$file.'.css');
}
$contents = preg_replace('/(\/\*).*?(\*\/)/s', '', $contents);
preg_match_all('/_[a-zA-Z\-]+:[\s_|][a-zA-Z0-9\.\-].+?;/sm', $contents, $keys);
preg_match_all('/[a-zA-Z\-]+:\s_[a-z].+?;/sm', $contents, $values);

$matches = array_merge($keys[0], $values[0]);
$styles  = [
    'background'          => ['-webkit-', '-moz-', '-ms-', '-o-', ''],
    'background-image'    => ['-webkit-', '-moz-', '-ms-', '-o-', ''],
    '_border-radius'      => ['-webkit-', '-moz-', ''],
    '_box-shadow'         => ['-webkit-', '-moz-', ''],
    '_box-sizing'         => ['-webkit-', '-moz-', '-ms-', ''],
    '_perspective'        => ['-webkit-', '-moz-', ''],
    '_perspective-origin' => ['-webkit-', '-moz-', ''],
    '_transform'          => ['-webkit-', '-moz-', '-ms-', '-o-', ''],
    '_transform-origin'   => ['-webkit-', '-moz-', '-ms-', '-o-', ''],
    'transform-style'     => ['-webkit-', '-moz-', '-ms-', '-o-', ''],
    '_transition'         => ['-webkit-', '-moz-', '-ms-', '-o-', ''],
];

foreach ($matches as $property) {
    foreach ($styles as $style => $prefixes) {
        $needle = explode(':', $property);
        if ($style === $needle[0]) {
            $result = '';
            foreach ($prefixes as $match) {
                $result .= str_replace('_', $match, $property);
            }
            $contents = str_replace($property, $result, $contents);
        }
    }
}

$contents = preg_replace(['/\s+([^\w\'\"]+)\s+/', '/([^\w\'\"])\s+/'], '\\1', $contents);

header("Content-Type: text/css");
header("Expires: ".gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
echo $contents;
