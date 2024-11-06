<?php

namespace App\Action;

use Exception;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
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
    private Manager $db;

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
    private function getZero($elem): ?object
    {
        return isset($elem[0]) ? (object)$elem[0] : null;
    }

    //User table functions

    /**
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return $this->db->table('users')
            ->get();
    }

    /**
     * @param null $key
     * @return Collection
     */
    public function getUsers($key = null): Collection
    {
        return $this->db->table('users')
            ->where(
                [
                    ['enabled', true],
                    ['for_events', 'like', "%$key%"],
                ]
            )
            ->get();
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
            ->update(
                [
                    'for_events' => $forEvents,
                ]
            );

        return null;
    }

    /**
     * @param $hash
     * @return null|object
     */
    public function getUserByPW($hash): ?object
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
    public function getUserByName($name): ?object
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
                ->update(
                    [
                        'hash' => $hash,
                    ]
                );

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
     * @return Collection
     */
    public function getCurrentEvents(): Collection
    {
        return $this->db->table('events')
            ->where('view', true)
            ->orderBy('start_date')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getAllEvents(): Collection
    {
        return $this->db->table('events')
            ->orderBy('start_date')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getEnabledEvents(): Collection
    {
        return $this->db->table('events')
            ->where('enabled', true)
            ->orderBy('start_date')
            ->get();
    }

    /**
     * @param $projectKey
     * @param bool $asCollection
     * @return null|object
     */
    public function getEvent($projectKey, bool $asCollection = false): ?object
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
    public function getEventByLabel($label): ?object
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
    public function getLocked($projectKey): ?bool
    {
        $status = $this->db->table('events')
            ->where('projectKey', '=', $projectKey)
            ->get();

        $status = $this->getZero($status);
        if (!is_null($status)) {
            return $status->locked;
        } else {
            return true;
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
     * @param $key
     * @return null
     */
    public function showMedalRoundDivisions($key)
    {
        $this->db->table('events')
            ->where('projectKey', $key)
            ->update(['show_medal_round_details' => true]);

        return null;
    }

    /**
     * @param $key
     * @return null
     */
    public function hideMedalRoundDivisions($key)
    {
        $this->db->table('events')
            ->where('projectKey', $key)
            ->update(['show_medal_round_details' => false]);

        return null;
    }

    /**
     * @param $key
     * @return null
     */
    public function showMedalRoundAssignments($key)
    {
        $this->db->table('events')
            ->where('projectKey', $key)
            ->update(['medal_round_assignments' => true]);

        return null;
    }

    /**
     * @param $key
     * @return null
     */
    public function hideMedalRoundAssignments($key)
    {
        $this->db->table('events')
            ->where('projectKey', $key)
            ->update(['medal_round_assignments' => false]);

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
     * @param $projectKey
     * @return mixed
     */
    public function getMedalRoundDivisions($projectKey)
    {
        $status = $this->db->table('events')
            ->where('projectKey', '=', $projectKey)
            ->get();

        $status = $this->getZero($status);
        if (!is_null($status)) {
            return $status->show_medal_round_details;
        } else {
            return null;
        }
    }

    /**
     * @param $projectKey
     * @return mixed
     */
    public function getMedalRoundAssignedNames($projectKey)
    {
        $status = $this->db->table('events')
            ->where('projectKey', '=', $projectKey)
            ->get();

        $status = $this->getZero($status);
        if (!is_null($status)) {
            return $status->medal_round_assignments;
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

    //Matches table functions

    /**
     * @param string $projectKey
     * @param string $group
     * @param bool $medalRound
     * @param string $sortOn
     * @return Collection
     */
    public function getGames(string $projectKey = '%', string $group = '%', bool $medalRound = false, string $sortOn = 'game_number'): Collection
    {

        $group = '%' . $group . '%';
        $medalRound = $medalRound ? '%' : false;
        $poolASC = "LENGTH(`pool`), `pool`, FIELD(`pool`, 'SF', 'CON', 'FIN') ASC";
        $fieldASC = "LENGTH(`field`), `field`, FIELD(`field`) ASC";
//        $poolDESC = "LENGTH(`pool`), `pool`, FIELD(`pool`, 'SF', 'CON', 'FIN') DESC";
        $field = "ExtractNumber(`field`)";
//        $homeASC = "CAST(`home` as unsigned) ASC";
//        $awayASC = "CAST(`away` as unsigned) ASC";

        $query = $this->db->table('games')
            ->where(
                [
                    ['projectKey', 'like', $projectKey],
                    ['division', 'like', $group],
                    ['medalRound', 'like', $medalRound],
                ]
            );

        switch ($sortOn) {
            case 'field':
                $query = $query
                    ->orderBy('date')
                    ->orderByRaw($field)
                    ->orderBy('time');
                break;
            case 'pool' :
                $query = $query
                    ->orderBy('date')
                    ->orderByRaw($poolASC)
                    ->orderBy('time')
                    ->orderByRaw($field);
                break;
            case 'home' :
                $query = $query
                    ->orderBy('date')
                    ->orderBy('home')
                    ->orderBy('division')
                    ->orderBy('time')
                    ->orderByRaw($field);
                break;
            case 'away' :
                $query = $query
                    ->orderBy('date')
                    ->orderBy('away')
                    ->orderBy('division')
                    ->orderBy('time')
                    ->orderByRaw($field);
                break;
            case 'assignor' :
                $query = $query
                    ->orderBy('date')
                    ->orderBy('assignor')
                    ->orderBy('field')
                    ->orderBy('time')
                    ->orderByRaw($field);
            default:
                $query = $query
                    ->orderBy($sortOn)
                    ->orderBy('date')
                    ->orderBy('time')
                    ->orderByRaw($poolASC)
                    ->orderByRaw($field);
        }

        try {
            $games = $query->get();
        } catch (Exception $e) {
            echo($e->getMessage());
            die();
        }
        return $games;

    }

    /**
     * @param string $projectKey
     * @param string $group
     * @param bool $medalRound
     * @return Collection
     */
    public function getUnassignedGames(string $projectKey = '%', string $group = '%', bool $medalRound = false): Collection
    {
        $group .= '%';
        $medalRound = $medalRound ? '%' : false;

        return $this->db->table('games')
            ->where(
                [
                    ['projectKey', 'like', $projectKey],
                    ['division', 'like', $group],
                    ['medalRound', 'like', $medalRound],
                    ['assignor', 'like', ''],
                ]
            )
            ->get();
    }

    /**
     * @param $projectKey
     * @param $rep
     * @param bool $medalRound
     * @return Collection
     */
    public function getGamesByRep($projectKey, $rep, bool $medalRound = false): Collection
    {
        $medalRound = $medalRound ? '%' : false;

        return $this->db->table('games')
            ->where(
                [
                    ['projectKey', '=', $projectKey],
                    ['medalRound', 'like', $medalRound],
                    ['assignor', '=', $rep],
                ]
            )
            ->get();
    }

    /**
     * @param $projectKey
     * @return array
     */
    public function getGroups($projectKey): array
    {
        $groups = $this->db->table('games')
            ->where('projectKey', $projectKey)
            ->select('division')
            ->distinct()
            ->get();

        $result = [];

        foreach ($groups as $group) {
            $result[] = $group->division;
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
            ->where(
                [
                    ['assignor', $rep],
                    ['projectKey', '=', $projectKey],
                ]
            )
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

        $data['r4th'] = $data['r4th'] ?? null;
        foreach ($data as $id => $value) {
            if (in_array($value, ['Update Assignments', 'Clear All'])) {
                $this->db->table('games')
                    ->where('id', $id)
                    ->update(
                        [
                            'cr' => trim($data['cr']),
                            'ar1' => trim($data['ar1']),
                            'ar2' => trim($data['ar2']),
                            'r4th' => trim($data['r4th']),
                        ]
                    );
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
    public function getGamesHeader(): ?array
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
    public function getNextGameId(): int
    {
        $id = 0;

        $games = $this->db->table('games')
            ->get();

        foreach ($games as $game) {
            $id = $game->id;
        }

        $id++;

        return $id;
    }

    /**
     * @param $data
     * @return array|null
     */
    public function modifyGames($data): ?array
    {
        if (is_null($data)) {
            return null;
        }

        $hdr = array_values($data['hdr']);
        $games = $data['data'];

        $changes = array('adds' => 0, 'updates' => 0, 'errors' => []);

        if (!empty($games)) {
            foreach ($games as $game) {
                $nextData = [];
                $match = array_values($game);
                foreach ($match as $key => $field) {
                    $nextData[$hdr[$key]] = $field;
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
                    $changes['errors'][] = 'Missing projectKey, unable to add match';
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
    public function getGameByKeyAndNumber($projectKey, $game_number): ?object
    {
        $game = $this->db->table('games')
            ->where(
                [
                    ['projectKey', '=', $projectKey],
                    ['game_number', '=', $game_number],
                ]
            )
            ->get();

        return $this->getZero($game);
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getGame($id): ?object
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
    public function updateGame($data): ?array
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

        if (isset($data['time'])) {
            $data['time'] = date('H:i:s', strtotime($data['time']));
        }

        $dif = array_diff_assoc($data, (array)$game);

        if (!empty($dif)) {
            $this->db->table('games')
                ->where(
                    [
                        ['projectKey', $key],
                        ['game_number', $num],
                    ]
                )
                ->update($data);

            $changes['updates']++;
        }

        return $changes;
    }

    /**
     * @param $data
     * @return array|null
     */
    public function insertGame($data): ?array
    {
        if (empty($data)) {
            return null;
        }

        $changes = array('adds' => 0, 'updates' => 0);

        $id = $data['id'] ?? null;

        if (empty($id)) {
            array_shift($data);
        }

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
     * @return Collection
     */
    public function getGameCounts($projectKey): Collection
    {
        return $this->db->table('games')
            ->selectRaw('assignor, date, division, COUNT(division) as game_count')
            ->where('projectKey', $projectKey)
            ->groupBy(['division', 'assignor', 'date'])
            ->get();
    }

    /**
     * @param $projectKey
     * @return Collection
     */
    public function getDatesDivisions($projectKey): Collection
    {
        return $this->db->table('games')
            ->selectRaw('DISTINCT assignor, date, division')
            ->where('projectKey', $projectKey)
            ->orderBy('date')
            ->orderBy('division')
            ->orderBy('assignor')
            ->get();
    }

    /**
     * @param $projectKey
     * @return Collection
     */
    public function getDivisions($projectKey): Collection
    {
        return $this->db->table('games')
            ->selectRaw('division as uDiv')
            ->distinct()
            ->where('projectKey', $projectKey)
            ->orderBy('division')
            ->get();
    }

    /**
     * @param $projectKey
     * @return Collection
     */
    public function getDates($projectKey): Collection
    {
        return $this->db->table('games')
            ->select('date')
            ->distinct()
            ->where('projectKey', $projectKey)
            ->orderBy('date')
            ->get();
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    static function firstLastSort($a, $b): int
    {
        $a = (object)$a;
        $b = (object)$b;

        if ($a == $b) {
            return 0;
        }

        $A = explode(' ', $a->name);
        $B = explode(' ', $b->name);

        $lastA = $A[1] ?? '';
        $lastB = $B[1] ?? '';

        return ($lastA < $lastB) ? -1 : 1;
    }

    /**
     * @param array $result
     * @return array
     */
    private function aggregateRefereeAssignments(array $result): array
    {

        $refList = [];

        foreach ($result as $ref) {
            $arrRef = (array)$ref;
            foreach ($arrRef as $hdr => $val) {
                $gameCount = $ref->crCount + $ref->ar1Count + $ref->ar2Count;
                if (isset($ref->r4thCount)) {
                    $gameCount += $ref->r4thCount;
                }
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
                            $refList[$ref->name][$val] = 0;
                        }
                        if ($val) {
                            $refList[$ref->name][$val] += $gameCount;
                        }
                        break;
                    case 'division':
                        $div = $val;
                        if (!isset($refList[$ref->name][$div])) {
                            $refList[$ref->name][$div] = 0;
                        }
                        $refList[$ref->name][$div] += $gameCount;
                        break;
                    case 'crCount':
                        if (!isset($refList[$ref->name]['ref'])) {
                            $refList[$ref->name]['ref'] = 0;
                        }
                        if ($val) {
                            $refList[$ref->name]['ref'] += $val;
                        }
                        break;
                    case 'ar1Count':
                    case 'ar2Count':
                        if (!isset($refList[$ref->name]['ar'])) {
                            $refList[$ref->name]['ar'] = 0;
                        }
                        if ($val) {
                            $refList[$ref->name]['ar'] += $val;
                        }
                        break;
                    case
                    'r4thCount':
                        if (!isset($refList[$ref->name]['4th'])) {
                            $refList[$ref->name]['4th'] = 0;
                        }
                        if ($val) {
                            $refList[$ref->name]['4th'] += $val;
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
    private function sortRefereeAssignments(array $refsList, $projectKey): array
    {
        usort($refsList, array($this, "firstLastSort"));

        $emptySortList = ['name' => '', 'Assignor' => '', 'all' => 0, 'ref' => 0, 'ar' => 0];
        if ($this->numberOfReferees($projectKey) > 3) {
            $emptySortList['4th'] = 0;
        }

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

        foreach ($refsList as $item) {
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
    public function refereeAssignmentMap($projectKey): array
    {
        $has4th = $this->numberOfReferees($projectKey) > 3;

        $select4th = $has4th ? ', 0 as r4th' : '';
        $db = $this->db->getDatabaseManager();

        $cr = $db->select('call rs_crAssignmentMap(?,?)', [$projectKey, $select4th]);

        $ar1 = $db->select('call rs_ar1AssignmentMap(?,?)', [$projectKey, $select4th]);

        $ar2 = $db->select('call rs_ar2AssignmentMap(?,?)', [$projectKey, $select4th]);

        $refs = array_merge($cr, $ar1, $ar2);

        if ($has4th) {
            $r4th = $db->select('call rs_r4thAssignmentMap(?,?)', [$projectKey, $select4th]);

            $refs = array_merge($refs, $r4th);
        }

        $arrResult = $refs;

        $refsList = $this->aggregateRefereeAssignments($arrResult);

        return $this->sortRefereeAssignments($refsList, $projectKey);

    }

//Limits table functions

    /**
     * @param $projectKey
     * @return Collection
     */
    public function getLimits($projectKey): Collection
    {
        return $this->db->table('limits')
            ->where('projectKey', '=', $projectKey)
            ->get();
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
            'note' => $msg,
        ];

        $this->db->table('log')
            ->insert($data);

        return null;
    }

    /**
     * @return Collection
     */
    public function getAccessLog(): Collection
    {
        return $this->db->table('log')
            ->get();
    }

    /**
     *
     */
    public function showVariables(): Connection
    {
        return $this->db->getConnection();
    }

//Log reader


    /**
     * @param $projectKey
     * @param $userName
     * @return string|null
     * @throws Exception
     */
    public function getLastLogon($projectKey, $userName): ?string
    {
        $timestamp = null;

        $ts = $this->db->table('log')
            ->where(
                [
                    ['projectKey', 'like', $projectKey],
                    ['note', 'like', "$userName: Scheduler greet%"],
                ]
            )
            ->orderBy('timestamp', 'desc')
            ->limit(1)
            ->get();

        $ts = $this->getZero($ts);

        if (!empty($ts)) {
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
    public function getSARRec($portal): ?object
    {
        $sarRec = $this->db->table('sar')
            ->where('portalName', '=', $portal)
            ->get();

        return $this->getZero($sarRec);
    }

    /**
     * @param $name
     * @return array|mixed
     */
    public function getPersonInfo($name)
    {
        if ($name == 'Forfeit') {
            return array();
        }

        $Refs = $this->createArray(
            $this->db->table('refs')
                ->select('refs.*', 'refNicknames.Nickname')
                ->join('refNicknames', 'refNicknames.AdminID', '=', 'refs.AdminID')
                ->where('Nickname', 'like', "$name")
                ->orderBy('MY','desc')
                ->get()
        );

//        $RefsNoCerts = $this->createArray(
//            $this->db->table('ref_no_certs')
//                ->select('ref_no_certs.*', 'refNicknames.Nickname')
//                ->join('refNicknames', 'refNicknames.AdminID', '=', 'ref_no_certs.AdminID')
//                ->where('Nickname', 'like', "$name")
//                ->get()
//        );
        $RefsNoCerts = array();

        $personRec = array_merge($Refs, $RefsNoCerts);

        if (count($personRec)) {
            return $this->createArray($personRec);
        }

        return array();
    }

    /**
     * @param $obj
     * @return mixed
     */
    protected function createArray($obj)
    {
        return json_decode(json_encode($obj), true);
    }

    /**
     * @string $ids
     * @return Collection
     */
    public function getCertsByID(string $ids): Collection
    {
        $ids = explode(',', $ids);
        $certs = $this->db->table('refs')
            ->select('*')
            ->whereIn('AdminID', $ids)
            ->orderBy('AdminID')
            ->orderBy( 'MY', 'ASC')
            ->get();

        return $certs;
    }

    /**
     * @param array $data
     * @return null
     */
//    public function modifyEvents(array $data)
//    {
//        $events = $data['data'];
//
//        $changes = array('adds' => 0, 'updates' => 0, 'errors' => []);
////TODO: update for event records update
//        if (!empty($events)) {
//            foreach ($events['data'] as &$event) {
//                $nextData = [];
//                $match = array_values($event);
//                foreach ($match as $key => $field) {
//                    $nextData[$hdr[$key]] = $match[$key];
//                }
//
////ensure empty fields default to correct type
//                foreach ($nextData as $key => $value) {
//                    if (is_null($value)) {
//                        switch ($key) {
//                            case 'date':
//                                $value = date('Y-m-d');
//                                break;
//                            case 'time':
//                                $value = "00:00";
//                                break;
//                            case 'medalRound':
//                                $value = 0;
//                                break;
//                            default:
//                                $value = '';
//                        }
//                    }
//                    $typedData[$key] = $value;
//                }
//
//                if (!empty($typedData['projectKey'])) {
//
//                    $isGame = $this->getGameByKeyAndNumber($typedData['projectKey'], $typedData['game_number']);
//                    if (empty($isGame)) {
//                        $result = $this->insertGame($typedData);
//                    } else {
//                        $result = $this->updateGame($typedData);
//                    }
//
//                    $changes['adds'] += $result['adds'];
//                    $changes['updates'] += $result['updates'];
//                } else {
//                    $changes['errors'][] = 'Missing projectKey, unable to add match';
//                }
//            }
//        }
//
//        return $changes;
//
//    }

    /**
     * @param $data
     * @return array|null
     */
//    public function updateEvent($data)
//    {
//        if (empty($data)) {
//            return null;
//        }
//
//        $changes = array('adds' => 0, 'updates' => 0);
//
//        $key = $data['projectKey'];
//        $num = $data['game_number'];
//
//        $game = $this->getGameByKeyAndNumber($key, $num);
//
//        //doing update by projectKey & game number; including id caused integrity error
//        unset($game->id);
//        unset($data['id']);
//
//        if (isset($data['time'])) {
//            $data['time'] = date('H:i:s', strtotime($data['time']));
//        }
//
//        $dif = array_diff_assoc($data, (array)$game);
//
//        if (!empty($dif)) {
//            $this->db->table('games')
//                ->where(
//                    [
//                        ['projectKey', $key],
//                        ['game_number', $num],
//                    ]
//                )
//                ->update($data);
//
//            $changes['updates']++;
//        }
//
//        return $changes;
//    }


}
