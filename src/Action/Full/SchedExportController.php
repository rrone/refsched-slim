<?php

namespace  App\Action\Full;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;
use App\Action\AbstractExporter;

class SchedExportController extends AbstractController
{
	private $exporter;
	private $outFileName;
	
	public function __construct(
			Container $container,
			SchedulerRepository $repository,
			AbstractExporter $exporter) {
		
		parent::__construct($container);
        
        $this->sr = $repository;
		//$exporter->setFormat('xls');
		$this->exporter = $exporter;
		$this->outFileName = 'GameSchedule_' . date('Ymd_His') . '.' . $exporter->getFileExtension();
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
        if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
         }

        $this->logger->info("Schedule export action dispatched");

        $this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;

        if (is_null($this->event)) {
            return $response->withRedirect($this->fullPath);
        }
        // generate the response
        $response = $response->withHeader('Content-Type', $this->exporter->contentType);
        $response = $response->withHeader('Content-Disposition', 'attachment; filename='. $this->outFileName);

		$content = $this->generateFile();		
        $body = $response->getBody();
        $body->write($this->exporter->export($content));

        return $response;		
		
    }
    public function generateFile()
    {
        $content = null;
        $event = $this->event;

		if (!empty($event)) {
			$projectKey = $event->projectKey;

			$games = $this->sr->getGames($projectKey);
			$has4th = $this->sr->numberOfReferees($projectKey) > 3;
			
			//set the header labels
			$labels = array ('Game','Date','Time','Field','Division','Home','Away','Referee Team','Referee','AR1','AR2');
			if ($has4th){
				$labels[] = '4th';
			}
			$data =  array($labels);
	
			//set the data : game in each row
			foreach ( $games as $game ) {
				$date = date('D, d M Y',strtotime($game->date));
				$time = date('H:i', strtotime($game->time));
				$row = array(
					$game->game_number,
					$date,
					$time,
					$game->field,
					$game->division,
					$game->home,
					$game->away,
					$game->assignor,
					$game->cr,
					$game->ar1,
					$game->ar2,
				);
				if ($has4th) {
					$row[] = $game->r4th;
				}
				$data[] = $row;
			}

			$content['FullSchedule']['data'] = $data;
			$content['FullSchedule']['options']['freezePane'] = 'A2';
	
		}

        return $content;

	}
}
