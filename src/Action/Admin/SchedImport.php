<?php
namespace App\Action\Admin;

use App\Action\AbstractImporter;
use App\Action\SchedulerRepository;

use PhpOffice\PhpSpreadsheet\Exception;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;


/**
 * Class SchedImport
 * @package App\Action\Admin
 */
class SchedImport extends AbstractImporter
{
    /* @ Container */
    private Container $container;

    /* @ SchedulerRepository */
    private SchedulerRepository $sr;

    private $user;
    private $event;

    private $uploadPath;
    private $view;
    private $msg;
    private $msgStyle;

    /**
     * SchedImport constructor.
     * @param Container $container
     * @param SchedulerRepository $schedulerRepository
     * @param $uploadPath
     *
     */
    public function __construct(Container $container, SchedulerRepository $schedulerRepository, $uploadPath)
    {
        parent::__construct('csv');

        $this->container = $container;
        $this->sr = $schedulerRepository;
        $this->uploadPath = $uploadPath;

        $this->view = $container->get('view');
    }

    /**
     * @param Request $request
     * @return null|string
     *
     * @throws Exception
     */
    public function handler(Request $request): ?string
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
                case 'btnDone':
                    $files = glob($this->uploadPath . '*'); // get all file names

                    foreach ($files as $file) { // iterate files
                        if (is_file($file))
                            unlink($file); // delete file
                    }

                    return $this->getBaseURL('adminPath');
            }

        }

        return null;
    }

    /**
     * @param Response $response
     *
     */
    public function render(Response $response)
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

    /**
     * @param UploadedFile $file
     * @return array|null
     * @throws Exception
     */
    protected function getData(UploadedFile $file): ?array
    {
        $path = $this->uploadPath . $file->getClientFilename();

        $file->moveTo($path);

        return $this->import($path);

    }

    /**
     * @param $file
     * @return bool|null
     * @throws Exception
     */
    protected function testFile($file): ?bool
    {
        $result = null;

        $data = $this->getData($file);

        if (!empty($data)) {

            $result = empty(array_diff($data[0], $this->validHeader()));
        }

        return $result;

    }

    /**
     * @param $file
     * @return null
     * @throws Exception
     */
    protected function importFile($file)
    {
        $data = $this->getData($file);

        $changes = array('adds' => 0, 'updates' => 0);
        $error = null;

        if (!empty($data)) {
            $games['hdr'] = $data[0];
            $games['data'] = [];
            $projectKey = $this->event->projectKey;

            foreach ($data as $key => $game) {
                if (($key > 0) && in_array($projectKey, $game)) {
                    $games['data'][] = $game;
                } elseif($key > 0) {
                    $error = 'Project key not found in import file.';
                }
            }

            $changes = $this->sr->modifyGames($games);
        }

        $adds = $changes['adds'];
        $updates = $changes['updates'];
        if(!empty($error)) {
            $changes['errors'] = $error;
        }

        $this->msg = "Upload complete. \n $adds items added. \n $updates items updated.";
        $this->msgStyle = "color:#0000FF";

        if (!empty($changes['errors'])) {
            $this->msg .= nl2br("\r\nError in data: " . $changes['errors']);
            $this->msgStyle = "color:#FF0000";
        }

        return null;
    }

    /**
     * @return array|null
     */
    protected function validHeader(): ?array
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

    /**
     * @param $path
     * @return string
     *
     */
    protected function getBaseURL($path): string
    {
        $request = $this->container->get('request');

        return $request->getUri()->getBasePath() . $this->container->get($path);
    }


}