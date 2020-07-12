<?php

$headlines = "https://www.kompas.com/";

$request = function ($headlines) {
    // init 
    $ch = curl_init();
    // setUrl
    curl_setopt($ch, CURLOPT_URL, $headlines);
    // return string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    // curl_close($ch);
    return $output;
};

$document = new DOMDocument();
libxml_use_internal_errors(true); // disable libxml error
$document->loadHTML($request($headlines));
$finder = new DOMXPath($document);
$article = $finder->query("//h3[contains(@class, 'article__title article__title--medium')]");

foreach ($article as  $articles) {
    echo "[title]: $articles->nodeValue\n", JSON_PRETTY_PRINT;
    foreach ($articles->firstChild->attributes as $attr) {
        if ($attr->name == 'href') {
            echo "[url]: $attr->value\n\n";
        }
    }
}
