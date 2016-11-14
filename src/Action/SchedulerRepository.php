<?php
namespace App\Action;

use Illuminate\Database\Capsule\Manager;

class SchedulerRepository
{
    /* @var Manager */
    private $db;

    public function __construct(Manager $db)
    {
        $this->db = $db;
		        
    }
	private function getZero($elem)
	{
		return isset($elem[0]) ? (object) $elem[0] : null;
	}
	//User table functions
    public function getAllUsers()
    {
        return $this->db->table('users')
			->get();
    }
    public function getUsers()
    {
        return $this->db->table('users')
			->where('enabled', true)
			->get();
    }
    public function getUserByPW($hash)
	{
		if(empty($hash)) {
			return null;
		}
		
		$user = $this->db->table('users')
			->where('hash', 'like', $hash)
			->get();
		
		return $this->getZero($user);

	}
    public function getUserByName($name)
	{
		if(empty($name)) {
			return null;
		}

		$user = $this->db->table('users')
			->where('name', 'like', $name)
			->get();

        return $this->getZero($user);

	}
	public function setUser($user)
	{
		if(empty($user)) {
			return null;
		}
		
		$u = $this->getUserByName($user['name']);
		if (empty($u)){
			$newUser = array (
				'name' => $user['name'],
				'enabled' => $user['enabled'],
				'hash' => $user['hash'],
			);

			$this->db->table('users')
                ->insert([$newUser]);
			
		}
		else {
			$hash = $user['hash'];
			
			$this->db->table('users')
				->where('id', $u->id)
				->update([
					'hash' => $hash,
				]);	
		}
	}
	//Events table functions
	public function getCurrentEvents()
    {
        return $this->db->table('events')
			->where('view', true)
			->orderBy('start_date', 'asc')
			->get();
    }
    public function getEnabledEvents()
    {
        return $this->db->table('events')
			->where('enabled', true)
			->orderBy('start_date', 'asc')
			->get();
    }
    public function getEvent($projectKey)
    {
		if(empty($projectKey)) {
			return null;
		}
		
        $event = $this->db->table('events')
			->where('projectKey', '=', $projectKey)
			->get();

		return $this->getZero($event);
    }
    public function getEventByLabel($label)
    {
        $event = $this->db->table('events')
			->where('label', '=', $label)
			->get();

		return $this->getZero($event);
    }
	public function getEventLabel($projectKey)
	{
		$event = $this->getEvent($projectKey);
		
		return $event->label;
	}
	public function getLocked($projectKey)
	{
		$status = $this->db->table('events')
			->where('projectKey', '=', $projectKey)
			->get();
			
		$status = $this->getZero($status);
		if (!is_null($status)) {
			return $status->locked;
		}
		else {
			return null;
		}
	}
	public function numberOfReferees($projectKey)
	{
		$numRefs = $this->db->table('events')
			->select('num_refs')
			->where('projectKey', '=', $projectKey)
			->get();
		$numRefs = $this->getZero($numRefs);
		
		if (!is_null($numRefs)) {
			return $numRefs->num_refs;
		}
		else {
			return null;
		}
	}
	public function lockProject($key)
	{
		$this->db->table('events')
			->where('projectKey', $key)
			->update(['locked' => true]);
	}
	public function unlockProject($key)
	{
		$this->db->table('events')
			->where('projectKey', $key)
			->update(['locked' => false]);
	}
	//Games table functions
	public function getGames($projectKey='%', $group='%', $medalRound = false)
	{
		$group .= '%';
        $medalRound = $medalRound ? '%' : false;

		return $this->db->table('games')
			->where([
				['projectKey', 'like', $projectKey],
				['division', 'like', $group],
                ['medalRound', 'like', $medalRound],
			])
			->get();
	}
    public function getUnassignedGames($projectKey='%', $group='%', $medalRound = false)
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
	public function getGamesByRep($projectKey, $rep, $medalRound = false)
	{
		return $this->db->table('games')
			->where([
				['projectKey', '=', $projectKey],
                ['medalRound', 'like', $medalRound],
				['assignor', '=', $rep],
			])
			->get();
		
	}
	public function getGroups($projectKey)
	{
		$groups = $this->db->table('games')
			->where('projectKey', $projectKey)
			->select('division')
			->distinct()
			->get();
			
		$result = [];
		foreach($groups as $group){
			$group = substr($group->division,0,3);
			if(!in_array($group,$result)){
				$result[] = $group;
			}
		}
		asort($result);
		
		return $result;
	}
	public function clearAssignor($projectKey, $rep)
	{
		$this->db->table('games')
				->where([
					['assignor', $rep],
					['projectKey', '=', $projectKey]
				   ])
				->update(['assignor' => '']);	
	}
	public function updateAssignor($data)
	{
		if (empty($data)){
			return null;
		}

		foreach($data as $id=>$rep){
			if ($id != 'Submit'){
				$rep = $rep == 'None' ? null : $rep;
				$this->db->table('games')
					->where('id', $id)
					->update(['assignor' => $rep]);							
			}
		}
	}
	public function updateAssignments($data)
	{
		if (empty($data)){
            return null;
		}

        $data['r4th'] = isset($data['r4th']) ? $data['r4th'] : null;
		foreach($data as $id=>$value){
			if ($value == 'Update Assignments'){
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
	}
	public function gameIdToGameNumber($id)
	{
		$gameNo = $this->db->table('games')
			->select('game_number')
			->where('id', '=', $id)
			->get();
		$gameNo = $this->getZero($gameNo);
		
		if (!is_null($gameNo)) {
			return $gameNo->game_number;
		}
		else {
			return null;
		}
	}
	public function getGamesHeader()
    {
        $games = $this->getGames();
        $gameLabels = (array) $this->getZero($games);

        if (!empty($gameLabels)){

            return array_keys($gameLabels);
        }

        return null;
    }
    public function getNextGameId()
    {
        $id = 0;

        $games = $this->db->table('games')
            ->get();

        foreach ($games as $game){
            $id = $game->id;
        };

        $id++;

        return $id;
    }
    public function addGames($data)
    {
        if (is_null($data)) {
            return null;
        }

        $hdr = $data['hdr'];
        $games = $data['data'];
        $changes = array('adds'=>0, 'updates'=>0);

        //convert float to int
        foreach($games as &$game){
            foreach($game as $key=>&$value) {
                if(is_float($value)) {
                    $value = (int)$value;
                }
            }
        }
        if (!empty($games)) {
            foreach ($games as $game){
                $nextData = [];
                foreach ($hdr as $key=>$field) {
                    $nextData[$hdr[$key]] = $game[$key];
                }
                $result = $this->updateGame($nextData);

                $changes['adds'] += $result['adds'];
                $changes['updates'] += $result['updates'];
            }
        }

        return $changes;

    }
    private function getGame($id)
    {
        $game = $this->db->table('games')
            ->where('id', '=', $id)
            ->get();

        return $this->getZero($game);
    }
    private function updateGame($data)
    {
        if (empty($data)) {
            return null;
        }

        $changes = array('adds'=>0, 'updates'=>0);

        $id = isset($data['id']) ? $data['id'] : null;

        if (is_null($id)) {
            $changes = $this->insertGame($data);
        } else {
            $game = $this->getGame($id);

            if (array_diff((array)$game, $data)){
                $this->db->table('games')
                    ->where('id', $id)
                    ->update($data);
                $changes['updates']++;
            }
        }

        return $changes;
    }
    private function insertGame($data)
    {
        if (empty($data)) {
            return null;
        }

        $changes = array('adds'=>0, 'updates'=>0);

        $id = isset($data['id']) ? $data['id'] : null;

        if (is_null($id)) {
            $this->db->table('games')
                ->insert($data);
            $changes['adds']++;
        } else {
            $changes = $this->updateGame($data);
        }

        return $changes;
    }
    public function getGameCounts($projectKey)
    {
        return $this->db->table('games')
            ->selectRaw('assignor, date, division, COUNT(division) as game_count')
            ->where('projectKey', 'like', $projectKey)
            ->groupBy(['assignor', 'division'])
            ->get();
    }
    public function getDatesDivisions($projectKey)
    {
        return $this->db->table('games')
            ->selectRaw('DISTINCT assignor, date, division')
            ->where('projectKey', 'like', $projectKey)
            ->orderBy('assignor', 'asc')
            ->orderBy('date', 'asc')
            ->orderBy('division', 'asc')
            ->get();
    }

	//Limits table functions
	public function getLimits($projectKey)
	{
		return $this->db->table('limits')
			->where('projectKey', '=', $projectKey)
			->get();
	}
	//Log writer
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
    public function getAccessLog()
    {
        return $this->db->table('log')
            ->get();
    }
}
