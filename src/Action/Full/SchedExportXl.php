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

    private $show_medal_round_divisions;

    public function __construct(SchedulerRepository $schedulerRepository)
    {
        parent::__construct('xls');

        $this->sr = $schedulerRepository;
        $this->outFileName = 'GameSchedule_'.date('Ymd_His').'.'.$this->getFileExtension();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        // generate the response
        $response = $response->withHeader('Content-Type', $this->contentType);
        $response = $response->withHeader('Content-Disposition', 'attachment; filename='.$this->outFileName);

        $content = null;

        $this->generateScheduleData($content);
        if ($this->user->admin) {
//            $this->generateSummaryCountData($content);
            $this->generateSummaryCountDateDivision($content);
            $this->generateAssignmentsByRefereeData($content);
        }
        /** @noinspection PhpUndefinedMethodInspection */
        $body = $response->getBody();
        /** @noinspection PhpUndefinedMethodInspection */
        $body->write($this->export($content));

        return $response;
    }

//    private function generateSummaryCountData(&$content)
//    {
//        $event = $this->event;
//
//        if (!empty($event)) {
//            $projectKey = $event->projectKey;
//
//            $counts = $this->sr->getGameCounts($projectKey);
//
//            //set the header labels
//            $labels = array('Assignor', 'Date', 'Division', 'Match Count');
//
//            $data = array($labels);
//
//            //set the data : match in each row
//            foreach ($counts as $count) {
//                $date = date('D, d M Y', strtotime($count->date));
//                $row = array(
//                    $count->assignor,
//                    $date,
//                    $count->division,
//                    $count->game_count,
//                );
//                $data[] = $row;
//            }
//
//            $content['Summary Count']['data'] = $data;
//            $content['Summary Count']['options']['freezePane'] = 'A2';
//            $content['Summary Count']['options']['horizontalAlignment'] = ['WS'=>'justify'];
//        }
//
//        return $content;
//    }

    /**
     * @param $content
     * @return mixed
     */
    private function generateAssignmentsByRefereeData(&$content)
    {
        $event = $this->event;
        $data = [];

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $games = $this->sr->refereeAssignmentMap($projectKey);

            //set the header labels
            if (!empty($games)) {
                $game = (array)$games[0];

                $labels = [];
                foreach ($game as $hdr => $val) {
                    switch ($hdr) {
                        case 'name':
                        case 'all':
                        case 'ref':
                            $labels[] = ucfirst($hdr);
                            break;
                        case 'ar':
                            $labels[] = strtoupper($hdr);
                            break;
                        default:
                            $labels[] = $hdr;
                    }
                }

                $data = array($labels);

                //set the data : match in each row
                foreach ($games as $game) {
                    $row = [];
                    if (!empty($game)) {
                        foreach ($game as $ref) {
                            $row[] = $ref;
                        }
                    }

                    $data[] = $row;
                }

            }
            if (!empty($data)) {
                $content['Referee Match Count']['data'] = $data;
                $content['Referee Match Count']['options']['freezePane'] = 'A2';
                $content['Referee Match Count']['options']['horizontalAlignment'] = ['B1:M' => 'center'];
            }
        }

        return $content;
    }

    /**
     * @param $content
     * @return mixed
     */
    private function generateScheduleData(&$content)
    {
        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $show_medal_round = $this->sr->getMedalRound($projectKey);
            $this->show_medal_round_divisions = $this->sr->getMedalRoundDivisions($projectKey);

            $games = $this->sr->getGames($projectKey, '%', $show_medal_round || $this->user->admin);
            $has4th = $this->sr->numberOfReferees($projectKey) > 3;

            //set the header labels
            $labels = array(
                'Match',
                'Date',
                'Time',
                'Field',
                'Division',
                'Pool',
                'Home',
                'Away',
                'Referee Team',
                'Referee',
                'AR1',
                'AR2',
            );
            if ($has4th) {
                $labels[] = '4th';
            }
            $data = array($labels);

            //set the data : match in each row
            foreach ($games as $game) {
                $time = date('H:i', strtotime($game->time));

                if ($this->show_medal_round_divisions || !$game->medalRound || $this->user->admin) {
                    if (is_null($this->event->field_map)) {
                        $field = $game->field;
                    } else {
                        $field = $game->field;
                    }
                    $division = $game->division;
                    $pool = $game->pool;
                    $home = $game->home;
                    $away = $game->away;
                } else {
                    $field = "";
                    $division = "";
                    $pool = "";
                    $home = "";
                    $away = "";
                }

                $row = array(
                    $game->game_number,
                    $game->date,
                    $time,
                    $field,
                    $division,
                    $pool,
                    $home,
                    $away,
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

            if(!empty($data)) {
                $content['FullSchedule']['data'] = $data;
                $content['FullSchedule']['options']['freezePane'] = 'A2';
                $content['FullSchedule']['options']['horizontalAlignment'] = ['WS' => 'left'];
            }
        }

        return $content;

    }

    /**
     * @param $content
     * @return mixed
     */
    private function generateSummaryCountDateDivision(&$content)
    {
        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $counts = $this->sr->getGameCounts($projectKey);
            $dateDivisions = $this->sr->getDatesDivisions($projectKey);

            //set the header labels
            $labels = array('Assignor');
            $assignor = null;
            foreach ($dateDivisions as $dateDivision) {
                $date = $dateDivision->date;
                $div = $dateDivision->division;
                $key = $date . " / " . $div;

                if (!in_array($key, $labels)) {
                    $labels[] = $key;
                }
            }

            $data = array($labels);
            $rows = [];
            foreach ($labels as $k => $v) {
                foreach ($counts as $count) {
                    $assignor = $count->assignor;
                    if (!isset($rows[$assignor])) {
                        $rows[$assignor]['Assignor'] = $assignor;
                    }
                    $key = $count->date . " / " . $count->division;
                    if ($key == $v) {
                        $rows[$assignor][$v] = $count->game_count;
                    } elseif (!isset($rows[$assignor][$v])) {
                        $rows[$assignor][$v] = '';
                    }
                }
            }

            foreach ($rows as $row) {
                $data[] = array_values($row);
            }

            usort($data, array($this, "sortOnRep"));

            if(!empty($data)) {
                $content['Summary by Date Division']['data'] = $data;
                $content['Summary by Date Division']['options']['freezePane'] = 'A2';
                $content['Summary by Date Division']['options']['horizontalAlignment'] = ['WS' => 'center'];
            }
        }

        return $content;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    static function sortOnRep($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        if ($a[0] == 'Assignor') {
            return -1;
        }

        if ($b[0] == 'Assignor') {
            return 1;
        }

        return ($a[0] < $b[0]) ? -1 : 1;
    }

}