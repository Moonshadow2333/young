<?php

require '../autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 实例化 Spreadsheet对象。
$spreadsheet = new Spreadsheet();
// 获取活动工作薄
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

// $writer = new Xlsx($spreadsheet);


// Set cell A1 with a string value
$sheet->setCellValue('A1', 'PhpSpreadsheet');
$sheet->setCellValue('A2', 12345.6789);
$spreadsheet->getActiveSheet()
    ->getCell('B8')
    ->setValue('Some value');
$rowArray = ['Value1', 'Value2', 'Value3', 'Value4'];
$spreadsheet->getActiveSheet()
    ->fromArray($rowArray,NULL,'C3');
var_dump($cellValue = $spreadsheet->getActiveSheet()->getCell('A2')->getValue());

// $writer->save('hello world.xlsx');