<?php
namespace App\Action;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\SchedulerRepository;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;

class SessionManager
{
    protected $sr;

    public function __construct(SchedulerRepository $scheduleRepository)
    {
        $this->sr = $scheduleRepository;
    }
    public function getSessionToken($request)
    {
        $GLOBALS['user'] = null;
        $GLOBALS['event'] = null;
        $GLOBALS['authed'] = false;

        $sessionKey = FigRequestCookies::get($request, 'sessionKey', null);

        if(!is_null($sessionKey)){
            $user = $this->sr->getUserByName($sessionKey->getValue());

            if (!is_null($user)) {
                if ($user->active) {
                    $event = $this->sr->getEventById($user->active_event_id);
                    $this->setSessionGlobals($user, $event);
                }
            }
        }
    }
    public function setSessionToken($response)
    {
        $response = FigResponseCookies::set($response, SetCookie::create('sessionKey')
            ->withValue($GLOBALS['user'])
        );

        return $response;
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