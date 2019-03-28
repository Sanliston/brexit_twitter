<?php

    //A simple script to act as the end point for AJAX calls.
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Origin, Content-Type');

    include(__DIR__ . "/../../api/models/tweets_model.php");

    if (isset($_POST['id'])){

        $data = (int) $_POST['id'];


        $model = new TweetsModel();
        $tweets = $model->getTweetsAfterId($data);
        $tweets = json_encode($tweets);
        print_r($tweets);

    }

    

?>