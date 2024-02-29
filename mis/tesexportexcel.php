<?php
include('db.php');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'NIM');
$sheet->setCellValue('C1', 'Nama');
$sheet->setCellValue('D1', 'IPK');
$sheet->setCellValue('E1', 'Jurusan');

$query = mysqli_query($conns,"select * from mix_package_composition");
$i = 2;
$no = 1;
while($row = mysqli_fetch_array($query))
{
	print_r($row);
	exit();
    $sheet->setCellValue('A'.$i, $no++);
    $sheet->setCellValue('B'.$i, $row['nim']);
    $sheet->setCellValue('C'.$i, $row['nama']);
    $sheet->setCellValue('D'.$i, $row['ipk']);
    $sheet->setCellValue('E'.$i, $row['jurusan']);    
    $i++;
}

$styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
$i = $i - 1;
$sheet->getStyle('A1:E'.$i)->applyFromArray($styleArray);


$writer = new Xlsx($spreadsheet);
$writer->save('Data Mahasiswa.xlsx');
echo "<script>window.location = 'Data Mahasiswa.xlsx'</script>";

?>