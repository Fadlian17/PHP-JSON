<?php

// GET

// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, "https://jsonplaceholder.typicode.com/users/1");
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// $results = curl_exec($curl);

// var_dump($results);
// if (curl_error($curl) != null) {
//     # code...
// }
// curl_close($curl);

//POST

$data = ["id"=>1, "name"=>"budi"];

// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, "https://httpbin.org/post");
// curl_setopt($curl, CURLOPT_POST, true);
// curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
// curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$options = [
    CURLOPT_URL => "https://httpbin.org/post",
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER, ["Content-Type: application/json"],
    CURLOPT_RETURNTRANSFER => true
];

$results = curl_setopt_array($curl, $options);

// $results = curl_exec($curl);

var_dump($results);
// if (curl_error($curl) != null) {
//     # code...
// }
// curl_close($curl);
