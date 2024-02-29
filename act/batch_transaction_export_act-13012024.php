<?php
/*
 * revisi 2 150909 by FSM : add reaport obat
 * revisi 2 181222 by FSM : add reaport obat SR3200
 */
ini_set('memory_limit', '-1');

include '../inc/constant.php';
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 

date_default_timezone_set("Asia/Jakarta");


if( isset($_POST['filter1']) AND isset($_POST['filter2']) && !empty($_POST['filter1']) AND !empty($_POST['filter2'])){ 
    
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }
    $output_name = $_POST['filter1'].date("His")."_".PLANT_ID."_produk.xls";

            
    //filter berdasarkan delv_date saja 20141021
    $query_select = "SELECT A.*,B.slump_code,B.description,C.process_code,C.received_vol, C.remarks,D.`desc` "
                    . "FROM `batch_transaction` A "
                    . "LEFT JOIN `mix_design` B ON A.`product_code` = B.`product_code` "
                    . "LEFT JOIN `batch_transaction2` C ON A.`docket_no`=C.`docket_no` "
                    . "LEFT JOIN `tbl_code_acceptance` D ON C.`process_code` = D.`code`"
                    . "WHERE A.`delv_date` BETWEEN '".$_POST['filter1']."' AND '".$_POST['filter2']."' "
                            . "AND A.`mch_code`='".PLANT_ID."' ORDER BY delv_date";
//    echo $query_select;exit();
    if ($result = $mysqli->query($query_select)) {

        $excel = new Spreadsheet();

        //activate worksheet number 1
        $excel->setActiveSheetIndex(0);
        //name the worksheet
        $excel->getActiveSheet()->setTitle('Sheet');
        //set cell A1 content with some text
        //TITLE
       $ymd = DateTime::createFromFormat('Ymd', $_POST['filter1'])->format('Y M d');
        $ymd = DateTime::createFromFormat('Ymd', $_POST['filter2'])->format('Y M d');
        $excel->getActiveSheet()->setCellValue('A1', "BATCH TRANSACTION FULL REPORT( ".$ymd." )");
        //change the font size
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);        
        //merge cell A1 until FH1
       $excel->getActiveSheet()->mergeCells('A1:FH1');
        //set aligment to center for that merged cell (A1 to D1)
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
         $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endColor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ];
        $excel->getActiveSheet()->getStyle('A2:FH4')->applyFromArray($styleArray);
        //make the font become bold
        $excel->getActiveSheet()->getStyle('A1:FH4')->getFont()->setBold(true);
        //HEADER
        $excel->getActiveSheet()->setCellValue('A2', "No");
        $excel->getActiveSheet()->mergeCells('A2:A4');
//        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->setCellValue('B2', "Delivery Date");
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
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
        
        //DESIGN QTY
        $excel->getActiveSheet()->setCellValue('O2', "DESIGN QTY");
        $excel->getActiveSheet()->getStyle('O2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('O2:AV2');
        $excel->getActiveSheet()->getStyle('O2:AV2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
        $excel->getActiveSheet()->setCellValue('O3', "0000-100094");
        $excel->getActiveSheet()->setCellValue('O4', "Pasir");
        $excel->getActiveSheet()->setCellValue('P3', "0000-100089");
        $excel->getActiveSheet()->setCellValue('P4', "MSand");
        $excel->getActiveSheet()->setCellValue('Q3', "0000-100093");
        $excel->getActiveSheet()->setCellValue('Q4', "STONEDUST");
        $excel->getActiveSheet()->setCellValue('R3', "0000-100090");
        $excel->getActiveSheet()->setCellValue('R4', "AGG1");
        $excel->getActiveSheet()->setCellValue('S3', "0000-100091");
        $excel->getActiveSheet()->setCellValue('S4', "AGG2");
        $excel->getActiveSheet()->setCellValue('T3', "0000-100092");
        $excel->getActiveSheet()->setCellValue('T4', "AGG3");
        $excel->getActiveSheet()->setCellValue('U3', "0000-000029");
        $excel->getActiveSheet()->setCellValue('U4', "SEMEN B");
        $excel->getActiveSheet()->setCellValue('V3', "0000-100097");
        $excel->getActiveSheet()->setCellValue('V4', "STEEL DUST");
        $excel->getActiveSheet()->setCellValue('W3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('W4', "SEMEN A");
        $excel->getActiveSheet()->setCellValue('X3', "0000-100017");
        $excel->getActiveSheet()->setCellValue('X4', "SEMEN OPC TYPE 2");
        $excel->getActiveSheet()->setCellValue('Y3', "0000-100060");
        $excel->getActiveSheet()->setCellValue('Y4', "SEMEN VAS");
        $excel->getActiveSheet()->setCellValue('Z3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('Z4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('AA3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('AA4', "Air");
        $excel->getActiveSheet()->setCellValue('AB3', "0002-100001");
        $excel->getActiveSheet()->setCellValue('AB4', "FLOBAS RPF-34");
        $excel->getActiveSheet()->setCellValue('AC3', "0002-100442");
        $excel->getActiveSheet()->setCellValue('AC4', "S523N");
        $excel->getActiveSheet()->setCellValue('AD3', "0002-100015");
        $excel->getActiveSheet()->setCellValue('AD4', "PLAST 83 AM");
        $excel->getActiveSheet()->setCellValue('AE3', "0002-100020");
        $excel->getActiveSheet()->setCellValue('AE4', "SBT CON-M");
        $excel->getActiveSheet()->setCellValue('AF3', "0002-100202");
        $excel->getActiveSheet()->setCellValue('AF4', "SBT JM-9");
        $excel->getActiveSheet()->setCellValue('AG3', "0000-100021");  // SEMEN C
        $excel->getActiveSheet()->setCellValue('AG4', "SEMEN C"); 
        $excel->getActiveSheet()->setCellValue('AH3', "0000-100063");
        $excel->getActiveSheet()->setCellValue('AH4', "AGG1 VAS");
        $excel->getActiveSheet()->setCellValue('AI3', "0000-100064");
        $excel->getActiveSheet()->setCellValue('AI4', "AGG2 VAS");
        $excel->getActiveSheet()->setCellValue('AJ3', "0000-100065");
        $excel->getActiveSheet()->setCellValue('AJ4', "AGG3 VAS");
        $excel->getActiveSheet()->setCellValue('AK3', "0000-100061");
        $excel->getActiveSheet()->setCellValue('AK4', "SAND VAS");
        $excel->getActiveSheet()->setCellValue('AL3', "0000-100062");
        $excel->getActiveSheet()->setCellValue('AL4', "STONE DUST VAS");
        $excel->getActiveSheet()->setCellValue('AM3', "0002-100015");
        $excel->getActiveSheet()->setCellValue('AM4', "P83");
        $excel->getActiveSheet()->setCellValue('AN3', "0002-100017");
        $excel->getActiveSheet()->setCellValue('AN4', "SIKAMENT183");
        $excel->getActiveSheet()->setCellValue('AO3', "0002-100449");
        $excel->getActiveSheet()->setCellValue('AO4', "FLBPD-14");
        $excel->getActiveSheet()->setCellValue('AP3', "0002-100021");
        $excel->getActiveSheet()->setCellValue('AP4', "SBT PCA-8S");
        $excel->getActiveSheet()->setCellValue('AQ3', "0002-100437");
        $excel->getActiveSheet()->setCellValue('AQ4', "GENR8212");
        $excel->getActiveSheet()->setCellValue('AR3', "0002-100438");
        $excel->getActiveSheet()->setCellValue('AR4', "GET702");
        $excel->getActiveSheet()->setCellValue('AS3', "0002-100439");
        $excel->getActiveSheet()->setCellValue('AS4', "GENB1714");
        $excel->getActiveSheet()->setCellValue('AT3', "0002-100430");
        $excel->getActiveSheet()->setCellValue('AT4', "VIS 3660LR");
        $excel->getActiveSheet()->setCellValue('AU3', "0002-100440");
        $excel->getActiveSheet()->setCellValue('AU4', "FLBNF-15");
        $excel->getActiveSheet()->setCellValue('AV3', "0002-100441");
        $excel->getActiveSheet()->setCellValue('AV4', "FLBPD-19");
       //SR3200
       //0002-100010


        //ACTUAL QTY
        $excel->getActiveSheet()->setCellValue('AW2', "Actual Qty");
        $excel->getActiveSheet()->getStyle('AW2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AW2:CD2');
        $excel->getActiveSheet()->getStyle('AW2:CD2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E8D0A9');
        $excel->getActiveSheet()->setCellValue('AW3', "0000-100094");
        $excel->getActiveSheet()->setCellValue('AW4', "Pasir");
        $excel->getActiveSheet()->setCellValue('AX3', "0000-100089");
        $excel->getActiveSheet()->setCellValue('AX4', "MSand");
        $excel->getActiveSheet()->setCellValue('AY3', "0000-100093");
        $excel->getActiveSheet()->setCellValue('AY4', "STONEDUST");
        $excel->getActiveSheet()->setCellValue('AZ3', "0000-100090");
        $excel->getActiveSheet()->setCellValue('AZ4', "AGG1");
        $excel->getActiveSheet()->setCellValue('BA3', "0000-100091");
        $excel->getActiveSheet()->setCellValue('BA4', "AGG2");
        $excel->getActiveSheet()->setCellValue('BB3', "0000-100092");
        $excel->getActiveSheet()->setCellValue('BB4', "AGG3");
        $excel->getActiveSheet()->setCellValue('BC3', "0000-000029");
        $excel->getActiveSheet()->setCellValue('BC4', "SEMEN B");
        $excel->getActiveSheet()->setCellValue('BD3', "0000-100097");
        $excel->getActiveSheet()->setCellValue('BD4', "STEEL DUST");
        $excel->getActiveSheet()->setCellValue('BE3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('BE4', "SEMEN A");
        $excel->getActiveSheet()->setCellValue('BF3', "0000-100017");
        $excel->getActiveSheet()->setCellValue('BF4', "SEMEN OPC TYPE 2");
        $excel->getActiveSheet()->setCellValue('BG3', "0000-100060");
        $excel->getActiveSheet()->setCellValue('BG4', "SEMEN VAS");
        $excel->getActiveSheet()->setCellValue('BH3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('BH4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('BI3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('BI4', "Air");
        $excel->getActiveSheet()->setCellValue('BJ3', "0002-100001");
        $excel->getActiveSheet()->setCellValue('BJ4', "FLOBAS RPF-34");
        $excel->getActiveSheet()->setCellValue('BK3', "0002-100442");
        $excel->getActiveSheet()->setCellValue('BK4', "S523N");
        $excel->getActiveSheet()->setCellValue('BL3', "0002-100015");
        $excel->getActiveSheet()->setCellValue('BL4', "PLAST 83 AM");
        $excel->getActiveSheet()->setCellValue('BM3', "0002-100020");
        $excel->getActiveSheet()->setCellValue('BM4', "SBT CON-M");
        $excel->getActiveSheet()->setCellValue('BN3', "0002-100202");
        $excel->getActiveSheet()->setCellValue('BN4', "SBT JM-9");
        $excel->getActiveSheet()->setCellValue('BO3', "0002-100402");
        $excel->getActiveSheet()->setCellValue('BO4', "VIS 1003");
        $excel->getActiveSheet()->setCellValue('BP3', "0000-100063");
        $excel->getActiveSheet()->setCellValue('BP4', "AGG1 VAS");
        $excel->getActiveSheet()->setCellValue('BQ3', "0000-100064");
        $excel->getActiveSheet()->setCellValue('BQ4', "AGG2 VAS");
        $excel->getActiveSheet()->setCellValue('BR3', "0000-100065");
        $excel->getActiveSheet()->setCellValue('BR4', "AGG3 VAS");
        $excel->getActiveSheet()->setCellValue('BS3', "0000-100061");
        $excel->getActiveSheet()->setCellValue('BS4', "SAND VAS");
        $excel->getActiveSheet()->setCellValue('BT3', "0000-100062");
        $excel->getActiveSheet()->setCellValue('BT4', "STONE DUST VAS");
        $excel->getActiveSheet()->setCellValue('BU3', "0002-100015");
        $excel->getActiveSheet()->setCellValue('BU4', "P83");
        $excel->getActiveSheet()->setCellValue('BV3', "0002-100017");
        $excel->getActiveSheet()->setCellValue('BV4', "SIKAMENT183");
        $excel->getActiveSheet()->setCellValue('BW3', "0002-100449");
        $excel->getActiveSheet()->setCellValue('BW4', "FLBPD-14");
        $excel->getActiveSheet()->setCellValue('BX3', "0002-100021");
        $excel->getActiveSheet()->setCellValue('BX4', "SBT PCA-8S");
        $excel->getActiveSheet()->setCellValue('BY3', "0002-100437");
        $excel->getActiveSheet()->setCellValue('BY4', "GENR8212");
        $excel->getActiveSheet()->setCellValue('BZ3', "0002-100438");
        $excel->getActiveSheet()->setCellValue('BZ4', "GET702");
        $excel->getActiveSheet()->setCellValue('CA3', "0002-100439");
        $excel->getActiveSheet()->setCellValue('CA4', "GENB1714");
        $excel->getActiveSheet()->setCellValue('CB3', "0002-100430");
        $excel->getActiveSheet()->setCellValue('CB4', "VIS 3660LR");
        $excel->getActiveSheet()->setCellValue('CC3', "0002-100440");
        $excel->getActiveSheet()->setCellValue('CC4', "FLBNF-15");
        $excel->getActiveSheet()->setCellValue('CD3', "0002-100441");
        $excel->getActiveSheet()->setCellValue('CD4', "FLBPD-19");
       //SR3200
       //0002-100010

        
        
        //TARGET QTY
        $excel->getActiveSheet()->setCellValue('CE2', "Target Qty");
        $excel->getActiveSheet()->getStyle('CE2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('CE2:DL2');
        $excel->getActiveSheet()->getStyle('CE2:DL2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D8DEAE');
        $excel->getActiveSheet()->setCellValue('CE3', "0000-100094");
        $excel->getActiveSheet()->setCellValue('CE4', "Pasir");
        $excel->getActiveSheet()->setCellValue('CF3', "0000-100089");
        $excel->getActiveSheet()->setCellValue('CF4', "MSand");
        $excel->getActiveSheet()->setCellValue('CG3', "0000-100093");
        $excel->getActiveSheet()->setCellValue('CG4', "STONEDUST");
        $excel->getActiveSheet()->setCellValue('CH3', "0000-100090");
        $excel->getActiveSheet()->setCellValue('CH4', "AGG1");
        $excel->getActiveSheet()->setCellValue('CI3', "0000-100091");
        $excel->getActiveSheet()->setCellValue('CI4', "AGG2");
        $excel->getActiveSheet()->setCellValue('CJ3', "0000-100092");
        $excel->getActiveSheet()->setCellValue('CJ4', "AGG3");
        $excel->getActiveSheet()->setCellValue('CK3', "0000-000029");
        $excel->getActiveSheet()->setCellValue('CK4', "SEMEN B");
        $excel->getActiveSheet()->setCellValue('CL3', "0000-100097");
        $excel->getActiveSheet()->setCellValue('CL4', "STEEL DUST");   
        $excel->getActiveSheet()->setCellValue('CM3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('CM4', "SEMEN A");
        $excel->getActiveSheet()->setCellValue('CN3', "0000-100017");
        $excel->getActiveSheet()->setCellValue('CN4', "SEMEN OPC TYPE 2");
        $excel->getActiveSheet()->setCellValue('CO3', "0000-100060");
        $excel->getActiveSheet()->setCellValue('CO4', "SEMEN VAS");
        $excel->getActiveSheet()->setCellValue('CP3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('CP4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('CQ3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('CQ3', "Air");
        $excel->getActiveSheet()->setCellValue('CR3', "0002-100001");
        $excel->getActiveSheet()->setCellValue('CR4', "FLOBAS RPF-34");
        $excel->getActiveSheet()->setCellValue('CS3', "0002-100442");
        $excel->getActiveSheet()->setCellValue('CS4', "S523N");
        $excel->getActiveSheet()->setCellValue('CT3', "0002-100015");
        $excel->getActiveSheet()->setCellValue('CT4', "PLAST 83 AM");
        $excel->getActiveSheet()->setCellValue('CU3', "0002-100020");
        $excel->getActiveSheet()->setCellValue('CU4', "SBT CON-M");
        $excel->getActiveSheet()->setCellValue('CV3', "0002-100202");
        $excel->getActiveSheet()->setCellValue('CV4', "SBT JM-9");
        $excel->getActiveSheet()->setCellValue('CW3', "0002-100402");
        $excel->getActiveSheet()->setCellValue('CW4', "VIS 1003");
        $excel->getActiveSheet()->setCellValue('CX3', "0000-100063");
        $excel->getActiveSheet()->setCellValue('CX4', "AGG1 VAS");
        $excel->getActiveSheet()->setCellValue('CY3', "0000-100064");
        $excel->getActiveSheet()->setCellValue('CY4', "AGG2 VAS");
        $excel->getActiveSheet()->setCellValue('CZ3', "0000-100065");
        $excel->getActiveSheet()->setCellValue('CZ4', "AGG3 VAS");
        $excel->getActiveSheet()->setCellValue('DA3', "0000-100061");
        $excel->getActiveSheet()->setCellValue('DA4', "SAND VAS");
        $excel->getActiveSheet()->setCellValue('DB3', "0000-100062");
        $excel->getActiveSheet()->setCellValue('DB4', "STONE DUST VAS");
        $excel->getActiveSheet()->setCellValue('DC3', "0002-100015");
        $excel->getActiveSheet()->setCellValue('DC4', "P83");
        $excel->getActiveSheet()->setCellValue('DD3', "0002-100017");
        $excel->getActiveSheet()->setCellValue('DD4', "SIKAMENT183");
        $excel->getActiveSheet()->setCellValue('DE3', "0002-100449");
        $excel->getActiveSheet()->setCellValue('DE4', "FLBPD-14");
        $excel->getActiveSheet()->setCellValue('DF3', "0002-100021");
        $excel->getActiveSheet()->setCellValue('DF4', "SBT PCA-8S");
        $excel->getActiveSheet()->setCellValue('DG3', "0002-100437");
        $excel->getActiveSheet()->setCellValue('DG4', "GENR8212");
        $excel->getActiveSheet()->setCellValue('DH3', "0002-100438");
        $excel->getActiveSheet()->setCellValue('DH4', "GET702");
        $excel->getActiveSheet()->setCellValue('DI3', "0002-100439");
        $excel->getActiveSheet()->setCellValue('DI4', "GENB1714");
        $excel->getActiveSheet()->setCellValue('DJ3', "0002-100430");
        $excel->getActiveSheet()->setCellValue('DJ4', "VIS3660LR");
        $excel->getActiveSheet()->setCellValue('DK3', "0002-100440");
        $excel->getActiveSheet()->setCellValue('DK4', "FLBNF-15");
        $excel->getActiveSheet()->setCellValue('DL3', "0002-100441");
        $excel->getActiveSheet()->setCellValue('DL4', "FLBPD-19");
       
        //SR3200
       //0002-100010
        
        
        //MOISTURE 
         $excel->getActiveSheet()->setCellValue('DM2', "Moisture");
        $excel->getActiveSheet()->getStyle('DM2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('DM2:DT2');
        $excel->getActiveSheet()->getStyle('DM2:DT2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C1DAD6');
        $excel->getActiveSheet()->setCellValue('DM3', "0000-100094");
        $excel->getActiveSheet()->setCellValue('DM4', "Pasir");
        $excel->getActiveSheet()->setCellValue('DN3', "0000-100089");
        $excel->getActiveSheet()->setCellValue('DN4', "MSand");
        $excel->getActiveSheet()->setCellValue('DO3', "0000-100093");
        $excel->getActiveSheet()->setCellValue('DO4', "STONEDUST");
        $excel->getActiveSheet()->setCellValue('DP3', "0000-100090");
        $excel->getActiveSheet()->setCellValue('DP4', "AGG1");
        $excel->getActiveSheet()->setCellValue('DQ3', "0000-100091");
        $excel->getActiveSheet()->setCellValue('DQ4', "AGG2");
        $excel->getActiveSheet()->setCellValue('DR3', "0000-100092");
        $excel->getActiveSheet()->setCellValue('DR4', "AGG3");
        $excel->getActiveSheet()->setCellValue('DS3', "0000-100038");
        $excel->getActiveSheet()->setCellValue('DS4', "SEMEN B");
        $excel->getActiveSheet()->setCellValue('DT3', "0000-100097");
        $excel->getActiveSheet()->setCellValue('DT4', "STEEL DUST");

        
        //VARIANCE
        $excel->getActiveSheet()->setCellValue('DU2', "Variance Percentage");
        $excel->getActiveSheet()->getStyle('DU2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('DU2:FB2');
         $excel->getActiveSheet()->getStyle('DU2:FB2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('7DB6D5');   
        $excel->getActiveSheet()->setCellValue('DU3', "0000-100094");
        $excel->getActiveSheet()->setCellValue('DU4', "Pasir");
        $excel->getActiveSheet()->setCellValue('DV3', "0000-100089");
        $excel->getActiveSheet()->setCellValue('DV4', "MSand");
        $excel->getActiveSheet()->setCellValue('DW3', "0000-100093");
        $excel->getActiveSheet()->setCellValue('DW4', "STONEDUST");
        $excel->getActiveSheet()->setCellValue('DX3', "0000-100090");
        $excel->getActiveSheet()->setCellValue('DX4', "AGG1");
        $excel->getActiveSheet()->setCellValue('DY3', "0000-100091");
        $excel->getActiveSheet()->setCellValue('DY4', "AGG2");
        $excel->getActiveSheet()->setCellValue('DZ3', "0000-100092");
        $excel->getActiveSheet()->setCellValue('DZ4', "AGG3");
        $excel->getActiveSheet()->setCellValue('EA3', "0000-000029");
        $excel->getActiveSheet()->setCellValue('EA4', "SEMEN B");
        $excel->getActiveSheet()->setCellValue('EB3', "0000-100097");
        $excel->getActiveSheet()->setCellValue('EB4', "STEEL DUST");
        $excel->getActiveSheet()->setCellValue('EC3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('EC4', "SEMEN A");
        $excel->getActiveSheet()->setCellValue('ED3', "0000-100017");
        $excel->getActiveSheet()->setCellValue('ED4', "SEMEN OPC TYPE 2");
        $excel->getActiveSheet()->setCellValue('EE3', "0000-100060");
        $excel->getActiveSheet()->setCellValue('EE4', "SEMEN VAS");
        $excel->getActiveSheet()->setCellValue('EF3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('EF4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('EG3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('EG4', "Air");
        $excel->getActiveSheet()->setCellValue('EH3', "0002-100001");
        $excel->getActiveSheet()->setCellValue('EH4', "FLOBAS RPF-34");
        $excel->getActiveSheet()->setCellValue('EI3', "0002-100442");
        $excel->getActiveSheet()->setCellValue('EI4', "S523N");
        $excel->getActiveSheet()->setCellValue('EJ3', "0002-100015");
        $excel->getActiveSheet()->setCellValue('EJ4', "PLAST 83 AM");
        $excel->getActiveSheet()->setCellValue('EK3', "0002-100020");
        $excel->getActiveSheet()->setCellValue('EK4', "SBT CON-M");
        $excel->getActiveSheet()->setCellValue('EL3', "0002-100202");
        $excel->getActiveSheet()->setCellValue('EL4', "SBT JM-9");
        $excel->getActiveSheet()->setCellValue('EM3', "0002-100402");
        $excel->getActiveSheet()->setCellValue('EM4', "VIS 1003");
        $excel->getActiveSheet()->setCellValue('EN3', "0000-100063");
        $excel->getActiveSheet()->setCellValue('EN4', "AGG1 VAS");
        $excel->getActiveSheet()->setCellValue('EO3', "0000-100064");
        $excel->getActiveSheet()->setCellValue('EO4', "AGG2 VAS");
        $excel->getActiveSheet()->setCellValue('EP3', "0000-100065");
        $excel->getActiveSheet()->setCellValue('EP4', "AGG3 VAS");
        $excel->getActiveSheet()->setCellValue('EQ3', "0000-100061");
        $excel->getActiveSheet()->setCellValue('EQ4', "SAND VAS");
        $excel->getActiveSheet()->setCellValue('ER3', "0000-100062");
        $excel->getActiveSheet()->setCellValue('ER4', "SAND DUST VAS");
        $excel->getActiveSheet()->setCellValue('ES3', "0002-100015");
        $excel->getActiveSheet()->setCellValue('ES4', "P83");
        $excel->getActiveSheet()->setCellValue('ET3', "0002-100017");
        $excel->getActiveSheet()->setCellValue('ET4', "SIKAMENT183");
        $excel->getActiveSheet()->setCellValue('EU3', "0002-100449");
        $excel->getActiveSheet()->setCellValue('EU4', "FLBPD-14");
        $excel->getActiveSheet()->setCellValue('EV3', "0002-100021");
        $excel->getActiveSheet()->setCellValue('EV4', "SBT PC8-8S");
        $excel->getActiveSheet()->setCellValue('EW3', "0002-100437");
        $excel->getActiveSheet()->setCellValue('EW4', "GENR8212");
        $excel->getActiveSheet()->setCellValue('EX3', "0002-100438");
        $excel->getActiveSheet()->setCellValue('EX4', "GET702");
        $excel->getActiveSheet()->setCellValue('EY3', "0002-100439");
        $excel->getActiveSheet()->setCellValue('EY4', "GENB1714");
        $excel->getActiveSheet()->setCellValue('EZ3', "0002-100430");
        $excel->getActiveSheet()->setCellValue('EZ4', "VIS3660LR");
        $excel->getActiveSheet()->setCellValue('FA3', "0002-100440");
        $excel->getActiveSheet()->setCellValue('FA4', "FLBNF-15");
        $excel->getActiveSheet()->setCellValue('FB3', "0002-100441");
        $excel->getActiveSheet()->setCellValue('FB4', "FLBPD-19");
        //SR3200
       //0002-100010
        
        
        //USAGE
        $excel->getActiveSheet()->setCellValue('FC2', "Usage Percentage");
        $excel->getActiveSheet()->getStyle('FC2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('FC2:FE3');
        $excel->getActiveSheet()->getStyle('FC2:FE3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8DC');
       
        $excel->getActiveSheet()->setCellValue('FC4', "Sand Percentage");
//        $excel->getActiveSheet()->mergeCells('BT4:BT4');
        $excel->getActiveSheet()->setCellValue('FD4', "MSand Percentage");
//        $excel->getActiveSheet()->mergeCells('BU4:BU4');
        $excel->getActiveSheet()->setCellValue('FE4', "FA Percentage");
//        $excel->getActiveSheet()->mergeCells('BV4:BV4');
        $excel->getActiveSheet()->setCellValue('FF2', "Process Code");
        $excel->getActiveSheet()->mergeCells('FF2:FF4');
        $excel->getActiveSheet()->setCellValue('FG2', "Desc");
        $excel->getActiveSheet()->mergeCells('FG2:FG4');
        $excel->getActiveSheet()->setCellValue('FH2', "Remarks");
        $excel->getActiveSheet()->mergeCells('FH2:FH4');
        
        $excel->getActiveSheet()->setCellValue('A5', "Total Vol");
        $excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->mergeCells('A5:J5');
        $excel->getActiveSheet()->getStyle('A5:FH5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D17DEAE');

        //DESIGN NORMAL
        
        
        


        $excelColumn = range('A', 'Z');
        $index_excelColumn = 0;
        $row = $rowstart = 6;
        $no=1;
        $delv_vol_sum=0;
        $received_vol_sum=0;
        
        while($obj = $result->fetch_object()){
            //total del vol & act vol
            $delv_vol_sum+=$obj->delv_vol;
            $received_vol_sum+=$obj->received_vol;
            
            $excel->getActiveSheet()->setCellValue('K5', $delv_vol_sum);
            $excel->getActiveSheet()->setCellValue('L5', $received_vol_sum);
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
                $act_total_stldust     = 0;
                $act_total_lms      = 0;
                $act_total_cementA   = 0;
                $act_total_cementtp2   = 0;
                $act_total_cementvas   = 0;
                $act_total_fa       = 0;
                $act_total_water    = 0;
                $act_total_stonedust = 0;
                $act_total_flobasRpf34    = 0;
                $act_total_s523n    = 0;
                $act_total_skhe     = 0;
                $act_total_vis1003  = 0;
                $act_total_agg1vas = 0;
                $act_total_agg2vas = 0;
                $act_total_agg3vas   = 0;
                $act_total_plast83am     = 0;
                $act_total_sbtconm    = 0;
                $act_total_sbtjm9    = 0;
                $act_total_vis3660lr = 0;
                $act_total_semenB = 0;
                $act_total_sandvas = 0;
                $act_total_stonedustvas = 0;
                $act_total_P83 = 0;
                $act_total_SIKAMENT183 = 0;
                $act_total_FLBPD14 = 0;
                $act_total_sbtpca8s = 0;
                $act_total_genr8212 = 0;
                $act_total_get702 = 0;
                $act_total_genb1714 = 0;
                $act_total_flbnf15 = 0;
                $act_total_flbpd19 = 0;

                $tar_total_sand     = 0;
                $tar_total_msand    = 0;
                $tar_total_agg1     = 0;
                $tar_total_agg2     = 0;
                $tar_total_agg3     = 0;
                $tar_total_stldust     = 0;
                $tar_total_lms      = 0;
                $tar_total_cementA   = 0;
                $tar_total_cementtp2   = 0;
                $tar_total_cementvas   = 0;
                $tar_total_fa       = 0;
                $tar_total_water    = 0;
                $tar_total_stonedust = 0;
                $tar_total_flobasRpf34    = 0;
                $tar_total_s523n    = 0;
                $tar_total_skhe     = 0;
                $tar_total_vis1003  = 0;
                $tar_total_agg1vas = 0;
                $tar_total_agg2vas = 0;
                $tar_total_agg3vas   = 0;
                $tar_total_plast83am     = 0;
                $tar_total_sbtconm    = 0;
                $tar_total_sbtjm9    = 0;
                $tar_total_vis3660lr = 0;
                $tar_total_semenB = 0;
                $tar_total_sandvas = 0;
                $tar_total_stonedustvas = 0;
                $tar_total_P83 = 0;
                $tar_total_SIKAMENT183 = 0;
                $tar_total_FLBPD14 = 0;
                $tar_total_sbtpca8s = 0;
                $tar_total_genr8212 = 0;
                $tar_total_get702 = 0;
                $tar_total_genb1714 = 0;
                $tar_total_flbnf15 = 0;
                $tar_total_flbpd19 = 0;
                
                 while($obj_total = $result_total->fetch_object()){
                    if($obj_total->material_code == "0000-100094"){ //sand / pasir
                        $act_total_sand+=$obj_total->actual_qty;
                        $tar_total_sand+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AW5', $act_total_sand);
                        $excel->getActiveSheet()->setCellValue('CE5', $tar_total_sand);
                    }
                    elseif($obj_total->material_code == "0000-100089"){ //MSand
                        $act_total_msand+=$obj_total->actual_qty;
                        $tar_total_msand+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AX5', $act_total_msand);
                        $excel->getActiveSheet()->setCellValue('CF5', $tar_total_msand);
                    }
                     elseif($obj_total->material_code == "0000-100093"){ // STONEDUST 
                        $act_total_stonedust+=$obj_total->actual_qty;
                        $tar_total_stonedust+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AY5', $act_total_stonedust);
                        $excel->getActiveSheet()->setCellValue('CG5', $tar_total_stonedust);
                    }
                    elseif($obj_total->material_code == "0000-100090"){ //AGG1 
                        $act_total_agg1+=$obj_total->actual_qty;
                        $tar_total_agg1+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('AZ5', $act_total_agg1);
                        $excel->getActiveSheet()->setCellValue('CH5', $tar_total_agg1);
                    }
                    elseif($obj_total->material_code == "0000-100091"){ //AGG2
                        $act_total_agg2+=$obj_total->actual_qty;
                        $tar_total_agg2+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BA5', $act_total_agg2);
                        $excel->getActiveSheet()->setCellValue('CI5', $tar_total_agg2);
                    }
                    elseif($obj_total->material_code == "0000-100092"){ //AGG3
                        $act_total_agg3+=$obj_total->actual_qty;
                        $tar_total_agg3+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('T5', $act_total_agg3);
                        $excel->getActiveSheet()->setCellValue('BB5', $tar_total_agg3);
                    }
                     elseif($obj_total->material_code == "0000-000029"){ //SEMEN B 
                        $act_total_semenB+=$obj_total->actual_qty;
                        $tar_total_semenB+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BC5', $act_total_semenB);
                        $excel->getActiveSheet()->setCellValue('CK5', $tar_total_semenB);
                    } 
                     elseif($obj_total->material_code == "0000-100097"){ //stldust
                        $act_total_stldust+=$obj_total->actual_qty;
                        $tar_total_stldust+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BD5', $act_total_stldust);
                        $excel->getActiveSheet()->setCellValue('CL5', $tar_total_stldust);
                    } 
                    elseif($obj_total->material_code == "0000-100011"){ //Semen A  
                        $act_total_cementA+=$obj_total->actual_qty;
                        $tar_total_cementA+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BE5', $act_total_cementA);
                        $excel->getActiveSheet()->setCellValue('CM5', $tar_total_cementA);
                    }
                    elseif($obj_total->material_code == "0000-100017"){ //Cementtp2  
                        $act_total_cementtp2+=$obj_total->actual_qty;
                        $tar_total_cementtp2+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BF5', $act_total_cementtp2);
                        $excel->getActiveSheet()->setCellValue('CN5', $tar_total_cementtp2);
                    }
                    elseif($obj_total->material_code == "0000-100060"){ //Cementvas
                        $act_total_cementvas+=$obj_total->actual_qty;
                        $tar_total_cementvas+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BG5', $act_total_cementvas);
                        $excel->getActiveSheet()->setCellValue('CO5', $tar_total_cementvas);
                    }
                    elseif($obj_total->material_code == "0000-000015"){ //Flying Ash
                        $act_total_fa+= $obj_total->actual_qty;
                        $tar_total_fa+= $obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BH5', $act_total_fa);
                        $excel->getActiveSheet()->setCellValue('CP5', $tar_total_fa);
                    }
                    elseif($obj_total->material_code == "0000-100030"){ // water / Air 
                        $act_total_water+=$obj_total->actual_qty;
                        $tar_total_water+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BI5', $act_total_water);
                        $excel->getActiveSheet()->setCellValue('CQ5', $tar_total_water);
                    }
                     
                    elseif($obj_total->material_code == "0002-100442"){ //P 121 R 
                        $act_total_flobasRpf34+=$obj_total->actual_qty;
                        $tar_total_flobasRpf34+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BJ5', $act_total_flobasRpf34);
                        $excel->getActiveSheet()->setCellValue('CR5', $tar_total_flobasRpf34);
                    }
                    elseif($obj_total->material_code == "0002-100201"){ //523 N 
                        $act_total_s523n+=$obj_total->actual_qty;
                        $tar_total_s523n+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BK5', $act_total_s523n);
                        $excel->getActiveSheet()->setCellValue('CS5', $tar_total_s523n);
                    }
                    elseif($obj_total->material_code == "0002-100015"){ //PLAST 83 AM
                        $act_total_plast83am+=$obj_total->actual_qty;
                        $tar_total_plast83am+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BL5', $act_total_plast83am);
                        $excel->getActiveSheet()->setCellValue('CT5', $tar_total_plast83am);
                    }
                    elseif($obj_total->material_code == "0002-100020"){ //sbtconm
                        $act_total_sbtconm+=$obj_total->actual_qty;
                        $tar_total_sbtconm+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BM5', $act_total_sbtconm);
                        $excel->getActiveSheet()->setCellValue('CU5', $tar_total_sbtconm);
                    }
                    elseif($obj_total->material_code == "0002-100202"){ //sbtjm9
                        $act_total_sbtjm9+=$obj_total->actual_qty;
                        $tar_total_sbtjm9+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BN5', $act_total_sbtjm9);
                        $excel->getActiveSheet()->setCellValue('CV5', $tar_total_sbtjm9);
                    }
                    elseif($obj_total->material_code == "0002-100402"){ //VIS 1003 
                        $act_total_vis1003+=$obj_total->actual_qty;
                        $tar_total_vis1003+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BO5', $act_total_vis1003);
                        $excel->getActiveSheet()->setCellValue('CW5', $tar_total_vis1003);
                    }
                    elseif($obj_total->material_code == "0000-100063"){ //VIS 1212R
                        $act_total_agg1vas+=$obj_total->actual_qty;
                        $tar_total_agg1vas+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BP5', $act_total_agg1vas);
                        $excel->getActiveSheet()->setCellValue('CX5', $tar_total_agg1vas);
                    }
                    elseif($obj_total->material_code == "0000-100064"){ //VIS 7080p 
                        $act_total_agg2vas+=$obj_total->actual_qty;
                        $tar_total_agg2vas+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BQ5', $act_total_agg2vas);
                        $excel->getActiveSheet()->setCellValue('CY5', $tar_total_agg2vas);
                    }
                    elseif($obj_total->material_code == "0000-100065"){ //SR 3200
                        $act_total_agg3vas+=$obj_total->actual_qty;
                        $tar_total_agg3vas+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BR5', $act_total_agg3vas);
                        $excel->getActiveSheet()->setCellValue('CZ5', $tar_total_agg3vas);
                    }    
                                   
                   
                    elseif($obj_total->material_code == "0000-100061"){ //sandvas 
                        $act_total_sandvas+=$obj_total->actual_qty;
                        $tar_total_sandvas+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BS5', $act_total_sandvas);
                        $excel->getActiveSheet()->setCellValue('DA5', $tar_total_sandvas);
                    }                        
                    elseif($obj_total->material_code == "0000-100062"){ //VISCO 8300 
                        $act_total_stonedustvas+=$obj_total->actual_qty;
                        $tar_total_stonedustvas+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BT5', $act_total_stonedustvas);
                        $excel->getActiveSheet()->setCellValue('DB5', $tar_total_stonedustvas);
                    }        
                    elseif($obj_total->material_code == "0002-100015"){ //P83 
                        $act_total_P83+=$obj_total->actual_qty;
                        $tar_total_P83+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BU5', $act_total_P83);
                        $excel->getActiveSheet()->setCellValue('DC5', $tar_total_P83);
                    }              
                    elseif($obj_total->material_code == "0002-100017"){ //MAST 1100 
                        $act_total_SIKAMENT183+=$obj_total->actual_qty;
                        $tar_total_SIKAMENT183+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BV5', $act_total_SIKAMENT183);
                        $excel->getActiveSheet()->setCellValue('DD5', $tar_total_SIKAMENT183);
                    }    
                    elseif($obj_total->material_code == "0002-100449"){ //MAST 1007 
                        $act_total_FLBPD14+=$obj_total->actual_qty;
                        $tar_total_FLBPD14+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BW5', $act_total_FLBPD14);
                        $excel->getActiveSheet()->setCellValue('DE5', $tar_total_FLBPD14);
                    }          
                    elseif($obj_total->material_code == "0002-100021"){ //SBT PCA-8S
                        $act_total_sbtpca8s+=$obj_total->actual_qty;
                        $tar_total_sbtpca8s+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BX5', $act_total_sbtpca8s);
                        $excel->getActiveSheet()->setCellValue('DF5', $tar_total_sbtpca8s);
                    }    
                    elseif($obj_total->material_code == "0002-100437"){ //GENR 8212
                        $act_total_genr8212+=$obj_total->actual_qty;
                        $tar_total_genr8212+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BY5', $act_total_genr8212);
                        $excel->getActiveSheet()->setCellValue('DG5', $tar_total_genr8212);
                    }       
                    elseif($obj_total->material_code == "0002-100438"){ //GET 702
                        $act_total_get702+=$obj_total->actual_qty;
                        $tar_total_get702+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('BZ5', $act_total_get702);
                        $excel->getActiveSheet()->setCellValue('DH5', $tar_total_get702);
                    }           
                    elseif($obj_total->material_code == "0002-100439"){ //GENB 1714
                        $act_total_genb1714+=$obj_total->actual_qty;
                        $tar_total_genb1714+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('CA5', $act_total_genb1714);
                        $excel->getActiveSheet()->setCellValue('DI5', $tar_total_genb1714);
                    }    
                     elseif($obj_total->material_code == "0002-100430"){ //vis3660lr 
                        $act_total_vis3660lr+=$obj_total->actual_qty;
                        $tar_total_vis3660lr+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('CB5', $act_total_vis3660lr);
                        $excel->getActiveSheet()->setCellValue('DJ5', $tar_total_vis3660lr);
                    } 
                      elseif($obj_total->material_code == "0002-100440"){ //flbnf15
                        $act_total_flbnf15+=$obj_total->actual_qty;
                        $tar_total_flbnf15+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('CC5', $act_total_flbnf15);
                        $excel->getActiveSheet()->setCellValue('DK5', $tar_total_flbnf15);
                    }    
                     elseif($obj_total->material_code == "0002-100441"){ //flbpd19
                        $act_total_flbpd19+=$obj_total->actual_qty;
                        $tar_total_flbpd19+=$obj_total->target_qty;
                        $excel->getActiveSheet()->setCellValue('CD5', $act_total_flbpd19);
                        $excel->getActiveSheet()->setCellValue('DL5', $tar_total_flbpd19);
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
            $excel->getActiveSheet()->setCellValue($excelColumn[$index_excelColumn++].$row, $obj->received_vol);
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
                $act_qty_cementA   = 0;
                $act_qty_cementtp2   = 0;
                $act_qty_cementvas   = 0;
                $act_qty_lms      = 0;
                $act_qty_agg1     = 0;
                $act_qty_agg2     = 0;
                $act_qty_agg3     = 0;
                $act_qty_stldust     = 0;
                $act_qty_flobasRpf34    = 0;
                $act_qty_stonedust = 0;
                $act_qty_s523n    = 0;
                $act_qty_skhe     = 0;
                $act_qty_vis1003  = 0;
                $act_qty_agg1vas = 0;
                $act_qty_agg2vas = 0;
                $act_qty_plast83am = 0;
                $act_qty_sbtconm    = 0;
                $act_qty_sbtjm9    = 0;
                $act_qty_agg3vas   = 0;
                $act_qty_vis3660lr = 0;
                $act_qty_semenB = 0;
                $act_qty_sandvas = 0;
                $act_qty_stonedustvas = 0;
                $act_qty_P83 = 0;
                $act_qty_SIKAMENT183 = 0;
                $act_qty_FLBPD14 = 0;
                $act_qty_sbtpca8s = 0;
                $act_qty_genr8212 = 0;
                $act_qty_get702 = 0;
                $act_qty_genb1714 = 0;
                $act_qty_flbnf15 = 0;
                $act_qty_flbpd19 = 0;
                
                //target qty properties
                $tar_qty_sand     = 0;
                $tar_qty_msand    = 0;
                $tar_qty_fa       = 0;
                $tar_qty_water    = 0;
                $tar_qty_cementA   = 0;
                $tar_qty_cementtp2   = 0;
                $tar_qty_cementvas   = 0;
                $tar_qty_lms      = 0;
                $tar_qty_agg1     = 0;
                $tar_qty_agg2     = 0;
                $tar_qty_agg3     = 0;
                $tar_qty_stldust     = 0;
                $tar_qty_flobasRpf34    = 0;
                $tar_qty_stonedust = 0;
                $tar_qty_s523n    = 0;
                $tar_qty_skhe     = 0;
                $tar_qty_vis1003  = 0;
                $tar_qty_agg1vas = 0;
                $tar_qty_agg2vas = 0;
                $tar_qty_plast83am = 0;
                $tar_qty_sbtconm    = 0;
                $tar_qty_sbtjm9    = 0;
                $tar_qty_agg3vas   = 0;
                $tar_qty_vis3660lr = 0;
                $tar_qty_semenB = 0;
                $tar_qty_sandvas = 0;
                $tar_qty_stonedustvas = 0;
                $tar_qty_P83 = 0;
                $tar_qty_SIKAMENT183 = 0;
                $tar_qty_FLBPD14 = 0;
                $tar_qty_sbtpca8s = 0;
                $tar_qty_genr8212 = 0;
                $tar_qty_get702 = 0;
                $tar_qty_genb1714 = 0;
                $tar_qty_flbnf15 = 0;
                $tar_qty_flbpd19 = 0;

                $moi_qty_sand     = 0;
                $moi_qty_msand    = 0;
                $moi_qty_agg1     = 0;
                $moi_qty_agg2     = 0;
                $moi_qty_agg3     = 0;
                $moi_qty_semenB     = 0;
                $moi_qty_stldust     = 0;
                $moi_qty_stonedust = 0;
                
                $all_target_qty   = 0;
                $all_actual_qty   = 0;
                $act_qty_sandd =0;
                while($obj_detail = $result_detail->fetch_object()){     
                    $all_target_qty+=$obj_detail->target_qty;
                    $all_actual_qty+=$obj_detail->actual_qty;
                    
                    if($obj_detail->material_code == "0000-100094"){ //sand / pasir
                        $act_qty_sand+=$obj_detail->actual_qty;
                        $tar_qty_sand+=$obj_detail->target_qty;
                        $moi_qty_sand+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("AW".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('CE'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('DM'.$row, $obj_detail->moisture);
                    }
                    elseif($obj_detail->material_code == "0000-100089"){ //MSand
                        $act_qty_msand+=$obj_detail->actual_qty;
                        $tar_qty_msand+=$obj_detail->target_qty;
                        $moi_qty_msand+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("AX".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('CF'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('DV'.$row, $obj_detail->moisture);
                      
                    }
                    elseif($obj_detail->material_code == "0000-100093"){ //StoneDust
                        $act_qty_stonedust+=$obj_detail->actual_qty;
                        $tar_qty_stonedust+=$obj_detail->target_qty;
                        $moi_qty_stonedust+=$obj_detail->moisture;
                         $excel->getActiveSheet()->setCellValue("AY".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CG".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('DW'.$row, $obj_detail->moisture);
                        
                    }
                    elseif($obj_detail->material_code == "0000-100090"){ //AGG1 
                        $act_qty_agg1+=$obj_detail->actual_qty;
                        $tar_qty_agg1+=$obj_detail->target_qty;
                        $moi_qty_agg1+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("AZ".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('CH'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('DX'.$row, $obj_detail->moisture);
                       
                    }
                    elseif($obj_detail->material_code == "0000-100091"){ //AGG2
                        $act_qty_agg2+=$obj_detail->actual_qty;
                        $tar_qty_agg2+=$obj_detail->target_qty;
                        $moi_qty_agg2+=$obj_detail->moisture;
                         $excel->getActiveSheet()->setCellValue("BA".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('CI'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('DY'.$row, $obj_detail->moisture);
                        
                    }
                    elseif($obj_detail->material_code == "0000-100092"){ //AGG3
                        $act_qty_agg3+=$obj_detail->actual_qty;
                        $tar_qty_agg3+=$obj_detail->target_qty;
                        $moi_qty_agg3+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("BB".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CJ".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('DZ'.$row, $obj_detail->moisture);
                      
                    }
                     elseif($obj_detail->material_code == "0000-000029"){ //SEMEN B
                        $moi_qty_semenB+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("BC".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CK".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('EA'.$row, $obj_detail->moisture);
                        $act_qty_semenB+=$obj_detail->actual_qty;
                        $tar_qty_semenB+=$obj_detail->target_qty;
                    }  
                     elseif($obj_detail->material_code == "0000-100097"){ //stldust
                        $moi_qty_stldust+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("BD".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CL".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('EB'.$row, $obj_detail->moisture);
                        $act_qty_stldust+=$obj_detail->actual_qty;
                        $tar_qty_stldust+=$obj_detail->target_qty;
                    }  
                    
                    elseif($obj_detail->material_code == "0000-100011"){ //Cement A
                        $excel->getActiveSheet()->setCellValue("BE".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CM".$row, $obj_detail->target_qty);
                        $act_qty_cementA+=$obj_detail->actual_qty;
                        $tar_qty_cementA+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100017"){ //Cementtp2
                        $excel->getActiveSheet()->setCellValue("BF".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CN".$row, $obj_detail->target_qty);
                        $act_qty_cementtp2+=$obj_detail->actual_qty;
                        $tar_qty_cementtp2+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100060"){ //Cementvas
                        $excel->getActiveSheet()->setCellValue("BG".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CO".$row, $obj_detail->target_qty);
                        $act_qty_cementvas+=$obj_detail->actual_qty;
                        $tar_qty_cementvas+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-000015"){ //Flying Ash
                        $excel->getActiveSheet()->setCellValue("BH".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CP".$row, $obj_detail->target_qty);
                        $act_qty_fa+= $obj_detail->actual_qty;
                        $tar_qty_fa+= $obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100030"){ // water / Air
                        $excel->getActiveSheet()->setCellValue("BI".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CQ".$row, $obj_detail->target_qty);
                        $act_qty_water+=$obj_detail->actual_qty;
                        $tar_qty_water+=$obj_detail->target_qty;
                    }
                    
                    elseif($obj_detail->material_code == "0002-100442"){ //P 121 R 
                        $excel->getActiveSheet()->setCellValue("BJ".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CR".$row, $obj_detail->target_qty);
                        $act_qty_flobasRpf34+=$obj_detail->actual_qty;
                        $tar_qty_flobasRpf34+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100201"){ //523 N 
                        $excel->getActiveSheet()->setCellValue("BK".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CS".$row, $obj_detail->target_qty);
                        $act_qty_s523n+=$obj_detail->actual_qty;
                        $tar_qty_s523n+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100015"){ //PLAST 83 AM
                        $excel->getActiveSheet()->setCellValue("DL".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CT".$row, $obj_detail->target_qty);
                        $act_qty_plast83am+=$obj_detail->actual_qty;
                        $tar_qty_plast83am+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100020"){ //sbtconm
                        $excel->getActiveSheet()->setCellValue("BM".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CU".$row, $obj_detail->target_qty);
                        $act_qty_sbtconm+=$obj_detail->actual_qty;
                        $tar_qty_sbtconm+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100202"){ //sbtjm9
                        $excel->getActiveSheet()->setCellValue("BN".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CV".$row, $obj_detail->target_qty);
                        $act_qty_sbtjm9+=$obj_detail->actual_qty;
                        $tar_qty_sbtjm9+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100402"){ //VIS 1003 
                        $excel->getActiveSheet()->setCellValue("BO".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CW".$row, $obj_detail->target_qty);
                        $act_qty_vis1003+=$obj_detail->actual_qty;
                        $tar_qty_vis1003+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100063"){ //VIS 1212R
                        $excel->getActiveSheet()->setCellValue("BP".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CX".$row, $obj_detail->target_qty);
                        $act_qty_agg1vas+=$obj_detail->actual_qty;
                        $tar_qty_agg1vas+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100064"){ //VIS 7080p
                        $excel->getActiveSheet()->setCellValue("BQ".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CY".$row, $obj_detail->target_qty);
                        $act_qty_agg2vas+=$obj_detail->actual_qty;
                        $tar_qty_agg2vas+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100065"){ //SR 3200
                        $excel->getActiveSheet()->setCellValue("BR".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CZ".$row, $obj_detail->target_qty);
                        $act_qty_agg3vas+=$obj_detail->actual_qty;
                        $tar_qty_agg3vas+=$obj_detail->target_qty;
                    } 
                                         
                   
                    elseif($obj_detail->material_code == "0000-100061"){ //sandvas
                        $excel->getActiveSheet()->setCellValue("BS".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DA".$row, $obj_detail->target_qty);
                        $act_qty_sandvas+=$obj_detail->actual_qty;
                        $tar_qty_sandvas+=$obj_detail->target_qty;
                    }                  
                    elseif($obj_detail->material_code == "0000-100062"){ //VISCO 8300
                        $excel->getActiveSheet()->setCellValue("BT".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DB".$row, $obj_detail->target_qty);
                        $act_qty_stonedustvas+=$obj_detail->actual_qty;
                        $tar_qty_stonedustvas+=$obj_detail->target_qty;
                    }        
                    elseif($obj_detail->material_code == "0002-100015"){ //P83
                        $excel->getActiveSheet()->setCellValue("BU".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DC".$row, $obj_detail->target_qty);
                        $act_qty_P83+=$obj_detail->actual_qty;
                        $tar_qty_P83+=$obj_detail->target_qty;
                    }     
                    elseif($obj_detail->material_code == "0002-100017"){ //MAST 1100
                        $excel->getActiveSheet()->setCellValue("BV".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DD".$row, $obj_detail->target_qty);
                        $act_qty_SIKAMENT183+=$obj_detail->actual_qty;
                        $tar_qty_SIKAMENT183+=$obj_detail->target_qty;
                    }    
                    elseif($obj_detail->material_code == "0002-100449"){ //FLBPD-14
                        $excel->getActiveSheet()->setCellValue("BW".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DE".$row, $obj_detail->target_qty);
                        $act_qty_FLBPD14+=$obj_detail->actual_qty;
                        $tar_qty_FLBPD14+=$obj_detail->target_qty;
                    }   
                    elseif($obj_detail->material_code == "0002-100021"){ //SBT PCA-8S
                        $excel->getActiveSheet()->setCellValue("BX".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DF".$row, $obj_detail->target_qty);
                        $act_qty_sbtpca8s+=$obj_detail->actual_qty;
                        $tar_qty_sbtpca8s+=$obj_detail->target_qty;
                    }  
                    elseif($obj_detail->material_code == "0002-100437"){ //GENR 8212
                        $excel->getActiveSheet()->setCellValue("BY".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DG".$row, $obj_detail->target_qty);
                        $act_qty_genr8212+=$obj_detail->actual_qty;
                        $tar_qty_genr8212+=$obj_detail->target_qty;
                    }    
                    elseif($obj_detail->material_code == "0002-100438"){ //GET 702
                        $excel->getActiveSheet()->setCellValue("BZ".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DH".$row, $obj_detail->target_qty);
                        $act_qty_get702+=$obj_detail->actual_qty;
                        $tar_qty_get702+=$obj_detail->target_qty;
                    }     
                    elseif($obj_detail->material_code == "0002-100439"){ //GENB 1714
                        $excel->getActiveSheet()->setCellValue("CA".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DI".$row, $obj_detail->target_qty);
                        $act_qty_genb1714+=$obj_detail->actual_qty;
                        $tar_qty_genb1714+=$obj_detail->target_qty;
                    }  
                    elseif($obj_detail->material_code == "0002-100430"){ //vis3660lr
                        $excel->getActiveSheet()->setCellValue("CB".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DJ".$row, $obj_detail->target_qty);
                        $act_qty_vis3660lr+=$obj_detail->actual_qty;
                        $tar_qty_vis3660lr+=$obj_detail->target_qty;
                    } 
                    elseif($obj_detail->material_code == "0002-100440"){ //flbnf15
                        $excel->getActiveSheet()->setCellValue("CC".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DK".$row, $obj_detail->target_qty);
                        $act_qty_flbnf15+=$obj_detail->actual_qty;
                        $tar_qty_flbnf15+=$obj_detail->target_qty;
                    }  
                    elseif($obj_detail->material_code == "0002-100441"){ //flbpd19
                        $excel->getActiveSheet()->setCellValue("CD".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("DL".$row, $obj_detail->target_qty);
                        $act_qty_flbpd19+=$obj_detail->actual_qty;
                        $tar_qty_flbpd19+=$obj_detail->target_qty;
                    }                                           
                                     
                }

               

            $query_select_detail1 = "SELECT * "
            . "FROM `mix_package_composition` "
            . "WHERE `product_code`='".$obj->product_code."' AND `chart_no`='1'"
            . "ORDER BY `chart_no`";

                              
                     
            if($result_detail1 = $mysqli->query($query_select_detail1)){
                //design qty 
                $nor_qty_sand     = 0;
                $nor_qty_msand    = 0;
                $nor_qty_fa       = 0;
                $nor_qty_water    = 0;
                $nor_qty_cementA   = 0;
                $nor_qty_cementtp2   = 0;
                $nor_qty_cementvas   = 0;
                $nor_qty_lms      = 0;
                $nor_qty_agg1     = 0;
                $nor_qty_agg2     = 0;
                $nor_qty_agg3     = 0;
                $nor_qty_stldust     = 0;
                $nor_qty_flobasRpf34    = 0;
                $nor_qty_stonedust = 0;
                $nor_qty_s523n    = 0;
                $nor_qty_skhe     = 0;
                $nor_qty_vis1003  = 0;
                $nor_qty_agg1vas = 0;
                $nor_qty_agg2vas = 0;
                $nor_qty_plast83am = 0;
                $nor_qty_sbtconm    = 0;
                $nor_qty_sbtjm9    = 0;
                $nor_qty_agg3vas   = 0;
                $nor_qty_vis3660lr = 0;
                $nor_qty_semenB = 0;
                $nor_qty_sandvas = 0;
                $nor_qty_stonedustvas = 0;
                $nor_qty_P83 = 0;
                $nor_qty_SIKAMENT183 = 0;
                $nor_qty_FLBPD14 = 0;
                $nor_qty_sbtpca8s = 0;
                $nor_qty_genr8212 = 0;
                $nor_qty_get702 = 0;
                $nor_qty_genb1714 = 0;
                $nor_qty_flbnf15 = 0;
                $nor_qty_flbpd19 = 0;

            while($obj1 = $result_detail1->fetch_object()){     
                    $nor_qty_sand+=$obj1->mix_qty;
                    
                    
                    if($obj1->material_code == "0000-100094"){ //sand / pasir
                        $nor_qty_sand+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue('O'.$row,$obj1->mix_qty);
 
                    }
                    elseif($obj1->material_code == "0000-100089"){ //MSand
                        $nor_qty_msand+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("P".$row, $obj1->mix_qty);
                    }
                    elseif($obj1->material_code == "0000-100093"){ //StoneDust
                        $nor_qty_stonedust+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("Q".$row, $obj1->mix_qty);
                        
                        
                    }
                    elseif($obj1->material_code == "0000-100090"){ //AGG1 
                        $nor_qty_agg1+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("R".$row, $obj1->mix_qty);
                       
                       
                    }
                    elseif($obj1->material_code == "0000-100091"){ //AGG2
                        $nor_qty_agg2+=$obj1->mix_qty;
                         $excel->getActiveSheet()->setCellValue("S".$row, $obj1->mix_qty);
                      
                        
                    }
                    elseif($obj1->material_code == "0000-100092"){ //AGG3
                        $nor_qty_agg3+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("T".$row, $obj1->mix_qty);
                       
                      
                    }
                     elseif($obj1->material_code == "0000-000029"){ //SEMEN B
                        $nor_qty_semenB+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("U".$row, $obj1->mix_qty);
                        
                       
                    }  
                     elseif($obj1->material_code == "0000-100097"){ //stldust
                        $nor_qty_stldust+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("V".$row, $obj1->mix_qty);
                       
                       
                    }  
                    
                    elseif($obj1->material_code == "0000-100011"){ //CementA 
                        $nor_qty_cementA+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("W".$row, $obj1->mix_qty);
                            
                    }
                    elseif($obj1->material_code == "0000-100017"){ //Cementtp2
                        $nor_qty_cementtp2+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("X".$row, $obj1->mix_qty);
                        
                    }
                    elseif($obj1->material_code == "0000-100060"){ //Cementvas
                        $nor_qty_cementvas+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("Y".$row, $obj1->mix_qty);
                    
                    }
                    elseif($obj1->material_code == "0000-000015"){ //Flying Ash
                        $nor_qty_fa+= $obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("Z".$row, $obj1->mix_qty);
                        
                    }
                    elseif($obj1->material_code == "0000-100030"){ // water / Air
                        $nor_qty_water+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AA".$row, $obj1->mix_qty);
                         
                    }
                    
                    elseif($obj1->material_code == "0002-100442"){ //P 121 R 
                        $nor_qty_flobasRpf34+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AB".$row, $obj1->mix_qty);
                        
                    }
                    elseif($obj1->material_code == "0002-100201"){ //523 N 
                        $nor_qty_s523n+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AC".$row, $obj1->mix_qty); 
                    
                    }
                    elseif($obj1->material_code == "0002-100015"){ //PLAST 83 AM
                        $nor_qty_plast83am+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AD".$row, $obj1->mix_qty);
  
                    }
                    elseif($obj1->material_code == "0002-100020"){ //sbtconm
                        $nor_qty_sbtconm+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AE".$row, $obj1->mix_qty);
 
                    }
                    elseif($obj1->material_code == "0002-100202"){ //sbtjm9
                        $nor_qty_sbtjm9+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AF".$row, $obj1->mix_qty);

                    }
                    elseif($obj1->material_code == "0002-100402"){ //VIS 1003 
                        $nor_qty_vis1003+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AG".$row, $obj1->mix_qty);
 
                    }
                    elseif($obj1->material_code == "0000-100063"){ //VIS 1212R
                         $nor_qty_agg1vas+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AH".$row, $obj1->mix_qty);
  
                    }
                    elseif($obj1->material_code == "0000-100064"){ //VIS 7080p
                        $nor_qty_agg2vas+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AI".$row, $obj1->mix_qty);

                    }
                    elseif($obj1->material_code == "0000-100065"){ //SR 3200
                        $nor_qty_agg3vas+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AJ".$row, $obj1->mix_qty);

                    } 

                    elseif($obj1->material_code == "0000-100061"){ //sandvas
                        $nor_qty_sandvas+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AK".$row, $obj1->mix_qty);

                    }                  
                    elseif($obj1->material_code == "0000-100062"){ //VISCO 8300
                        $nor_qty_stonedustvas+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AL".$row, $obj1->mix_qty);

                    }        
                    elseif($obj1->material_code == "0002-100015"){ //P83
                        $nor_qty_P83+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AM".$row, $obj1->mix_qty);

                    }     
                    elseif($obj1->material_code == "0002-100017"){ //MAST 1100
                        $nor_qty_SIKAMENT183+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AN".$row, $obj1->mix_qty);
                      
                    }    
                    elseif($obj1->material_code == "0002-100449"){ //FLBPD-14
                        $nor_qty_FLBPD14+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AO".$row, $obj1->mix_qty);

                    }   
                    elseif($obj1->material_code == "0002-100021"){ //sbtpca8s
                        $nor_qty_sbtpca8s+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AP".$row, $obj1->mix_qty);
   
                    }  
                    elseif($obj1->material_code == "0002-100437"){ //GENR 8212
                        $nor_qty_genr8212+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AQ".$row, $obj1->mix_qty);

                    }    
                    elseif($obj1->material_code == "0002-100438"){ //GET 702
                        $nor_qty_get702+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AR".$row, $obj1->mix_qty);
                         
                    }     
                    elseif($obj1->material_code == "0002-100439"){ //GENB 1714
                        $nor_qty_genb1714+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AS".$row, $obj1->mix_qty);
   
                    }  
                    elseif($obj1->material_code == "0002-100430"){ //vis3660lr
                        $nor_qty_vis3660lr+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AT".$row, $obj1->mix_qty);
  
                    } 
                    elseif($obj1->material_code == "0002-100440"){ //flbnf15
                        $nor_qty_flbnf15+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AU".$row, $obj1->mix_qty);
                   
                       
                       
                    }  
                    elseif($obj1->material_code == "0002-100441"){ //flbpd19
                        $nor_qty_flbpd19+=$obj1->mix_qty;
                        $excel->getActiveSheet()->setCellValue("AV".$row, $obj1->mix_qty);
   
                    }

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
               if($act_qty_fa+$act_qty_cementA==0){$fa_percentage=0;}
                else{$fa_percentage = 100*($act_qty_fa/($act_qty_fa+$act_qty_cementA));}
                if($act_qty_fa+$act_qty_cementtp2==0){$fa_percentage=0;}
                else{$fa_percentage = 100*($act_qty_fa/($act_qty_fa+$act_qty_cementtp2));}
                if($act_qty_fa+$act_qty_cementvas==0){$fa_percentage=0;}
                else{$fa_percentage = 100*($act_qty_fa/($act_qty_fa+$act_qty_cementvas));}

                $excel->getActiveSheet()->setCellValue("FC".$row, round($sand_percentage,2));
                $excel->getActiveSheet()->setCellValue("FD".$row, round($msand_percentage,2));
                $excel->getActiveSheet()->setCellValue("FE".$row, round($fa_percentage,2));
                
                
                /*
                 * variance properties
                 */
                $var_sand     = 0;
                $var_msand    = 0;
                $var_water    = 0;
                $var_fa       = 0;
                $var_cementA   = 0;
                $var_cementtp2   = 0;
                $var_cementvas   = 0;
                $var_agg1     = 0;
                $var_agg2     = 0;
                $var_agg3     = 0;
                $var_semenB = 0;
                $var_stldust = 0;
                $var_flobasRpf34    = 0;
                $var_stonedust = 0;
                $var_s523n    = 0;
                $var_vis1003  = 0;
                $var_agg1vas = 0;
                $var_agg2vas = 0;
                $var_agg3vas = 0;
                $var_plast83am = 0;
                $var_lms      = 0;
                $var_sbtconm    = 0;
                $var_vis3660lr = 0;
                $var_sandvas = 0;
                $var_stonedustvas = 0;
                $var_P83 = 0;
                $var_SIKAMENT183 = 0;
                $var_FLBPD14 = 0;
                $var_sbtpca8s = 0;
                $var_genr8212 = 0;
                $var_get702 = 0;
                $var_genb1714 = 0;
                $var_flbnf15 = 0;
                $var_flbpd19 = 0;
                
                
                //sand
                if($tar_qty_sand === 0){
                    $excel->getActiveSheet()->setCellValue("DU".$row,"");
                }
                else{
                    $var_sand = (($act_qty_sand - $tar_qty_sand)/($tar_qty_sand))*100;
                    $excel->getActiveSheet()->setCellValue("DU".$row, round($var_sand,2));
                }
                //msand
                if($tar_qty_msand === 0){
                    $excel->getActiveSheet()->setCellValue("DV".$row,"");
                }
                else{
                    $var_msand = (($act_qty_msand - $tar_qty_msand)/($tar_qty_msand))*100;
                    $excel->getActiveSheet()->setCellValue("DV".$row, round($var_msand,2));
                }
                //stonedust
                if($tar_qty_stonedust === 0){
                    //$var_water = 0;
                    $excel->getActiveSheet()->setCellValue("DW".$row,"");
                }
                else{$var_stonedust = (($act_qty_stonedust - $tar_qty_stonedust)/($tar_qty_stonedust))*100;
                        $excel->getActiveSheet()->setCellValue("DW".$row, round($var_stonedust,2));
                }
                //agg1
                if($tar_qty_agg1 === 0){
                    $excel->getActiveSheet()->setCellValue("DX".$row,"");
                }
                else{$var_agg1 = (($act_qty_agg1 - $tar_qty_agg1)/($tar_qty_agg1))*100;
                    $excel->getActiveSheet()->setCellValue("DX".$row, round($var_agg1,2));
                }
                //agg2
                if($tar_qty_agg2 === 0){
                    $excel->getActiveSheet()->setCellValue("DY".$row,"");
                    }
                else{$var_agg2 = (($act_qty_agg2 - $tar_qty_agg2)/($tar_qty_agg2))*100;
                    $excel->getActiveSheet()->setCellValue("DY".$row, round($var_agg2,2));
                }
                //agg3
                if($tar_qty_agg3 === 0){
                    $excel->getActiveSheet()->setCellValue("DZ".$row,"");
                }
                else{$var_agg3 = (($act_qty_agg3 - $tar_qty_agg3)/($tar_qty_agg3))*100;
                    $excel->getActiveSheet()->setCellValue("DZ".$row, round($var_agg3,2));
                }
                //var_semenB
                 if($tar_qty_semenB === 0){ 
                    $excel->getActiveSheet()->setCellValue("EA".$row,"");
                }
                else{
                    $var_semenB = (($act_qty_semenB - $tar_qty_semenB)/($tar_qty_semenB))*100;
                    $excel->getActiveSheet()->setCellValue("EA".$row, round($var_semenB,2));
                }
                //var_stldust
                 if($tar_qty_stldust === 0){ 
                    $excel->getActiveSheet()->setCellValue("EB".$row,"");
                }
                else{
                    $var_stldust = (($act_qty_stldust - $tar_qty_stldust)/($tar_qty_stldust))*100;
                    $excel->getActiveSheet()->setCellValue("EB".$row, round($var_stldust,2));
                }
                
                //sementtp1
                if($tar_qty_cementA === 0){//$var_cement = 0;
                    $excel->getActiveSheet()->setCellValue("EC".$row,"");
                }
                else{$var_cementA = (($act_qty_cementA - $tar_qty_cementA)/($tar_qty_cementA))*100;}
                $excel->getActiveSheet()->setCellValue("EC".$row, round($var_cementA,2));
                //sementtp2        
                if($tar_qty_cementtp2 === 0){//$var_cement = 0;
                    $excel->getActiveSheet()->setCellValue("ED".$row,"");
                }
                else{$var_cementtp2 = (($act_qty_cementtp2 - $tar_qty_cementtp2)/($tar_qty_cementtp2))*100;}
                $excel->getActiveSheet()->setCellValue("ED".$row, round($var_cementtp2,2));
                //semenpcc
                if($tar_qty_cementvas === 0){//$var_cement = 0;
                    $excel->getActiveSheet()->setCellValue("EE".$row,"");
                }
                else{$var_cementvas = (($act_qty_cementvas - $tar_qty_cementvas)/($tar_qty_cementvas))*100;}
                $excel->getActiveSheet()->setCellValue("EE".$row, round($var_cementvas,2));
                
                if($tar_qty_fa === 0){
                    //$var_fa = 0;
                    $excel->getActiveSheet()->setCellValue("EF".$row,"");
                }
                else{$var_fa = (($act_qty_fa - $tar_qty_fa)/($tar_qty_fa))*100;
                    $excel->getActiveSheet()->setCellValue("EF".$row, round($var_fa,2));
                }
                if($tar_qty_water === 0){
                    //$var_water = 0;
                    $excel->getActiveSheet()->setCellValue("EG".$row,"");
                }
                else{$var_water = (($act_qty_water - $tar_qty_water)/($tar_qty_water))*100;
                        $excel->getActiveSheet()->setCellValue("EG".$row, round($var_water,2));
                }
               
                if($tar_qty_flobasRpf34 === 0){
                  //  $var_flobasRpf34 = 0;
                    $excel->getActiveSheet()->setCellValue("EH".$row,"");
                }
                else{
                    $var_flobasRpf34 = (($act_qty_flobasRpf34 - $tar_qty_flobasRpf34)/($tar_qty_flobasRpf34))*100;
                    $excel->getActiveSheet()->setCellValue("EH".$row, round($var_flobasRpf34,2));
                }
                if($tar_qty_s523n === 0){
                    //$var_s523n = 0;
                    $excel->getActiveSheet()->setCellValue("EI".$row,"");
                }
                else{
                    $var_s523n = (($act_qty_s523n - $tar_qty_s523n)/($tar_qty_s523n))*100;
                    $excel->getActiveSheet()->setCellValue("EI".$row, round($var_s523n,2));
                }
                if ($tar_qty_plast83am === 0){
                    $excel->getActiveSheet()->setCellValue("EJ".$row,"");
                }else{
                    $var_plast83am = (($act_qty_plast83am- $tar_qty_plast83am)/($tar_qty_plast83am))*100;
                    $excel->getActiveSheet()->setCellValue("EJ".$row, round($var_plast83am,2));
                }
                //sbtconm
                if($tar_qty_sbtconm === 0){
                    $excel->getActiveSheet()->setCellValue("EK".$row,"");
                }else{
                    $var_sbtconm = (($act_qty_sbtconm - $tar_qty_sbtconm)/($tar_qty_sbtconm))*100;
                    $excel->getActiveSheet()->setCellValue("EK".$row, round($var_sbtconm,2));
                }
                //sbtjm9
                if($tar_qty_sbtjm9 === 0){
                    $excel->getActiveSheet()->setCellValue("EL".$row,"");
                }else{
                    $var_sbtjm9 = (($act_qty_sbtjm9 - $tar_qty_sbtjm9)/($tar_qty_sbtjm9))*100;
                    $excel->getActiveSheet()->setCellValue("EL".$row, round($var_sbtjm9,2));
                }
                //vis1003
                if($tar_qty_vis1003 === 0){
                    $excel->getActiveSheet()->setCellValue("EM".$row,"");
                }
                else{
                    $var_vis1003 = (($act_qty_vis1003 - $tar_qty_vis1003)/($tar_qty_vis1003))*100;
                    $excel->getActiveSheet()->setCellValue("EM".$row, round($var_vis1003,2));
                }
                
                
                if($tar_qty_agg1vas === 0){
                    $excel->getActiveSheet()->setCellValue("EN".$row,"");
                }
                else{
                    $var_agg1vas = (($act_qty_agg1vas - $tar_qty_agg1vas)/($tar_qty_agg1vas))*100;
                    $excel->getActiveSheet()->setCellValue("EN".$row, round($var_agg1vas,2));
                }
                
                
                if($tar_qty_agg2vas === 0){ 
                    //$var_sksf = 0;
                    $excel->getActiveSheet()->setCellValue("EO".$row,"");
                }
                else{
                    $var_agg2vas = (($act_qty_agg2vas - $tar_qty_agg2vas)/($tar_qty_agg2vas))*100;
                    $excel->getActiveSheet()->setCellValue("EO".$row, round($var_agg2vas,2));
                }
                //var_agg3vas
                if($tar_qty_agg3vas === 0){ 
                    $excel->getActiveSheet()->setCellValue("EP".$row,"");
                }
                else{
                    $var_agg3vas = (($act_qty_agg3vas - $tar_qty_agg3vas)/($tar_qty_agg3vas))*100;
                    $excel->getActiveSheet()->setCellValue("EP".$row, round($var_agg3vas,2));
                }
                 //var_vis3660lr
                
                 
                
                 //var_sandvas
                 if($tar_qty_sandvas === 0){ 
                    $excel->getActiveSheet()->setCellValue("EQ".$row,"");
                }
                else{
                    $var_sandvas = (($act_qty_sandvas - $tar_qty_sandvas)/($tar_qty_sandvas))*100;
                    $excel->getActiveSheet()->setCellValue("EQ".$row, round($var_sandvas,2));
                }
                 //var_stonedustvas
                 if($tar_qty_stonedustvas === 0){ 
                    $excel->getActiveSheet()->setCellValue("ER".$row,"");
                }
                else{
                    $var_stonedustvas = (($act_qty_stonedustvas - $tar_qty_stonedustvas)/($tar_qty_stonedustvas))*100;
                    $excel->getActiveSheet()->setCellValue("ER".$row, round($var_stonedustvas,2));
                }
                //var_P83
                if($tar_qty_P83 === 0){ 
                    $excel->getActiveSheet()->setCellValue("ES".$row,"");
                }
                else{
                    $var_P83 = (($act_qty_P83 - $tar_qty_P83)/($tar_qty_P83))*100;
                    $excel->getActiveSheet()->setCellValue("ES".$row, round($var_P83,2));
                }
                //var_SIKAMENT183
                if($tar_qty_SIKAMENT183 === 0){ 
                    $excel->getActiveSheet()->setCellValue("ET".$row,"");
                }
                else{
                    $var_SIKAMENT183 = (($act_qty_SIKAMENT183 - $tar_qty_SIKAMENT183)/($tar_qty_SIKAMENT183))*100;
                    $excel->getActiveSheet()->setCellValue("ET".$row, round($var_SIKAMENT183,2));
                }
                //var_FLBPD14
                if($tar_qty_FLBPD14 === 0){ 
                    $excel->getActiveSheet()->setCellValue("EU".$row,"");
                }
                else{
                    $var_FLBPD14 = (($act_qty_FLBPD14 - $tar_qty_FLBPD14)/($tar_qty_FLBPD14))*100;
                    $excel->getActiveSheet()->setCellValue("EU".$row, round($var_FLBPD14,2));
                }
                //var_sbtpca8s
                if($tar_qty_sbtpca8s === 0){ 
                    $excel->getActiveSheet()->setCellValue("EP".$row,"");
                }
                else{
                    $var_sbtpca8s = (($act_qty_sbtpca8s - $tar_qty_sbtpca8s)/($tar_qty_sbtpca8s))*100;
                    $excel->getActiveSheet()->setCellValue("EP".$row, round($var_sbtpca8s,2));
                }
                //var_genr8212
                if($tar_qty_genr8212 === 0){ 
                    $excel->getActiveSheet()->setCellValue("EW".$row,"");
                }
                else{
                    $var_genr8212 = (($act_qty_genr8212 - $tar_qty_genr8212)/($tar_qty_genr8212))*100;
                    $excel->getActiveSheet()->setCellValue("EW".$row, round($var_genr8212,2));
                }
                //var_get702
                if($tar_qty_get702 === 0){ 
                    $excel->getActiveSheet()->setCellValue("EX".$row,"");
                }
                else{
                    $var_get702 = (($act_qty_get702 - $tar_qty_get702)/($tar_qty_get702))*100;
                    $excel->getActiveSheet()->setCellValue("EX".$row, round($var_get702,2));
                }
                 //var_genb1714
                 if($tar_qty_genb1714 === 0){ 
                    $excel->getActiveSheet()->setCellValue("EY".$row,"");
                }
                else{
                    $var_genb1714 = (($act_qty_genb1714 - $tar_qty_genb1714)/($tar_qty_genb1714))*100;
                    $excel->getActiveSheet()->setCellValue("EY".$row, round($var_genb1714,2));
                }
                 if($tar_qty_vis3660lr === 0){ 
                    $excel->getActiveSheet()->setCellValue("EZ".$row,"");
                }
                else{
                    $var_vis3660lr = (($act_qty_vis3660lr - $tar_qty_vis3660lr)/($tar_qty_vis3660lr))*100;
                    $excel->getActiveSheet()->setCellValue("EZ".$row, round($var_vis3660lr,2));
                }
                 if($tar_qty_flbnf15 === 0){ 
                    $excel->getActiveSheet()->setCellValue("FA".$row,"");
                }
                else{
                    $var_flbnf15 = (($act_qty_flbnf15 - $tar_qty_flbnf15)/($tar_qty_flbnf15))*100;
                    $excel->getActiveSheet()->setCellValue("FA".$row, round($var_flbnf15,2));
                }
                 if($tar_qty_flbpd19 === 0){ 
                    $excel->getActiveSheet()->setCellValue("FB".$row,"");
                }
                else{
                    $var_flbpd19 = (($act_qty_flbpd19 - $tar_qty_flbpd19)/($tar_qty_flbpd19))*100;
                    $excel->getActiveSheet()->setCellValue("FB".$row, round($var_flbpd19,2));
                }  
                
            }
           $excel->getActiveSheet()->setCellValue("FF".$row, " ".$obj->process_code);
            $excel->getActiveSheet()->setCellValue("FG".$row, " ".$obj->desc);
            $excel->getActiveSheet()->setCellValue("FH".$row, " ".$obj->remarks);
            $row++;
            $index_excelColumn=0;
        }
        
        //autosize
     
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
   $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
   $writer->save('php://output');
}

else{
    echo"missing input";
}
?>