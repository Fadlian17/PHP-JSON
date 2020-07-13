<?php


require_once './simplehtmdom/simple_html_dom.php';
require './dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class CGVMovies
{

    protected function getCinemas($link)
    {
        print("Wait for Get url movie...\n");
        $html = file_get_html($link);
        $getLink = $html->find('a');

        $link = [];
        foreach ($getLink as $get) {
            $links = $get->href;
            $repQuot = str_replace('"', '', $links);
            $repSlash = str_replace('\\', '', $repQuot);
            array_push($link, "https://www.cgv.id" . $repSlash);
        }
        print("url movie success! \n");
        return $link;
    }

    protected function getMoviesScript($links)
    {
        print("Get Movie Data...\n");
        $all = [];
        foreach ($links as $link) {

            $result = [];
            $data_cinemas = file_get_html($link);

            /* GET HTML CODE */
            $result['title'] = $data_cinemas->find('div.movie-info-title', 0)->innertext;
            $result['info'] = $data_cinemas->find('div.movie-add-info', 0)->innertext;
            $result['synopsis'] = $data_cinemas->find('div.movie-synopsis', 0)->innertext;
            $result['image'] = $data_cinemas->find('div.poster-section', 0)->innertext;

            $all[] = $result;
        }
        print("Movie Data success!\n");
        return $all;
    }


    //memuat dompdf
    public function htmlpdf($linkAll)
    {
        $content = '<h1><center style="font-family: sans-serif">GET INFORMATION MOVIES</center></h1><br><br>';

        $this->GenerateImages();

        foreach ($linkAll as $movie) {
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

        return $content;
    }

    //hasil pdf dari cgv
    public function pdfResults($link)
    {
        $getCinemas = $this->getCinemas($link);
        $getData = $this->getMoviesScript($getCinemas);
        $htmlPdf = $this->htmlpdf($getData);

        print("Please Wait To Saving Into PDF File...\n");

        /** save to pdf */
        $dompdf = new Dompdf();
        $dompdf->load_html($htmlPdf);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $r = $dompdf->output();

        $pdf = './pdf/movie.pdf';
        if (file_put_contents($pdf, $r)) {
            echo "success saved as to folder{$pdf}\n";
        }
    }

    public function GenerateImages()
    {
        print("Checking Folder Image...\n");
        sleep(1);
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
    }
}

$link = 'https://www.cgv.id/en/loader/home_movie_list';
$scrapeCinemas = new CGVMovies();
$scrapeCinemas->pdfResults($link);
