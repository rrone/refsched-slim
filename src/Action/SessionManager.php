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
    public function getPhpSessionId($request)
    {
        $sessionKey = FigRequestCookies::get($request, 'PHPSESSID', null);

        if(!empty($sessionKey)) {
            return $sessionKey->getValue();
        }

        return null;
    }
    public function getSessionVars($request)
    {
        $session = array(
            'user' => null,
            'event' => null,
            'authed' => false
        );

        $sessId = $this->getPhpSessionId($request);

        if(!is_null($sessId)){
            $user = $this->sr->getUserBySessionId($sessId);

            if (!is_null($user)) {
                $session['user'] = $user->name;
                if ($user->active) {
                    $event = $this->sr->getEventById($user->active_event_id);
                    $session['event'] = $event;
                    $session['authed'] = $this->sr->setUserActive($user->id, $event->id, $sessId);
                }
            }
        }

        return $session;
    }
    public function setSessionVars($user, $event, $sessId)
    {
        $this->sr->setUserActive($user->id, $event->id, $sessId);
    }
    public function clearGlobals($user)
    {
        $this->sr->setUserActive($user->id, null, null, false);
    }
    public function emptySessionVars()
    {
        $session = array(
            'user' => null,
            'event' => null,
            'authed' => false
        );

        return $session;
    }
}