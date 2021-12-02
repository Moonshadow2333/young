<?php

// 最终版本！！！
include('./funcs.php');
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
function solveException($lat)
{
    // 解决异常，高如彤和徐升泽填错了信息。
    $exception = ['1420181923','徐升泽/1420181940'];
    $normal = ['高如彤','徐升泽'];
    for ($i=0; $i < count($exception); $i++) {
        if (member($exception[$i], $lat)) {
            $lat = replace($normal[$i], $exception[$i], $lat);
        }
    }
    return $lat;
}
function getAllUsersInfo()
{
    $all_users_info = include('info.php');
    return $all_users_info;
}

function getUndoneUsers()
{
    $fileName = getFileName('./download');
    $startRow = 'A3';
    $endCol = 'D34';
    $users_done = readDataFromExcel($fileName, $startRow, $endCol);
    // 排除异常。（高如彤和徐升泽填错了）
    $users_done = solveException($users_done);
    // 获取全部团员的信息。
    $all_users_info = getAllUsersInfo();
    // 已经观看和全部团员取差集，获得没有观看团员的数组。
    $users_undone = array_diff($all_users_info, $users_done);
    return $users_undone;
}
function filePutContents($contents)
{
    file_put_contents('./undone.php', "<?php\r\n return ".var_export($contents, true).";\r\n");
}
// 将所有未观看的团员写入文件中。
$users_undone = getUndoneUsers();
filePutContents($users_undone);
echo '<a href="./index.php">点我</a>';
