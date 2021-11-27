<?php

include('./funcs.php');
// $all_users = include('info.php');
require './vendor/autoload.php';
// $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
function readDataFromExcel($fileName,$startRow,$endCol)
{
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(true);
    // $fileName = 'hello world.xlsx';
    // $fileName = './download/data.xlsx';
    $spreadsheet = $reader->load($fileName);
    // $worksheet = $spreadsheet->getActiveSheet();
    $dataArray = $spreadsheet->getActiveSheet()
    ->rangeToArray(
        // 'F3:F32',     // The worksheet range that we want to retrieve
        $startRow.':'.$endCol,
        null,        // Value that should be returned for empty cells
        true,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        true,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        true         // Should the array be indexed by cell row and cell column
    );
    $users_done = getColVal($dataArray,'F');
    // var_dump($users_done);
    return $users_done;
}

function getColVal(array $dataArray,$colName){
    // 获取某一列的值。
    $colVal = [];
    foreach ($dataArray as $key => $val) {
        if(!is_null($val[$colName])){
            $colVal[] = $val[$colName];
        }
    }
    return $colVal;
}

function solveException($lat)
{
    // 解决异常，高如彤和徐升泽填错了信息。
    $exception = ['142181923','徐升泽/1420181940'];
    $normal = ['高如彤','徐升泽'];
    for ($i=0; $i < count($exception); $i++) {
        if (member($exception[$i], $lat)) {
            $lat = replace($normal[$i], $exception[$i], $lat);
        }
    }
	return $lat;
}
function getAllUsersInfo(){
	$all_users_info = include('info.php');
	return $all_users_info;
}

function getUndoneUsers(){
    $fileName = './download/data.xlsx';
    $startRow = 'F3';
    $endCol = 'F34';
    $users_done = readDataFromExcel($fileName,$startRow,$endCol);
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
