<?php
/*
 * revisi 2 150909 by FSM : add reaport obat
 * 
 */
include 'inc/constant.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
date_default_timezone_set("Asia/Jakarta");



    
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
 
    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
    }
    $output_name = $_POST['tanggal'].date("His")."_".PLANT_ID."_produk.xls";
    $tanggal = $_POST['tanggal'];
   
    //filter berdasarkan delv_date saja 20141021
    $query_select = "SELECT * FROM `delivery_schedule` WHERE `schedule_date` = '$tanggal'";
//    echo $query_select;exit();
    if ($result = $mysqli->query($query_select)) {
        $excel = new Spreadsheet();
        //activate worksheet number 1
        $excel->setActiveSheetIndex(0);
        //name the worksheet
        $excel->getActiveSheet()->setTitle('Sheet');
        //set cell A1 content with some text
        //TITLE
     
        $excel->getActiveSheet()->setCellValue('A1', "PRODUCTION SCHEDULE PPIC ( ".PLANT_ID.'_'.$tanggal." )");
        //change the font size
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        
        //merge cell A1 until D1
        $excel->getActiveSheet()->mergeCells('A1:AD1');
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
        $excel->getActiveSheet()->getStyle('A2:AE3')->applyFromArray($styleArray);
        //make the font become bold
        $excel->getActiveSheet()->getStyle('A1:AE3')->getFont()->setBold(true);
        //HEADER
        $excel->getActiveSheet()->setCellValue('A2', "No");
        $excel->getActiveSheet()->mergeCells('A2:A3');
//        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->setCellValue('B2', "SO No");
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('B2:B3');
        $excel->getActiveSheet()->setCellValue('C2', "Customer Name");
        $excel->getActiveSheet()->getStyle('BC2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C2:C3');
        $excel->getActiveSheet()->setCellValue('D2', "Product Code"); 
        $excel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);  
        $excel->getActiveSheet()->mergeCells('D2:D3');
        $excel->getActiveSheet()->setCellValue('E2', "1-24Hr");  //DOC : DEC-20150120-2
        $excel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('E2:E3');
        $excel->getActiveSheet()->setCellValue('F2', "1");   //DOC : DEC-20150120-3
        $excel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('F2:F3');
        $excel->getActiveSheet()->setCellValue('G2', "2");    //DOC : DEC-20150120-3
        $excel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('G2:G3');
        $excel->getActiveSheet()->setCellValue('H2', "3");
        $excel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('H2:H3');
        $excel->getActiveSheet()->setCellValue('I2', "4");
        $excel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('I2:I3');
        $excel->getActiveSheet()->setCellValue('J2', "5");
        $excel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('J2:J3');
        $excel->getActiveSheet()->setCellValue('K2', "6");
        $excel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('K2:K3');
        $excel->getActiveSheet()->setCellValue('L2', "7");
        $excel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('L2:L3');
        $excel->getActiveSheet()->setCellValue('M2', "8");
        $excel->getActiveSheet()->getStyle('M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('M2:M3');
        $excel->getActiveSheet()->setCellValue('N2', "9");
        $excel->getActiveSheet()->getStyle('N2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('N2:N3');
        $excel->getActiveSheet()->setCellValue('O2', "10");
        $excel->getActiveSheet()->getStyle('O2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('O2:O3');
        $excel->getActiveSheet()->setCellValue('P2', "11");   
        $excel->getActiveSheet()->getStyle('P2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('P2:P3');
        $excel->getActiveSheet()->setCellValue('Q2', "12");  //DOC : DEC-20150120-2
        $excel->getActiveSheet()->getStyle('Q2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('Q2:Q3');
        $excel->getActiveSheet()->setCellValue('R2', "13");   //DOC : DEC-20150120-3
        $excel->getActiveSheet()->getStyle('R2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('R2:R3');
        $excel->getActiveSheet()->setCellValue('S2', "14");    //DOC : DEC-20150120-3
        $excel->getActiveSheet()->getStyle('S2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('S2:S3');
        $excel->getActiveSheet()->setCellValue('T2', "15");
        $excel->getActiveSheet()->getStyle('T2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('T2:T3');
        $excel->getActiveSheet()->setCellValue('U2', "16");
        $excel->getActiveSheet()->getStyle('U2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('U2:U3');
        $excel->getActiveSheet()->setCellValue('V2', "17");
        $excel->getActiveSheet()->getStyle('V2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('V2:V3');
        $excel->getActiveSheet()->setCellValue('W2', "18");
        $excel->getActiveSheet()->getStyle('W2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('W2:W3');
        $excel->getActiveSheet()->setCellValue('X2', "19");
        $excel->getActiveSheet()->getStyle('X2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('X2:X3');
        $excel->getActiveSheet()->setCellValue('Y2', "20");
        $excel->getActiveSheet()->getStyle('Y2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('Y2:Y3');
        $excel->getActiveSheet()->setCellValue('Z2', "21");
        $excel->getActiveSheet()->getStyle('Z2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('Z2:Z3');
        $excel->getActiveSheet()->setCellValue('AA2', "22");
        $excel->getActiveSheet()->getStyle('AA2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AA2:AA3');
        $excel->getActiveSheet()->setCellValue('AB2', "23");
        $excel->getActiveSheet()->getStyle('AB2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AB2:AB3');
        $excel->getActiveSheet()->setCellValue('AC2', "24");
        $excel->getActiveSheet()->getStyle('AC2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AC2:AC3');
        $excel->getActiveSheet()->setCellValue('AD2', "Unload Method");
        $excel->getActiveSheet()->getStyle('AD2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AD2:AD3');
        $excel->getActiveSheet()->setCellValue('AE2', "Remarks");
        $excel->getActiveSheet()->getStyle('AE2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('AE2:AE3');
      
        //ACTUAL QTY
        $i = 4;
        $no = 1;
        $total = 0;
        $satu = 0;
        $dua = 0;
        $tiga = 0;
        $empat = 0 ;
        $lima = 0 ;
        $enam = 0 ;
        $tujuh = 0;
        $delapan = 0 ;
        $sembilan = 0;
        $sepuluh =0;
        $sebelas = 0;
        $duabelas = 0;
        $tigabelas = 0;
        $empatbelas = 0;
        $limabelas = 0 ;
        $enambelas = 0 ;
        $tujuhbelas = 0 ;
        $delapanbelas = 0;
        $sembilanbelas = 0 ;
        $duapuluh = 0;
        $duasatu =0;
        $duadua = 0;
        $duatiga = 0;
        $duaempat = 0;

        while($row = mysqli_fetch_array($result))
        {

            
            $excel->getActiveSheet()->setCellValue('A'.$i, $no++);
            $excel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('B'.$i, $row['so_no']);
            $excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('C'.$i, $row['customer_name']);
            $excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('D'.$i, $row['product_code']);
            $excel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('E'.$i, $row['1-24hr']);
            $excel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('F'.$i, $row['1']);
            $excel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('G'.$i, $row['2']);
            $excel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('H'.$i, $row['3']);
            $excel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('I'.$i, $row['4']);
            $excel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('J'.$i, $row['5']);
            $excel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('K'.$i, $row['6']);
            $excel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('L'.$i, $row['7']);
            $excel->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('M'.$i, $row['8']);
            $excel->getActiveSheet()->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('N'.$i, $row['9']);
            $excel->getActiveSheet()->getStyle('N')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('O'.$i, $row['10']);
            $excel->getActiveSheet()->getStyle('O')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('P'.$i, $row['11']);
            $excel->getActiveSheet()->getStyle('P')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('Q'.$i, $row['12']);
            $excel->getActiveSheet()->getStyle('Q')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('R'.$i, $row['13']);
            $excel->getActiveSheet()->getStyle('R')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('S'.$i, $row['14']);
            $excel->getActiveSheet()->getStyle('S')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('T'.$i, $row['15']);
            $excel->getActiveSheet()->getStyle('T')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('U'.$i, $row['16']);
            $excel->getActiveSheet()->getStyle('U')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('V'.$i, $row['17']);
            $excel->getActiveSheet()->getStyle('V')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('W'.$i, $row['18']);
            $excel->getActiveSheet()->getStyle('W')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('X'.$i, $row['19']);
            $excel->getActiveSheet()->getStyle('X')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('Y'.$i, $row['20']);
            $excel->getActiveSheet()->getStyle('Y')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('Z'.$i, $row['21']);
            $excel->getActiveSheet()->getStyle('Z')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('AA'.$i, $row['22']);
            $excel->getActiveSheet()->getStyle('AA')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('AB'.$i, $row['23']);
            $excel->getActiveSheet()->getStyle('AB')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('AC'.$i, $row['24']);
            $excel->getActiveSheet()->getStyle('AC')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('AD'.$i, $row['unload_method']);
            $excel->getActiveSheet()->getStyle('AD')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('AE'.$i, $row['remark']);
            $excel->getActiveSheet()->getStyle('AE')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
          
            $total+=$row['1-24hr'];
            $satu+=$row['1'];
            $dua+=$row['2'];
            $tiga+=$row['3'];
            $empat+=$row['4'];
            $lima+=$row['5'];
            $enam+=$row['6'];
            $tujuh+=$row['7'];
            $delapan+=$row['8'];
            $sembilan+=$row['9'];
            $sepuluh+=$row['10'];
            $sebelas+=$row['11'];
            $duabelas+=$row['12'];
            $tigabelas+=$row['13'];
            $empatbelas+=$row['14'];
            $limabelas+=$row['15'];
            $enambelas+=$row['16'];
            $tujuhbelas+=$row['17'];
            $delapanbelas+=$row['18'];
            $sembilanbelas+=$row['19'];
            $duapuluh+=$row['20'];
            $duasatu+=$row['21'];
            $duadua+=$row['22'];
            $duatiga+=$row['23'];
            $duaempat+=$row['24'];

      
            $i++; 
         
            // $excel->getActiveSheet()->setCellValue('E'.$i, $row['total']);
            // $excel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
           
        }
        $excel->getActiveSheet()->setCellValue('D'.$i, 'TOTAL');
        $excel->getActiveSheet()->setCellValue('E'.$i, $total);
        $excel->getActiveSheet()->setCellValue('F'.$i, $satu);
        $excel->getActiveSheet()->setCellValue('G'.$i, $dua);
        $excel->getActiveSheet()->setCellValue('H'.$i, $tiga);
        $excel->getActiveSheet()->setCellValue('I'.$i, $empat);
        $excel->getActiveSheet()->setCellValue('J'.$i, $lima);
        $excel->getActiveSheet()->setCellValue('K'.$i, $enam);
        $excel->getActiveSheet()->setCellValue('L'.$i, $tujuh);
        $excel->getActiveSheet()->setCellValue('M'.$i, $delapan);
        $excel->getActiveSheet()->setCellValue('N'.$i, $sembilan);
        $excel->getActiveSheet()->setCellValue('O'.$i, $sepuluh);
        $excel->getActiveSheet()->setCellValue('P'.$i, $sebelas);
        $excel->getActiveSheet()->setCellValue('Q'.$i, $duabelas);
        $excel->getActiveSheet()->setCellValue('R'.$i, $tigabelas);
        $excel->getActiveSheet()->setCellValue('S'.$i, $empatbelas);
        $excel->getActiveSheet()->setCellValue('T'.$i, $limabelas);
        $excel->getActiveSheet()->setCellValue('U'.$i, $enambelas);
        $excel->getActiveSheet()->setCellValue('V'.$i, $tujuhbelas);
        $excel->getActiveSheet()->setCellValue('W'.$i, $delapanbelas);
        $excel->getActiveSheet()->setCellValue('X'.$i, $sembilanbelas);
        $excel->getActiveSheet()->setCellValue('Y'.$i, $duapuluh);
        $excel->getActiveSheet()->setCellValue('Z'.$i, $duasatu);
        $excel->getActiveSheet()->setCellValue('AA'.$i, $duadua);
        $excel->getActiveSheet()->setCellValue('AB'.$i, $duatiga);
        $excel->getActiveSheet()->setCellValue('AC'.$i, $duaempat);
  
        
      
        
       
        
        //autosize
       
        $rowakhir = $i - 1;
  
//        $excel->getActiveSheet()->getStyle('A'.$rowstart.':AU'.$rowakhir)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }
    else{
        die(mysqli_error($mysqli));
    }
    $result->close(); 
    unset($obj);    

    // Send Header
    header('Content-Disposition: attachment;filename="'.$output_name.'"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    header("Pragma: no-cache");
    header ("Expires: 0");


    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
   $writer->save('php://output');


?>