<?php
namespace App\Action;

use App\Action\SchedulerRepository;

class SessionManager
{
    protected $sr;

    public function __construct(SchedulerRepository $scheduleRepository)
    {
        $this->sr = $scheduleRepository;
    }
    public function loadSessionToken($request)
    {
        $GLOBALS['user'] = null;
        $GLOBALS['event'] = null;
        $GLOBALS['authed'] = false;

        $sessionKey = json_decode($request->getCookieParam('sessionKey'));
        if(!is_null($sessionKey)){
            if(!is_null($sessionKey->user)) {
                $user = $this->sr->getUserByName($sessionKey->user);
                if (!is_null($user)) {
                    if ($user->active) {
                        setSessionGlobals($sessionKey->user, $sessionKey->event);
                    }
                }
            }
        }
    }
    public function setSessionGlobals($user, $event)
    {
        $GLOBALS['event'] = $event;
        $GLOBALS['user'] = $user->name;

        $GLOBALS['authed'] = $this->sr->setUserActive($user->id, $event->id);
    }
    public function clearGlobals($user)
    {
        $this->sr->setUserActive($user->id, null, false);
        $GLOBALS['event'] = null;
        $GLOBALS['user'] = null;

        $GLOBALS['authed'] = false;
    }

}