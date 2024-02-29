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


if( isset($_POST['filter']) && !empty($_POST['filter'])){  
    
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }
    $output_name = $_POST['filter'].date("His")."_".PLANT_ID."_produk.xls";

            
    //filter berdasarkan delv_date saja 20141021
    $query_select = "SELECT A.*,B.slump_code,B.description,C.process_code,C.received_vol,C.remarks,D.`desc` "
                    . "FROM `batch_transaction` A "
                    . "LEFT JOIN `mix_design` B ON A.`product_code` = B.`product_code` "
                    . "LEFT JOIN `batch_transaction2` C ON A.`docket_no`=C.`docket_no` "
                    . "LEFT JOIN `tbl_code_acceptance` D ON C.`process_code` = D.`code`"
                    . "WHERE (UPPER(A.`product_code`)='Adjustment' OR UPPER(A.`product_code`)='SELFUSAGE')"
                                . "AND  A.`delv_date` = '".$_POST['filter']."' "
                                . "AND A.`mch_code`='".PLANT_ID."' ORDER BY delv_time";
//    echo $query_select;exit();
    if ($result = $mysqli->query($query_select)) {

        $excel = new Spreadsheet();

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
        $excel->getActiveSheet()->mergeCells('A1:EC1');
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

        $excel->getActiveSheet()->getStyle('A2:EC4')->applyFromArray($styleArray);
        //make the font become bold
        $excel->getActiveSheet()->getStyle('A1:EC4')->getFont()->setBold(true);
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
        //ACTUAL QTY
        $excel->getActiveSheet()->setCellValue('O2', "Actual Qty");
        $excel->getActiveSheet()->getStyle('O2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('O2:AW2');
        $excel->getActiveSheet()->getStyle('O2:AW2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E8D0A9');
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
        $excel->getActiveSheet()->setCellValue('AB3', "0002-100442");
        $excel->getActiveSheet()->setCellValue('AB4', "FLOBAS RPF-34");
        $excel->getActiveSheet()->setCellValue('AC3', "0000-100040");
        $excel->getActiveSheet()->setCellValue('AC4', "STEEL AGG1");
        $excel->getActiveSheet()->setCellValue('AD3', "0002-100006");
        $excel->getActiveSheet()->setCellValue('AD4', "RT6P");
        $excel->getActiveSheet()->setCellValue('AE3', "0002-100020");
        $excel->getActiveSheet()->setCellValue('AE4', "SBT CON-M");
        $excel->getActiveSheet()->setCellValue('AF3', "0002-100202");
        $excel->getActiveSheet()->setCellValue('AF4', "SBT JM-9");
        $excel->getActiveSheet()->setCellValue('AG3', "0000-100021");
        $excel->getActiveSheet()->setCellValue('AG4', "SEMEN C");
        $excel->getActiveSheet()->setCellValue('AH3', "0000-100063");
        $excel->getActiveSheet()->setCellValue('AH4', "AGG1 VAS");
        $excel->getActiveSheet()->setCellValue('AI3', "0000-100064");
        $excel->getActiveSheet()->setCellValue('AI4', "AGG2 VAS");
        $excel->getActiveSheet()->setCellValue('AJ3', "0000-100065");
        $excel->getActiveSheet()->setCellValue('AJ4', "AGG3 VAS");
        $excel->getActiveSheet()->setCellValue('AK3', "0000-100061");
        $excel->getActiveSheet()->setCellValue('AK4', "SAND VAS");
        $excel->getActiveSheet()->setCellValue('AL3', "0002-100028");
        $excel->getActiveSheet()->setCellValue('AL4', "GCP P26");
        $excel->getActiveSheet()->setCellValue('AM3', "0002-100029");
        $excel->getActiveSheet()->setCellValue('AM4', "GCP 512LN");
        $excel->getActiveSheet()->setCellValue('AN3', "0002-100027");
        $excel->getActiveSheet()->setCellValue('AN4', "FLBPF-24");
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
        $excel->getActiveSheet()->setCellValue('AT3', "0000-100098");
        $excel->getActiveSheet()->setCellValue('AT4', "B SAND");
        $excel->getActiveSheet()->setCellValue('AU3', "0002-100440");
        $excel->getActiveSheet()->setCellValue('AU4', "FLBNF-15");
        $excel->getActiveSheet()->setCellValue('AV3', "0002-100467");
        $excel->getActiveSheet()->setCellValue('AV4', "VIS 8097 SV");
        $excel->getActiveSheet()->setCellValue('AW3', "0002-100023");
        $excel->getActiveSheet()->setCellValue('AW4', "GENCB10");
       //SR3200
       //0002-100010

        
        
        //TARGET QTY
        $excel->getActiveSheet()->setCellValue('AX2', "Target Qty");
        $excel->getActiveSheet()->getStyle('AX2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AX2:CF2');
        $excel->getActiveSheet()->getStyle('AX2:CF2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D8DEAE');
        $excel->getActiveSheet()->setCellValue('AX3', "0000-100094");
        $excel->getActiveSheet()->setCellValue('AX4', "Pasir");
        $excel->getActiveSheet()->setCellValue('AY3', "0000-100089");
        $excel->getActiveSheet()->setCellValue('AY4', "MSand");
        $excel->getActiveSheet()->setCellValue('AZ3', "0000-100093");
        $excel->getActiveSheet()->setCellValue('AZ4', "STONEDUST");
        $excel->getActiveSheet()->setCellValue('BA3', "0000-100090");
        $excel->getActiveSheet()->setCellValue('BA4', "AGG1");
        $excel->getActiveSheet()->setCellValue('BB3', "0000-100091");
        $excel->getActiveSheet()->setCellValue('BB4', "AGG2");
        $excel->getActiveSheet()->setCellValue('BC3', "0000-100092");
        $excel->getActiveSheet()->setCellValue('BC4', "AGG3");
        $excel->getActiveSheet()->setCellValue('BD3', "0000-000029");
        $excel->getActiveSheet()->setCellValue('BD4', "SEMEN B");
        $excel->getActiveSheet()->setCellValue('BE3', "0000-100097");
        $excel->getActiveSheet()->setCellValue('BE4', "STEEL DUST");   
        $excel->getActiveSheet()->setCellValue('BF3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('BF4', "SEMEN A");
        $excel->getActiveSheet()->setCellValue('BG3', "0000-100017");
        $excel->getActiveSheet()->setCellValue('BG4', "SEMEN OPC TYPE 2");
        $excel->getActiveSheet()->setCellValue('BH3', "0000-100060");
        $excel->getActiveSheet()->setCellValue('BH4', "SEMEN VAS");
        $excel->getActiveSheet()->setCellValue('BI3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('BI4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('BJ3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('BJ4', "Air");
        $excel->getActiveSheet()->setCellValue('BK3', "0002-100442");
        $excel->getActiveSheet()->setCellValue('BK4', "FLOBAS RPF-34");
        $excel->getActiveSheet()->setCellValue('BL3', "0000-100040");
        $excel->getActiveSheet()->setCellValue('BL4', "STEEL AGG1");
        $excel->getActiveSheet()->setCellValue('BM3', "0002-100006");
        $excel->getActiveSheet()->setCellValue('BM4', "RT6P");
        $excel->getActiveSheet()->setCellValue('BN3', "0002-100020");
        $excel->getActiveSheet()->setCellValue('BN4', "SBT CON-M");
        $excel->getActiveSheet()->setCellValue('BO3', "0002-100202");
        $excel->getActiveSheet()->setCellValue('BO4', "SBT JM-9");
        $excel->getActiveSheet()->setCellValue('BP3', "0000-100021");
        $excel->getActiveSheet()->setCellValue('BP4', "SEMEN C");
        $excel->getActiveSheet()->setCellValue('BQ3', "0000-100063");
        $excel->getActiveSheet()->setCellValue('BQ4', "AGG1 VAS");
        $excel->getActiveSheet()->setCellValue('BR3', "0000-100064");
        $excel->getActiveSheet()->setCellValue('BR4', "AGG2 VAS");
        $excel->getActiveSheet()->setCellValue('BS3', "0000-100065");
        $excel->getActiveSheet()->setCellValue('BS4', "AGG3 VAS");
        $excel->getActiveSheet()->setCellValue('BT3', "0000-100061");
        $excel->getActiveSheet()->setCellValue('BT4', "SAND VAS");
        $excel->getActiveSheet()->setCellValue('BU3', "0002-100028");
        $excel->getActiveSheet()->setCellValue('BU4', "GCP P26");
        $excel->getActiveSheet()->setCellValue('BV3', "0002-100029");
        $excel->getActiveSheet()->setCellValue('BV4', "GCP 512LN");
        $excel->getActiveSheet()->setCellValue('BW3', "0002-100027");
        $excel->getActiveSheet()->setCellValue('BW4', "FLBPF-24");
        $excel->getActiveSheet()->setCellValue('BX3', "0002-100449");
        $excel->getActiveSheet()->setCellValue('BX4', "FLBPD-14");
        $excel->getActiveSheet()->setCellValue('BY3', "0002-100021");
        $excel->getActiveSheet()->setCellValue('BY4', "SBT PCA-8S");
        $excel->getActiveSheet()->setCellValue('BZ3', "0002-100437");
        $excel->getActiveSheet()->setCellValue('BZ4', "GENR8212");
        $excel->getActiveSheet()->setCellValue('CA3', "0002-100438");
        $excel->getActiveSheet()->setCellValue('CA4', "GET702");
        $excel->getActiveSheet()->setCellValue('CB3', "0002-100439");
        $excel->getActiveSheet()->setCellValue('CB4', "GENB1714");
        $excel->getActiveSheet()->setCellValue('CC3', "0000-100098");
        $excel->getActiveSheet()->setCellValue('CC4', "B SAND");
        $excel->getActiveSheet()->setCellValue('CD3', "0002-100440");
        $excel->getActiveSheet()->setCellValue('CD4', "FLBNF-15");
        $excel->getActiveSheet()->setCellValue('CE3', "0002-100467");
        $excel->getActiveSheet()->setCellValue('CE4', "VIS 8097 SV");
        $excel->getActiveSheet()->setCellValue('CF3', "0002-100023");
        $excel->getActiveSheet()->setCellValue('CF4', "GENCB10");
       
        //SR3200
       //0002-100010
        
        
        //MOISTURE 
         $excel->getActiveSheet()->setCellValue('CG2', "Moisture");
        $excel->getActiveSheet()->getStyle('CG2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('CG2:CN2');
        $excel->getActiveSheet()->getStyle('CG2:CN2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C1DAD6');
        $excel->getActiveSheet()->setCellValue('CG3', "0000-100094");
        $excel->getActiveSheet()->setCellValue('CG4', "Pasir");
        $excel->getActiveSheet()->setCellValue('CH3', "0000-100089");
        $excel->getActiveSheet()->setCellValue('CH4', "MSand");
        $excel->getActiveSheet()->setCellValue('CI3', "0000-100093");
        $excel->getActiveSheet()->setCellValue('CI4', "STONEDUST");
        $excel->getActiveSheet()->setCellValue('CJ3', "0000-100090");
        $excel->getActiveSheet()->setCellValue('CJ4', "AGG1");
        $excel->getActiveSheet()->setCellValue('CK3', "0000-100091");
        $excel->getActiveSheet()->setCellValue('CK4', "AGG2");
        $excel->getActiveSheet()->setCellValue('CL3', "0000-100092");
        $excel->getActiveSheet()->setCellValue('CL4', "AGG3");
        $excel->getActiveSheet()->setCellValue('CM3', "0000-000029");
        $excel->getActiveSheet()->setCellValue('CM4', "SEMEN B");
        $excel->getActiveSheet()->setCellValue('CN3', "0000-100097");
        $excel->getActiveSheet()->setCellValue('CN4', "STEEL DUST");

        
        //VARIANCE
        $excel->getActiveSheet()->setCellValue('CO2', "Variance Percentage");
        $excel->getActiveSheet()->getStyle('CO2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('CO2:DW2');
         $excel->getActiveSheet()->getStyle('CO2:DW2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('7DB6D5');   
        $excel->getActiveSheet()->setCellValue('CO3', "0000-100094");
        $excel->getActiveSheet()->setCellValue('CO4', "Pasir");
        $excel->getActiveSheet()->setCellValue('CP3', "0000-100089");
        $excel->getActiveSheet()->setCellValue('CP4', "MSand");
        $excel->getActiveSheet()->setCellValue('CQ3', "0000-100093");
        $excel->getActiveSheet()->setCellValue('CQ4', "STONEDUST");
        $excel->getActiveSheet()->setCellValue('CR3', "0000-100090");
        $excel->getActiveSheet()->setCellValue('CR4', "AGG1");
        $excel->getActiveSheet()->setCellValue('CS3', "0000-100091");
        $excel->getActiveSheet()->setCellValue('CS4', "AGG2");
        $excel->getActiveSheet()->setCellValue('CT3', "0000-100092");
        $excel->getActiveSheet()->setCellValue('CT4', "AGG3");
        $excel->getActiveSheet()->setCellValue('CU3', "0000-000029");
        $excel->getActiveSheet()->setCellValue('CU4', "SEMEN B");
        $excel->getActiveSheet()->setCellValue('CV3', "0000-100097");
        $excel->getActiveSheet()->setCellValue('CV4', "STEEL DUST");
        $excel->getActiveSheet()->setCellValue('CW3', "0000-100011");
        $excel->getActiveSheet()->setCellValue('CW4', "SEMEN A");
        $excel->getActiveSheet()->setCellValue('CX3', "0000-100017");
        $excel->getActiveSheet()->setCellValue('CX4', "SEMEN OPC TYPE 2");
        $excel->getActiveSheet()->setCellValue('CY3', "0000-100060");
        $excel->getActiveSheet()->setCellValue('CY4', "SEMEN VAS");
        $excel->getActiveSheet()->setCellValue('CZ3', "0000-000015");
        $excel->getActiveSheet()->setCellValue('CZ4', "Fly Ash");
        $excel->getActiveSheet()->setCellValue('DA3', "0000-100030");
        $excel->getActiveSheet()->setCellValue('DA4', "Air");
        $excel->getActiveSheet()->setCellValue('DB3', "0002-100442");
        $excel->getActiveSheet()->setCellValue('DB4', "FLOBAS RPF-34");
        $excel->getActiveSheet()->setCellValue('DC3', "0000-100040");
        $excel->getActiveSheet()->setCellValue('DC4', "STEEL AGG1");
        $excel->getActiveSheet()->setCellValue('DD3', "0002-100006");
        $excel->getActiveSheet()->setCellValue('DD4', "RT6P");
        $excel->getActiveSheet()->setCellValue('DE3', "0002-100020");
        $excel->getActiveSheet()->setCellValue('DE4', "SBT CON-M");
        $excel->getActiveSheet()->setCellValue('DF3', "0002-100202");
        $excel->getActiveSheet()->setCellValue('DF4', "SBT JM-9");
        $excel->getActiveSheet()->setCellValue('DG3', "0000-100021");
        $excel->getActiveSheet()->setCellValue('DG4', "SEMEN C");
        $excel->getActiveSheet()->setCellValue('DH3', "0000-100063");
        $excel->getActiveSheet()->setCellValue('DH4', "AGG1 VAS");
        $excel->getActiveSheet()->setCellValue('DI3', "0000-100064");
        $excel->getActiveSheet()->setCellValue('DI4', "AGG2 VAS");
        $excel->getActiveSheet()->setCellValue('DJ3', "0000-100065");
        $excel->getActiveSheet()->setCellValue('DJ4', "AGG3 VAS");
        $excel->getActiveSheet()->setCellValue('DK3', "0000-100061");
        $excel->getActiveSheet()->setCellValue('DK4', "SAND VAS");
        $excel->getActiveSheet()->setCellValue('DL3', "0002-100028");
        $excel->getActiveSheet()->setCellValue('DL4', "GCP P26");
        $excel->getActiveSheet()->setCellValue('DM3', "0002-100029");
        $excel->getActiveSheet()->setCellValue('DM4', "GCP 512LN");
        $excel->getActiveSheet()->setCellValue('DN3', "0002-100027");
        $excel->getActiveSheet()->setCellValue('DN4', "FLBPF-24");
        $excel->getActiveSheet()->setCellValue('DO3', "0002-100449");
        $excel->getActiveSheet()->setCellValue('DO4', "FLBPD-14");
        $excel->getActiveSheet()->setCellValue('DP3', "0002-100021");
        $excel->getActiveSheet()->setCellValue('DP4', "SBT PCA-8S");
        $excel->getActiveSheet()->setCellValue('DQ3', "0002-100437");
        $excel->getActiveSheet()->setCellValue('DQ4', "GENR8212");
        $excel->getActiveSheet()->setCellValue('DR3', "0002-100438");
        $excel->getActiveSheet()->setCellValue('DR4', "GET702");
        $excel->getActiveSheet()->setCellValue('DS3', "0002-100439");
        $excel->getActiveSheet()->setCellValue('DS4', "GENB1714");
        $excel->getActiveSheet()->setCellValue('DT3', "0000-100098");
        $excel->getActiveSheet()->setCellValue('DT4', "B SAND");
        $excel->getActiveSheet()->setCellValue('DU3', "0002-100440");
        $excel->getActiveSheet()->setCellValue('DU4', "FLBNF-15");
        $excel->getActiveSheet()->setCellValue('DV3', "0002-100467");
        $excel->getActiveSheet()->setCellValue('DV4', "VIS 8097 SV");
        $excel->getActiveSheet()->setCellValue('DW3', "0002-100023");
        $excel->getActiveSheet()->setCellValue('DW4', "GENCB10");
        //SR3200
       //0002-100010
        
        
        //USAGE
        $excel->getActiveSheet()->setCellValue('DX2', "Usage Percentage");
        $excel->getActiveSheet()->getStyle('DX2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->mergeCells('DX2:DZ3');
        $excel->getActiveSheet()->getStyle('DX2:DZ3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8DC');
       
        $excel->getActiveSheet()->setCellValue('DX4', "Sand Percentage");
//        $excel->getActiveSheet()->mergeCells('BT4:BT4');
        $excel->getActiveSheet()->setCellValue('DY4', "MSand Percentage");
//        $excel->getActiveSheet()->mergeCells('BU4:BU4');
        $excel->getActiveSheet()->setCellValue('DZ4', "FA Percentage");
//        $excel->getActiveSheet()->mergeCells('BV4:BV4');
        $excel->getActiveSheet()->setCellValue('EA2', "Process Code");
        $excel->getActiveSheet()->mergeCells('EA2:EA4');
        $excel->getActiveSheet()->setCellValue('EB2', "Desc");
        $excel->getActiveSheet()->mergeCells('EB2:EB4');
        $excel->getActiveSheet()->setCellValue('EC2', "Remarks");
        $excel->getActiveSheet()->mergeCells('EC2:EC4');
        
        $excel->getActiveSheet()->setCellValue('A5', "Total Vol");
        $excel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $excel->getActiveSheet()->mergeCells('A5:J5');
        $excel->getActiveSheet()->getStyle('A5:EC5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('D17DEAE');

        
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
            $query_select_total = "SELECT `material_code`,`design_qty`,`actual_qty`,`target_qty` 
                                    FROM `batch_transaction`,`batch_transaction_detail`
                                    WHERE `batch_transaction`.`docket_no` = `batch_transaction_detail`.`docket_no`
                                            AND `batch_transaction`.`delv_date` = '".$obj->delv_date."'
                                            AND (UPPER(`batch_transaction`.`product_code`)='Adjustment' OR UPPER(`batch_transaction`.`product_code`)='SELFUSAGE')     
                                            AND `batch_transaction`.`mch_code`='".PLANT_ID."'";
            if($result_total = $mysqli->query($query_select_total)){
                 $act_qty_sand     = 0;
                $act_qty_msand    = 0;
                $act_qty_fa       = 0;
                $act_qty_water    = 0;
                $act_qty_cementtp1   = 0;
                $act_qty_cementtp2   = 0;
                $act_qty_cementvas   = 0;
                $act_qty_lms      = 0;
                $act_qty_agg1     = 0;
                $act_qty_agg2     = 0;
                $act_qty_agg3     = 0;
                $act_qty_steelDust     = 0;
                $act_qty_flobasrpf34    = 0;
                $act_qty_stonedust = 0;
                $act_qty_steelAgg1    = 0;
                $act_qty_skhe     = 0;
                $act_qty_semenC  = 0;
                $act_qty_agg1vas = 0;
                $act_qty_agg2vas = 0;
                $act_qty_rt6p     = 0;
                $act_qty_sbtconm    = 0;
                $act_qty_sbtjm9    = 0;
                $act_qty_agg3vas   = 0;
                $act_qty_bSand = 0;
                $act_qty_semenB = 0;
                $act_qty_sandvas = 0;
                $act_qty_gcpp26 = 0;
                $act_qty_gcp512ln = 0;
                $act_qty_flbpf24 = 0;
                $act_qty_flbpd14 = 0;
                $act_qty_sbtpca8s = 0;
                $act_qty_genr8212 = 0;
                $act_qty_get702 = 0;
                $act_qty_genb1714 = 0;
                $act_qty_flbnf15 = 0;
                $act_qty_vis8097sv = 0;
                
                //target qty properties
                $tar_qty_sand     = 0;
                $tar_qty_msand    = 0;
                $tar_qty_fa       = 0;
                $tar_qty_water    = 0;
                $tar_qty_cementtp1   = 0;
                $tar_qty_cementtp2   = 0;
                $tar_qty_cementvas   = 0;
                $tar_qty_lms      = 0;
                $tar_qty_agg1     = 0;
                $tar_qty_agg2     = 0;
                $tar_qty_agg3     = 0;
                $tar_qty_steelDust     = 0;
                $tar_qty_flobasrpf34    = 0;
                $tar_qty_stonedust = 0;
                $tar_qty_steelAgg1    = 0;
                $tar_qty_skhe     = 0;
                $tar_qty_semenC  = 0;
                $tar_qty_agg1vas= 0;
                $tar_qty_agg2vas = 0;
                $tar_qty_plast8am  = 0;
                $tar_qty_sbtconm    = 0;
                $tar_qty_sbtjm9    = 0;
                $tar_qty_agg3vas   = 0;
                $tar_qty_bSand = 0;
                $tar_qty_semenB = 0;
                $tar_qty_sandvas = 0;
                $tar_qty_gcpp26 = 0;
                $tar_qty_gcp512ln = 0;
                $tar_qty_flbpf24 = 0;
                $tar_qty_flbpd14 = 0;
                $tar_qty_sbtpca8s = 0;
                $tar_qty_genr8212 = 0;
                $tar_qty_get702 = 0;
                $tar_qty_genb1714 = 0;
                $tar_qty_flbnf15 = 0;
                $tar_qty_vis8097sv = 0;

                $moi_qty_sand     = 0;
                $moi_qty_msand    = 0;
                $moi_qty_agg1     = 0;
                $moi_qty_agg2     = 0;
                $moi_qty_agg3     = 0;
                $moi_qty_semenB     = 0;
                $moi_qty_steelDust     = 0;
                $moi_qty_stonedust = 0;
                
                $all_target_qty   = 0;
                $all_actual_qty   = 0;
                $act_qty_sandd =0;
                while($obj_detail = $result_total->fetch_object()){     
                    $all_target_qty+=$obj_detail->target_qty;
                    $all_actual_qty+=$obj_detail->actual_qty;
                    
                   if($obj_detail->material_code == "0000-100094"){ //sand / pasir
                        $act_qty_sand+=$obj_detail->actual_qty;
                        $tar_qty_sand+=$obj_detail->target_qty;
                        $moi_qty_sand+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("O".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('AW'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('CE'.$row, $obj_detail->moisture);
                    }
                    elseif($obj_detail->material_code == "0000-100089"){ //MSand
                        $act_qty_msand+=$obj_detail->actual_qty;
                        $tar_qty_msand+=$obj_detail->target_qty;
                        $moi_qty_msand+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("P".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('AX'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('CF'.$row, $obj_detail->moisture);
                      
                    }
                    elseif($obj_detail->material_code == "0000-100093"){ //StoneDust
                        $act_qty_stonedust+=$obj_detail->actual_qty;
                        $tar_qty_stonedust+=$obj_detail->target_qty;
                        $moi_qty_stonedust+=$obj_detail->moisture;
                         $excel->getActiveSheet()->setCellValue("Q".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("AY".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('CG'.$row, $obj_detail->moisture);
                        
                    }
                    elseif($obj_detail->material_code == "0000-100090"){ //AGG1 
                        $act_qty_agg1+=$obj_detail->actual_qty;
                        $tar_qty_agg1+=$obj_detail->target_qty;
                        $moi_qty_agg1+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("R".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('AZ'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('CH'.$row, $obj_detail->moisture);
                       
                    }
                    elseif($obj_detail->material_code == "0000-100091"){ //AGG2
                        $act_qty_agg2+=$obj_detail->actual_qty;
                        $tar_qty_agg2+=$obj_detail->target_qty;
                        $moi_qty_agg2+=$obj_detail->moisture;
                         $excel->getActiveSheet()->setCellValue("S".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue('BA'.$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('CI'.$row, $obj_detail->moisture);
                        
                    }
                    elseif($obj_detail->material_code == "0000-100092"){ //AGG3
                        $act_qty_agg3+=$obj_detail->actual_qty;
                        $tar_qty_agg3+=$obj_detail->target_qty;
                        $moi_qty_agg3+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("T".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BB".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('CJ'.$row, $obj_detail->moisture);
                      
                    }
                     elseif($obj_detail->material_code == "0000-000029"){ //semenB
                        $moi_qty_semenB+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("U".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BC".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('CK'.$row, $obj_detail->moisture);
                        $act_qty_semenB+=$obj_detail->actual_qty;
                        $tar_qty_semenB+=$obj_detail->target_qty;
                    }  
                     elseif($obj_detail->material_code == "0000-100097"){ //steelDust
                        $moi_qty_steelDust+=$obj_detail->moisture;
                        $excel->getActiveSheet()->setCellValue("V".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BD".$row, $obj_detail->target_qty);
                        $excel->getActiveSheet()->setCellValue('CL'.$row, $obj_detail->moisture);
                        $act_qty_steelDust+=$obj_detail->actual_qty;
                        $tar_qty_steelDust+=$obj_detail->target_qty;
                    }  
                    
                    elseif($obj_detail->material_code == "0000-100011"){ //Cementtp1 
                        $excel->getActiveSheet()->setCellValue("W".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BE".$row, $obj_detail->target_qty);
                        $act_qty_cementtp1+=$obj_detail->actual_qty;
                        $tar_qty_cementtp1+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100017"){ //Cementtp2
                        $excel->getActiveSheet()->setCellValue("X".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BF".$row, $obj_detail->target_qty);
                        $act_qty_cementtp2+=$obj_detail->actual_qty;
                        $tar_qty_cementtp2+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100060"){ //Cementvas
                        $excel->getActiveSheet()->setCellValue("Y".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BG".$row, $obj_detail->target_qty);
                        $act_qty_cementvas+=$obj_detail->actual_qty;
                        $tar_qty_cementvas+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-000015"){ //Flying Ash
                        $excel->getActiveSheet()->setCellValue("Z".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BH".$row, $obj_detail->target_qty);
                        $act_qty_fa+= $obj_detail->actual_qty;
                        $tar_qty_fa+= $obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100030"){ // water / Air
                        $excel->getActiveSheet()->setCellValue("AA".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BI".$row, $obj_detail->target_qty);
                        $act_qty_water+=$obj_detail->actual_qty;
                        $tar_qty_water+=$obj_detail->target_qty;
                    }
                    
                    // elseif($obj_detail->material_code == "0002-100442"){ //flobas rpf 34
                    //     $excel->getActiveSheet()->setCellValue("AB".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BJ".$row, $obj_detail->target_qty);
                    //     $act_qty_flobasrpf34+=$obj_detail->actual_qty;
                    //     $tar_qty_flobasrpf34+=$obj_detail->target_qty;
                    // }
                    elseif($obj_detail->material_code == "0000-100040"){ //523 N 
                        $excel->getActiveSheet()->setCellValue("AC".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BK".$row, $obj_detail->target_qty);
                        $act_qty_steelAgg1+=$obj_detail->actual_qty;
                        $tar_qty_steelAgg1+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100006"){ //RT6P
                        $excel->getActiveSheet()->setCellValue("AD".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BL".$row, $obj_detail->target_qty);
                        $act_qty_rt6p+=$obj_detail->actual_qty;
                        $tar_qty_rt6p+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100020"){ //sbtconm
                        $excel->getActiveSheet()->setCellValue("AE".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BM".$row, $obj_detail->target_qty);
                        $act_qty_sbtconm+=$obj_detail->actual_qty;
                        $tar_qty_sbtconm+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0002-100202"){ //sbtjm9
                        $excel->getActiveSheet()->setCellValue("AF".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BN".$row, $obj_detail->target_qty);
                        $act_qty_sbtjm9+=$obj_detail->actual_qty;
                        $tar_qty_sbtjm9+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100021"){ //SEMEN C 
                        $excel->getActiveSheet()->setCellValue("AG".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BO".$row, $obj_detail->target_qty);
                        $act_qty_semenC+=$obj_detail->actual_qty;
                        $tar_qty_semenC+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100063"){ //VIS 1212R
                        $excel->getActiveSheet()->setCellValue("AH".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BP".$row, $obj_detail->target_qty);
                        $act_qty_agg1vas+=$obj_detail->actual_qty;
                        $tar_qty_agg1vas+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100064"){ //VIS 7080p
                        $excel->getActiveSheet()->setCellValue("AI".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BQ".$row, $obj_detail->target_qty);
                        $act_qty_agg2vas+=$obj_detail->actual_qty;
                        $tar_qty_agg2vas+=$obj_detail->target_qty;
                    }
                    elseif($obj_detail->material_code == "0000-100065"){ //SR 3200
                        $excel->getActiveSheet()->setCellValue("AJ".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BR".$row, $obj_detail->target_qty);
                        $act_qty_agg3vas+=$obj_detail->actual_qty;
                        $tar_qty_agg3vas+=$obj_detail->target_qty;
                    } 
                                         
                   
                    elseif($obj_detail->material_code == "0000-100061"){ //sandvas
                        $excel->getActiveSheet()->setCellValue("AK".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BS".$row, $obj_detail->target_qty);
                        $act_qty_sandvas+=$obj_detail->actual_qty;
                        $tar_qty_sandvas+=$obj_detail->target_qty;
                    }                  
                    elseif($obj_detail->material_code == "00002-100028"){ //VISCO 8300
                        $excel->getActiveSheet()->setCellValue("AL".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BT".$row, $obj_detail->target_qty);
                        $act_qty_gcpp26+=$obj_detail->actual_qty;
                        $tar_qty_gcpp26+=$obj_detail->target_qty;
                    }        
                    elseif($obj_detail->material_code == "0002-100029"){ //gcp512ln
                        $excel->getActiveSheet()->setCellValue("AM".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BU".$row, $obj_detail->target_qty);
                        $act_qty_gcp512ln+=$obj_detail->actual_qty;
                        $tar_qty_gcp512ln+=$obj_detail->target_qty;
                    }     
                    elseif($obj_detail->material_code == "0002-100027"){ //MAST 1100
                        $excel->getActiveSheet()->setCellValue("AN".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BV".$row, $obj_detail->target_qty);
                        $act_qty_flbpf24+=$obj_detail->actual_qty;
                        $tar_qty_flbpf24+=$obj_detail->target_qty;
                    }    
                    elseif($obj_detail->material_code == "0002-100449"){ //MAST 1007
                        $excel->getActiveSheet()->setCellValue("AO".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BW".$row, $obj_detail->target_qty);
                        $act_qty_flbpd14+=$obj_detail->actual_qty;
                        $tar_qty_flbpd14+=$obj_detail->target_qty;
                    }   
                    elseif($obj_detail->material_code == "0002-100021"){ //SBT PCA-8S
                        $excel->getActiveSheet()->setCellValue("AP".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BX".$row, $obj_detail->target_qty);
                        $act_qty_sbtpca8s+=$obj_detail->actual_qty;
                        $tar_qty_sbtpca8s+=$obj_detail->target_qty;
                    }  
                    elseif($obj_detail->material_code == "0002-100437"){ //GENR 8212
                        $excel->getActiveSheet()->setCellValue("AQ".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BY".$row, $obj_detail->target_qty);
                        $act_qty_genr8212+=$obj_detail->actual_qty;
                        $tar_qty_genr8212+=$obj_detail->target_qty;
                    }    
                    elseif($obj_detail->material_code == "0002-100438"){ //GET 702
                        $excel->getActiveSheet()->setCellValue("AR".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("BZ".$row, $obj_detail->target_qty);
                        $act_qty_get702+=$obj_detail->actual_qty;
                        $tar_qty_get702+=$obj_detail->target_qty;
                    }     
                    elseif($obj_detail->material_code == "0002-100439"){ //GENB 1714
                        $excel->getActiveSheet()->setCellValue("AS".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CA".$row, $obj_detail->target_qty);
                        $act_qty_genb1714+=$obj_detail->actual_qty;
                        $tar_qty_genb1714+=$obj_detail->target_qty;
                    }  
                    elseif($obj_detail->material_code == "0000-100098"){ //bSand
                        $excel->getActiveSheet()->setCellValue("AT".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CB".$row, $obj_detail->target_qty);
                        $act_qty_bSand+=$obj_detail->actual_qty;
                        $tar_qty_bSand+=$obj_detail->target_qty;
                    } 
                    elseif($obj_detail->material_code == "0002-100440"){ //flbnf15
                        $excel->getActiveSheet()->setCellValue("AU".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CC".$row, $obj_detail->target_qty);
                        $act_qty_flbnf15+=$obj_detail->actual_qty;
                        $tar_qty_flbnf15+=$obj_detail->target_qty;
                    }  
                    elseif($obj_detail->material_code == "0002-100467"){ //vis8097sv
                        $excel->getActiveSheet()->setCellValue("AV".$row, $obj_detail->actual_qty);
                        $excel->getActiveSheet()->setCellValue("CD".$row, $obj_detail->target_qty);
                        $act_qty_vis8097sv+=$obj_detail->actual_qty;
                        $tar_qty_vis8097sv+=$obj_detail->target_qty;
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
                $act_qty_cementtp1   = 0;
                $act_qty_cementtp2   = 0;
                $act_qty_cementvas   = 0;
                $act_qty_lms      = 0;
                $act_qty_agg1     = 0;
                $act_qty_agg2     = 0;
                $act_qty_agg3     = 0;
                $act_qty_steelDust     = 0;
                $act_qty_flobasrpf34    = 0;
                $act_qty_stonedust = 0;
                $act_qty_steelAgg1    = 0;
                $act_qty_skhe     = 0;
                $act_qty_semenC  = 0;
                $act_qty_agg1vas = 0;
                $act_qty_agg2vas = 0;
                $act_qty_rt6p     = 0;
                $act_qty_sbtconm    = 0;
                $act_qty_sbtjm9    = 0;
                $act_qty_agg3vas   = 0;
                $act_qty_bSand = 0;
                $act_qty_semenB = 0;
                $act_qty_sandvas = 0;
                $act_qty_gcpp26 = 0;
                $act_qty_gcp512ln = 0;
                $act_qty_flbpf24 = 0;
                $act_qty_flbpd14 = 0;
                $act_qty_sbtpca8s = 0;
                $act_qty_genr8212 = 0;
                $act_qty_get702 = 0;
                $act_qty_genb1714 = 0;
                $act_qty_flbnf15 = 0;
                $act_qty_vis8097sv = 0;
                
                //target qty properties
                $tar_qty_sand     = 0;
                $tar_qty_msand    = 0;
                $tar_qty_fa       = 0;
                $tar_qty_water    = 0;
                $tar_qty_cementtp1   = 0;
                $tar_qty_cementtp2   = 0;
                $tar_qty_cementvas   = 0;
                $tar_qty_lms      = 0;
                $tar_qty_agg1     = 0;
                $tar_qty_agg2     = 0;
                $tar_qty_agg3     = 0;
                $tar_qty_steelDust     = 0;
                $tar_qty_flobasrpf34    = 0;
                $tar_qty_stonedust = 0;
                $tar_qty_steelAgg1    = 0;
                $tar_qty_skhe     = 0;
                $tar_qty_semenC  = 0;
                $tar_qty_agg1vas = 0;
                $tar_qty_agg2vas = 0;
                $tar_qty_rt6p  = 0;
                $tar_qty_sbtconm    = 0;
                $tar_qty_sbtjm9    = 0;
                $tar_qty_agg3vas   = 0;
                $tar_qty_bSand = 0;
                $tar_qty_semenB = 0;
                $tar_qty_sandvas = 0;
                $tar_qty_gcpp26 = 0;
                $tar_qty_gcp512ln = 0;
                $tar_qty_flbpf24 = 0;
                $tar_qty_flbpd14 = 0;
                $tar_qty_sbtpca8s = 0;
                $tar_qty_genr8212 = 0;
                $tar_qty_get702 = 0;
                $tar_qty_genb1714 = 0;
                $tar_qty_flbnf15 = 0;
                $tar_qty_vis8097sv = 0;

                $moi_qty_sand     = 0;
                $moi_qty_msand    = 0;
                $moi_qty_agg1     = 0;
                $moi_qty_agg2     = 0;
                $moi_qty_agg3     = 0;
                $moi_qty_semenB     = 0;
                $moi_qty_steelDust     = 0;
                $moi_qty_stonedust = 0;
                
                $all_target_qty   = 0;
                $all_actual_qty   = 0;
                $act_qty_sandd =0;
                while($obj_detail = $result_detail->fetch_object()){     
                    $all_target_qty+=$obj_detail->target_qty;
                    $all_actual_qty+=$obj_detail->actual_qty;
                    
                    //  if($obj_detail->material_code == "0000-100094"){ //sand / pasir
                    //     $act_qty_sand+=$obj_detail->actual_qty;
                    //     $tar_qty_sand+=$obj_detail->target_qty;
                    //     $moi_qty_sand+=$obj_detail->moisture;
                    //     $excel->getActiveSheet()->setCellValue("O".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue('AW'.$row, $obj_detail->target_qty);
                    //     $excel->getActiveSheet()->setCellValue('CE'.$row, $obj_detail->moisture);
                    // }
                    // elseif($obj_detail->material_code == "0000-100089"){ //MSand
                    //     $act_qty_msand+=$obj_detail->actual_qty;
                    //     $tar_qty_msand+=$obj_detail->target_qty;
                    //     $moi_qty_msand+=$obj_detail->moisture;
                    //     $excel->getActiveSheet()->setCellValue("P".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue('AX'.$row, $obj_detail->target_qty);
                    //     $excel->getActiveSheet()->setCellValue('CF'.$row, $obj_detail->moisture);
                      
                    // }
                    // elseif($obj_detail->material_code == "0000-100093"){ //StoneDust
                    //     $act_qty_stonedust+=$obj_detail->actual_qty;
                    //     $tar_qty_stonedust+=$obj_detail->target_qty;
                    //     $moi_qty_stonedust+=$obj_detail->moisture;
                    //      $excel->getActiveSheet()->setCellValue("Q".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("AY".$row, $obj_detail->target_qty);
                    //     $excel->getActiveSheet()->setCellValue('CG'.$row, $obj_detail->moisture);
                        
                    // }
                    // elseif($obj_detail->material_code == "0000-100090"){ //AGG1 
                    //     $act_qty_agg1+=$obj_detail->actual_qty;
                    //     $tar_qty_agg1+=$obj_detail->target_qty;
                    //     $moi_qty_agg1+=$obj_detail->moisture;
                    //     $excel->getActiveSheet()->setCellValue("R".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue('AZ'.$row, $obj_detail->target_qty);
                    //     $excel->getActiveSheet()->setCellValue('CH'.$row, $obj_detail->moisture);
                       
                    // }
                    // elseif($obj_detail->material_code == "0000-100091"){ //AGG2
                    //     $act_qty_agg2+=$obj_detail->actual_qty;
                    //     $tar_qty_agg2+=$obj_detail->target_qty;
                    //     $moi_qty_agg2+=$obj_detail->moisture;
                    //      $excel->getActiveSheet()->setCellValue("S".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue('BA'.$row, $obj_detail->target_qty);
                    //     $excel->getActiveSheet()->setCellValue('CI'.$row, $obj_detail->moisture);
                        
                    // }
                    // elseif($obj_detail->material_code == "0000-100092"){ //AGG3
                    //     $act_qty_agg3+=$obj_detail->actual_qty;
                    //     $tar_qty_agg3+=$obj_detail->target_qty;
                    //     $moi_qty_agg3+=$obj_detail->moisture;
                    //     $excel->getActiveSheet()->setCellValue("T".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BB".$row, $obj_detail->target_qty);
                    //     $excel->getActiveSheet()->setCellValue('CJ'.$row, $obj_detail->moisture);
                      
                    // }
                    //  elseif($obj_detail->material_code == "0000-000029"){ //semenB
                    //     $moi_qty_semenB+=$obj_detail->moisture;
                    //     $excel->getActiveSheet()->setCellValue("U".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BC".$row, $obj_detail->target_qty);
                    //     $excel->getActiveSheet()->setCellValue('CK'.$row, $obj_detail->moisture);
                    //     $act_qty_semenB+=$obj_detail->actual_qty;
                    //     $tar_qty_semenB+=$obj_detail->target_qty;
                    // }  
                    //  elseif($obj_detail->material_code == "0000-100097"){ //steelDust
                    //     $moi_qty_steelDust+=$obj_detail->moisture;
                    //     $excel->getActiveSheet()->setCellValue("V".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BD".$row, $obj_detail->target_qty);
                    //     $excel->getActiveSheet()->setCellValue('CL'.$row, $obj_detail->moisture);
                    //     $act_qty_steelDust+=$obj_detail->actual_qty;
                    //     $tar_qty_steelDust+=$obj_detail->target_qty;
                    // }  
                    
                    // elseif($obj_detail->material_code == "0000-100011"){ //Cementtp1 
                    //     $excel->getActiveSheet()->setCellValue("W".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BE".$row, $obj_detail->target_qty);
                    //     $act_qty_cementtp1+=$obj_detail->actual_qty;
                    //     $tar_qty_cementtp1+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0000-100017"){ //Cementtp2
                    //     $excel->getActiveSheet()->setCellValue("X".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BF".$row, $obj_detail->target_qty);
                    //     $act_qty_cementtp2+=$obj_detail->actual_qty;
                    //     $tar_qty_cementtp2+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0000-100060"){ //Cementvas
                    //     $excel->getActiveSheet()->setCellValue("Y".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BG".$row, $obj_detail->target_qty);
                    //     $act_qty_cementvas+=$obj_detail->actual_qty;
                    //     $tar_qty_cementvas+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0000-000015"){ //Flying Ash
                    //     $excel->getActiveSheet()->setCellValue("Z".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BH".$row, $obj_detail->target_qty);
                    //     $act_qty_fa+= $obj_detail->actual_qty;
                    //     $tar_qty_fa+= $obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0000-100030"){ // water / Air
                    //     $excel->getActiveSheet()->setCellValue("AA".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BI".$row, $obj_detail->target_qty);
                    //     $act_qty_water+=$obj_detail->actual_qty;
                    //     $tar_qty_water+=$obj_detail->target_qty;
                    // }
                    
                    // // elseif($obj_detail->material_code == "0002-100442"){ //FLOBAS RPF34
                    // //     $excel->getActiveSheet()->setCellValue("AB".$row, $obj_detail->actual_qty);
                    // //     $excel->getActiveSheet()->setCellValue("BJ".$row, $obj_detail->target_qty);
                    // //     $act_qty_flobasrpf34+=$obj_detail->actual_qty;
                    // //     $tar_qty_flobasrpf34+=$obj_detail->target_qty;
                    // // }
                    // elseif($obj_detail->material_code == "0000-100040"){ //523 N 
                    //     $excel->getActiveSheet()->setCellValue("AC".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BK".$row, $obj_detail->target_qty);
                    //     $act_qty_steelAgg1+=$obj_detail->actual_qty;
                    //     $tar_qty_steelAgg1+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0002-100006"){ //rt6p
                    //     $excel->getActiveSheet()->setCellValue("AD".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BL".$row, $obj_detail->target_qty);
                    //     $act_qty_rt6p+=$obj_detail->actual_qty;
                    //     $tar_qty_rt6p+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0002-100409"){ //sbtconm
                    //     $excel->getActiveSheet()->setCellValue("AE".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BM".$row, $obj_detail->target_qty);
                    //     $act_qty_sbtconm+=$obj_detail->actual_qty;
                    //     $tar_qty_sbtconm+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0002-100202"){ //sbtjm9
                    //     $excel->getActiveSheet()->setCellValue("AF".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BN".$row, $obj_detail->target_qty);
                    //     $act_qty_sbtjm9+=$obj_detail->actual_qty;
                    //     $tar_qty_sbtjm9+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0000-100021"){ //SEMEN C 
                    //     $excel->getActiveSheet()->setCellValue("AG".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BO".$row, $obj_detail->target_qty);
                    //     $act_qty_semenC+=$obj_detail->actual_qty;
                    //     $tar_qty_semenC+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0002-100407"){ //VIS 1212R
                    //     $excel->getActiveSheet()->setCellValue("AH".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BP".$row, $obj_detail->target_qty);
                    //     $act_qty_vis1212r+=$obj_detail->actual_qty;
                    //     $tar_qty_vis1212r+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0000-100064"){ //VIS 7080p
                    //     $excel->getActiveSheet()->setCellValue("AI".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BQ".$row, $obj_detail->target_qty);
                    //     $act_qty_agg2vas+=$obj_detail->actual_qty;
                    //     $tar_qty_agg2vas+=$obj_detail->target_qty;
                    // }
                    // elseif($obj_detail->material_code == "0002-100010"){ //SR 3200
                    //     $excel->getActiveSheet()->setCellValue("AJ".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BR".$row, $obj_detail->target_qty);
                    //     $act_qty_sr3200+=$obj_detail->actual_qty;
                    //     $tar_qty_sr3200+=$obj_detail->target_qty;
                    // } 
                                         
                   
                    // elseif($obj_detail->material_code == "0002-100403"){ //V3115N
                    //     $excel->getActiveSheet()->setCellValue("AK".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BS".$row, $obj_detail->target_qty);
                    //     $act_qty_V3115N+=$obj_detail->actual_qty;
                    //     $tar_qty_V3115N+=$obj_detail->target_qty;
                    // }                  
                    // elseif($obj_detail->material_code == "0002-100432"){ //VISCO 8300
                    //     $excel->getActiveSheet()->setCellValue("AL".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BT".$row, $obj_detail->target_qty);
                    //     $act_qty_visco8300+=$obj_detail->actual_qty;
                    //     $tar_qty_visco8300+=$obj_detail->target_qty;
                    // }        
                    // elseif($obj_detail->material_code == "0002-100006"){ //gcp512ln
                    //     $excel->getActiveSheet()->setCellValue("AM".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BU".$row, $obj_detail->target_qty);
                    //     $act_qty_gcp512ln+=$obj_detail->actual_qty;
                    //     $tar_qty_gcp512ln+=$obj_detail->target_qty;
                    // }     
                    // elseif($obj_detail->material_code == "0002-100027"){ //MAST 1100
                    //     $excel->getActiveSheet()->setCellValue("AN".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BV".$row, $obj_detail->target_qty);
                    //     $act_qty_flbpf24+=$obj_detail->actual_qty;
                    //     $tar_qty_flbpf24+=$obj_detail->target_qty;
                    // }    
                    // elseif($obj_detail->material_code == "0002-100449"){ //MAST 1007
                    //     $excel->getActiveSheet()->setCellValue("AO".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BW".$row, $obj_detail->target_qty);
                    //     $act_qty_flbpd14+=$obj_detail->actual_qty;
                    //     $tar_qty_flbpd14+=$obj_detail->target_qty;
                    // }   
                    // elseif($obj_detail->material_code == "0002-100021"){ //SBT PCA-8S
                    //     $excel->getActiveSheet()->setCellValue("AP".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BX".$row, $obj_detail->target_qty);
                    //     $act_qty_sbtpca8s+=$obj_detail->actual_qty;
                    //     $tar_qty_sbtpca8s+=$obj_detail->target_qty;
                    // }  
                    // elseif($obj_detail->material_code == "0002-100437"){ //GENR 8212
                    //     $excel->getActiveSheet()->setCellValue("AQ".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BY".$row, $obj_detail->target_qty);
                    //     $act_qty_genr8212+=$obj_detail->actual_qty;
                    //     $tar_qty_genr8212+=$obj_detail->target_qty;
                    // }    
                    // elseif($obj_detail->material_code == "0002-100438"){ //GET 702
                    //     $excel->getActiveSheet()->setCellValue("AR".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("BZ".$row, $obj_detail->target_qty);
                    //     $act_qty_get702+=$obj_detail->actual_qty;
                    //     $tar_qty_get702+=$obj_detail->target_qty;
                    // }     
                    // elseif($obj_detail->material_code == "0002-100439"){ //GENB 1714
                    //     $excel->getActiveSheet()->setCellValue("AS".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("CA".$row, $obj_detail->target_qty);
                    //     $act_qty_genb1714+=$obj_detail->actual_qty;
                    //     $tar_qty_genb1714+=$obj_detail->target_qty;
                    // }  
                    // elseif($obj_detail->material_code == "0000-100098"){ //bSand
                    //     $excel->getActiveSheet()->setCellValue("AT".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("CB".$row, $obj_detail->target_qty);
                    //     $act_qty_bSand+=$obj_detail->actual_qty;
                    //     $tar_qty_bSand+=$obj_detail->target_qty;
                    // } 
                    // elseif($obj_detail->material_code == "0002-100440"){ //flbnf15
                    //     $excel->getActiveSheet()->setCellValue("AU".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("CC".$row, $obj_detail->target_qty);
                    //     $act_qty_flbnf15+=$obj_detail->actual_qty;
                    //     $tar_qty_flbnf15+=$obj_detail->target_qty;
                    // }  
                    // elseif($obj_detail->material_code == "0002-100467"){ //vis8097sv
                    //     $excel->getActiveSheet()->setCellValue("AV".$row, $obj_detail->actual_qty);
                    //     $excel->getActiveSheet()->setCellValue("CD".$row, $obj_detail->target_qty);
                    //     $act_qty_vis8097sv+=$obj_detail->actual_qty;
                    //     $tar_qty_vis8097sv+=$obj_detail->target_qty;
                    // }               
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
                if($act_qty_fa+$act_qty_cementtp1==0){$fa_percentage=0;}
                else{$fa_percentage = 100*($act_qty_fa/($act_qty_fa+$act_qty_cementtp1));}
                if($act_qty_fa+$act_qty_cementtp2==0){$fa_percentage=0;}
                else{$fa_percentage = 100*($act_qty_fa/($act_qty_fa+$act_qty_cementtp2));}
                if($act_qty_fa+$act_qty_cementvas==0){$fa_percentage=0;}
                else{$fa_percentage = 100*($act_qty_fa/($act_qty_fa+$act_qty_cementvas));}

                $excel->getActiveSheet()->setCellValue("EA".$row, round($sand_percentage,2));
                $excel->getActiveSheet()->setCellValue("EB".$row, round($msand_percentage,2));
                $excel->getActiveSheet()->setCellValue("EC".$row, round($fa_percentage,2));
                
                
                
                /*
                 * variance properties
                 */
                $var_sand     = 0;
                $var_msand    = 0;
                $var_water    = 0;
                $var_fa       = 0;
                $var_cementtp1   = 0;
                $var_cementtp2   = 0;
                $var_cementvas   = 0;
                $var_agg1     = 0;
                $var_agg2     = 0;
                $var_agg3     = 0;
                $var_semenB = 0;
                $var_steelDust = 0;
                $var_flobasrpf34    = 0;
                $var_stonedust = 0;
                $var_steelAgg1    = 0;
                $var_semenC  = 0;
                $var_agg1vas = 0;
                $var_agg2vas = 0;
                $var_rt6p  = 0;
                $var_lms      = 0;
                $var_sbtconm    = 0;
                $var_agg3vas = 0;
                $var_bSand = 0;
                $var_sandvas = 0;
                $var_gcpp26 = 0;
                $var_gcp512ln = 0;
                $var_flbpf24 = 0;
                $var_flbpd14 = 0;
                $var_sbtpca8s = 0;
                $var_genr8212 = 0;
                $var_get702 = 0;
                $var_genb1714 = 0;
                $var_flbnf15 = 0;
                $var_vis8097sv = 0;
                
                
                //sand
                 if($tar_qty_sand === 0){
                    $excel->getActiveSheet()->setCellValue("CM".$row,"");
                }
                else{
                    $var_sand = (($act_qty_sand - $tar_qty_sand)/($tar_qty_sand))*100;
                    $excel->getActiveSheet()->setCellValue("CM".$row, round($var_sand,2));
                }
                //msand
                if($tar_qty_msand === 0){
                    $excel->getActiveSheet()->setCellValue("CN".$row,"");
                }
                else{
                    $var_msand = (($act_qty_msand - $tar_qty_msand)/($tar_qty_msand))*100;
                    $excel->getActiveSheet()->setCellValue("CN".$row, round($var_msand,2));
                }
                //stonedust
                if($tar_qty_stonedust === 0){
                    //$var_water = 0;
                    $excel->getActiveSheet()->setCellValue("CO".$row,"");
                }
                else{$var_stonedust = (($act_qty_stonedust - $tar_qty_stonedust)/($tar_qty_stonedust))*100;
                        $excel->getActiveSheet()->setCellValue("CO".$row, round($var_stonedust,2));
                }
                //agg1
                if($tar_qty_agg1 === 0){
                    $excel->getActiveSheet()->setCellValue("CP".$row,"");
                }
                else{$var_agg1 = (($act_qty_agg1 - $tar_qty_agg1)/($tar_qty_agg1))*100;
                    $excel->getActiveSheet()->setCellValue("CP".$row, round($var_agg1,2));
                }
                //agg2
                if($tar_qty_agg2 === 0){
                    $excel->getActiveSheet()->setCellValue("CQ".$row,"");
                    }
                else{$var_agg2 = (($act_qty_agg2 - $tar_qty_agg2)/($tar_qty_agg2))*100;
                    $excel->getActiveSheet()->setCellValue("CQ".$row, round($var_agg2,2));
                }
                //agg3
                if($tar_qty_agg3 === 0){
                    $excel->getActiveSheet()->setCellValue("CR".$row,"");
                }
                else{$var_agg3 = (($act_qty_agg3 - $tar_qty_agg3)/($tar_qty_agg3))*100;
                    $excel->getActiveSheet()->setCellValue("CR".$row, round($var_agg3,2));
                }
                //var_semenB
                 if($tar_qty_semenB === 0){ 
                    $excel->getActiveSheet()->setCellValue("CS".$row,"");
                }
                else{
                    $var_semenB = (($act_qty_semenB - $tar_qty_semenB)/($tar_qty_semenB))*100;
                    $excel->getActiveSheet()->setCellValue("CS".$row, round($var_semenB,2));
                }
                //var_steelDust
                 if($tar_qty_steelDust === 0){ 
                    $excel->getActiveSheet()->setCellValue("CT".$row,"");
                }
                else{
                    $var_steelDust = (($act_qty_steelDust - $tar_qty_steelDust)/($tar_qty_steelDust))*100;
                    $excel->getActiveSheet()->setCellValue("CT".$row, round($var_steelDust,2));
                }
                
                //sementtp1
                if($tar_qty_cementtp1 === 0){//$var_cement = 0;
                    $excel->getActiveSheet()->setCellValue("CU".$row,"");
                }
                else{$var_cementtp1 = (($act_qty_cementtp1 - $tar_qty_cementtp1)/($tar_qty_cementtp1))*100;}
                $excel->getActiveSheet()->setCellValue("CU".$row, round($var_cementtp1,2));
                //sementtp2        
                if($tar_qty_cementtp2 === 0){//$var_cement = 0;
                    $excel->getActiveSheet()->setCellValue("CV".$row,"");
                }
                else{$var_cementtp2 = (($act_qty_cementtp2 - $tar_qty_cementtp2)/($tar_qty_cementtp2))*100;}
                $excel->getActiveSheet()->setCellValue("CV".$row, round($var_cementtp2,2));
                //semenpcc
                if($tar_qty_cementvas === 0){//$var_cement = 0;
                    $excel->getActiveSheet()->setCellValue("CW".$row,"");
                }
                else{$var_cementvas = (($act_qty_cementvas - $tar_qty_cementvas)/($tar_qty_cementvas))*100;}
                $excel->getActiveSheet()->setCellValue("CW".$row, round($var_cementvas,2));
                
                if($tar_qty_fa === 0){
                    //$var_fa = 0;
                    $excel->getActiveSheet()->setCellValue("CX".$row,"");
                }
                else{$var_fa = (($act_qty_fa - $tar_qty_fa)/($tar_qty_fa))*100;
                    $excel->getActiveSheet()->setCellValue("CX".$row, round($var_fa,2));
                }
                if($tar_qty_water === 0){
                    //$var_water = 0;
                    $excel->getActiveSheet()->setCellValue("CY".$row,"");
                }
                else{$var_water = (($act_qty_water - $tar_qty_water)/($tar_qty_water))*100;
                        $excel->getActiveSheet()->setCellValue("CY".$row, round($var_water,2));
                }
               
                if($tar_qty_flobasrpf34 === 0){
                  //  $var_flobasrpf34 = 0;
                    $excel->getActiveSheet()->setCellValue("CZ".$row,"");
                }
                else{
                    $var_flobasrpf34 = (($act_qty_flobasrpf34 - $tar_qty_flobasrpf34)/($tar_qty_flobasrpf34))*100;
                    $excel->getActiveSheet()->setCellValue("CZ".$row, round($var_flobasrpf34,2));
                }
                if($tar_qty_steelAgg1 === 0){
                    //$var_steelAgg1 = 0;
                    $excel->getActiveSheet()->setCellValue("DA".$row,"");
                }
                else{
                    $var_steelAgg1 = (($act_qty_steelAgg1 - $tar_qty_steelAgg1)/($tar_qty_steelAgg1))*100;
                    $excel->getActiveSheet()->setCellValue("DA".$row, round($var_steelAgg1,2));
                }
                if ($tar_qty_rt6p === 0){
                    $excel->getActiveSheet()->setCellValue("DB".$row,"");
                }else{
                    $var_rt6p = (($act_qty_rt6p - $tar_qty_rt6p)/($tar_qty_rt6p))*100;
                    $excel->getActiveSheet()->setCellValue("DB".$row, round($var_rt6p,2));
                }
                //sbtconm
                if($tar_qty_sbtconm === 0){
                    $excel->getActiveSheet()->setCellValue("DC".$row,"");
                }else{
                    $var_sbtconm = (($act_qty_sbtconm - $tar_qty_sbtconm)/($tar_qty_sbtconm))*100;
                    $excel->getActiveSheet()->setCellValue("DC".$row, round($var_sbtconm,2));
                }
                //sbtjm9
                if($tar_qty_sbtjm9 === 0){
                    $excel->getActiveSheet()->setCellValue("DD".$row,"");
                }else{
                    $var_sbtjm9 = (($act_qty_sbtjm9 - $tar_qty_sbtjm9)/($tar_qty_sbtjm9))*100;
                    $excel->getActiveSheet()->setCellValue("DD".$row, round($var_sbtjm9,2));
                }
                //semenC
                if($tar_qty_semenC === 0){
                    $excel->getActiveSheet()->setCellValue("DE".$row,"");
                }
                else{
                    $var_semenC = (($act_qty_semenC - $tar_qty_semenC)/($tar_qty_semenC))*100;
                    $excel->getActiveSheet()->setCellValue("DE".$row, round($var_semenC,2));
                }
                
                
                if($tar_qty_agg1vas === 0){
                    $excel->getActiveSheet()->setCellValue("DF".$row,"");
                }
                else{
                    $var_agg1vas = (($act_qty_agg1vas - $tar_qty_agg1vas)/($$tar_qty_agg1vas))*100;
                    $excel->getActiveSheet()->setCellValue("DF".$row, round($var_agg1vas,2));
                }
                
                
                if($tar_qty_agg2vas === 0){ 
                    //$var_sksf = 0;
                    $excel->getActiveSheet()->setCellValue("DG".$row,"");
                }
                else{
                    $var_agg2vas = (($act_qty_agg2vas - $tar_qty_agg2vas)/($tar_qty_agg2vas))*100;
                    $excel->getActiveSheet()->setCellValue("DG".$row, round($var_agg2vas,2));
                }
                //var_agg3vas
                if($tar_qty_agg3vas === 0){ 
                    $excel->getActiveSheet()->setCellValue("DH".$row,"");
                }
                else{
                    $var_agg3vas = (($act_qty_agg3vas - $tar_qty_agg3vas)/($tar_qty_agg3vas))*100;
                    $excel->getActiveSheet()->setCellValue("DH".$row, round($var_agg3vas,2));
                }
                 //var_bSand
                
                 
                
                 //var_sandvas
                 if($tar_qty_sandvas === 0){ 
                    $excel->getActiveSheet()->setCellValue("DI".$row,"");
                }
                else{
                    $var_sandvas = (($act_qty_sandvas - $tar_qty_sandvas)/($tar_qty_sandvas))*100;
                    $excel->getActiveSheet()->setCellValue("DI".$row, round($var_sandvas,2));
                }
                 //var_gcpp26
                 if($tar_qty_gcpp26 === 0){ 
                    $excel->getActiveSheet()->setCellValue("DJ".$row,"");
                }
                else{
                    $var_gcpp26 = (($act_qty_gcpp26 - $tar_qty_gcpp26)/($tar_qty_gcpp26))*100;
                    $excel->getActiveSheet()->setCellValue("DJ".$row, round($var_gcpp26,2));
                }
                //var_gcp512ln
                if($tar_qty_gcp512ln === 0){ 
                    $excel->getActiveSheet()->setCellValue("DK".$row,"");
                }
                else{
                    $var_gcp512ln = (($act_qty_gcp512ln - $tar_qty_gcp512ln)/($tar_qty_gcp512ln))*100;
                    $excel->getActiveSheet()->setCellValue("DK".$row, round($var_gcp512ln,2));
                }
                //var_flbpf24
                if($tar_qty_flbpf24 === 0){ 
                    $excel->getActiveSheet()->setCellValue("DL".$row,"");
                }
                else{
                    $var_flbpf24 = (($act_qty_flbpf24 - $tar_qty_flbpf24)/($tar_qty_flbpf24))*100;
                    $excel->getActiveSheet()->setCellValue("DL".$row, round($var_flbpf24,2));
                }
                //var_flbpd14
                if($tar_qty_flbpd14 === 0){ 
                    $excel->getActiveSheet()->setCellValue("DM".$row,"");
                }
                else{
                    $var_flbpd14 = (($act_qty_flbpd14 - $tar_qty_flbpd14)/($tar_qty_flbpd14))*100;
                    $excel->getActiveSheet()->setCellValue("DM".$row, round($var_flbpd14,2));
                }
                //var_sbtpca8s
                if($tar_qty_sbtpca8s === 0){ 
                    $excel->getActiveSheet()->setCellValue("DN".$row,"");
                }
                else{
                    $var_sbtpca8s = (($act_qty_sbtpca8s - $tar_qty_sbtpca8s)/($tar_qty_sbtpca8s))*100;
                    $excel->getActiveSheet()->setCellValue("DN".$row, round($var_sbtpca8s,2));
                }
                //var_genr8212
                if($tar_qty_genr8212 === 0){ 
                    $excel->getActiveSheet()->setCellValue("DO".$row,"");
                }
                else{
                    $var_genr8212 = (($act_qty_genr8212 - $tar_qty_genr8212)/($tar_qty_genr8212))*100;
                    $excel->getActiveSheet()->setCellValue("DO".$row, round($var_genr8212,2));
                }
                //var_get702
                if($tar_qty_get702 === 0){ 
                    $excel->getActiveSheet()->setCellValue("DP".$row,"");
                }
                else{
                    $var_get702 = (($act_qty_get702 - $tar_qty_get702)/($tar_qty_get702))*100;
                    $excel->getActiveSheet()->setCellValue("DP".$row, round($var_get702,2));
                }
                 //var_genb1714
                 if($tar_qty_genb1714 === 0){ 
                    $excel->getActiveSheet()->setCellValue("DQ".$row,"");
                }
                else{
                    $var_genb1714 = (($act_qty_genb1714 - $tar_qty_genb1714)/($tar_qty_genb1714))*100;
                    $excel->getActiveSheet()->setCellValue("DQ".$row, round($var_genb1714,2));
                }
                 if($tar_qty_bSand === 0){ 
                    $excel->getActiveSheet()->setCellValue("DR".$row,"");
                }
                else{
                    $var_bSand = (($act_qty_bSand - $tar_qty_bSand)/($tar_qty_bSand))*100;
                    $excel->getActiveSheet()->setCellValue("DR".$row, round($var_bSand,2));
                }
                 if($tar_qty_flbnf15 === 0){ 
                    $excel->getActiveSheet()->setCellValue("DS".$row,"");
                }
                else{
                    $var_flbnf15 = (($act_qty_flbnf15 - $tar_qty_flbnf15)/($tar_qty_flbnf15))*100;
                    $excel->getActiveSheet()->setCellValue("DS".$row, round($var_flbnf15,2));
                }
                 if($tar_qty_vis8097sv === 0){ 
                    $excel->getActiveSheet()->setCellValue("DT".$row,"");
                }
                else{
                    $var_vis8097sv = (($act_qty_vis8097sv - $tar_qty_vis8097sv)/($tar_qty_vis8097sv))*100;
                    $excel->getActiveSheet()->setCellValue("DT".$row, round($var_vis8097sv,2));
                }  
                
            }
            $excel->getActiveSheet()->setCellValue("EA".$row, " ".$obj->process_code);
            $excel->getActiveSheet()->setCellValue("EB".$row, " ".$obj->desc);
            $excel->getActiveSheet()->setCellValue("EC".$row, " ".$obj->remarks);
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