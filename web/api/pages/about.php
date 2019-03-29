<?php
//A simple script to act as the end point for AJAX calls.
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Origin, Content-Type');

    try{
        $template = file_get_contents(__DIR__."/../../../app/views/about.html");
        $data = ["data"=>$template];
        $data = json_encode($data);
        print_r($data);
    }catch(Exception $e){
        LogError($e->message);
        $message = ["message" => "ERROR: unable to perform request"];
        print_r($message);
    }
?>