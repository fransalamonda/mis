<?php
function close_con($con) {
    mysqli_close($con);
}
include "../inc/constant.php";
ini_set('max_execution_time',1000);
$upload_dir = "../uploads/mixdesign/";
$con = @mysqli_connect(DB_SERVER, DB_USER, DB_PASS) or die(json_encode(array("status"=>0,"msg"=>  mysqli_error($con))));
$plant = mysqli_select_db($con,DB_NAME);

if(!isset($_FILES["myfile"]) || !isset($_FILES["myfile2"])){
    $output = array(
        'msg'    => 'Error : #101 You have to input the 2 files (BMA & BMB)! ', 
        'status' => 0
    );
        close_con($con);
    exit(json_encode($output));
}
if(empty($_POST['chart_no']) || empty($_POST['chart_no'])){
    $output = array(
        'msg'    => 'Error : #102 You must select version 1 or 2! ', 
        'status' => 0
    );
        close_con($con);
    exit(json_encode($output));
}
if ($_FILES["myfile"]["error"] > 0 || $_FILES["myfile2"]["error"] > 0) {
    $output = array(
        "status"    => 0, 
        "msg"       => "Error : #103 ".$_FILES["myfile"]["error"]
    );
    exit($output);
}
$allowedExts    = array("csv","CSV");
$temp           = explode(".", $_FILES["myfile"]["name"]);
$temp2          = explode(".", $_FILES["myfile2"]["name"]);
$extension      = end($temp);
$extension2     = end($temp2);

if(!in_array($extension,$allowedExts) || !in_array($extension2,$allowedExts)){
    $output = array(
        "status"    => 0, 
        "msg"       => "Error : #104 Only data type .csv can use! "
        );
    close_con($con);
    exit(json_encode($output));
}
$nama_file = date("Ymdhms")."_BMA.csv"; //Nama file yang akan di Upload
$nama_file2 = date("Ymdhms")."_BMB.csv"; //Nama file yang akan di Upload
if(!move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_dir.$nama_file) || !move_uploaded_file($_FILES["myfile2"]["tmp_name"], $upload_dir . $nama_file2)){
    $output = array(
        "msg"       => "Error #105 : Files cannot be uploaded to the directory ".$upload_dir, 
        "status"    => 0
        );close_con($con);
    exit(json_encode($output));   
}
$filename   = $upload_dir.$nama_file;
$filename2  = $upload_dir.$nama_file2;
$handle1    = fopen($filename, "r");
$handle3    = fopen($filename, "r");
$handle2    = fopen("$filename2", "r");
$handle4    = fopen("$filename2", "r");
$handle5    = fopen("$filename2", "r");
$i          = 0;
//cocokan data (machine_code and product_code)
$mach_code      =   array();
$product_code   =   array();
$skip   =   0;//0 row skip once, 1 no skip
while (($data = fgetcsv($handle1, 1000, ",")) !== FALSE)
{
    if($skip > 0){
        //PLANT ID Checking
        if(PLANT_ID != $data[10]){
            $output = array(
                "status"    => 0, 
                "msg"       => "Error #105 PLANT ID not match with the configuration!"
                );close_con($con);
            exit(json_encode($output));
        }
        if($skip >5000){
            $max_pc = $skip - 1;
            $output = array(
                "status"    => 0, 
                "msg"       => "Error #105 Max $max_pc Product code"
            );
                        close_con($con);
                        exit(json_encode($output));
        }
        if(!in_array($data[0].'-'.$data[10],$mach_code)){
            array_push($mach_code,$data[0].'-'.$data[10]);
        }
        else{
            $output = array(
                "status"    => 0, 
                "msg"       => "Mix Design Data has duplication machine code and product code"
                );close_con($con);
            exit(json_encode($output));
        }
    }
    $skip++;
}   
//jika pilihan var 2, var 1 harus ada..
$chartno = $_POST['chart_no']; 
$chark = $chartno - 1 ;
//print_r($chartno);exit();
$skip11=0;
$difference10 = 0;
while (($data2 = fgetcsv($handle5, 2000, ",")) !== FALSE) {
    if($skip11 > 0){
        if ($_POST['chart_no'] > '1'){
                $cn_query = "SELECT * FROM `mix_package_composition` 
                             WHERE `product_code`='".$data2[0]."' AND  
                                    `mch_code` = '".$data2[32]."' AND 
                                    `chart_no` = '$chark'"; 
//                echo $cn_query."<br>";exit();
                $cn_q = mysqli_query($con,$cn_query);
                $count = mysqli_num_rows($cn_q);
                if($count == 0){
                        $output = array(
                                "status"        => 0, 
                                "msg"           => "Error #107 : you must upload <b> version $chark Product Code  .$data2[0].</b>".mysqli_error($con),
                                "difference"    => $difference10
                            );close_con($con);
                    exit(json_encode($output));
                      }
            }
    }
    $skip11++;
  //  print_r($ch_q);exit();
}

            
$difference = 0;
$skip2=0;//0 skip one line, 1 no skip
$difference_list = "";
while (($data2 = fgetcsv($handle2, 1000, ",")) !== FALSE)
{
    if($skip2 > 0){
        if(!in_array($data2[0].'-'.$data2[32],$mach_code)){
            $difference++;
            $difference_list.='<br/>'.$data2[0]."-".$data2[32]." baris ke-".($skip2+1)." BMB";
        }
    }
    $skip2++;
}
//cocokan data (machine_code and product_code) - e
            
//jika tida sesuai dengan table'
if($difference > 0){
    $output = array(
        "status"        => 0, 
        "msg"           => "Error #108: Data is not valid, there\'s difference machine code and product code combination, ".$difference_list,
        "difference"    => $difference
            );close_con($con);
    exit(json_encode($output));
}

//MIX DESIGN - s
$skip = 0;
$count_updated=0;
$updated_list="";
while (($data = fgetcsv($handle3, 10000, ",")) !== FALSE){
    if($skip > 0){
        $string = "SELECT * FROM `mix_package` WHERE `product_code`='".$data[0]."' AND `mch_code` = '".$data[10]."'";
       // echo $string."<br>";exit();
        $q = mysqli_query($con,$string);
        if(!$q){
            //mysqli_error($con)
            $output = array(
                'status'    => 0, 
                'msg'       => 'Error : #109'.mysqli_error($con),
                'difference'=>$difference
                );close_con($con);
            echo json_encode($output);exit();
        }
        $count = mysqli_num_rows($q);
        if($count == 0){
            $a1 = "INSERT INTO `mix_package` "
                    . "(`product_code`,`mch_code`, `slump_code`,`discharge`,`description`,`specification`,`qlt_group`,`flag_code`,`max_size`,`cre_by`,`cre_date`,`upd_by`,`upd_date`) "
                    . "VALUES ('".$data[0]."','".$data[10]."','".$data[18]."','NORMAL','".$data[34]."','".$data[34]."',' ','I','0','DEC',NOW(),'DEC',NOW())";
            //echo "<br>".$a1;
            $a = mysqli_query($con,$a1);
            if($a){
                $i++;
            }
            else{
                $output = array(
                    "status"    => 0, 
                    "msg"       => "Error #110 : ".mysqli_error($con));close_con($con);
                exit(json_encode($output));
            }
        }
        else{
            $row = mysqli_fetch_array($q);
            $query_delete = "DELETE FROM `mix_package` WHERE `mch_code` = '".$data[10]."' AND `product_code`='".$data[0]."'";
            $status_delete = mysqli_query($con,$query_delete);
            $a1 = "INSERT INTO `mix_package` "
                    . "(`product_code`,`mch_code`, `slump_code`,`discharge`,`description`,`specification`,`qlt_group`,`flag_code`,`max_size`,`cre_by`,`cre_date`,`upd_by`,`upd_date`) "
                    . "VALUES ('".$data[0]."','".$data[10]."','".$data[18]."','NORMAL','".$data[34]."','".$data[34]."',' ','I','0','DEC',NOW(),'DEC',NOW())";
            $status_insert = mysqli_query($con,$a1);
            if(!$status_insert || !mysqli_query($con,"DELETE FROM `mix_package_composition` WHERE `product_code`='".$data[0]."' AND `mch_code` = '".$data[10]."' AND `chart_no` = '".$chartno."'")){
                $output = array(
                    'status' => 0, 
                    'msg' => 'Error #111 : '.mysqli_error($con)
                );close_con($con);
                echo json_encode($output);exit();
            }
            else {
                    $i++;
                    $count_updated++;
                    $updated_list.=$data[0]."_".$data[10]."<br/>";
            };//hitung jumlah Mix Design yang terupload
        }
    }
    $skip++;
}//end while
//MIX DESIGN - e
                
//MIX DESIGN COMPOSITION - s
$j      = 0;
$mch    = "";
$skip   = 0;
while(($data2 = fgetcsv($handle4,2000,","))!==FALSE){
        if($skip > 0){
                $string = "SELECT * FROM `mix_package_composition` WHERE `product_code`='".$data2[0]."' AND `mch_code` = '".$data2[32]."' AND `material_code` = '".$data2[2]."' AND `chart_no` = '".$chartno."'";
                $q = mysqli_query($con,$string);
                if(!$q){
                    $output = array(
                        "status"        => 0, 
                        "msg"           => "Error #112 : ".mysqli_error($con),
                        "difference"    => $difference
                            );close_con($con);
                    exit(json_encode($output));
                }
                $count = mysqli_num_rows($q);
                
                if($count == 0){
                    $material_group = "";
                    if($data2[2] == "0000-100030") $material_group = "Water"; //WATER
                    if($data2[2] == "0000-100011") $material_group = "SEMEN OPC TYPE 1"; //CEMENT
                    if($data2[2] == "0000-000015") $material_group = "Fly Ash"; //FA
                    if($data2[2] == "0000-000017") $material_group = "SEMEN OPC TYPE 2"; 
                    if($data2[2] == "0000-000018") $material_group = "SEMEN PCC"; //Cement2
                    if($data2[2] == "0000-100094") $material_group = "Aggregate"; //SAND
                    if($data2[2] == "0000-100089") $material_group = "Aggregate"; //MSAND
                    if($data2[2] == "0000-100090") $material_group = "Aggregate"; //AGG1
                    if($data2[2] == "0002-100001") $material_group = "Admixture"; //P121R
                    if($data2[2] == "0000-100091") $material_group = "Aggregate"; //AGG2
                    if($data2[2] == "0000-100092") $material_group = "Aggregate"; //AGG3F
                    if($data2[2] == "0000-100009") $material_group = "Aggregate"; //AGG4
                    if($data2[2] == "0000-100038") $material_group = "Aggregate"; //AGG5
                    if($data2[2] == "0000-100039") $material_group = "Aggregate"; //AGG6
                    if($data2[2] == "0000-100093") $material_group = "Aggregate"; //STONEDUST
                    if(substr($data2[2],0,4) == "0002") $material_group = "Admixture";
                    /*if($data2[2] == "0002-100201") $material_group = "Admixture"; //S523N
                    if($data2[2] == "0002-100401") $material_group = "Admixture"; //S523N
                    if($data2[2] == "0002-100402") $material_group = "Admixture"; //S523N
                    if($data2[2] == "0002-100404") $material_group = "Admixture"; //S523N*/
                    $q_compos = "INSERT INTO `mix_package_composition`(`mch_code`,`product_code`,
                                                                       `material_group`,`seq_no`,
                                                                       `material_code`,`material_name`,
                                                                       `mix_qty`,`mix_qty_adj`,
                                                                       `unit`,`flag_code`,`code_trans`,`chart_no`)
                                VALUES ('".$data2[32]."','".$data2[0]."',
                                        '$material_group',' ',
                                        '".$data2[2]."','".$data2[55]."',
                                        '".$data2[5]."','0',
                                        'KG','I','N','$chartno')";
                                $result = mysqli_query($con,$q_compos);
                    if(!$result){
                        $output = array(
                            "status"        => 0, 
                            "msg"           => "Error #113 : ".mysqli_error($con),
                            "difference"    =>$difference
                            );close_con($con);
                        exit(json_encode($output));
                    }
                    else{
                            $j++;//hitung jumlah composition yg terupload
                    }
                }
        }
        $skip++;
}
//MIX DESIGN COMPOSITION - e
if($i > 0){
    if($count_updated > 0){
        echo json_encode(array( 'status'    => 1,
                                'duplicate' => $mch,
                                'msg'       => 'data uploaded, Mix design: '.$i.' data, Material : '.$j.' data, Data updated : '.$updated_list. 'Var'.$chartno)); }
    else echo json_encode(array('status'    =>  1,'duplicate'   =>  $mch,'msg'=>'data uploaded, Mix design: '.$i.' data, Material : '.$j.' data Var'.$chartno));
}
else{
    echo json_encode(array('status' =>  2,'msg'=>'There\'s no updated or inserted data'));
}
close_con($con);
