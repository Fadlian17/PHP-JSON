<?php

require_once './simplehtmdom/simple_html_dom.php';

$jsoncode = 'https://jsonplaceholder.typicode.com/posts';
$jsoncode2 = 'https://jsonplaceholder.typicode.com/users';

$req = function ($url) {
    // init 
    $ch = curl_init();
    // setUrl
    curl_setopt($ch, CURLOPT_URL, $url);
    // return string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
};

$mixed1 = $req($jsoncode);
$mixed2 = $req($jsoncode2);

$data = [];

foreach ($mixed1 as $post) {
    foreach ($mixed2 as $user) {
        if ($post['userId'] == $user['id']) {
            $post['users'] = $user;
        }
    }
    $data[] = $post;
}

$encode = json_encode($data, JSON_PRETTY_PRINT);
if (file_put_contents("json/datas.json", $encode)) {
    echo "Success saved! \n";
} else {
    echo "can't saved data!\n";
}
