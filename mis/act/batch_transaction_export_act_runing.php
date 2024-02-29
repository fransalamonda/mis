<?php
/*
 * revisi 1 141022 by IAD : bug fixing download file filtered only by date
 * revisi 2 150909 by FSM : add reaport obat
 */
require_once '../lib/Excel.class.php';
require_once '../lib/PHPExcel.php';
include '../inc/constant.php';
include '../lib/mis.php';
date_default_timezone_set("Asia/Jakarta");


if( isset($_POST['filter']) && !empty($_POST['filter'])){  
    
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $mis = new mis();
    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }
    $output_name = $_POST['filter'].date("His")."_".PLANT_ID."_produk.xls";

            
            
    //filter berdasarkan delv_date saja 20141021
    $query_select = "SELECT A.*,B.slump_code,B.description,C.process_code,C.remarks,D.`desc` FROM `batch_transaction` A "
            . "INNER JOIN `mix_design` B ON A.`product_code` = B.`product_code` "
            . "LEFT JOIN `batch_transaction2` C ON A.`docket_no`=C.`docket_no` "
            . "LEFT JOIN `tbl_code_acceptance` D ON C.`process_code` = D.`code`"
            . "WHERE A.`delv_date` = '".$_POST['filter']."' AND B.`mch_code`='".PLANT_ID."' ORDER BY delv_time";
//    echo $query_select;exit();
    if ($result = $mysqli->query($query_select)) {
        $excel = new PHPExcel();
        //activate worksheet number 1
        $excel->setActiveSheetIndex(0);
        //name the worksheet
        $excel->getActiveSheet()->setTitle('Sheet');
        //set cell A1 content with some text
        //TITLE
        $ymd = DateTime::createFromFormat('Ymd', $_POST['filter'])->format('Y M d');
        $excel->getActiveSheet()->setCellValue('A1', "BATCH TRANSACTION FULL REPORT( ".$ymd." )");
        //change the font size
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        
        //merge cell A1 until D1
        $excel->getActiveSheet()->mergeCells('A1:BX1');
        //set aligment to center for that merged cell (A1 to D1)
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sharedStyleTitles = new PHPExcel_Style();
        $sharedStyleTitles->applyFromArray(
                array('borders' => 
                    array(
                        'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
                        'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
                        'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
                        'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
                        )
         ));
        $excel->getActiveSheet()->setSharedStyle($sharedStyleTitles, 'A2:BX4');
        
        
        //make the font become bold
        $excel->getActiveSheet()->getStyle('A1:BX4')->getFont()->setBold(true);
        
        
        //HEADER
        $excel->getActiveSheet()->setCellValue('A2', "No");
        $excel->getActiveSheet()->mergeCells('A2:A4');
//        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->setCellValue('B2', "Delivery Date");
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('B2:B4');
        $excel->getActiveSheet()->setCellValue('C2', "Delivery Time");
        $excel->getActiveSheet()->mergeCells('C2:C4');
        $excel->getActiveSheet()->setCellValue('D2', "SO NO");   
        $excel->getActiveSheet()->mergeCells('D2:D4');
        $excel->getActiveSheet()->setCellValue('E2', "Docket No");  //DOC : DEC-20150120-2
        $excel->getActiveSheet()->mergeCells('E2:E4');
        $excel->getActiveSheet()->setCellValue('F2', "Product Code");   //DOC : DEC-20150120-3
        $excel->getActiveSheet()->mergeCells('F2:F4');
        $excel->getActiveSheet()->setCellValue('G2', "Description");    //DOC : DEC-20150120-3
        $excel->getActiveSheet()->mergeCells('G2:G4');
        $excel->getActiveSheet()->setCellValue('H2', "Slump");
        $excel->getActiveSheet()->mergeCells('H2:H4');
        $excel->getActiveSheet()->setCellValue('I2', "Customer");
        $excel->getActiveSheet()->mergeCells('I2:I4');
        $excel->getActiveSheet()->setCellValue('J2', "Location");
        $excel->getActiveSheet()->mergeCells('J2:J4');
        $excel->getActiveSheet()->setCellValue('K2', "Delivery Volume");
        $excel->getActiveSheet()->mergeCells('K2:K4');
        $excel->getActiveSheet()->setCellValue('L2', "Volume Acceptance");
        $excel->getActiveSheet()->mergeCells('L2:L4');
        $excel->getActiveSheet()->setCellValue('M2', "Truck No");
        $excel->getActiveSheet()->mergeCells('M2:M4');
        $excel->getActiveSheet()->setCellValue('N2', "Driver");
        $excel->getActiveSheet()->mergeCells('N2:N4');
        //ACTUAL QTY
        $excel->getActiveSheet()->setCellValue('O2', "Actual Qty");
        $excel->getActiveSheet()->getStyle('O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('O2:AE2');
        $excel->getActiveSheet()->getStyle('O2:AE2')->getFill()->applyFromArray(
                array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'E8D0A9'),
                        'endcolor'   => array('rgb' => 'E8D0A9')
                )
        );
        $excel->getActiveSheet()->setCellValue('O3', "0000-100001");
        $excel->getActiveSheet()->setCellValue('O4', "Pasir");
        $excel->getActiveSheet()->setCellValue('P3', "0000-100002");
        $excel->getActiveSheet()->setCellValue('P4', "MSand");
        $excel->getActiveSheet()->setCellValue('Q3', "0000-100005");
        $excel->getActiveSheet()->setCellValue('Q4', "AGG1");
        $excel->getActiveSheet()->setCellValue('R3', "0000-100006");
        $excel->getActiveSheet()->setCellValue('R4', "AGG2");
        $excel->getActiveSheet()->setCellValue('S3', "0000-100007");
        $excel->getActiveSheet()->setCellValue('S4', "AGG3");
        $excel->getActiveSheet()->setCellValue('T3', "0000-100050");
        $excel->getActiveSheet()->setCellValue('T4', "limestone");
        $excel->getActiveSheet()->setCellValue('U3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('U4', "Semen");
        $excel->getActiveSheet()->setCellValue('V3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('V4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('W3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('W4', "Air");
        $excel->getActiveSheet()->setCellValue('X3', "0002-100001");
        $excel->getActiveSheet()->setCellValue('X4', "P121R");
        $excel->getActiveSheet()->setCellValue('Y3', "0002-100201");
        $excel->getActiveSheet()->setCellValue('Y4', "S523N");
        $excel->getActiveSheet()->setCellValue('Z3', "0002-100006");
        $excel->getActiveSheet()->setCellValue('Z4', "RT6P");
        $excel->getActiveSheet()->setCellValue('AA3', "0002-100409");
        $excel->getActiveSheet()->setCellValue('AA4', "SK163");
        $excel->getActiveSheet()->setCellValue('AB3', "0002-100410");
        $excel->getActiveSheet()->setCellValue('AB4', "SIKATARD930");
        $excel->getActiveSheet()->setCellValue('AC3', "0002-100402");
        $excel->getActiveSheet()->setCellValue('AC4', "VIS 1003");
        $excel->getActiveSheet()->setCellValue('AD3', "0002-100407");
        $excel->getActiveSheet()->setCellValue('AD4', "VIS 1221R");
        $excel->getActiveSheet()->setCellValue('AE3', "0002-100416");
        $excel->getActiveSheet()->setCellValue('AE4', "VIS 7080P");
        
        //TARGET QTY
        $excel->getActiveSheet()->setCellValue('AF2', "Target Qty");
        $excel->getActiveSheet()->getStyle('AF2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AF2:AV2');
        $excel->getActiveSheet()->getStyle('AF2:AV2')->getFill()->applyFromArray(
                array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'D8DEAE'),
                        'endcolor'   => array('rgb' => 'D8DEAE')
                )
        );
        $excel->getActiveSheet()->setCellValue('AF3', "0000-100001");
        $excel->getActiveSheet()->setCellValue('AF4', "Pasir");
        $excel->getActiveSheet()->setCellValue('AG3', "0000-100002");
        $excel->getActiveSheet()->setCellValue('AG4', "MSand");
        $excel->getActiveSheet()->setCellValue('AH3', "0000-100005");
        $excel->getActiveSheet()->setCellValue('AH4', "AGG1");
        $excel->getActiveSheet()->setCellValue('AI3', "0000-100006");
        $excel->getActiveSheet()->setCellValue('AI4', "AGG2");
        $excel->getActiveSheet()->setCellValue('AJ3', "0000-100007");
        $excel->getActiveSheet()->setCellValue('AJ4', "AGG3");
        $excel->getActiveSheet()->setCellValue('AK3', "0000-100050");
        $excel->getActiveSheet()->setCellValue('AK4', "limestone");
        $excel->getActiveSheet()->setCellValue('AL3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('AL4', "Semen");
        $excel->getActiveSheet()->setCellValue('AM3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('AM4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('AN3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('AN4', "Air");
        $excel->getActiveSheet()->setCellValue('AO3', "0002-100001");
        $excel->getActiveSheet()->setCellValue('AO4', "P121R");
        $excel->getActiveSheet()->setCellValue('AP3', "0002-100201");
        $excel->getActiveSheet()->setCellValue('AP4', "S523N");
        $excel->getActiveSheet()->setCellValue('AQ3', "0002-100006");
        $excel->getActiveSheet()->setCellValue('AQ4', "RT6P");
        $excel->getActiveSheet()->setCellValue('AR3', "0002-100409");
        $excel->getActiveSheet()->setCellValue('AR4', "SK163");
        $excel->getActiveSheet()->setCellValue('AS3', "0002-100410");
        $excel->getActiveSheet()->setCellValue('AS4', "SIKATARD930");
        $excel->getActiveSheet()->setCellValue('AT3', "0002-100402");
        $excel->getActiveSheet()->setCellValue('AT4', "VIS 1003");
        $excel->getActiveSheet()->setCellValue('AU3', "0002-100407");
        $excel->getActiveSheet()->setCellValue('AU4', "VIS 1221R");
        $excel->getActiveSheet()->setCellValue('AV3', "0002-100416");
        $excel->getActiveSheet()->setCellValue('AV4', "VIS 7080P");
        
        
        //MOISTURE 
        $excel->getActiveSheet()->setCellValue('AW2', "Moisture");
        $excel->getActiveSheet()->getStyle('AW2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AW2:BA2');
        $excel->getActiveSheet()->getStyle('AW2:BA2')->getFill()->applyFromArray(
                array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'C1DAD6'),
                        'endcolor'   => array('rgb' => 'C1DAD6')
                )
        );
        $excel->getActiveSheet()->setCellValue('AW3', "0000-100001");
        $excel->getActiveSheet()->setCellValue('AW4', "Pasir");
        $excel->getActiveSheet()->setCellValue('AX3', "0000-100002");
        $excel->getActiveSheet()->setCellValue('AX4', "MSand");
        $excel->getActiveSheet()->setCellValue('AY3', "0000-100005");
        $excel->getActiveSheet()->setCellValue('AY4', "AGG1");
        $excel->getActiveSheet()->setCellValue('AZ3', "0000-100006");
        $excel->getActiveSheet()->setCellValue('AZ4', "AGG2");
        $excel->getActiveSheet()->setCellValue('BA3', "0000-100007");
        $excel->getActiveSheet()->setCellValue('BA4', "AGG3");
        
        //VARIANCE
        $excel->getActiveSheet()->setCellValue('BB2', "Variance Percentage");
        $excel->getActiveSheet()->getStyle('BB2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('BB2:BR2');
        $excel->getActiveSheet()->getStyle('BB2:BR2')->getFill()->applyFromArray(
                array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => '7DB6D5'),
                        'endcolor'   => array('rgb' => '7DB6D5')
                )
        );
        $excel->getActiveSheet()->setCellValue('BB3', "0000-100001");
        $excel->getActiveSheet()->setCellValue('BB4', "Pasir");
        $excel->getActiveSheet()->setCellValue('BC3', "0000-100002");
        $excel->getActiveSheet()->setCellValue('BC4', "MSand");
        $excel->getActiveSheet()->setCellValue('BD3', "0000-100005");
        $excel->getActiveSheet()->setCellValue('BD4', "AGG1");
        $excel->getActiveSheet()->setCellValue('BE3', "0000-100006");
        $excel->getActiveSheet()->setCellValue('BE4', "AGG2");
        $excel->getActiveSheet()->setCellValue('BF3', "0000-100007");
        $excel->getActiveSheet()->setCellValue('BF4', "AGG3");
        $excel->getActiveSheet()->setCellValue('BG3', "0000-100050");
        $excel->getActiveSheet()->setCellValue('BG4', "limestone");
        $excel->getActiveSheet()->setCellValue('BH3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('BH4', "Semen");
        $excel->getActiveSheet()->setCellValue('BI3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('BI4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('BJ3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('BJ4', "Air");
        $excel->getActiveSheet()->setCellValue('BK3', "0002-100001");
        $excel->getActiveSheet()->setCellValue('BK4', "P121R");
        $excel->getActiveSheet()->setCellValue('BL3', "0002-100201");
        $excel->getActiveSheet()->setCellValue('BL4', "S523N");
        $excel->getActiveSheet()->setCellValue('BM3', "0002-100006");
        $excel->getActiveSheet()->setCellValue('BM4', "RT6P");
        $excel->getActiveSheet()->setCellValue('BN3', "0002-100409");
        $excel->getActiveSheet()->setCellValue('BN4', "SK163");
        $excel->getActiveSheet()->setCellValue('BO3', "0002-100410");
        $excel->getActiveSheet()->setCellValue('BO4', "SIKATARD930");
        $excel->getActiveSheet()->setCellValue('BP3', "0002-100402");
        $excel->getActiveSheet()->setCellValue('BP4', "VIS 1003");
        $excel->getActiveSheet()->setCellValue('BQ3', "0002-100407");
        $excel->getActiveSheet()->setCellValue('BQ4', "VIS 1221R");
        $excel->getActiveSheet()->setCellValue('BR3', "0002-100416");
        $excel->getActiveSheet()->setCellValue('BR4', "VIS 7080P");
        
        //USAGE
        $excel->getActiveSheet()->setCellValue('BS2', "Usage Percentage");
        $excel->getActiveSheet()->getStyle('BS2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('BS2:BU3');
        $excel->getActiveSheet()->getStyle('BS2:BU3')->getFill()->applyFromArray(
                array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'FFF8DC'),
                        'endcolor'   => array('rgb' => 'FFF8DC')
                )
        );
       
        $excel->getActiveSheet()->setCellValue('BS4', "Sand Percentage");
        $excel->getActiveSheet()->mergeCells('BS4:BS4');
        $excel->getActiveSheet()->setCellValue('BT4', "MSand Percentage");
        $excel->getActiveSheet()->mergeCells('BT4:BT4');
        $excel->getActiveSheet()->setCellValue('BU4', "FA Percentage");
        $excel->getActiveSheet()->mergeCells('BU4:BU4');
        $excel->getActiveSheet()->setCellValue('BV2', "Process Code");
        $excel->getActiveSheet()->mergeCells('BV2:BV4');
        $excel->getActiveSheet()->setCellValue('BW2', "Desc");
        $excel->getActiveSheet()->mergeCells('BW2:BW4');
        $excel->getActiveSheet()->setCellValue('BX2', "Remarks");
        $excel->getActiveSheet()->mergeCells('BX2:BX4');
        
        $excel->getActiveSheet()->setCellValue('A5', "Total Vol");
        $excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->mergeCells('A5:J5');
        $excel->getActiveSheet()->getStyle('A5:BX5')->getFill()->applyFromArray(
                array(
                        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'D17DEAE'),
                        'endcolor'   => array('rgb' => 'D17DEAE')
                )
        );
        
        $excelColumn = range('A', 'Z');
        $index_excelColumn = 0;
        $row = $rowstart = 6;
        $no=1;
        $delv_vol_sum=0;
        
        while($obj = $result->fetch_object()){
            $delv_vol_sum+=$obj->delv_vol;
            $excel->getActiveSheet()->setCellValue('K5', $delv_vol_sum);
            
            $query_select_total = "SELECT `material_code`,`design_qty`,`actual_qty`,`target_qty` FROM `batch_transaction`,`batch_transaction_detail`
                                    WHERE `batch_transaction`.`docket_no` = `batch_transaction_detail`.`docket_no`
                                    AND `batch_transaction`.`delv_date` = '".$obj->delv_date."'
                                    AND `batch_transaction`.`mch_code`='".PLANT_ID."'";
            if($result_total = $mysqli->query($query_select_total)){
                $act_total_sand     = 0;
                $act_total_msand    = 0;
                $act_total_agg1     = 0;
                $act_total_agg2     = 0;
                $act_total_agg3     = 0;
                $act_total_lms      = 0;
                $act_total_cement   = 0;
                $act_total_fa       = 0;
                $act_total_water    = 0;
                $act_total_p121r    = 0;
                $act_total_s523n    = 0;
                $act_total_skhe     = 0;
                $act_total_vis1003  = 0;
                $act_total_vis1212r = 0;
                $act_total_vis7080p = 0;
                $act_total_rt6p     = 0;
                $act_total_sk163    = 0;
                $act_total_sk930    = 0;
                
                $tar_total_sand     = 0;
                $tar_total_msand    = 0;
                $tar_total_agg1     = 0;
                $tar_total_agg2     = 0;
                $tar_total_agg3     = 0;
                $tar_total_lms      = 0;
                $tar_total_cement   = 0;
                $tar_total_fa       = 0;
                $tar_total_water    = 0;
                $tar_total_p121r    = 0;
                $tar_total_s523n    = 0;
                $tar_total_skhe     = 0;
                $tar_total_vis1003  = 0;
                $tar_total_vis1212r = 0;
                $tar_total_vis7080p = 0;
                $tar_total_rt6p     = 0;
                $tar_total_sk163    = 0;
                $tar_total_sk930    = 0;
                
                while($obj_total = $result_total->fetch_object()){
                    if($obj_total->material_code == "0000-100001"){ //sand / pasir
                        $act_total_sand+=$obj_total->actual_qty;
                        $tar_total_sand+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('O5', $act_total_sand);
                        $excel->getActiveSheet()->setCellValue('AF5', $tar_total_sand);
                    }
                    elseif($obj_total->material_code == "0000-100002"){ //MSand
                        $act_total_msand+=$obj_total->actual_qty;
                        $tar_total_msand+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('P5', $act_total_msand);
                        $excel->getActiveSheet()->setCellValue('AG5', $tar_total_msand);
                    }
                    elseif($obj_total->material_code == "0000-100005"){ //AGG1 
                        $act_total_agg1+=$obj_total->actual_qty;
                        $tar_total_agg1+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('Q5', $act_total_agg1);
                        $excel->getActiveSheet()->setCellValue('AH5', $tar_total_agg1);
                    }
                    elseif($obj_total->material_code == "0000-100006"){ //AGG2
                        $act_total_agg2+=$obj_total->actual_qty;
                        $tar_total_agg2+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('R5', $act_total_agg2);
                        $excel->getActiveSheet()->setCellValue('AI5', $tar_total_agg2);
                    }
                    elseif($obj_total->material_code == "0000-100007"){ //AGG3
                        $act_total_agg3+=$obj_total->actual_qty;
                        $tar_total_agg3+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('S5', $act_total_agg3);
                        $excel->getActiveSheet()->setCellValue('AJ5', $tar_total_agg3);
                    }
                    elseif($obj_total->material_code == "0000-100050"){ //LMS
                        $act_total_lms+=$obj_total->actual_qty;
                        $tar_total_lms+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('T5', $act_total_lms);
                        $excel->getActiveSheet()->setCellValue('AK5', $tar_total_lms);
                    }
                    elseif($obj_total->material_code == "0000-100011"){ //Cement  
                        $act_total_cement+=$obj_total->actual_qty;
                        $tar_total_cement+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('U5', $act_total_cement);
                        $excel->getActiveSheet()->setCellValue('AL5', $tar_total_cement);
                    }
                    elseif($obj_total->material_code == "0000-000015"){ //Flying Ash
                        $act_total_fa+= $obj_total->actual_qty;
                        $tar_total_fa+= $obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('V5', $act_total_fa);
                        $excel->getActiveSheet()->setCellValue('AM5', $tar_total_fa);
                    }
                    elseif($obj_total->material_code == "0000-100030"){ // water / Air 
                        $act_total_water+=$obj_total->actual_qty;
                        $tar_total_water+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('W5', $act_total_water);
                        $excel->getActiveSheet()->setCellValue('AN5', $tar_total_water);
                    }
                    elseif($obj_total->material_code == "0002-100001"){ //P 121 R 
                        $act_total_p121r+=$obj_total->actual_qty;
                        $tar_total_p121r+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('X5', $act_total_p121r);
                        $excel->getActiveSheet()->setCellValue('AO5', $tar_total_p121r);
                    }
                    elseif($obj_total->material_code == "0002-100201"){ //523 N 
                        $act_total_s523n+=$obj_total->actual_qty;
                        $tar_total_s523n+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('Y5', $act_total_s523n);
                        $excel->getActiveSheet()->setCellValue('AP5', $tar_total_s523n);
                    }
                    elseif($obj_total->material_code == "0002-100006"){ //RT6P
                        $act_total_rt6p+=$obj_total->actual_qty;
                        $tar_total_rt6p+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('Z5', $act_total_rt6p);
                        $excel->getActiveSheet()->setCellValue('AQ5', $tar_total_rt6p);
                    }
                    elseif($obj_total->material_code == "0002-100409"){ //SK163
                        $act_total_sk163+=$obj_total->actual_qty;
                        $tar_total_sk163+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AA5', $act_total_sk163);
                        $excel->getActiveSheet()->setCellValue('AR5', $tar_total_sk163);
                    }
                    elseif($obj_total->material_code == "0002-100410"){ //SK930
                        $act_total_sk930+=$obj_total->actual_qty;
                        $tar_total_sk930+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AB5', $act_total_sk930);
                        $excel->getActiveSheet()->setCellValue('AS5', $tar_total_sk930);
                    }
                    elseif($obj_total->material_code == "0002-100402"){ //VIS 1003 
                        $act_total_vis1003+=$obj_total->actual_qty;
                        $tar_total_vis1003+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AC5', $act_total_vis1003);
                        $excel->getActiveSheet()->setCellValue('AT5', $tar_total_vis1003);
                    }
                    elseif($obj_total->material_code == "0002-100407"){ //VIS 1212R
                        $act_total_vis1212r+=$obj_total->actual_qty;
                        $tar_total_vis1212r+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AD5', $act_total_vis1212r);
                        $excel->getActiveSheet()->setCellValue('AU5', $tar_total_vis1212r);
                    }
                    elseif($obj_total->material_code == "0002-100416"){ //VIS 7080p 
                        $act_total_vis7080p+=$obj_totaltotal->actual_qty;
                        $tar_total_vis7080p+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AE5', $act_total_vis7080p);
                        $excel->getActiveSheet()->setCellValue('AV5', $tar_total_vis7080p);
                    }
                }
                
            }
            
            
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $no++);
            $ymd = DateTime::createFromFormat('Ymd', $obj->delv_date)->format('Y-m-d');
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $ymd);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->delv_time);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->so_no);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->docket_no);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->product_code);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->description);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->slump_code);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->cust_name);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->proj_name);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->delv_vol);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, " ");
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->unit_no);
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->driver_name);
            

            $query_select_detail = "SELECT * "
                    . "FROM `batch_transaction_detail` "
                    . "WHERE `so_no` = '".$obj->so_no."' AND `docket_no` = '".$obj->docket_no."'";
            if($result_detail = $mysqli->query($query_select_detail)){
                //actual qty properties
                $act_qty_sand     = 0;
                $act_qty_msand    = 0;
                $act_qty_fa       = 0;
                $act_qty_water    = 0;
                $act_qty_cement   = 0;
                $act_qty_lms      = 0;
                $act_qty_agg1     = 0;
                $act_qty_agg2     = 0;
                $act_qty_agg3     = 0;
                $act_qty_p121r    = 0;
                $act_qty_s523n    = 0;
                $act_qty_skhe     = 0;
                $act_qty_vis1003  = 0;
                $act_qty_vis1212r = 0;
                $act_qty_vis7080p = 0;
                $act_qty_rt6p     = 0;
                $act_qty_sk163    = 0;
                $act_qty_sk930    = 0;
                
                //target qty properties
                $tar_qty_sand     = 0;
                $tar_qty_msand    = 0;
                $tar_qty_fa       = 0;
                $tar_qty_water    = 0;
                $tar_qty_cement   = 0;
                $tar_qty_lms      = 0;
                $tar_qty_agg1     = 0;
                $tar_qty_agg2     = 0;
                $tar_qty_agg3     = 0;
                $tar_qty_p121r    = 0;
                $tar_qty_s523n    = 0;
                $tar_qty_skhe     = 0;
                $tar_qty_vis1003  = 0;
                $tar_qty_vis1212r = 0;
                $tar_qty_vis7080p = 0;
                $tar_qty_rt6p     = 0;
                $tar_qty_sk163    = 0;
                $tar_qty_sk930    = 0;
                
                $all_target_qty   = 0;
                $all_actual_qty   = 0;
                $act_qty_sandd =0;
                while($obj_detail = $result_detail->fetch_object()){     
                    
                    $all_target_qty+=$obj_detail->target_qty;
                    $all_actual_qty+=$obj_detail->actual_qty;
                    
                    if($obj_detail->material_code == "0000-100001"){ //sand / pasir
                        $excel->getActiveSheet()->setCellValue("O".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('AF'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('AW'.$row, $obj_detail->moisture);
                        $act_qty_sand+=$obj_detail->actual_qty;
                        $tar_qty_sand+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100002"){ //MSand
                        $excel->getActiveSheet()->setCellValue("P".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('AG'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('AX'.$row, $obj_detail->moisture);
                        $act_qty_msand+=$obj_detail->actual_qty;
                        $tar_qty_msand+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100005"){ //AGG1 
                        $excel->getActiveSheet()->setCellValue("Q".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('AH'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('AY'.$row, $obj_detail->moisture);
                        $act_qty_agg1+=$obj_detail->actual_qty;
                        $tar_qty_agg1+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100006"){ //AGG2
                        $excel->getActiveSheet()->setCellValue("R".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('AI'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('AZ'.$row, $obj_detail->moisture);
                        $act_qty_agg2+=$obj_detail->actual_qty;
                        $tar_qty_agg2+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100007"){ //AGG3
                        $excel->getActiveSheet()->setCellValue("S".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AJ".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('BA'.$row, $obj_detail->moisture);
                        $act_qty_agg3+=$obj_detail->actual_qty;
                        $tar_qty_agg3+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100050"){ //LMS
                        $excel->getActiveSheet()->setCellValue("T".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AK".$row, $obj_detail->target_qty);
                        $act_qty_lms+=$obj_detail->actual_qty;
                        $tar_qty_lms+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100011"){ //Cement 
                        $excel->getActiveSheet()->setCellValue("U".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AL".$row, $obj_detail->target_qty);
                        $act_qty_cement+=$obj_detail->actual_qty;
                        $tar_qty_cement+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-000015"){ //Flying Ash
                        $excel->getActiveSheet()->setCellValue("V".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AM".$row, $obj_detail->target_qty);
                        $act_qty_fa+= $obj_detail->actual_qty;
                        $tar_qty_fa+= $obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100030"){ // water / Air
                        $excel->getActiveSheet()->setCellValue("W".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AN".$row, $obj_detail->target_qty);
                        $act_qty_water+=$obj_detail->actual_qty;
                        $tar_qty_water+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100001"){ //P 121 R 
                        $excel->getActiveSheet()->setCellValue("X".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AO".$row, $obj_detail->target_qty);
                        $act_qty_p121r+=$obj_detail->actual_qty;
                        $tar_qty_p121r+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100201"){ //523 N 
                        $excel->getActiveSheet()->setCellValue("Y".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AP".$row, $obj_detail->target_qty);
                        $act_qty_s523n+=$obj_detail->actual_qty;
                        $tar_qty_s523n+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100006"){ //RT6P
                        $excel->getActiveSheet()->setCellValue("Z".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AQ".$row, $obj_detail->target_qty);
                        $act_qty_rt6p+=$obj_detail->actual_qty;
                        $tar_qty_rt6p+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100409"){ //SK163
                        $excel->getActiveSheet()->setCellValue("AA".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AR".$row, $obj_detail->target_qty);
                        $act_qty_sk163+=$obj_detail->actual_qty;
                        $tar_qty_sk163+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100410"){ //SK930
                        $excel->getActiveSheet()->setCellValue("AB".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AS".$row, $obj_detail->target_qty);
                        $act_qty_sk930+=$obj_detail->actual_qty;
                        $tar_qty_sk930+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100402"){ //VIS 1003 
                        $excel->getActiveSheet()->setCellValue("AC".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AT".$row, $obj_detail->target_qty);
                        $act_qty_vis1003+=$obj_detail->actual_qty;
                        $tar_qty_vis1003+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100407"){ //VIS 1212R
                        $excel->getActiveSheet()->setCellValue("AD".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AU".$row, $obj_detail->target_qty);
                        $act_qty_vis1212r+=$obj_detail->actual_qty;
                        $tar_qty_vis1212r+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100416"){ //VIS 7080p
                        $excel->getActiveSheet()->setCellValue("AE".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AV".$row, $obj_detail->target_qty);
                        $act_qty_vis7080p+=$obj_detail->actual_qty;
                        $tar_qty_vis7080p+=$obj_detail->target_qty;
                    }
                }
                
                           
                /*
                * SAND , MSand & FA Percentage
                */
                $sand_percentage    = 0;
                $msand_percentage   = 0;
                $fa_percentage      = 0;
                
                if($act_qty_sand+$act_qty_msand == 0){
                    $sand_percentage=0;
                    $msand_percentage=0;
                }
                else{
                    $sand_percentage = 100*($act_qty_sand/($act_qty_sand+$act_qty_msand));
                    $msand_percentage = 100*($act_qty_msand/($act_qty_sand+$act_qty_msand));
                }
                if($act_qty_fa+$act_qty_cement==0){$fa_percentage=0;}
                else{$fa_percentage = 100*($act_qty_fa/($act_qty_fa+$act_qty_cement));}
                
                $excel->getActiveSheet()->setCellValue("BS".$row, round($sand_percentage,2));
                $excel->getActiveSheet()->setCellValue("BT".$row, round($msand_percentage,2));
                $excel->getActiveSheet()->setCellValue("BU".$row, round($fa_percentage,2));
                
                
                /*
                 * variance properties
                 */
                $var_sand     = 0;
                $var_msand    = 0;
                $var_water    = 0;
                $var_fa       = 0;
                $var_cement   = 0;
                $var_agg1     = 0;
                $var_agg2     = 0;
                $var_agg3     = 0;
                $var_p121r    = 0;
                $var_s523n    = 0;
                $var_vis1003  = 0;
                $var_vis1221r = 0;
                $var_vis7080p = 0;
                $var_rt6p     = 0;
                $var_lms      = 0;
                $var_sk163    = 0;
                
                
                
                //sand
                if($tar_qty_sand === 0){
                    $excel->getActiveSheet()->setCellValue("BB".$row,"");
                }
                else{
                    $var_sand = (($act_qty_sand - $tar_qty_sand)/($tar_qty_sand))*100;
                    $excel->getActiveSheet()->setCellValue("BB".$row, round($var_sand,2));
                }
                
                //msand
                if($tar_qty_msand === 0){
                    $excel->getActiveSheet()->setCellValue("BC".$row,"");
                }
                else{
                    $var_msand = (($act_qty_msand - $tar_qty_msand)/($tar_qty_msand))*100;
                    $excel->getActiveSheet()->setCellValue("BC".$row, round($var_msand,2));
                }
                //agg1
                if($tar_qty_agg1 === 0){
                    $excel->getActiveSheet()->setCellValue("BD".$row,"");
                }
                else{$var_agg1 = (($act_qty_agg1 - $tar_qty_agg1)/($tar_qty_agg1))*100;
                    $excel->getActiveSheet()->setCellValue("BD".$row, round($var_agg1,2));
                }
                //agg2
                if($tar_qty_agg2 === 0){
                    $excel->getActiveSheet()->setCellValue("BE".$row,"");
                    }
                else{$var_agg2 = (($act_qty_agg2 - $tar_qty_agg2)/($tar_qty_agg2))*100;
                    $excel->getActiveSheet()->setCellValue("BE".$row, round($var_agg2,2));
                }
                //agg3
                if($tar_qty_agg3 === 0){
                    $excel->getActiveSheet()->setCellValue("BF".$row,"");
                }
                else{$var_agg3 = (($act_qty_agg3 - $tar_qty_agg3)/($tar_qty_agg3))*100;
                    $excel->getActiveSheet()->setCellValue("BF".$row, round($var_agg3,2));
                }
                //lms
                if($tar_qty_lms === 0){
                    $excel->getActiveSheet()->setCellValue("BG".$row,"");
                }
                else{
                    $var_lms = (($act_qty_lms - $tar_qty_lms)/($tar_qty_lms))*100;
                    $excel->getActiveSheet()->setCellValue("BG".$row, round($var_lms,2));
                }
                //sement
                if($tar_qty_cement === 0){//$var_cement = 0;
                    $excel->getActiveSheet()->setCellValue("BH".$row,"");
                }
                else{$var_cement = (($act_qty_cement - $tar_qty_cement)/($tar_qty_cement))*100;}
                $excel->getActiveSheet()->setCellValue("BH".$row, round($var_cement,2));
                
                if($tar_qty_fa === 0){
                    //$var_fa = 0;
                    $excel->getActiveSheet()->setCellValue("BI".$row,"");
                }
                else{$var_fa = (($act_qty_fa - $tar_qty_fa)/($tar_qty_fa))*100;
                    $excel->getActiveSheet()->setCellValue("BI".$row, round($var_fa,2));
                }
                if($tar_qty_water === 0){
                    //$var_water = 0;
                    $excel->getActiveSheet()->setCellValue("BJ".$row,"");
                }
                else{$var_water = (($act_qty_water - $tar_qty_water)/($tar_qty_water))*100;
                        $excel->getActiveSheet()->setCellValue("BJ".$row, round($var_water,2));
                }
                if($tar_qty_p121r === 0){
                  //  $var_p121r = 0;
                    $excel->getActiveSheet()->setCellValue("BK".$row,"");
                }
                else{
                    $var_p121r = (($act_qty_p121r - $tar_qty_p121r)/($tar_qty_p121r))*100;
                    $excel->getActiveSheet()->setCellValue("BK".$row, round($var_p121r,2));
                }
                if($tar_qty_s523n === 0){
                    //$var_s523n = 0;
                    $excel->getActiveSheet()->setCellValue("BL".$row,"");
                }
                else{
                    $var_s523n = (($act_qty_s523n - $tar_qty_s523n)/($tar_qty_s523n))*100;
                    $excel->getActiveSheet()->setCellValue("BL".$row, round($var_s523n,2));
                }
                if ($tar_qty_rt6p === 0){
                    $excel->getActiveSheet()->setCellValue("BM".$row,"");
                }else{
                    $var_rt6p = (($act_qty_rt6p - $tar_qty_rt6p)/($tar_qty_rt6p))*100;
                    $excel->getActiveSheet()->setCellValue("BM".$row, round($var_rt6p,2));
                }
                //sk163
                if($tar_qty_sk163 === 0){
                    $excel->getActiveSheet()->setCellValue("BN".$row,"");
                }else{
                    $var_sk163 = (($act_qty_sk163 - $tar_qty_sk163)/($tar_qty_sk163))*100;
                    $excel->getActiveSheet()->setCellValue("BN".$row, round($var_sk163,2));
                }
                //sk930
                if($tar_qty_sk930 === 0){
                    $excel->getActiveSheet()->setCellValue("BO".$row,"");
                }else{
                    $var_sk930 = (($act_qty_sk930 - $tar_qty_sk930)/($tar_qty_sk930))*100;
                    $excel->getActiveSheet()->setCellValue("BO".$row, round($var_sk930,2));
                }
                //vis1003
                if($tar_qty_vis1003 === 0){
                    $excel->getActiveSheet()->setCellValue("BP".$row,"");
                }
                else{
                    $var_vis1003 = (($act_qty_vis1003 - $tar_qty_vis1003)/($tar_qty_vis1003))*100;
                    $excel->getActiveSheet()->setCellValue("BP".$row, round($var_vis1003,2));
                }
                
                
                if($tar_qty_vis1212r === 0){
                    $excel->getActiveSheet()->setCellValue("BQ".$row,"");
                }
                else{
                    $var_vis1212r = (($act_qty_vis1212r - $tar_qty_vis1212r)/($$tar_qty_vis1212r))*100;
                    $excel->getActiveSheet()->setCellValue("BQ".$row, round($var_vis1212r,2));
                }
                
                
                if($tar_qty_vis7080p === 0){ 
                    //$var_sksf = 0;
                    $excel->getActiveSheet()->setCellValue("BR".$row,"");
                }
                else{
                    $var_vis7080p = (($act_qty_vis7080p - $tar_qty_vis7080p)/($tar_qty_vis7080p))*100;
                    $excel->getActiveSheet()->setCellValue("BR".$row, round($var_vis7080p,2));
                }  
                
            }
            $excel->getActiveSheet()->setCellValue("BV".$row, " ".$obj->process_code);
            $excel->getActiveSheet()->setCellValue("BW".$row, " ".$obj->desc);
            $excel->getActiveSheet()->setCellValue("BX".$row, " ".$obj->remarks);
            $row++;
            $index_excelColumn=0;
        }
        
        //autosize
        $column = $mis->createColumnsArray("BX");
        foreach($column as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $rowakhir = $row - 1;
//        $excel->getActiveSheet()->getStyle('A'.$rowstart.':AU'.$rowakhir)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }
    
    else{
        die(mysqli_error($mysqli));
    }
    $result->close(); 
    unset($obj); 
    

    // Send Header
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="'.$output_name.'"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
	header("Pragma: no-cache");
    header ("Expires: 0");

    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');  
    //force user to download the Excel file without writing it to server's HD
    $objWriter->save('php://output');
}

else{
    echo"missing input";
}
?>