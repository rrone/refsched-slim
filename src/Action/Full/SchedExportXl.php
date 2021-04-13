<?php

namespace App\Action\Full;


use App\Action\AbstractExporter;
use App\Action\SchedulerRepository;
use Exception;
use Slim\Http\Response;
use Slim\Http\Request;
use DateTime;
use DateTimeZone;

define("CERT_URL", "https://national.ayso.org/Volunteers/ViewCertification?UserName=");

class SchedExportXl extends AbstractExporter
{
    /* @var SchedulerRepository */
    private $sr;

    private $isUnique;

    private $certCheck;

    private $outFileName;
    private $user;
    private $event;

    private $mtCerts;
    private $show_medal_round_divisions;


    /**
     * SchedExportXl constructor.
     * @param SchedulerRepository $schedulerRepository
     * @throws Exception
     */
    public function __construct(SchedulerRepository $schedulerRepository)
    {
        parent::__construct('xlsx');

        $this->sr = $schedulerRepository;

        $tz = 'America/Los_Angeles';
        $timestamp = time();
        $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
        $dt->setTimestamp($timestamp); //adjust the object to correct timestamp

        $this->outFileName = 'GameSchedule_'.$dt->format('Ymd_His').'.'.$this->getFileExtension();

        $this->mtCerts = array(
            'AYSOID' => '',
            'MY' => '',
            'SAR' => '',
            'SafeHavenDate' => '',
            'CDCDate' => '',
            'RefCertDesc' => '',
            'RefCertDate' => '',
            'eAYSOName' => '',
        );

//        set_time_limit(0);
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
        $this->certCheck = $request->getQueryParam('certCheck') == 'on';

        $this->outFileName = $this->certCheck ? 'CertCheck.'.$this->outFileName : $this->outFileName;

        // generate the response
        $response = $response->withHeader('Content-Type', $this->contentType);
        $response = $response->withHeader('Content-Disposition', 'attachment; filename='.$this->outFileName);

        $content = null;
        $this->generateScheduleData($content);
        if ($this->user->admin) {
//            $this->generateSummaryCountData($content);
            $this->generateSummaryCountDateDivision($content);
            if ($this->certCheck) {
                $this->generateAssignmentsByRefereeData($content);
            }
        }

        $body = $response->getBody();
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

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $games = $this->sr->refereeAssignmentMap($projectKey);
            $games = json_decode(json_encode($games), true);

            //set the header labels
            if (!empty($games)) {
                $adminLabels = array(
                    'AYSOID',
                    'MY',
                    'SAR',
                    'Safe Haven Date',
                    'CDC Date',
                    'Ref Cert Desc',
                    'Ref Cert Date',
                    'eAYSO Name',
                );

                $labels = [];
                if ($this->user->admin && $this->certCheck) {
                    $labels = $adminLabels;
                }

                $game = $games[0];
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

                $mtArray = [];
                for ($i = 0; $i < count($adminLabels); $i++) {
                    $mtArray[] = '';
                }

                $data = array($labels);
                $ids = [];
                $idGames = [];

                //set the data : match in each row
                if ($this->certCheck) {
                    foreach ($games as $game) {
                        if (!empty($game)) {
                            $id = 0;
                            if ($this->user->admin && $game['name'] != 'Forfeit') {
                                $personRec = $this->sr->getPersonInfo($game['name']);
                                $this->isUnique = count($personRec) == 1;
                                if ($this->isUnique) {
                                    $id = $personRec[0]['AYSOID'];
                                } else {
                                    $assignor = explode(' ', $game['Assignor']);
                                    if (isset($assignor[1]) AND strlen(end($assignor)) > 1) {
                                        $assignor = $assignor[1][0].'/'.$assignor[1][1];
                                    } else {
                                        $assignor = end($assignor);
                                    }

                                    foreach ($personRec as $rec) {
                                        if (strpos($rec['SAR'], $assignor) > -1) {
                                            $id = $rec['AYSOID'];
                                            continue;
                                        } else {
                                            $id = $personRec[0]['AYSOID'];
                                        }
                                    }
                                }
                            }
                            $idGames[$id] = $game;

                            $ids[] = $id;
                        }
                    }
                }

                $certs = $this->getCerts($ids);
                $certs = json_decode(json_encode($certs), true);
                foreach ($idGames as $id => $game) {
                    if (!empty($game)) {
                        try {
                            if (isset($certs[$id])) {
                                $cert = $certs[$id];
                            } else {
                                $cert = $this->mtCerts;
                            }

                            $data[] = array_merge(
                                $cert,
                                $game
                            );
                        } catch (Exception $e) {
                            echo $e;
                            var_dump($cert);
                            var_dump($game);
                            die();
                        }

                    }
                }
            } else {
                foreach ($games as &$game) {
                    if (!empty($game)) {
                        $data[] = json_decode(json_encode($game), true);
                    }
                }
            }

            if (!empty($data)) {
                $content['Referee Match Count']['data'] = $data;
                if ($this->user->admin) {
                    $content['Referee Match Count']['options']['freezePane'] = 'J2';
                    $content['Referee Match Count']['options']['horizontalAlignment'] = ['J1:ZZ' => 'center'];
                } else {
                    $content['Referee Match Count']['options']['freezePane'] = 'B2';
                    $content['Referee Match Count']['options']['horizontalAlignment'] = ['B1:ZZ' => 'center'];
                }
            }
        }


        return $content;
    }

    /**
     * @param array $certs
     * @return array
     */
    private function getCerts(array $certs)
    {
        if (empty($certs)) {
            return array();
        }

        $ids = implode(',', $certs);
        $json = $this->curl_get("https://vc.ayso1ref.com/id/$ids");
        $certs = $this->parseCerts(json_decode($json));

        return $certs;
    }

    /**
     * @param array $certs
     * @return array
     */
    private function parseCerts(array $certs)
    {

        $volCerts = [];
        foreach ($certs as $cert) {
            $aysoID = $cert->AYSOID;

            if ($aysoID != 0) {
                $url = CERT_URL.$aysoID;

                if ($this->isUnique) {
                    $hrefAysoID = "=HYPERLINK(\"$url\", \"$aysoID\")";
                } else {
                    $hrefAysoID = "=HYPERLINK(\"$url\", \"$aysoID**\")";
                }
                $volCerts[$aysoID] = array(
                    'AYSOID' => $hrefAysoID,
                    'MY' => $cert->MY,
                    'SAR' => $cert->SAR,
                    'SafeHavenDate' => $cert->SafeHavenDate,
                    'CDCDate' => $cert->CDCDate,
                    'RefCertDesc' => $cert->RefCertDesc,
                    'RefCertDate' => $cert->RefCertDate,
                    'eAYSOName' => $cert->FullName,
                );
            } else {
                $volCerts[$aysoID] = $this->mtCerts;
            }
        }

        return $volCerts;
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

            if (!empty($data)) {
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
    private
    function generateSummaryCountDateDivision(
        &$content
    ) {
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
                $key = $date." / ".$div;

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
                    $key = $count->date." / ".$count->division;
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

            if (!empty($data)) {
                $content['Summary by Assignor']['data'] = $data;
                $content['Summary by Assignor']['options']['freezePane'] = 'A2';
                $content['Summary by Assignor']['options']['horizontalAlignment'] = ['WS' => 'center'];
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

    /**
     * @param $a
     * @param $b
     * @return int
     */
    static function sortOnID($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        return ($a['AYSOID'] < $b['AYSOID']) ? -1 : 1;

    }


//    private function curl_get($url, array $get = array(), array $options = array())

    /**
     * @param string $url
     * @param array $options
     * @return bool|string
     */
    private function curl_get($url, array $options = array())
    {
        $defaults = array(
//            CURLOPT_URL => $url.(strpos($url, '?') === false ? '?' : '').http_build_query($get),
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 900,
            CURLOPT_FAILONERROR => true,
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }

}