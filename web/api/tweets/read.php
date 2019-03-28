<?php

    //A simple script to act as the end point for AJAX calls.
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Origin, Content-Type');

    include(__DIR__ . "/../../api/models/tweets_model.php");

    try{
        $model = new TweetsModel();
        $tweets = $model->getAllTweets(25);
        $tweets = json_encode($tweets);
        print_r($tweets);
    }catch(Exception $e){
        
        $message = ["message" => "ERROR: unable to perform request"];
        print_r($message);
    }

    


?>