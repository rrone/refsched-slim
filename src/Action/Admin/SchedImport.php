<?php
namespace App\Action\Admin;

use App\Action\AbstractImporter;
use App\Action\SchedulerRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;


class SchedImport extends AbstractImporter
{
    /* @ Container */
    private $container;

    /* @ SchedulerRepository */
    private $sr;

    private $user;
    private $event;

    private $uploadPath;
    private $view;
    private $msg;
    private $msgStyle;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository, $uploadPath)
    {
        parent::__construct('csv');

        $this->container = $container;
        $this->sr = $schedulerRepository;
        $this->uploadPath = $uploadPath;

        $this->view = $container->get('view');
    }

    public function handler(Request $request)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        $this->msg = null;

        if ($request->isPost()) {
            $parsedBody = $request->getParsedBody();
            $key = strtolower(array_keys($parsedBody)[0]);

            $files = $request->getUploadedFiles();

            if (empty($files)) {
                return $this->getBaseURL('adminPath');
            }

            $upload = $files['uploadfile'];

            if (empty($upload->file)) {
                $this->msg = "Select a file and try again.";
                $this->msgStyle = "color:#FF0000";
            }

            switch ($key) {
                case 'test':
                    if (!empty($upload->file)) {
                        if ($this->testFile($upload)) {
                            $this->msg = "Success! The file contains recognized fields.<br>You should select the file again and click 'Upload File' button.";
                            $this->msgStyle = "color:#0000FF";
                        } else {
                            $this->msg = "Whoa there! The file contains unrecognized fields.<br> You should export the template and ensure input data fields match the template.";
                            $this->msgStyle = "color:#FF0000";
                        }
                    }
                    break;
                case 'upload':
                    if (!empty($upload->file)) {
                        $this->importFile($upload);
                    }
                    break;
                case 'done':
                    $files = glob($this->uploadPath . '*'); // get all file names

                    foreach ($files as $file) { // iterate files
                        if (is_file($file))
                            unlink($file); // delete file
                    };

                    return $this->getBaseURL('adminPath');
            }

        }

        return null;
    }

    public function render(Response &$response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'action' => $this->getBaseURL('schedImportPath'),
                'message' => $this->msg,
                'messageStyle' => $this->msgStyle,
            )
        );

        $this->view->render($response, 'import.html.twig', $content);

    }

    protected function getData(UploadedFile $file)
    {
        $path = $this->uploadPath . $file->getClientFilename();

        $file->moveTo($path);

        $ext = strtoupper(pathinfo($path, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'CSV':
                $data = $this->importCSV($path);
                break;
            case 'XLSX':
                $data = $this->importXLSX($path);
                break;
            default:
                $data = null;
        }

        return $data;

    }

    protected function testFile($file)
    {
        $result = null;

        $data = $this->getData($file);

        if (!empty($data)) {

            $result = empty(array_diff($data[0], $this->validHeader()));
        }

        return $result;

    }

    protected function importFile($file)
    {
        $data = $this->getData($file);
        $projectKey = $this->event->projectKey;
        $changes = array('adds' => 0, 'updates' => 0);

        if (!empty($data)) {
            $games['hdr'] = $data[0];

            foreach ($data as $key => $game) {
                if (($key > 0) && in_array($projectKey, $game)) {
                    $games['data'][] = $game;
                }
            }

            $changes = $this->sr->addGames($games);
        }

        $adds = $changes['adds'];
        $updates = $changes['updates'];
        $this->msg = "Upload complete. \n $adds items added. \n $updates items updated.";
        $this->msgStyle = "color:#0000FF";

        return null;

    }

    protected function validHeader()
    {
        $event = $this->event;

        if (!empty($event)) {
            //set the header labels
            $labels = $this->sr->getGamesHeader();
        } else {
            $labels = null;
        }

        return $labels;

    }


}