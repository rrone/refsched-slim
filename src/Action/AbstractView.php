<?php
namespace App\Action;

use Slim\Container;
use Slim\Views\Twig;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\ContainerException;

abstract class AbstractView
{
    /* @var Container */
    protected $container;

    /* @var Twig */
    protected $view;

    /* @var SchedulerRepository */
    protected $sr;

    //view variables
    protected $projectKey;

    protected $user;
    protected $event;
    protected $groups;
    protected $limit_list = [];
    protected $assigned_list = [];


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


    /**
     * AbstractView constructor.
     * @param Container $container
     * @param SchedulerRepository $schedulerRepository
     *
     */
    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        $this->container = $container;
        $this->view = $container['view'];
        $this->sr = $schedulerRepository;

        $this->page_title = $this->view['header'];
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    abstract protected function handler(Request $request, Response $response);

    /**
     * @param Response $response
     * @return mixed
     */
    abstract protected function render(Response $response);

    protected function divisionAge($div)
    {
        $u = stripos($div, "U");

        switch ($u) {
            case 0:
                $div = substr_replace(substr($div,1) ,"U", -1);
                break;
            default:
                $div = substr($div,1);
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

    /**
     * @param $path
     * @return string
     *
     */
    protected function getBaseURL($path)
    {
        $request = $this->container['request'];

        return $request->getUri()->getBasePath() . $this->container->get($path);
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

    /**
     * @param $path
     * @param string $field
     * @return null|string
     *
     */
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

    /**
     * @return null
     */
    protected function initLimitLists()
    {
        $this->assigned_list = [];
        $this->limit_list = [];

        //initialize assigned and limit lists
        $this->groups = $this->sr->getGroups($this->projectKey);

        foreach ($this->groups as $group) {
            $group = explode(' ', $group);
            $group = $group[0];

            $this->limit_list[$group] = 'none';
            $this->assigned_list[$group] = 0;
        }

        $limits = $this->sr->getLimits($this->projectKey);

        foreach ($limits as $limit) {
            if ($limit->division != 'all') {
                foreach ($this->groups as $group) {
                    $this->limit_list[$group] = $limit->limit;
                }

                return null;
            }

            foreach ($this->groups as $group) {
                $div = $this->divisionAge($limit->division);
                if (stripos($group, $div)) {
                    $this->limit_list[$group] = $limit->limit;
                }
            }
        }
        return null;
    }

}