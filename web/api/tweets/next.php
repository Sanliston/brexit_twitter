<?php

    //A simple script to act as the end point for AJAX calls.
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include(__DIR__ . "/../../api/models/tweets_model.php");

    if (isset($_POST['id'])){

        try{
           $data = (int) $_POST['id']; 
        }catch(Exception $e){
            $message = ["message" => "ERROR: possible cause - Invalid data passed to API"];
            print_r($message);
        }
        


        $model = new TweetsModel();
        $tweets = $model->getTweetsAfterId($data);
        $tweets = json_encode($tweets);
        print_r($tweets);

    }

    

?>