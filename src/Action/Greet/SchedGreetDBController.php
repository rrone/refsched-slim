<?php
namespace App\Action\Greet;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedGreetDBController extends AbstractController
{
    // SchedulerRepository //
    private $sr;
    
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
        
        $this->sr = $repository;
		
    }
     public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule greet page action dispatched");

        $content = array(
            'view' => array (
                'content' => $this->renderGreet(),
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
                'menu' => null
                )
            );        
        
        $this->view->render($response, 'sched.html.twig', $content);

    }

    private function renderGreet()
    {
        $html = null;
        
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_HOST'];
        $from_url = parse_url( $referer );
        $from = $from_url['path'];
  
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
		$event = null;
		$projectKey = null;

        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            $this->rep = $_POST['area'];
           
            $pass = crypt( $_POST['passwd'], 11);
   //             for debugging
            //$html .= "<h3>$pass</h3>";
            //$pass = $_POST['passwd'];
            //$html .= "<h3>$pass</h3>";
            $user = $this->sr->getUserByPW($pass);

			$event = $this->sr->getEvent($_POST['event']);

            $this->logon_good = !empty($user);
            
            if ($this->logon_good) {
                $this->authed = 1;
                $_SESSION['authed'] = 1;
                $_SESSION['unit'] = $this->rep;
                $_SESSION['event'] = $event;
            }
            else {
                $this->logon_good = false;
            }
        }
        else {
            if ( $this->authed ) { 
            $this->logon_good = 1;
            $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
            $event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
            }
            else { 
               $this->logon_good = 0;
            }
        }
        
        $used_list[ 'none' ] = null;
        $assigned_list = null;
        $limit_list = null;

        if ( $this->logon_good > 0 ) {
			$projectKey = $event->projectKey;
		
            $limits = $this->sr->getLimits($projectKey);
			$groups = $this->sr->getGroups($projectKey);
            foreach($limits as $group){
				$limit_list[ $group->division ] = $group->limit;
				$used_list[ $group->division ] = 0;
				$assigned_list[ $group->division ] = 0;
			}
            $delim = ' - ';
            $no_assigned = 0;
            $no_unassigned = 0;
            $no_area = 0;
            $oneatlimit = 0;
      
            $this->page_title = $event->name;
			$this->dates = $event->dates;
			$this->location = $event->location;
            
            $locked = $this->sr->getLocked($projectKey);

            $games = $this->sr->getGames($projectKey);
            foreach( $games as $game ) {
                if ( $this->rep == "Section 1" && $game->assignor ) { $no_assigned++; }
                elseif ( $this->rep == "Section 1" ) { $no_unassigned++; }
                elseif ( $this->rep == $game->assignor ) { 
                   $no_area++;
                   $assigned_list[ substr($game->division,0,3) ]++;
                }
                $used_list[ substr($game->division,0,3) ] = 1;
            }
            
            $html = null;
            if ( $this->rep == 'Section 1' ) {
               $html .= "<h3 align=\"center\">Welcome $this->rep Scheduler</h3>\n";
               $html .= "<h3 align=\"center\"><font color=\"$this->colorAlert\">STATUS</font> - At this time:<br>\n";
               if ( $locked ) {
                  $html .= "The schedule is:&nbsp;<font color=\"$this->colorAlert\">Locked</font>&nbsp;-&nbsp;(<a href=\"$this->unlockPath\">Unlock</a> the schedule now)<br>\n";
               }
               else {
                  $html .= "The schedule is:&nbsp;<font color=\"$this->colorSuccess\">Unlocked</font>&nbsp;-&nbsp;(<a href=\"$this->lockPath\">Lock</a> the schedule now)<br>\n";
               }
               $html .= "<font color=\"#008800\">$no_assigned</font> games are assigned and <font color=\"$this->colorAlert\">$no_unassigned</font> are unassigned.<br>\n";
               if ( array_key_exists( 'all', $limit_list ) ) {
                  $tmplimit = $limit_list['all'];
                  $html .= "There is a <font color=\"$this->colorWarning\">$tmplimit</font> game limit.</h3>\n";
               }
               elseif ( array_key_exists( 'none', $limit_list ) ) {
                  $html .= "There is <font color=\"$this->colorWarning\">no</font> game limit.</h3>\n";
               }
               elseif ( !array_key_exists( 'all', $limit_list ) && count( $limit_list) ) {
                  foreach ( $limit_list as $k => $v ) {
                     if ( $used_list[ $k ] ) { $html .= "There is a <font color=\"$this->colorWarning\">$v</font> game limit for $k.<br>\n"; }
                  }
                  $html .= "</h3>\n";
               }
               else {
                  $html .= "There is <font color=\"$this->colorWarning\">no</font> game limit at this time.</h3>\n";
               }
                  
            }
            else {
                $html .= "<h3 align=\"center\">Welcome $this->rep Representative</h3>";
                $html .= "<h3 align=\"center\"><font color=\"$this->colorAlert\">Status</font><br>";
                if ( $no_area == 0 ) { $html .= "$this->rep is not currently assigned to any games.<br>"; }
                elseif ( $no_area == 1 ) { $html .= "$this->rep is currently assigned to <font color=\"$this->colorSuccess\">$no_area</font> game.<br>"; }
                else { $html .= "$this->rep is currently assigned to <font color=\"$this->colorSuccess\">$no_area</font> games.<br>"; }

                if ( array_key_exists( 'all', $limit_list ) ) {
                   $tmplimit = $limit_list[ 'all' ];
                   $html .= "There is a limit of <font color=\"$this->colorWarning\">$tmplimit</font> Area assigned games at this time.</h3>\n";
                }
                elseif ( array_key_exists( 'none', $limit_list ) ) {
                   $html .= "There is <font color=\"$this->colorWarning\">no</font> limit on Area assigned games at this time.</h3>\n";
                }
                elseif ( count( $limit_list ) ) {
                    foreach ( $limit_list as $k => $v ) {
                        $tmpassigned = $assigned_list[ $k ];
                        if ( $used_list[ $k ] ) { 
                           $html .= "You have assigned <font color=\"$this->colorWarning\">$tmpassigned</font> of your <font color=\"$this->colorWarning\">$v</font> game limit for $k.<br>\n";
                           if ( $tmpassigned >= $v ) { $oneatlimit = 1; }
                        }
                    }
                    $html .= "</h3>\n";
                }
                else {
                   $html .= "There is no game limit at this time.</h3>\n";
                }
                if ( $locked && !array_key_exists( 'none', $limit_list ) ) {
                    $html .= "<h3 align=\"center\"><font color=\"$this->colorAlert\">The schedule is presently locked.</font><br>\n";
                    if ( !$oneatlimit ) {
                      $html .= "You may sign $this->rep teams up for games but not remove them.</h3>\n";
                    }
                    else {
                      $html .= "Since $this->rep is at or above the limit you will not be able to sign teams up for games.</h3>\n";
                    }
                }
              
            }
            $html .= "<center><hr width=\"25%\"><h3><font color=\"$this->colorAlert\">ACTIONS</font></h3>\n";
            if ( $this->rep == 'Section 1' ) {
                $html .= "<h3 align=\"center\"><a href=\"$this->masterPath\">Schedule All Referee Teams</a></h3>";
            }
            else {
                $html .= "<h3 align=\"center\"><a href=\"$this->schedPath\">Schedule $this->rep Referee Teams</a></h3>";
                $html .= "<h3 align=\"center\">Schedule 1 division: ";
                foreach ($groups as $group) {
                    $html .= "<a href=\"$this->schedPath?group=$group\">$group</a>" . $delim;
                }
                $html = substr($html, 0, strlen($html)-3) ."</h3>";
            }
            $html .= "<h3 align=\"center\"><a href=\"$this->fullPath\">Go to the full game schedule</a></h3>";
            $html .= "<h3 align=\"center\"><a href=\"$this->refsPath\">Add/Modify Referee Names to Assigned Games</a></h3>";
   //         $html .= "<h3 align=\"center\"><a href=\"/summary.htm\">Summary of the playoffs</a></h3>";
            $html .= "<h3 align=\"center\"><a href=\"$this->endPath\">LOG OFF</a></h3>";
            $html .= "</center>";
        }
        elseif ( $this->logon_good < 0 ) {
           $html .=  "<center><h1>Logon Failure</h1></center>";
           $html .= "<h3 align=\"center\"><a href=\"$this->logonPath\">Go to Logon Page to Try Again.</a></h3>";
           //session_destroy();
        }
        else {
           $html .=  "<center><h1>You are not Logged On</h1></center>";
           $html .= "<h3 align=\"center\"><a href=\"$this->logonPath\">Logon Page</a></h3>";
           //session_destroy();
        }
    
        return $html;
          
    }
}


