<?php

    //A simple script to act as the end point for AJAX calls.
    header("Content-Type: application/json; charset=UTF-8");

    include(__DIR__ . "/../../api/models/tweets_model.php");

    $model = new TweetsModel();
    $tweets = $model->getAllTweets(100);


?>