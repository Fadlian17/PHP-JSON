<?php

require_once './simplehtmdom/simple_html_dom.php';
require_once './dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$movie_theater = 'https://www.cgv.id/en/loader/home_movie_list';


$movies = function ($movie_theater) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $movie_theater);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    // $decode = json_decode($output, true);
    curl_close($ch);
    return $output;
};

print("Getting url movie...\n");
$html = file_get_html($movie_theater);
$getLink = $html->find('a');

/* Looping for replace junk symbol  */
$link_movie = [];
foreach ($getLink as $get) {
    $links = $get->href;

    $repQuot = str_replace('"', '', $links);
    $repSlash = str_replace('\\', '', $repQuot);

    array_push($link_movie, "https://www.cgv.id" . $repSlash);
}

print("url movie success! \n");
// var_dump($link);


print("Get Movie Data...\n");
$all = [];
for ($i = 0; $i < count($link_movie); $i++) {

    $result = [];
    $data = file_get_html($link_movie[$i]);
    $result['title'] = $data->find('div.movie-info-title', 0)->innertext;
    $result['info'] = $data->find('div.movie-add-info', 0)->innertext;
    $result['synopsis'] = $data->find('div.movie-synopsis', 0)->innertext;
    $result['image'] = $data->find('div.poster-section', 0)->innertext;

    $all[] = $result;
}

print("Movie Data success!\n");
// $encode = json_encode($all, JSON_PRETTY_PRINT);
// file_put_contents('test.json', $encode);
// var_dump($all);


/** save to pdf */
$content = '<h1><center style="font-family: sans-serif">GET INFORMATION MOVIES</center></h1><br><br>';

print("Checking Folder Image...\n");
if (!file_exists('image/')) {
    print("Folder Image Not Found!\n");
    sleep(1);
    print("Generating Folder Image...\n");
    mkdir('image');
    print("Folder Image Success Generated!\n");
} else {
    sleep(1);
    print("Folder Image Found!\n");
}

foreach ($all as $key => $movie) {
    $xpath = new DOMXPath(@DOMDocument::loadHTML($movie['image']));
    $src = $xpath->evaluate("string(//img/@src)");
    $filename = basename($src);

    $dir = 'image/';
    $saveloc = $dir . $filename;
    file_put_contents($saveloc, file_get_contents($src));

    $content .= '<center><img src="image/' . $filename . '"></center>';
    $content .= "<p><center style='font-family: sans-serif'><h3>{$movie['title']}</h3></center></p>";
    $content .= $movie['info'];
    $content .= "<h4>Synopsis</h4> <p style='margin:none'>{$movie['synopsis']}</p>";
}

print("Please Wait To Saving Into PDF File...\n");

$dompdf = new Dompdf();
$dompdf->load_html($content);
$dompdf->setPaper('A4', 'potrait');
$dompdf->render();
$r = $dompdf->output();

$pdf = './pdf/movie.pdf';
if (file_put_contents($pdf, $r)) {
    echo "success saved location as {$pdf}\n";
}
