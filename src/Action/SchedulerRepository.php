<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Capsule\Manager;

class SchedulerRepository
{
    private $db;

    public function __construct(Manager $db)
    {
        $this->db = $db;
		        
    }
	//User table functions
    public function getUsers()
    {
        return $this->db->table('users')
			->where('enabled', 'like', 1)
			->get();
    }
    public function getUserByPW($pw)
	{
		return $this->db->table('users')
			->where('password', 'like', $pw);
	}
	//Events table functions
	public function getCurrentEvents()
    {
        return $this->db->table('events')
			->where('enabled', 'like', 1)
			->get();
    }
    public function getEnabledEvents()
    {
        return $this->db->table('events')
			->where('enabled', 'like', 1)
			->get();
    }
    public function getEvent($projectKey)
    {
        return $this->db->table('events')
			->where('eventKey', '=', $key)
			->get()[0];
    }
    public function getEventByLabel($label)
    {
        return $this->db->table('events')
			->where('label', '=', $label)
			->get()[0];
    }
	public function getEventLabel($projectKey)
	{
		$event = $this->getEvent($projectKey);
		
		return $event->label;
	}
	public function getLocked($projectKey)
	{
		return $this->db->table('events')
			->where('projectKey', '=', $projectKey)
			->get()[0]
			->locked;
	}
	public function numberOfReferees($projectKey)
	{
		$numRefs = $this->db->table('events')
			->select('num_refs')
			->where('projectKey', '=', $projectKey)
			->get()[0]
			->num_refs;
		
		return $numRefs;
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
			->get()[0]
			->game_number;
		
		return $gameNo;
	}	//Limits table functions
	public function getLimits($projectKey)
	{
		return $this->db->table('limits')
			->where('projectKey', '=', $projectKey)
			->get();
	}
}
