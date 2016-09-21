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
    public function getUsers()
    {
        return $this->db->table('users')->where('enabled', 'like', 1)->get();
    }
    public function getUserByPW($pw)
	{
		return $this->db->table('users')->where('password', 'like', $pw);
	}
	public function getEvents()
    {
        return $this->db->table('events')->where('enabled', 'like', 1)->get();
    }
    public function getEnabled()
    {
        return $this->db->table('events')->where('enabled', 'like', 1)->get();
    }
    public function getEvent($key)
    {
        return $this->db->table('events')->where('eventKey', '=', $key)->get()[0];
    }
	public function getEventKey($name)
	{
		$event = $this->getEvent($name);
		
		return $event->eventKey;
	}
	public function getLocked($projectKey)
	{
		return $this->db->table('events')->where('projectKey', '=', $projectKey)->get()[0]->locked;
	}
	public function getGames($projectKey, $group='%')
	{
		$group .= '%';

		return $this->db->table('games')->where([
			['projectKey', '=', $projectKey],
			['division', 'like', $group],
		])->get();
	}
	public function getLimits($projectKey)
	{
		return $this->db->table('limits')->where('projectKey', '=', $projectKey)->get();
	}
	public function getGroups($projectKey)
	{
		$groups = $this->db->table('games')->where('projectKey', $projectKey)->select('division')->distinct()->get();
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
	public function updateAssignor($data)
	{
		if (empty($data)){
			exit;
		}
		
		foreach($data as $key=>$value){
			if ($key != 'Submit'){
				$this->db->table('games')
					->where('id', $key)
					->update(['assignor' => $value]);							
			}
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
	public function updateAssignments($data)
	{
		if (empty($data)){
			exit;
		}

		foreach($data as $key=>$value){
			if ($value == 'Update Assignments'){
				$game_id = $key;

				$this->db->table('games')
					->where('id', $game_id)
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
		$gameNo = $this->db->table('games')->select('game_number')->where('id', '=', $id)->get()[0]->game_number;
		
		return $gameNo;
	}
	public function numberOfReferees($projectKey)
	{
		$numRefs = $this->db->table('events')->select('num_refs')->where('projectKey', '=', $projectKey)->get()[0]->num_refs;
		
		return $numRefs;
	}
}
