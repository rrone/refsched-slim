<?php
namespace App\Action\Admin;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;
use App\Action\AbstractExporter;

class LogExportController extends AbstractController
{
    private $exporter;
    private $outFileName;

    public function __construct(
        Container $container,
        SchedulerRepository $repository,
        AbstractExporter $exporter) {

        parent::__construct($container);

        $this->sr = $repository;
        $exporter->setFormat('xls');
        $this->exporter = $exporter;
        $this->outFileName = 'Access_Log_' . date('Ymd_His') . '.' . $exporter->getFileExtension();

    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        if (is_null($this->user) || !$this->user->admin) {
            return $response->withRedirect($this->container->get('logonPath'));
        }

        $this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->container->get('logonPath'));
        }

        $this->logStamp($request);


        if (is_null($this->event)) {
            return $response->withRedirect($this->container->get('fullPath'));
        }
        // generate the response
        $response = $response->withHeader('Content-Type', $this->exporter->contentType);
        $response = $response->withHeader('Content-Disposition', 'attachment; filename='. $this->outFileName);

        $content = null;

        $this->generateAccessLogData($content);

        $body = $response->getBody();
        $body->write($this->exporter->export($content));

        return $response;

    }
    public function generateAccessLogData(&$content)
    {
        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $log = $this->sr->getAccessLog();

            //set the header labels
            $labels = array ('Timestamp','Project Key','User','Memo');
            $data =  array($labels);

            //set the data : game in each row
            foreach ( $log as $item ) {
                $msg = explode(':', $item->note);
                if (isset($msg[1])) {
                    $user = $msg[0];
                    $note = $msg[1];
                } else {
                    $user = '';
                    $note = $item->note;
                }

                $row = array(
                    $item->timestamp,
                    $item->projectKey,
                    $user,
                    $note
                );

                $data[] = $row;
            }

            $content['Access_Log']['data'] = $data;
            $content['Access_Log']['options']['freezePane'] = 'A2';

        }

        return $content;

    }
}
