<?php
namespace  App\Action\Admin;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;
use App\Action\AbstractImporter;

class SchedImportController extends AbstractController
{
    private $importer;
    private $uploadPath;

	public function __construct(
	    Container $container,
        SchedulerRepository $sr,
        AbstractImporter $importer,
        $upload_path)
    {
		parent::__construct($container);

        $this->sr = $sr;
        $this->importer = $importer;
        $this->uploadPath = $upload_path;

    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
        if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
        }

        $this->logger->info("Schedule import dispatched");

        $this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;

        $path = $this->handleRequest($request);

        if (!empty($path)){
            return $response->withRedirect($path);
        }

        $content = array(
            'view' => array(
                'rep' => $this->rep,
                'action' => $this->schedImportPath,
                'message' => $this->msg,
                'messageStyle' => $this->msgStyle,
            )
        );

        $this->view->render($response, 'import.html.twig', $content);
    }
    private function handleRequest($request)
    {
        $this->msg = null;

        if ($request->isPost()){
            $parsedBody = $request->getParsedBody();
            $key = strtolower(array_keys($parsedBody)[0]);

            $files = $request->getUploadedFiles();

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

                    foreach($files as $file){ // iterate files
                        if(is_file($file))
                            unlink($file); // delete file
                    };

                    return $this->adminPath;
            }

        }

        return null;
    }
    private function getData($file)
    {
        $path = $this->uploadPath . $file->getClientFilename();

        $file->moveTo($path);

        $ext = strtoupper(pathinfo($path, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'CSV':
                $data = $this->importer->importCSV($path);
                break;
            case 'XLSX':
                $data = $this->importer->importXLSX($path);
                break;
            default:
                $data = null;
        }

        return $data;

    }
    public function testFile($file)
    {
        $result = null;

        $data = $this->getData($file);

        if(!empty($data)) {

            $result = empty(array_diff($data[0], $this->validHeader()));
        }

        return $result;

    }
    public function importFile($file)
    {
        $data = $this->getData($file);
        $projectKey = $this->event->projectKey;

        if(!empty($data)) {
            $games['hdr'] = $data[0];

            foreach ($data as $key=>$game) {
                if ( ($key > 0) && in_array($projectKey, $game) ) {
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
    private function validHeader()
    {
        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            //set the header labels
            $labels = $this->sr->getGamesHeader($projectKey);
        } else {
        $labels = null;
        }

        return $labels;

    }

}
