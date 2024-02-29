<?php
function exportMysqlToCsv($sql_query,$filename = 'export.csv',$mode="",$mode_query="")
{
    include '../db.php';
    $result = mysqli_query($conns,$sql_query);
    
    $f = fopen('php://temp', 'wt');

    $first = true;
    ob_end_clean();
    while ($row = mysqli_fetch_assoc($result)) {

        if ($first) {
            fputcsv($f, array_keys($row));
            $first = false;
        }
        fputcsv($f, $row);
    } // end while
     if(isset($mode) && !empty($mode)){
        if($mode == "update"){
            $q_update = $mode_query;
            $r_up = mysqli_query($conns,$q_update);
        }
    }
    $size = ftell($f);
    rewind($f);

    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: $size");

 // Output to browser with appropriate mime type, you choose ;)
    header("Content-type: text/x-csv");
    header("Content-type: text/csv");
    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=$filename");
    fpassthru($f);
    exit;
 
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

