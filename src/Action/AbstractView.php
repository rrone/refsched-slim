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
        return substr($div, 0, 3);
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

}