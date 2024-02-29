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
    $output_name = $_POST['$filter1']."_".PLANT_ID."_produk.xls";
    $product_code = $_POST['$filter1'];
   
    //filter berdasarkan delv_date saja 20141021
    $query_select = "SELECT * FROM `mix_design_composition` WHERE `product_code` = '$product_code'";
//    echo $query_select;exit();
    if ($result = $mysqli->query($query_select)) {
        $excel = new Spreadsheet();
        //activate worksheet number 1
        $excel->setActiveSheetIndex(0);
        //name the worksheet
        $excel->getActiveSheet()->setTitle('Sheet');
        //set cell A1 content with some text
        //TITLE
     
        $excel->getActiveSheet()->setCellValue('A1', "Data Mix Design Composition ( ".PLANT_ID.'_'.$product_code." )");
        //change the font size
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        
        //merge cell A1 until D1
        $excel->getActiveSheet()->mergeCells('A1:G1');
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
                            'argb' => '0099FF',
                        ],
                        'endColor' => [
                            'argb' => '0099FF',
                        ],
                    ],
                ];
        $excel->getActiveSheet()->getStyle('A2:G3')->applyFromArray($styleArray);
        //make the font become bold
        $excel->getActiveSheet()->getStyle('A1:G3')->getFont()->setBold(true);
        //HEADER
        $excel->getActiveSheet()->setCellValue('A2', "No");
        $excel->getActiveSheet()->mergeCells('A2:A3');
//        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->setCellValue('B2', "Product Code");
        $excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('B2:B3');
        $excel->getActiveSheet()->setCellValue('C2', "Material Group");
        $excel->getActiveSheet()->getStyle('BC2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('C2:C3');
        $excel->getActiveSheet()->setCellValue('D2', "Material Code"); 
        $excel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);  
        $excel->getActiveSheet()->mergeCells('D2:D3');
        $excel->getActiveSheet()->setCellValue('E2', "Material Name");  //DOC : DEC-20150120-2
        $excel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('E2:E3');
        $excel->getActiveSheet()->setCellValue('F2', "Mix Qty");   //DOC : DEC-20150120-3
        $excel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('F2:F3');
        $excel->getActiveSheet()->setCellValue('G2', "Unit");    //DOC : DEC-20150120-3
        $excel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $excel->getActiveSheet()->mergeCells('G2:G3');
      
        //ACTUAL QTY
        $i = 4;
        $no = 1;
        $product_code ="";
        $material_group = "";
        $material_code = "";
        $material_name = "";
        $mix_qty = 0;
        $unit ="";
        

        while($row = mysqli_fetch_array($result))
        {

            
            $excel->getActiveSheet()->setCellValue('A'.$i, $no++);
            $excel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('B'.$i, $row['product_code']);
            $excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('C'.$i, $row['material_group']);
            $excel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('D'.$i, $row['material_code']);
            $excel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('E'.$i, $row['material_name']);
            $excel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('F'.$i, $row['mix_qty']);
            $excel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue('G'.$i, $row['unit']);
            $excel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            
            
           

      
            $i++; 
         
            // $excel->getActiveSheet()->setCellValue('E'.$i, $row['total']);
            // $excel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
           
        }
        $excel->getActiveSheet()->setCellValue('B'.$i, $product_code);
        // $excel->getActiveSheet()->setCellValue('C'.$i, $material_group);
        // $excel->getActiveSheet()->setCellValue('D'.$i, $material_code);
        // $excel->getActiveSheet()->setCellValue('E'.$i, $material_name);
        // $excel->getActiveSheet()->setCellValue('F'.$i, $mix_qty);
        // $excel->getActiveSheet()->setCellValue('G'.$i, $unit);
       
  
        
      
        
       
        
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