<?php

namespace App\Action;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
    private $objSpreadsheet;

    public $fileExtension;
    public $contentType;

    /**
     * AbstractImporter constructor.
     * @param $format
     */
    public function __construct($format)
    {
        $this->format = $format;
        $this->objSpreadsheet = new Spreadsheet();

        switch ($format) {
            case 'csv':
                $this->fileExtension = 'csv';
                $this->contentType = 'text/csv';
                break;
            case 'xls':
                $this->fileExtension = 'xlsx';
                $this->contentType = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                break;
            case 'xlsx':
                $this->fileExtension = 'xlsx';
                $this->contentType = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                break;
        }
    }

    /**
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @param $filename
     * @return array|null
     * @throws Exception
     */
    public function import($filename)
    {
        switch ($this->format) {
            case 'csv':
                return $this->importCSV($filename);
            case 'xls':
            case 'xlsx':
                return $this->importXLSX($filename);
        }

        return null;
    }

    /**
     * @param $inputFileName
     * @return array|null
     * @throws Exception
     */
    public function importCSV($inputFileName)
    {
//      import a CSV file into a PHPExcel object
        $inputFileType = 'Csv';

        return $this->_import($inputFileName, $inputFileType);
    }

    /**
     * @param $inputFileName
     * @return array|null
     * @throws Exception
     */
    public function importXLSX($inputFileName)
    {
//      import a XLSX file into a PHPExcel object
        $inputFileType = 'Xlsx';

        return $this->_import($inputFileName, $inputFileType);
    }

    /**
     * @param $inputFileName
     * @param $inputFileType
     * @return array|null
     * @throws Exception
     */
    private function _import($inputFileName, $inputFileType)
    {
        $objReader = IOFactory::createReader($inputFileType);
        $objSpreadsheet = $objReader->load($inputFileName);

        $data = $this->readWorksheet($objSpreadsheet);

        return $data;
    }

    /**
     * @param Spreadsheet $objSpreadsheet
     * @return array|null
     * @throws Exception
     */
    private function readWorksheet(Spreadsheet $objSpreadsheet)
    {
        $data = null;
//      read through the rows and cells

        $worksheet = $objSpreadsheet->getActiveSheet();
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

