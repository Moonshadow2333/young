<?php

require '../autoload.php';
class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
{
    private $startRow = 0;

    private $endRow   = 0;

    private $columns  = [];



    public function __construct($startRow, $endRow, $columns)
    {
        $this->startRow = $startRow;

        $this->endRow   = $endRow;

        $this->columns  = $columns;
    }



    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= $this->startRow && $row <= $this->endRow) {
            if (in_array($column, $this->columns)) {
                return true;
            }
        }

        return false;
    }
}



$myFilter = new MyReadFilter(3, 32, ['F']);
