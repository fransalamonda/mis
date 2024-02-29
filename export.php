<?php
include('db.php');


if(isset($_POST['table']) && !empty($_POST['table'])){
	extract($_POST);
	if($table == 'bt'){
		$select_table=mysql_query('select * from `batch_plant_docket`');
	}
	if($table == 'btd'){
		$select_table=mysql_query('select * from `batch_transaction_detail`');
	}
}
//select table to export the data
//header to give the order to the browser
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=exported-data_'.$table.'.csv');
$rows = mysql_fetch_assoc($select_table);
//print_r($rows);
if ($rows){
	
	getcsv(array_keys($rows));
}
while($rows){
	//echo $rows['column1']."<br>";
	getcsv($rows);
	$rows = mysql_fetch_assoc($select_table);
}

// get total number of fields present in the database
function getcsv($no_of_field_names){
	$separate = '';


	// do the action for all field names as field name
	foreach ($no_of_field_names as $field_name){
		if (preg_match('/\\r|\\n|,|"/', $field_name)){
			$field_name	= '' . str_replace('', $field_name,$field_name) . '';
		}
		echo $separate . $field_name;

		//sepearte with the comma
		$separate = ',';
	}

	//make new row and line
	echo "\r\n";
}
?>