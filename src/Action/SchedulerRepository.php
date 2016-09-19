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
        return $this->users = $this->db->table('users')->get();
    }
    public function getEvents()
    {
        return $this->events = $this->db->table('events')->where('enabled', 'like', 1)->get();
    }
    public function getSelect()
    {
        return $this->events = $this->db->table('events')->where('select', 'like', 1)->get();
    }
}
