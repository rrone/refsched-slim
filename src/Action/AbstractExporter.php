<?php

namespace App\Action;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style;

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

abstract class AbstractExporter
{
    private $format;
    private $objSpreadsheet;

    public $fileExtension;
    public $contentType;

    /**
     * AbstractExporter constructor.
     * @param $format
     */
    public function __construct($format)
    {
        $this->format = $format;
        $this->objSpreadsheet = new Spreadsheet();

        switch ($format) {
            case 'xls':
            case 'xlsx':
                $this->fileExtension = 'xlsx';
                $this->contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            //case 'pdf':
            //    $this->fileExtension = "pdf";
            //    $this->contentType = "application/pdf";
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
     * @param $content
     * @return null|string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function export($content)
    {
        switch ($this->format) {
            case 'xls':
            case 'xlsx':
                return $this->exportXLSX($content);
            default:
                return null;
        }
    }
    //public function exportPdf($content, $padlen = 18)
    //{
    //    $this->writeWorksheet($content);
    //
    //    $objWriter = IOFactory::createWriter($this->objSpreadsheet, 'Mpdf');
    //
    //    ob_start();
    //    $objWriter->save('php://output'); // Instead of file name
    //
    //    return ob_get_clean();
    //
    //}
//    public function exportCSV($content)
//    {
//        //for csv type, only export first sheet
//        $content = array_values($content);
//
//        $this->writeWorksheet($content[0]);
//
//        $objWriter = IOFactory::createWriter($this->objSpreadsheet, 'CSV');
//
//        ob_start();
//        $objWriter->save('php://output'); // Instead of file name
//
//        return ob_get_clean();
//
//    }

    /**
     * @param $a
     * @return bool
     */
    public function is_asso($a)
    {
        foreach (array_keys($a) as $key)
            if (!is_int($key)) return true;

        return false;
    }

    /**
     * @param $content
     * @param string $sheetName
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportXLSX($content, $sheetName = 'Sheet')
    {
        $xl = $this->objSpreadsheet;

        //check for sheet names as keys
        $isAssoc = $this->is_asso($content);

        // ensure unique sheetname
        foreach ($content as $shName => $data) {
            if ($isAssoc) {
                $sheetName = $shName;
            }

            $xl->createSheet();
            $xl->setActiveSheetIndex($xl->getSheetCount() - 1);

            $this->writeWorksheet($data, $sheetName);
        }

        //remove first sheet -- is blank
        $xl->removeSheetByIndex(0);

        //write to application output buffer
        $objWriter = IOFactory::createWriter($xl, 'Xlsx');

        ob_start();
        $objWriter->save('php://output'); // Instead of file name

        return ob_get_clean();

    }

    /**
     * @param $rng
     * @return string
     */
    private function pregMatch($rng)
    {
        preg_match('/(.+[a-zA-Z])/', $rng, $matches);

        return strtoupper($matches[0]);
    }

    /**
     * @param $content
     * @param string $shName
     * @return null|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeWorksheet($content, $shName = "Sheet")
    {
        //check for data
        if (!isset($content['data'])) return null;

        //get data
        $data = $content['data'];
        //get options (if any)
        $options = isset($content['options']) ? $content['options'] : null;

        //select active sheet
        $ws = $this->objSpreadsheet->getActiveSheet();

        //load data into sheet
        $ws->fromArray($data, NULL, 'A1');

        //auto-size columns
        foreach (range('A', $ws->getHighestDataColumn()) as $col) {
            $ws->getColumnDimension($col)->setAutoSize(true);
        }

        //apply options
        if (isset($options['hideCols'])) {
            // Hide sheet columns.
            $cols = $options['hideCols'];
            foreach ($cols as $col) {
                $ws->getColumnDimension($col)->setVisible(FALSE);
            }
        }

        //freeze pane
        //$options['freezePane'] = 'A2';
        if (isset($options['freezePane'])) {
            $ws->freezePane($options['freezePane']);
        }

        // date format
        // ['options']['style'] = array('M:M'=>'yyyy-mm-dd');
        if (isset($options['style'])) {
            foreach ($options['style'] as $rng => $format) {
                if ($rng == 'WS') {
                    $rng = $ws->calculateWorksheetDimension();
                } else {
                    $rowCount = $ws->getHighestRow();
                    $rng = $this->pregMatch($rng);
                    $rng .= $rowCount;
                }

                $ws->getStyle($rng)
                    ->getNumberFormat()
                    ->setFormatCode($format);
            }
        }

        //horizontal alignment
        //$options['horizontalAlignment'] = ['WS'=>'left'];
        if (isset($options['horizontalAlignment'])) {
            foreach ($options['horizontalAlignment'] as $rng => $format) {
                if ($rng == 'WS') {
                    $rng = $ws->calculateWorksheetDimension();
                } else {
                    $rowCount = $ws->getHighestRow();
                    $rng = $this->pregMatch($rng);
                    $rng .= $rowCount;
                }

                switch ($format) {
                    case 'center':
                        $ws->getStyle($rng)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_CENTER);
                        break;
                    case 'general':
                        $ws->getStyle($rng)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_GENERAL);
                        break;
                    case 'justify':
                        $ws->getStyle($rng)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_JUSTIFY);
                        break;
                    case 'left':
                        $ws->getStyle($rng)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_LEFT);
                        break;
                    case 'right':
                        $ws->getStyle($rng)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_RIGHT);
                        break;
                }
            }
        }


        //protect cells
        //$options['protect'] = array('pw' => '2016NG', 'range' => array('A:D'));
        //reference: http://stackoverflow.com/questions/20543937/disable-few-cells-in-phpexcel

        if (isset($options['protection']['pw']) and isset($options['protection']['unlocked'])) {
//            $pw = $options['protection']['pw'];
            $range = $options['protection']['unlocked'];

            //turn protection on
            $ws->getProtection()->setSheet(true);

            //now unprotect requested range
            foreach ($range as $cells) {
                $ws->getStyle($cells)->getProtection()->setLocked(Style\Protection::PROTECTION_UNPROTECTED);
            }
        }

        //ensure sheet name is unique
        $inc = 1;
        $name = $shName;
        while (!is_null($this->objSpreadsheet->getSheetByName($name))) {
            $name = $shName . $inc;
            $inc += 1;
        }

        //$shName = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $name);

        //Excel limit sheet names to 31 characters
        if (strlen($shName) > 31) {
            $shName = substr($name, -31);
        }

        //name the sheet
        $ws->setTitle($shName);

        return;

    }
}

