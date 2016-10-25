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
    public function getSessionUser($request)
    {
        $userName = FigRequestCookies::get($request, 'user', null);

        if(!empty($userName)){
            $name = $userName->getValue();

            return $this->sr->getUserByName($name);
        }

        return null;
    }
    public function setSessionUser($response, $userName)
    {
        $response = FigResponseCookies::set($response, SetCookie::create('user')
            ->withValue($userName)
        );

        return $response;
    }
    public function getSessionVars($request)
    {
        $session = array(
            'user' => null,
            'event' => null,
            'authed' => false
        );

        $user = $this->getSessionUser($request);

        if (!is_null($user)) {
            $session['user'] = $user->name;
            if ($user->active) {
                $event = $this->sr->getEventById($user->active_event_id);
                $session['event'] = $event;
                $session['authed'] = $this->sr->setUserActive($user->id, $event->id);
            }
        }

        return $session;
    }
    public function setSessionVars($user, $event)
    {
        $this->sr->setUserActive($user->id, $event->id);
    }
    public function clearGlobals($user)
    {
        $this->sr->setUserActive($user->id, null, false);
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