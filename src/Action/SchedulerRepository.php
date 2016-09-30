<?php
namespace App\Action;

use Illuminate\Database\Capsule\Manager;

class SchedulerRepository
{
    private $db;

    public function __construct(Manager $db)
    {
        $this->db = $db;
		        
    }
	private function getZero($elem)
	{
		return isset($elem[0]) ? $elem[0] : null;
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
    public function getUserByPW($pw)
	{
		if(empty($pw)) {
			return null;
		}
		
		$user = $this->db->table('users')
			->where('password', 'like', $pw)
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
				'password' => $user['password'],
				'hash' => $user['hash'],
			);

			$this->db->table('users')
				->create([$newUser]);
			
		}
		else {
			$pw = $user['password'];
			$hash = $user['hash'];
			
			$this->db->table('users')
				->where('id', $u->id)
				->update([
					'password' => $pw,
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
			->where('eventKey', '=', $projectKey)
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
	public function getGames($projectKey, $group='%')
	{
		$group .= '%';

		return $this->db->table('games')
			->where([
				['projectKey', '=', $projectKey],
				['division', 'like', $group],
			])
			->get();
	}
	public function getGamesByRep($projectKey, $rep)
	{
		return $this->db->table('games')
			->where([
				['projectKey', '=', $projectKey],
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
			exit;
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
			exit;
		}

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
	//Limits table functions
	public function getLimits($projectKey)
	{
		return $this->db->table('limits')
			->where('projectKey', '=', $projectKey)
			->get();
	}
}
