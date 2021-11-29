<?php

// 这里测试用spreadsheet读取csv文件。
require './vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

function readDataFromExcel($fileName, $startRow, $endCol)
{
    $csvObject = IOFactory::createReader('Csv')
    ->setDelimiter(',')
    ->setInputEncoding('GBK')
    ->setEnclosure('"')
    ->setReadDataOnly(true)
    ->setSheetIndex(0);
    try {
        $spreadsheet = $csvObject->load($fileName);
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        echo("文件载入失败：{$file}, Error:". $e->getMessage());
    }
    $dataArray = $spreadsheet->getActiveSheet()
    ->rangeToArray(
        // 'F3:F32',     // The worksheet range that we want to retrieve
        $startRow.':'.$endCol,
        null,        // Value that should be returned for empty cells
        true,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        true,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        true         // Should the array be indexed by cell row and cell column
    );
    $users_done = getColVal($dataArray, 'D');
    // var_dump($users_done);
    return $users_done;
}
function getColVal(array $dataArray, $colName)
{
    // 获取某一列的值。
    $colVal = [];
    foreach ($dataArray as $key => $val) {
        if (!is_null($val[$colName])) {
            $pos = strpos($val[$colName], ',');
            if ($pos) {
                $name = rtrim(substr($val[$colName], 0, $pos), '"');
            } else {
                $name = $val[$colName];
            }
            $colVal[] = $name;
        }
    }
    return $colVal;
}
function getFileName($dirName)
{
    //1.打开一个目录 （目录-->文件夹）
    $dirName = rtrim($dirName, '/').'/';
    $dir = opendir($dirName);
    //2.读取目录 readdir()
    $targetFile = [];
    while ($fileName = readdir($dir)) {
        if ($fileName != '.' && $fileName != '..') {
            $suffix = ltrim(strrchr($fileName, '.'), '.');
            if ($suffix == 'csv') {
                $targetFile[] = $dirName.$fileName;
            }
        }
    }
    return $targetFile[0];
}
$fileName = getFileName('./download');
$startRow = 'A3';
$endCol = 'D34';
$re = readDataFromExcel($fileName, $startRow, $endCol);
var_dump($re);
exit;
//导入csv文件内容:注意Csv是有大小写要求的
$csvObject = IOFactory::createReader('Csv')
    ->setDelimiter(',')
    ->setInputEncoding('GBK')
    ->setEnclosure('"')
    ->setReadDataOnly(true)
    ->setSheetIndex(0);
try {
    $spreadsheet = $csvObject->load('./download/9118f1cf19192503a52ea298e1503472.csv');
} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
    echo("文件载入失败：{$file}, Error:". $e->getMessage());
}


// 成功的脚本
// use PhpOffice\PhpSpreadsheet\IOFactory;

$objReader = PHPExcel_IOFactory::createReader('CSV')
         ->setDelimiter(',')
         ->setInputEncoding('GBK') //不设置将导致中文列内容返回boolean(false)或乱码
         ->setEnclosure('"')
         ->setSheetIndex(0);
        $spreadsheet = $objReader->load('./download/9118f1cf19192503a52ea298e1503472.csv');

$dataArray = $spreadsheet->getActiveSheet()
    ->rangeToArray(
        // 'F3:F32',     // The worksheet range that we want to retrieve
        'A3:F34',
        null,        // Value that should be returned for empty cells
        true,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        true,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        true         // Should the array be indexed by cell row and cell column
    );
    var_dump($dataArray);
