<?php
namespace App\Action;

use Slim\Container;
use Slim\Views\Twig;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class AbstractView
{
    /* @var Container */
    protected $container;

    /* @var Twig */
    protected $view;

    /* @var SchedulerRepository */
    protected $sr;

    //view variables
    protected $user;
    protected $event;

    //view variables
    protected $page_title;
    protected $dates;
    protected $location;
    protected $msg;
    protected $msgStyle;
    protected $menu;

    //default layout colors
    protected $colorTitle = '#80ccff';
    protected $colorOpenSlots = '#FFF484';
    protected $colorUnassigned = '#ffb3b3';
    protected $colorGroup1 = '#00e67a';
    protected $colorGroup2 = '#4dffac';
    protected $colorOpenOpen = '#ffcccc';
    protected $colorHighlight = '#FFBC00';
    protected $colorAlert = '#CC0000';
    protected $colorWarning = '#CC00CC';
    protected $colorSuccess = '#02C902';
    protected $colorLtGray = '#D3D3D3';
    protected $colorDarkGray = '#B7B7B7';

    protected $justOpen;
    protected $sortOn;
    protected $uri;


    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        $this->container = $container;
        $this->view = $container->get('view');
        $this->sr = $schedulerRepository;

        $this->page_title = "Section 1 Referee Scheduler";
    }

    abstract protected function handler(Request $request, Response $response);

    abstract protected function render(Response &$response);

    protected function divisionAge($div)
    {
        $u = stripos($div, "U");

        switch ($u) {
            case 1:
                $div = substr($div, $u, 3);;
                break;
            case 2:
                $div = substr($div, $u - 1, 3);;
                break;
            default:
                $div = substr($div, $u - 2, 3);;
        }

        return $div;
    }

    protected function isRepost(Request $request)
    {

        if ($request->isPost()) {
            $_POST = $request->getParsedBody();

            if (isset($_SESSION['postdata'])) {
                if ($_POST == $_SESSION['postdata']) {
                    return true;
                } else {
                    $_SESSION['postdata'] = $_POST;
                }
            } else {
                $_SESSION['postdata'] = $_POST;
            }
        }

        return false;
    }

    protected function getBaseURL($path)
    {
        $request = $this->container->get('request');
        $baseUri = $request->getUri()->getBasePath() . $this->container->get($path);

        return $baseUri;
    }

    protected function getCurrentEvents()
    {
        $events = $this->sr->getCurrentEvents();

        $linkedEvents = [];
        foreach ($events as $event) {
            if (!empty($event->infoLink)) {
                $event->name = "<a href='$event->infoLink' target='_blank'>$event->name</a>";
            }

            $linkedEvents[] = $event;
        }

        return $linkedEvents;
    }

    protected function getUri($path, $field = 'game_number')
    {
        $uri = null;

        switch ($field) {
            case 'game_number':
                if (!$this->justOpen) {
                    $uri = $this->getBaseURL($path) ;
                }
                else {
                    $uri = $this->getBaseURL($path) . "?open";
                }
                break;
            default:
                if (!$this->justOpen) {
                    $uri = $this->getBaseURL($path) . "?sort=" . $field;
                }
                else {
                    $uri = $this->getBaseURL($path) . "?open&sort=" . $field;
                }
        }

        return $uri;
    }
}