<?php

require '../autoload.php';



// $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(TRUE);
// $fileName = 'hello world.xlsx';
$fileName = './data.xlsx';
$spreadsheet = $reader->load($fileName);
// $worksheet = $spreadsheet->getActiveSheet();

$dataArray = $spreadsheet->getActiveSheet()
    ->rangeToArray(
        'F3:F32',     // The worksheet range that we want to retrieve
        null,        // Value that should be returned for empty cells
        true,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        true,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        true         // Should the array be indexed by cell row and cell column
    );


    $data = [];
    foreach($dataArray as $key => $val){
        $data[] = $val['F'];
    }
    var_dump($data);
