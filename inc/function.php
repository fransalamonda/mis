<?php
function write_log($file,$log_msg){
    if(file_exists($file) == 0){ 
        $myfile = fopen($file, "w");
        //file_put_contents($file, "\n===================================", FILE_APPEND | LOCK_EX);
        file_put_contents($file, $log_msg, FILE_APPEND | LOCK_EX);
        if(!$myfile){
            echo json_encode(array('status'=>0,'msg'=>"Cannot open log file"));
        }
        fclose($myfile);
        exit();
    }
    else{
        file_put_contents($file, $log_msg, FILE_APPEND | LOCK_EX);
    }
}


/*
 * 
 */
function trim_value(&$value){ 
    $value = trim($value); 
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

