<?php

include_once("vendor/autoload.php");

use Goutte\Client;
use OsmanSorkar\Corona\Generate;


$generate= new Generate();
$generate->generateData();

if(isset($_GET["bref"])){
    header('Content-Type: application/json');
    echo file_get_contents("bref.json");
    die();
}

if(isset($_GET["latest"])){
    header('Content-Type: application/json');
    echo file_get_contents("latest.json");
    die();
}

if(isset($_GET["country"])){
    header('Content-Type: application/json');
    $latest=json_decode(file_get_contents("latest.json"),true);

    if(key_exists($_GET["country"],$latest)){
        echo json_encode($latest[$_GET["country"]]);
    }
    else{
        echo json_encode([
            "error"=>"data not found"
        ]);
    }

    die();
}


die("This is api server for corona update");
