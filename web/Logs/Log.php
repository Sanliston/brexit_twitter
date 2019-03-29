<?php

    function LogError($message){
        $path = __DIR__."/error.log";
        $date = date("Y-m-d H:i:s");
        $log = "Time: ".$date."  Log message: ".$message."\n";
        
        file_put_contents($path, $log, FILE_APPEND | LOCK_EX);
    }

    function LogServer($message){
        $path = __DIR__."/server.log";
        $date = date("Y-m-d H:i:s");
        $log = "Time: ".$date."   Log message: ".$message."\n";
        
        file_put_contents($path, $log, FILE_APPEND | LOCK_EX);

    }

    function LogBackground($message){
        $path = __DIR__."/background.log";
        $date = date("Y-m-d H:i:s");
        $log = "Time: ".$date."   Log message: ".$message."\n";
        
        file_put_contents($path, $log, FILE_APPEND | LOCK_EX);
    }
?>