<?php
namespace  App\Action\Admin;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;
use App\Action\AbstractExporter;

class SchedTemplateExportController extends AbstractController
{
	private $exporter;
	private $outFileName;
	
	public function __construct(
			Container $container,
            SchedulerRepository $repository,
			AbstractExporter $exporter)
    {
		
		parent::__construct($container);

        $this->sr = $repository;
		$this->exporter = $exporter;

		$this->outFileName = 'GameScheduleTemplate_' . date('Ymd_His') . '.' . $exporter->getFileExtension();
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($GLOBALS['authed']) ? $GLOBALS['authed'] : null;
        if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
         }

        $this->logger->info("Schedule template export dispatched");

        $this->event = isset($GLOBALS['event']) ?  $GLOBALS['event'] : false;
        $this->user = isset($GLOBALS['user']) ? $GLOBALS['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->logonPath);
        }

		$file = $this->generateFile();
        if ($file['valid']) {
            // generate the response
            $response = $response->withHeader('Content-Type', $this->exporter->contentType);
            $response = $response->withHeader('Content-Disposition', 'attachment; filename='. $this->outFileName);

            $body = $response->getBody();
            $content = $file['content'];
            $body->write($this->exporter->export($content));

            return $response;

        } else {
            if (!empty($this->event)) {
                $msg = $this->event->name . ' at ' . $this->event->location . ' on ' . $this->event->dates;
                $content = array(
                    'view' => array(
                        'rep' => $this->user,
                        'action' => $this->userUpdatePath,
                        'message' => "There are no games in the database for the event: \"$msg\"",
                    )
                );

                $this->view->render($response, 'modal.html.twig', $content);
            }
        }

        return null;
    }
    public function generateFile()
    {
        $content = null;
        
        $event = $this->event;

		if (!empty($event)) {
			$projectKey = $event->projectKey;

			//set the header labels
            $labels = $this->sr->getGamesHeader();

            if (!is_null($labels)){
                foreach($labels as $key=>$label) {
                    $hdr[] = $label;
                    switch ($label) {
                        case 'projectKey':
                            $row[] = $projectKey;
                            break;
                        case 'medalRound':
                            $row[] = '0';
                            break;
                        case 'date':
                            $dateCol = $key;
                            break;
                        default:
                            $row[] = null;
                    }
                }

                $data[] = $hdr;
                $data[] = $row;

                $dateCol = chr($dateCol+65) . ":" . chr($dateCol+65);

                $wkbk['FullSchedule']['data'] = $data;
                $wkbk['FullSchedule']['options']['freezePane'] = 'A2';
                $wkbk['FullSchedule']['options']['style'] = array($dateCol=>'yyyy-mm-dd');

                $content = array('valid'=>true, 'content'=>$wkbk);

            } else {

                $content = array('valid'=>false, 'content'=>null);

            }
		}

        return $content;

	}
}
