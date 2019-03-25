<?php

    header("Access-Control-Allow-Origin");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include(__DIR__ . "/../../api/models/tweets_model.php");

    class TweetsController{

        //This will be the class that handles calls to the API for tweets
    }

?>