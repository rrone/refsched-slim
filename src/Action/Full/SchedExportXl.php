<?php

namespace App\Action\Full;


use App\Action\AbstractExporter;
use App\Action\SchedulerRepository;
use Slim\Http\Response;
use Slim\Http\Request;

class SchedExportXl extends AbstractExporter
{
    /* @var SchedulerRepository */
    private $sr;

    private $outFileName;
    private $user;
    private $event;

    public function __construct(SchedulerRepository $schedulerRepository)
    {
        parent::__construct('xls');

        $this->sr = $schedulerRepository;
        $this->outFileName = 'GameSchedule_' . date('Ymd_His') . '.' . $this->getFileExtension();
    }
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        // generate the response
        $response = $response->withHeader('Content-Type', $this->contentType);
        $response = $response->withHeader('Content-Disposition', 'attachment; filename='. $this->outFileName);

        $content = null;

        $this->generateScheduleData($content);
        $this->generateSummaryCountData($content);
        $this->generateSummaryCountDateDivision($content);

        $body = $response->getBody();
        $body->write($this->export($content));

        return $response;
    }
    private function generateSummaryCountData(&$content)
    {
        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $counts = $this->sr->getGameCounts($projectKey);

            //set the header labels
            $labels = array ('Assignor','Date','Division','Game Count');

            $data =  array($labels);

            //set the data : game in each row
            foreach ( $counts as $count ) {
                $date = date('D, d M Y',strtotime($count->date));
                $row = array(
                    $count->assignor,
                    $date,
                    $count->division,
                    $count->game_count,
                );
                $data[] = $row;
            }

            $content['Summary Count']['data'] = $data;
            $content['Summary Count']['options']['freezePane'] = 'A2';

        }

        return $content;
    }
    private function generateScheduleData(&$content)
    {
        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $games = $this->sr->getGames($projectKey, '%', $this->user->admin);
            $has4th = $this->sr->numberOfReferees($projectKey) > 3;

            //set the header labels
            $labels = array ('Game','Date','Time','Field','Division','Pool','Home','Away','Referee Team','Referee','AR1','AR2');
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
                    $game->pool,
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
    private function generateSummaryCountDateDivision(&$content)
    {
        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $counts = $this->sr->getGameCounts($projectKey);
            $dateDivisions = $this->sr->getDatesDivisions($projectKey);

            //set the header labels
            $labels = array ('Assignor');
            $assignor = null;
            foreach ($dateDivisions as $dateDivision) {
                $date = $dateDivision->date;
                $div =  $dateDivision->division;
                $key = $date . " / " . $div;

                if(!in_array($key, $labels)){
                    $labels[] = $key;
                }
            }

            $data =  array($labels);
            $assignorList = [];

            foreach ($dateDivisions as $dateDivision) {
                $assignor = $dateDivision->assignor;
                //set the data : game in each row
                $row = array($assignor);

                foreach ($labels as $k=>$item) {
                    foreach ($counts as $count) {
                        if($assignor == $count->assignor){
                            $key = $count->date . " / " . $count->division;
                            if($key == $item){
                                $row[$k] = ''.$count->game_count;
                            } elseif ($k > 0 && empty($row[$k])) {
                                $row[$k] = null;
                            }
                        }
                    }
                }

                if(!in_array($assignor, $assignorList)){
                    $data[] = $row;
                    if(!in_array($assignor, $assignorList)) {
                        $assignorList[] = $assignor;
                    }
                }
            }

            $content['Summary by Date Division']['data'] = $data;
            $content['Summary by Date Division']['options']['freezePane'] = 'A2';
            $content['Summary by Date Division']['options']['horizontalAlignment'] = 'center';

        }

        return $content;
    }

}