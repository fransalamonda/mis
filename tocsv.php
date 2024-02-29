<?php
include "exportcsv.inc.php";
include "db.php";
if(isset($_POST['table']) && !empty($_POST['table'])){
	extract($_POST);
	if($table == "tmp_ds"){
		$csv_table = "tmp_delivery_schedule";
	}
	elseif($table == "temp_docket"){
		$csv_table = "tmp_batch_plant_docket";
	}
	elseif($table == "temp_material"){
		$csv_table = "tmp_batch_plant_material_issue";
	}
	elseif($table == "mix_design"){
		$csv_table = "tmp_mix_design";
	}
	elseif($table == "docket"){
		$csv_table = "batch_plant_docket";
	}
	elseif($table == "material"){
		$csv_table = "batch_plant_material_issue";
	}
	exportMysqlToCsv($csv_table,$csv_table.".csv");
}

?>