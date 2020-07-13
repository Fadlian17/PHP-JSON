<?php

class movieDB
{

    private function films($path, $query = '')
    {
        $url = 'https://api.themoviedb.org/3';
        $key_api = '5baf45214039cf9ae474a0d55c8939a4';

        $get = file_get_contents("$url/$path?api_key=$key_api$query");
        return json_decode($get, true);
    }

    //10 judul film indonesia
    public function indonesianMovies()
    {
        $path = 'discover/movie';
        $query = '&region=id&sort_by=popularity.asc&include_adult=false';

        $data_of_the_year = $this->films($path, $query);

        foreach ($data_of_the_year['results'] as $d) {
            echo "[TITLE FILMS] " . $d['title'] . "\n";
        }
    }

    //film yang dibintangi Aktor Keanu Reeves(single actor)
    public function KeanuMovies($path)
    {
        $path = "person/$path/movie_credits";
        $dataKeanu = $this->films($path);

        foreach ($dataKeanu['cast'] as $dk) {
            echo "[TITLE FILMS] " . $dk['title'] . "\n";
        }
    }

    //film yang dibintangi dua aktor yaitu Robert Downey Junior dan Tom Holland(double actor)
    public function RobertTom($actor1, $actor2)
    {
        $path1 = "person/$actor1/movie_credits";
        $path2 = "person/$actor2/movie_credits";

        $actors1 = $this->films($path1)['cast'];
        $actors2 = $this->films($path2)['cast'];

        foreach ($actors1 as $values1) {
            foreach ($actors2 as $values2) {
                if ($values1['title'] == $values2['title']) {
                    echo "[TITLE FILMS]" . $values1['title'] . "\n";
                }
            }
        }
    }

    //Film Tahunan dengan Rating terbaik
    public function MovieofTheYear($year, $votes)
    {
        $path = 'discover/movie';
        $query = "&sort_by=popularity.desc&include_adult=false&primary_release_year=$year&vote_average.gte=$votes";
        $data = $this->films($path, $query);

        foreach ($data['results'] as $d) {
            echo "[TITLE FILMS] " . $d['title'] . "\n";
            echo "[VOTES ] " . $d['vote_average'] . "\n\n";
        }
    }
}
$keanuid = '6384';
$Robertid = '3223';
$Tomid = '1136406';

$movieDB = new movieDB();
echo "==========Top 10 Film Indonesia========== \n";
$movieDB->indonesianMovies();
echo "==================Next=======================\n";
echo "==========Keanu Reeves Films==========\n\n";
$movieDB->KeanuMovies($keanuid);
echo "=================Next=====================\n\n";
echo "==========RDJ and Tom Holland Films==========\n\n";
$movieDB->RobertTom($Robertid, $Tomid);
echo "=================Next=====================\n\n";
echo "==========Movies Of The Year By Rate==========\n\n";
$movieDB->MovieofTheYear(2016, 7.5);
echo "======================================\n";
