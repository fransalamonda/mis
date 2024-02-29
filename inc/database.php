<?php
function close_con($con) {
    mysql_close($con);
}
function trans_begin($con) {
    $sql = "SET autocommit = 0";
    return mysql_query($sql,$con);
}
function trans_end($con) {
    $sql = "SET autocommit = 1";
    return mysql_query($sql,$con);
}
function trans_commit($con) {
    trans_end($con);
    $sql = "COMMIT";
    return mysql_query($sql,$con);
}
function trans_rollback($con) {
    trans_end($con);
    $sql = "ROLLBACK";
    return mysql_query($sql,$con);
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

