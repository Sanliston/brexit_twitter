<?php

    //A simple script to act as the end point for AJAX calls.
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include(__DIR__ . "/../../api/models/tweets_model.php");
    $contents = json_decode(stripslashes($_REQUEST['id']), true);
    //$count = json_decode(stripslashes($_REQUEST['count']), true);
    $count = 25;

    if ($contents){

        try{
            $contents = (int) $contents; 
            $count = (int) $count;
        }catch(Exception $e){
            $message = ["message" => "ERROR: possible cause - Invalid data passed to API"];
            print_r($message);
        }
        
        $model = new TweetsModel();
        $tweets = $model->getTweetsAfterId($contents, $count);
        $tweets = json_encode($tweets);
        print_r($tweets);

    } 

?>