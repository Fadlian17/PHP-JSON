<?php

require_once './simplehtmdom/simple_html_dom.php';

class News
{

    protected function request($headlines)
    {
        // init 
        $ch = curl_init();
        // setUrl
        curl_setopt($ch, CURLOPT_URL, $headlines);
        // return string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        // curl_close($ch);
        return $output;
    }

    protected function domDocument($headlines)
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true); // disable libxml error
        $document->loadHTML($this->request($headlines));
        $finder = new DOMXPath($document);
        return $finder;
    }

    public function headlineNews($headlines)
    {
        $req = $this->domDocument($headlines);
        $article = $req->query("//h3[contains(@class, 'article__title article__title--medium')]");


        foreach ($article as  $articles) {
            echo "[title]: $articles->nodeValue\n";
            foreach ($articles->firstChild->attributes as $attr) {
                if ($attr->name == 'href') {
                    echo "[url]: $attr->value\n\n";
                }
            }
        }
    }
}
$article = new News();
echo "==========Berita Kompas.com=========";
$article->headlineNews($headlines = 'https://www.kompas.com/');
