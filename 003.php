<?php
//Join Data

require_once './simplehtmdom/simple_html_dom.php';


class JoinData
{
    function request($url)
    {
        // init 
        $ch = curl_init();
        // setUrl
        curl_setopt($ch, CURLOPT_URL, $url);
        // return string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    function responses()
    {
        $jsoncode = $this->request('https://jsonplaceholder.typicode.com/posts');
        $jsoncode2 = $this->request('https://jsonplaceholder.typicode.com/users');
        $data = [];


        foreach ($jsoncode as $post) {
            foreach ($jsoncode2 as $user) {
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
    }
}

$result_json = new JoinData();
$result_json->responses();
