<?php
namespace App\Action;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Collection;
use DateTime;
use DateTimeZone;

/**
 * Class SchedulerRepository
 * @package App\Action
 */
class SchedulerRepository
{
    /* @var Manager */
    private $db;

    /**
     * SchedulerRepository constructor.
     * @param Manager $db
     */
    public function __construct(Manager $db)
    {
        $this->db = $db;

    }

    /**
     * @param $elem
     * @return null|object
     */
    private function getZero($elem)
    {
        return isset($elem[0]) ? (object)$elem[0] : null;
    }

    //User table functions
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllUsers()
    {
        return $this->db->table('users')
            ->get();
    }

    /**
     * @param null $key
     * @return Collection
     */
    public function getUsers($key = null)
    {
        $users = $this->db->table('users')
            ->where([
                ['enabled', true],
                ['for_events', 'like', "%$key%"]
            ])
            ->get();

        return $users;
    }

    /**
     * @param $id
     * @param $keys
     * @return null
     */
    public function updateUserEvents($id, $keys)
    {
        $forEvents = serialize($keys);

        $this->db->table('users')
            ->where('id', $id)
            ->update([
                'for_events' => $forEvents,
            ]);

        return null;
    }

    /**
     * @param $hash
     * @return null|object
     */
    public function getUserByPW($hash)
    {
        if (empty($hash)) {
            return null;
        }

        $user = $this->db->table('users')
            ->where('hash', 'like', $hash)
            ->get();

        return $this->getZero($user);

    }

    /**
     * @param $name
     * @return null|object
     */
    public function getUserByName($name)
    {
        if (empty($name)) {
            return null;
        }

        $user = $this->db->table('users')
            ->where('name', 'like', $name)
            ->get();

        return $this->getZero($user);

    }

    /**
     * @param $user
     * @return null
     */
    public function setUser($user)
    {
        if (empty($user)) {
            return null;
        }

        $u = $this->getUserByName($user['name']);
        if (empty($u)) {
            $newUser = array(
                'name' => $user['name'],
                'enabled' => $user['enabled'],
                'hash' => $user['hash'],
            );

            $this->db->table('users')
                ->insert([$newUser]);

            $newUser = $this->getUserByName($newUser['name']);

            return $newUser->id;

        } else {
            $hash = $user['hash'];

            $this->db->table('users')
                ->where('id', $u->id)
                ->update([
                    'hash' => $hash,
                ]);

            return $u->id;
        }

    }

    /**
     * @param $id
     * @return null
     */
    public function dropUserById($id)
    {
        if (empty($id)) {
            return null;
        }

        $this->db->table('users')
            ->where('id', $id)
            ->delete();

        return $id;
    }
    //Events table functions
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCurrentEvents()
    {
        return $this->db->table('events')
            ->where('view', true)
            ->orderBy('start_date', 'asc')
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getEnabledEvents()
    {
        return $this->db->table('events')
            ->where('enabled', true)
            ->orderBy('id', 'asc')
            ->orderBy('start_date', 'asc')
            ->get();
    }

    /**
     * @param $projectKey
     * @param bool $asCollection
     * @return null|object
     */
    public function getEvent($projectKey, $asCollection = false)
    {
        if (empty($projectKey)) {
            return null;
        }

        $event = $this->db->table('events')
            ->where('projectKey', '=', $projectKey)
            ->get();

        if (!$asCollection) {
            $event = $this->getZero($event);
        }

        return $event;
    }

    /**
     * @param $label
     * @return null|object
     */
    public function getEventByLabel($label)
    {
        $event = $this->db->table('events')
            ->where('label', '=', $label)
            ->get();

        return $this->getZero($event);
    }

    /**
     * @param $projectKey
     * @return mixed
     */
    public function getEventLabel($projectKey)
    {
        $event = $this->getEvent($projectKey);

        return $event->label;
    }

    /**
     * @param $projectKey
     * @return null
     */
    public function getLocked($projectKey)
    {
        $status = $this->db->table('events')
            ->where('projectKey', '=', $projectKey)
            ->get();

        $status = $this->getZero($status);
        if (!is_null($status)) {
            return $status->locked;
        } else {
            return null;
        }
    }

    /**
     * @param $projectKey
     * @return null
     */
    public function numberOfReferees($projectKey)
    {
        $numRefs = $this->db->table('events')
            ->select('num_refs')
            ->where('projectKey', '=', $projectKey)
            ->get();
        $numRefs = $this->getZero($numRefs);

        if (!is_null($numRefs)) {
            return $numRefs->num_refs;
        } else {
            return null;
        }
    }

    /**
     * @param $key
     */
    public function lockProject($key)
    {
        $this->db->table('events')
            ->where('projectKey', $key)
            ->update(['locked' => true]);
    }

    /**
     * @param $key
     * @return null
     */
    public function showMedalRound($key)
    {
        $this->db->table('events')
            ->where('projectKey', $key)
            ->update(['show_medal_round' => true]);

        return null;
    }

    /**
     * @param $key
     * @return null
     */
    public function hideMedalRound($key)
    {
        $this->db->table('events')
            ->where('projectKey', $key)
            ->update(['show_medal_round' => false]);

        return null;
    }

    /**
     * @param $projectKey
     * @return mixed
     */
    public function getMedalRound($projectKey)
    {
        $status = $this->db->table('events')
            ->where('projectKey', '=', $projectKey)
            ->get();

        $status = $this->getZero($status);
        if (!is_null($status)) {
            return $status->show_medal_round;
        } else {
            return null;
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function unlockProject($key)
    {
        $this->db->table('events')
            ->where('projectKey', $key)
            ->update(['locked' => false]);

        return null;
    }

    /**
     * @return null
     */
    public function getEventMessage()
    {
        $status = $this->db->table('messages')
            ->where('enabled', true)
            ->get();

        $status = $this->getZero($status);
        if (!is_null($status)) {
            return $status->message;
        } else {
            return null;
        }

    }

    //Games table functions
    /**
     * @param string $projectKey
     * @param string $group
     * @param bool $medalRound
     * @param string $sortOn
     * @return \Illuminate\Support\Collection
     */
    public function getGames($projectKey = '%', $group = '%', $medalRound = false,  $sortOn = 'game_number')
    {

        $group = '%'. $group . '%';
        $medalRound = $medalRound ? '%' : false;

        $games = $this->db->table('games')
            ->where([
                ['projectKey', '=', $projectKey],
                ['division', 'like', $group],
                ['medalRound', 'like', $medalRound],
            ])
            ->orWhere([
                ['projectKey', 'like', $projectKey],
                ['division', 'like', $group],
                ['date', '<=', date('Y-m-d')]

            ])
            ->orderBy($sortOn, 'asc')
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        return $games;
    }

    /**
     * @param string $projectKey
     * @param string $group
     * @param bool $medalRound
     * @return \Illuminate\Support\Collection
     */
    public function getUnassignedGames($projectKey = '%', $group = '%', $medalRound = false)
    {
        $group .= '%';
        $medalRound = $medalRound ? '%' : false;

        return $this->db->table('games')
            ->where([
                ['projectKey', 'like', $projectKey],
                ['division', 'like', $group],
                ['medalRound', 'like', $medalRound],
                ['assignor', 'like', '']
            ])
            ->get();
    }

    /**
     * @param $projectKey
     * @param $rep
     * @param bool $medalRound
     * @return \Illuminate\Support\Collection
     */
    public function getGamesByRep($projectKey, $rep, $medalRound = false)
    {
        $medalRound = $medalRound ? '%' : false;

        $games = $this->db->table('games')
            ->where([
                ['projectKey', '=', $projectKey],
                ['medalRound', 'like', $medalRound],
                ['assignor', '=', $rep],
            ])
            ->get();

        return $games;
    }

    /**
     * @param $projectKey
     * @return array
     */
    public function getGroups($projectKey)
    {
        $groups = $this->db->table('games')
            ->where('projectKey', $projectKey)
            ->select('division')
            ->distinct()
            ->get();

        $result = [];
        foreach ($groups as $group) {
            $u = stripos($group->division, "U");
            $group = substr($group->division, $u-2, 3);
            if (!in_array($group, $result)) {
                $result[] = $group;
            }
        }
        asort($result);

        return $result;
    }

    /**
     * @param $projectKey
     * @param $rep
     */
    public function clearAssignor($projectKey, $rep)
    {
        $this->db->table('games')
            ->where([
                ['assignor', $rep],
                ['projectKey', '=', $projectKey]
            ])
            ->update(['assignor' => '']);
    }

    /**
     * @param $data
     * @return null
     */
    public function updateAssignor($data)
    {
        if (empty($data)) {
            return null;
        }

        foreach ($data as $id => $rep) {
            if ($id != 'Submit') {
                $rep = $rep == 'None' ? null : $rep;
                $this->db->table('games')
                    ->where('id', $id)
                    ->update(['assignor' => $rep]);
            }
        }

        return null;
    }

    /**
     * @param $data
     * @return null
     */
    public function updateAssignments($data)
    {
        if (empty($data)) {
            return null;
        }

        $data['r4th'] = isset($data['r4th']) ? $data['r4th'] : null;
        foreach ($data as $id => $value) {
            if ( in_array($value, ['Update Assignments', 'Clear All'])) {
                $this->db->table('games')
                    ->where('id', $id)
                    ->update([
                        'cr' => trim($data['cr']),
                        'ar1' => trim($data['ar1']),
                        'ar2' => trim($data['ar2']),
                        'r4th' => trim($data['r4th'])
                    ]);
            }
        }

        return null;
    }

    /**
     * @param $id
     * @return null
     */
    public function gameIdToGameNumber($id)
    {
        $gameNo = $this->db->table('games')
            ->select('game_number')
            ->where('id', '=', $id)
            ->get();
        $gameNo = $this->getZero($gameNo);

        if (!is_null($gameNo)) {
            return $gameNo->game_number;
        } else {
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function getGamesHeader()
    {
        $games = $this->getGames();
        $gameLabels = (array)$this->getZero($games);

        if (!empty($gameLabels)) {

            return array_keys($gameLabels);
        }

        return null;
    }

    /**
     * @return int
     */
    public function getNextGameId()
    {
        $id = 0;

        $games = $this->db->table('games')
            ->get();

        foreach ($games as $game) {
            $id = $game->id;
        };

        $id++;

        return $id;
    }

    /**
     * @param $data
     * @return array|null
     */
    public function modifyGames($data)
    {
        if (is_null($data)) {
            return null;
        }

        $hdr = array_values($data['hdr']);
        $games = $data['data'];

        $changes = array('adds' => 0, 'updates' => 0, 'errors' => []);

        if (!empty($games)) {
            foreach ($games as &$game) {
                $nextData = [];
                $match = array_values($game);
                foreach ($match as $key => $field) {
                    $nextData[$hdr[$key]] = $match[$key];
                }

                //ensure empty fields default to correct type
                foreach ($nextData as $key => $value) {
                    if (is_null($value)) {
                        switch ($key) {
                            case 'date':
                                $value = date('Y-m-d');
                                break;
                            case 'time':
                                $value = "00:00";
                                break;
                            case 'medalRound':
                                $value = 0;
                                break;
                            default:
                                $value = '';
                        }
                    }
                    $typedData[$key] = $value;
                }

                if (!empty($typedData['projectKey'])) {

                    $isGame = $this->getGameByKeyAndNumber($typedData['projectKey'], $typedData['game_number']);

                    if (empty($isGame)) {
                        $result = $this->insertGame($typedData);
                    } else {
                        $result = $this->updateGame($typedData);
                    }

                    $changes['adds'] += $result['adds'];
                    $changes['updates'] += $result['updates'];
                } else {
                    $changes['errors'][] = 'Missing projectKey, unable to add game';
                }
            }
        }

        return $changes;

    }

    /**
     * @param $projectKey
     * @param $game_number
     * @return null|object
     */
    public function getGameByKeyAndNumber($projectKey, $game_number)
    {
        $game = $this->db->table('games')
            ->where([
                ['projectKey', '=', $projectKey],
                ['game_number', '=', $game_number]
            ])
            ->get();

        return $this->getZero($game);
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getGame($id)
    {
        $game = $this->db->table('games')
            ->where('id', '=', $id)
            ->get();

        return $this->getZero($game);
    }

    /**
     * @param $data
     * @return array|null
     */
    public function updateGame($data)
    {
        if (empty($data)) {
            return null;
        }

        $changes = array('adds' => 0, 'updates' => 0);

        $key = $data['projectKey'];
        $num = $data['game_number'];

        $game = $this->getGameByKeyAndNumber($key, $num);

        //doing update by projectKey & game number; including id caused integrity error
        unset($game->id);
        unset($data['id']);

        $data['time'] = date('H:i:s', strtotime($data['time']));

        $dif = array_diff_assoc($data, (array)$game);

        if (!empty($dif)) {
            $this->db->table('games')
                ->where([
                    ['projectKey', $key],
                    ['game_number', $num]
                ])
                ->update($data);

            $changes['updates']++;
        }

        return $changes;
    }

    /**
     * @param $data
     * @return array|null
     */
    public function insertGame($data)
    {
        if (empty($data)) {
            return null;
        }

        $changes = array('adds' => 0, 'updates' => 0);

        $id = isset($data['id']) ? $data['id'] : null;

        $game = $this->getGame($id);

        if (is_null($game)) {
            $this->db->table('games')
                ->insert($data);
            $changes['adds']++;
        } else {
            $changes = $this->updateGame($data);
        }

        return $changes;
    }

    /**
     * @param $projectKey
     * @return \Illuminate\Support\Collection
     */
    public function getGameCounts($projectKey)
    {
        return $this->db->table('games')
            ->selectRaw('assignor, date, division, COUNT(division) as game_count')
            ->where('projectKey', $projectKey)
            ->groupBy(['division', 'assignor', 'date'])
            ->get();
    }

    /**
     * @param $projectKey
     * @return \Illuminate\Support\Collection
     */
    public function getDatesDivisions($projectKey)
    {
        $result = $this->db->table('games')
            ->selectRaw('DISTINCT assignor, date, division')
            ->where('projectKey', $projectKey)
            ->orderBy('date', 'asc')
            ->orderBy('division', 'asc')
            ->orderBy('assignor', 'asc')
            ->get();

        return $result;
    }

    /**
     * @param $projectKey
     * @return \Illuminate\Support\Collection
     */
    public function getDivisions($projectKey)
    {
        $result = $this->db->table('games')
            ->selectRaw('SUBSTR(division,LOCATE(\'U\',division),3) as uDiv')
            ->distinct()
            ->where('projectKey', $projectKey)
            ->orderBy('division', 'asc')
            ->get();

        return $result;
    }

    /**
     * @param $projectKey
     * @return \Illuminate\Support\Collection
     */
    public function getDates($projectKey)
    {
        $result = $this->db->table('games')
            ->select('date')
            ->distinct()
            ->where('projectKey', $projectKey)
            ->orderBy('date', 'asc')
            ->get();

        return $result;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    static function firstLastSort($a, $b)
    {
        $a = (object)$a;
        $b = (object)$b;

        if ($a == $b) {
            return 0;
        }

        $A = explode(' ', $a->name);
        $B = explode(' ', $b->name);

        $lastA = isset($A[1]) ? $A[1] : '';
        $lastB = isset($B[1]) ? $B[1] : '';

        return ($lastA < $lastB) ? -1 : 1;
    }

    /**
     * @param array $result
     * @return array
     */
    private function aggregateRefereeAssignments(array $result)
    {

        $refList = [];
        $div = null;
        foreach ($result as $ref) {
            $arrRef = (array)$ref;
            foreach ($arrRef as $hdr => $val) {
                switch ($hdr) {
                    case 'name':
                        if (!isset($refList[$ref->name])) {
                            $refList[$ref->name] = [];
                        }
                        break;
                    case 'assignor':
                        if (!isset($refList[$ref->name][$val])) {
                            $refList[$ref->name][ucwords($hdr)] = $val;
                        }
                        break;
                    case 'date':
                        if (!isset($refList[$ref->name][$val])) {
                            $refList[$ref->name][$val] = [];
                            $refList[$ref->name][$val] = 0;
                        }
                        if ($val) {
                            $refList[$ref->name][$val] += 1;
                        }
                        break;
                    case 'division':
                        $u = stripos($val, "U");
                        $div = substr($val, $u-2, 3);
                        if (!isset($refList[$ref->name][$div])) {
                            $refList[$ref->name][$div] = 0;
                        }
                        $refList[$ref->name][$div] += 1;
                        break;
                    case 'crCount':
                        if (!isset($refList[$ref->name]['ref'])) {
                            $refList[$ref->name]['ref'] = [];
                            $refList[$ref->name]['ref'] = 0;
                        }
                        if ($val) {
                            $refList[$ref->name]['ref'] += 1;
                        }
                        break;
                    case 'ar1Count':
                    case 'ar2Count':
                        if (!isset($refList[$ref->name]['ar'])) {
                            $refList[$ref->name]['ar'] = [];
                            $refList[$ref->name]['ar'] = 0;
                        }
                        if ($val) {
                            $refList[$ref->name]['ar'] += 1;
                        }
                        break;
                    case
                    'r4th':
                        if (!isset($refList[$ref->name]['4th'])) {
                            $refList[$ref->name]['4th'] = [];
                            $refList[$ref->name]['4th'] = 0;
                        }
                        if ($val) {
                            $refList[$ref->name]['4th'] += 1;
                        }
                }
            }
            $refList[$ref->name]['all'] = $refList[$ref->name]['ref'];
            $refList[$ref->name]['all'] += $refList[$ref->name]['ar'];
            if (isset($refList[$ref->name]['4th'])) {
                $refList[$ref->name]['all'] += $refList[$ref->name]['4th'];
            }
        }

        $refsList = [];
        foreach ($refList as $name => $data) {
            $ref = ['name' => $name];

            foreach ($data as $k => $item) {
                $ref[$k] = $item;
            }
            $refsList[] = $ref;
        }

        return $refsList;
    }

    /**
     * @param array $refsList
     * @param $projectKey
     * @return array
     */
    private function sortRefereeAssignments(array $refsList, $projectKey)
    {
        usort($refsList, array($this, "firstLastSort"));

        $emptySortList = ['name' => '', 'Assignor' => '', 'all' => 0, 'ref' => 0, 'ar' => 0];
        if( $this->numberOfReferees($projectKey) > 3) {
            $emptySortList['4th'] = 0;
        };

        $result = $this->getDates($projectKey);
        $arrDates = $result->all();
        foreach ($arrDates as $date) {
            $emptySortList[$date->date] = 0;
        }

        $result = $this->getDivisions($projectKey);
        $arrDivs = $result->all();

        foreach ($arrDivs as $div) {
            $emptySortList[$div->uDiv] = 0;
        }

        $sortedRefsList = [];
        foreach (array_values($refsList) as $item) {
            $sortedList = $emptySortList;
            foreach ($item as $k => $v) {
                $sortedList[$k] = $v;
            }
            $sortedRefsList[] = (object)$sortedList;
        }

        return $sortedRefsList;
    }

    /**
     * @param $projectKey
     * @return array
     */
    public function refereeAssignmentMap($projectKey)
    {
        $has4th = $this->numberOfReferees($projectKey) > 3;

        $select4th = $has4th ? ', 0 as r4th' : '';

        $cr = $this->db->table('games')
            ->selectRaw('cr as name, assignor, date, time, division, COUNT(cr) as crCount, 0 as ar1Count, 0 as ar2Count ' . $select4th)
            ->where([
                ['projectKey', $projectKey],
                ['cr', '<>', '']
            ])
            ->groupBy(['cr', 'date', 'division']);

        $ar1 = $this->db->table('games')
            ->selectRaw('ar1 as name, assignor, date, time, division, 0 as crCount, COUNT(ar1) as ar1Count, 0 as ar2Count ' . $select4th)
            ->where([
                ['projectKey', $projectKey],
                ['ar1', '<>', '']
            ])
            ->groupBy(['ar1', 'date', 'division']);

        $ar2 = $this->db->table('games')
            ->selectRaw('ar2 as name, assignor, date, time, division, 0 as crCount, 0 as ar1Count, COUNT(ar2) as ar2Count ' . $select4th)
            ->where([
                ['projectKey', $projectKey],
                ['ar2', '<>', '']
            ])
            ->groupBy(['ar2', 'date', 'division']);

        $refs = $cr
            ->union($ar1)
            ->union($ar2);

        if ($has4th) {
            $r4th = $this->db->table('games')
                ->selectRaw('r4th as name, assignor, date, time, division, 0 as crCount, 0 as ar1Count, 0 as ar2Count, COUNT(r4th) as r4thCount')
                ->where([
                    ['projectKey', $projectKey],
                    ['r4th', '<>', '']
                ])
                ->groupBy(['r4th', 'date', 'division']);

            $refs = $refs
                ->union($r4th);
        }

        $result = $refs
            ->orderBy('name', 'asc')
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->orderBy('division', 'asc')
            ->get();
        $arrResult = $result->all();

        $refsList = $this->aggregateRefereeAssignments($arrResult);

        $sortedRefsList = $this->sortRefereeAssignments($refsList, $projectKey);

        return $sortedRefsList;

    }

//Limits table functions
    /**
     * @param $projectKey
     * @return \Illuminate\Support\Collection
     */
    public function getLimits($projectKey)
    {
        $limits = $this->db->table('limits')
            ->where('projectKey', '=', $projectKey)
            ->get();

        return $limits;
    }

//Log writer
    /**
     * @param $projectKey
     * @param $msg
     * @return null
     */
    public function logInfo($projectKey, $msg)
    {
        $data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'projectKey' => $projectKey,
            'note' => $msg
        ];

        $this->db->table('log')
            ->insert($data);

        return null;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAccessLog()
    {
        return $this->db->table('log')
            ->get();
    }

    /**
     *
     */
    public function showVariables()
    {
        return $this->db->getConnection();
    }

//Log reader


    /**
     * @param $projectKey
     * @param $userName
     * @return null|string
     */
    public function getLastLogon($projectKey, $userName)
    {
        $timestamp = null;

        $ts = $this->db->table('log')
            ->where([
                ['projectKey', 'like', $projectKey],
                ['note', 'like', "$userName: Scheduler sched%"]
            ])
            ->orderBy('timestamp', 'desc')
            ->limit(1)
            ->get();

        $ts = $this->getZero($ts);

        if (!empty($ts)){
            $utc = new DateTime($ts->timestamp, new DateTimeZone('UTC'));
            $time = $utc->setTimezone(new DateTimeZone('America/Los_Angeles'));
            $timestamp = $time->format('Y-M-j H:i');
        }

        return $timestamp;
    }

//    SAR function

    /**
     * @param $portal
     * @return null|object
     */
    public function getSARRec($portal)
    {
        $sarRec = $this->db->table('sar')
            ->where('portalName', '=', $portal)
            ->get();

        return $this->getZero($sarRec);
    }
}
