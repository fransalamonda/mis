<?php
require "config/config.php"; // connection details
error_reporting(0);// With this no error reporting will be there
//////////
/////////////////////////////////////////////////////////////////////////////
$endrecord=$_GET['endrecord'];// 
if(strlen($endrecord) > 0 AND (!is_numeric($endrecord))){
echo "Data Error";
exit;
} 

$limit=10; // Number of records per page
//$count=$dbo->prepare("select id from student ");
//$count->execute();
//$nume=$count->rowCount(); // Total number of records
//$nume = $dbo->query("select id from student")->fetchColumn(); 
$nume = 35;

if($endrecord < $limit) {$endrecord = 0;}

switch($_GET['direction'])   // Let us know forward or backward button is pressed
{
case "fw":
$eu = $endrecord ;
break;

case "bk":
$eu = $endrecord - 2*$limit;
break;

default:
echo "Data Error";
exit;
break;
}
if($eu < 0){$eu=0;}
$endrecord =$eu+$limit;


$sql="SELECT old,new as baru FROM limit $eu,$limit"; 
$row=$dbo->prepare($sql);
$row->execute();
$result=$row->fetchAll(PDO::FETCH_ASSOC);


if(($endrecord) < $nume ){$end="yes";}
else{$end="no";}

if(($endrecord) > $limit ){$startrecord="yes";}
else{$startrecord="no";}

$main = array('data'=>$result,'value'=>array("endrecord"=>"$endrecord","limit"=>"$limit","end"=>"$end","startrecord"=>"$startrecord"));
echo json_encode($main); 



////////////End of script /////////////////////////////////////////////////////////////////////////////////




?>