<?php

require '../autoload.php';



$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

$fileName = './data.csv';

try {
    $spreadsheet = $reader->load($fileName);
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    die($e->getMessage());
}



$sheet = $spreadsheet->getActiveSheet();



$res = array();

foreach ($sheet->getRowIterator(2) as $row) {
    $tmp = array();

    foreach ($row->getCellIterator() as $cell) {
        $tmp[] = $cell->getFormattedValue();
    }

    $res[$row->getRowIndex()] = $tmp;
}



echo json_encode($res);
