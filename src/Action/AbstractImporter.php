<?php

namespace App\Action;

use PHPExcel;
use PHPExcel_IOFactory;

/*
    // Sample array of data to publish
    $arrayData = array(
        array(NULL, 2010, 2011, 2012),   //heading labels
        array('Q1',   12,   15,   21),
        array('Q2',   56,   73,   86),
        array('Q3',   52,   61,   69),
        array('Q4',   30,   32,    0),
    );
*/

abstract class AbstractImporter
{
    private $format;
    private $objPHPExcel;

    public $fileExtension;
    public $contentType;

    public function __construct($format)
    {
        $this->format = $format;
        $this->objPHPExcel = new PHPExcel();

        switch($format) {
            case 'csv':
                $this->fileExtension = 'csv';
                $this->contentType = 'text/csv';
                break;
            case 'xls':
                $this->fileExtension = 'xlsx';
                $this->contentType = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                break;
        }
    }
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @param $filename
     * @return null|array
     */
    public function import($filename)
    {
        switch ($this->format) {
            case 'csv': return $this->importCSV ($filename);
            case 'xls': return $this->importXLSX($filename);
        }

        return null;
    }
    public function importCSV($inputFileName)
    {
//      import a CSV file into a PHPExcel object
        $inputFileType = 'CSV';

        return $this->_import($inputFileName, $inputFileType);
    }
    public function importXLSX($inputFileName)
    {
//      import a XLSX file into a PHPExcel object
        $inputFileType = 'Excel2007';

        return $this->_import($inputFileName, $inputFileType);
    }
    private function _import($inputFileName, $inputFileType)
    {
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);

        $data = $this->readWorksheet($objPHPExcel);

        return $data;
    }
    private function readWorksheet(PHPExcel $objPHPExcel)
    {
        $data = null;
//      read through the rows and cells

        $worksheet = $objPHPExcel->getActiveSheet();
        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
            foreach ($cellIterator as $cell) {
                if (!is_null($cell)) {
                    $rowData[] = $cell->getValue();
                }
            }

            $data[] = $rowData;
        }

        return $data;

    }
}

