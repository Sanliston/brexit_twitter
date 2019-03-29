<?php

    //A simple script to act as the end point for AJAX calls.
    header("Content-Type: application/json; charset=UTF-8");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Origin, Content-Type');

    include(__DIR__ . "/../../api/models/analysis_model.php");
    include(__DIR__ . "/../../Logs/Log.php");

    try{
        $model = new AnalysisModel();
        $entries = $model->getAllEntries(10000);
        $entries = json_encode($entries);
        LogServer(" analysis read API call");
        print_r($entries);
    }catch(Exception $e){
        LogError($e->message);
        $message = ["message" => "ERROR: unable to perform request"];
        print_r($message);
    }

    


?>