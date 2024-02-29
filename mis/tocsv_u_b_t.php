<?php
include "exportcsv.inc.php";
include "db.php";
//print_r($_POST);
if(isset($_POST['table']) && !empty($_POST['table'])){
	extract($_POST);
	//echo $table;
	if($table == "docket"){
		$csv_table = "batch_plant_docket";
		$output=$code."-Batch_Plant_Doc";
	}
	elseif($table == "material"){
		$csv_table = "batch_plant_material_issue";
		$output=$code."-Batch_Plant_Mat_Is";
	}
	elseif($table == "batch_transaction"){
            
            $condition = " WHERE `delv_date` = '".$filter."'";
		$csv_table = "`batch_transaction`".$condition;
		$output="BT-Export-".date("dmYHis");
	}
        /*
         * author dinov
         * 20141008 -> modifikasi untuk file upload_batch_transaction , penambahan format export filename menjadi tgldocketHis_MAchCode_docket/Mat.is.csv
         */
        elseif($table == "docket_BTU"){
            $csv_table = "batch_plant_docket";
            $output = $code."_Docket";
        }
        elseif($table == "material_BTU"){
            $csv_table = "batch_plant_material_issue";
            $output = $code."_Mat.is";
        }
	exportMysqlToCsv($csv_table,$output.".csv");
}

?>