<?php
namespace App\Action\Greet;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedGreetController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule greet page action dispatched");

        $content = array(
            'view' => array (
                'content' => $this->renderGreet(),
                'title' => $this->page_title,
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
  //      for debugging
        //$html .= "$from";

        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;

        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            $this->rep = $_POST['area'];
           
            $pass = crypt( $_POST['passwd'], 11);
   //             for debugging
            //$html .= "<h3>$pass</h3>";
            //$pass = $_POST['passwd'];
            //$html .= "<h3>$pass</h3>";
            $event = $_POST['event'];
  //
  //   Be sure the string match fits the event title from from in index.htm
  //    And the event schedule file name is correct
  //
            if (!$event) {
               $event = 'Upper ';
            }
            if ( substr( $event, 0, 6 ) == 'League' ) {
               $schedule_file = $this->refdata . 'sched160227.dat';
            }
            elseif ( substr( $event, 0, 6 ) == 'All St' ) {
               $schedule_file = $this->refdata . 'sched160220.dat';
            }
            elseif ( substr( $event, 0, 6 ) == 'Upper ' ) {
                $schedule_file = $this->refdata . 'sched112115.dat';
            }
            elseif ( substr( $event, 0, 6 ) == 'Wester' ) {
                $schedule_file = $this->refdata . 'sched160319_4.dat';
            }
            $this->logon_good = 0;
            $authdata = fopen( $this->authdat . "auth.dat", "r");
            while (($line = fgetcsv($authdata, 1024)) && !$this->logon_good) {
                //print_r($line);
                if ($line[0] == $this->rep && $line[1] == $pass) { 
                    $this->logon_good=1;
                    $this->authed = 1;
                    $_SESSION['authed'] = 1;
                    $_SESSION['unit'] = $this->rep;
                    if ( !$schedule_file ) { $schedule_file = $this->refdata . 'sched1117.dat'; }
                    $_SESSION['eventfile'] = $schedule_file;
                }
            }
            fclose( $authdata );
            if ( !$this->logon_good ) { $this->logon_good = -1; }
        }
        elseif ( $this->authed ) { 
            $this->logon_good = 1;
            $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
            $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;
        }
        else { 
           $this->logon_good = 0;
        }
        
        $used_list[ 'none' ] = null;
        $assigned_list = null;
        $limit_list = null;

        if ( $this->logon_good > 0 ) {
            if ( file_exists( $this->authdat . "limit" ) ) {
                $fp = fopen( $this->authdat . "limit", "r" );
                while ( $line = fgets( $fp, 1024 ) ) {
                   $record = explode( ',', $line );
                   $limit_list[ $record[ 0 ] ] = $record[1];
                   $used_list[ $record[ 0 ] ] = 0;
                   $assigned_list[ $record[ 0 ] ] = 0;
                }
               fclose( $fp );
            }
            else { $limit_list[ 'none' ] = 1; } 

            $no_assigned = 0;
            $no_unassigned = 0;
            $no_area = 0;
            $locked = 0;
            $oneatlimit = 0;
            $scheddata = fopen( $schedule_file, "r");
            $no = fgets( $scheddata, 1024 );
            $title = fgets( $scheddata, 1024 );
            $this->page_title = substr( $title, 1);

            while ( $line = fgets( $scheddata, 1024 ) ) {
               if ( strtoupper( trim( $line ) ) == '#LOCKED' ) {
                  $locked = 1;
               }
               elseif ( substr( $line, 0, 1 ) != '#' ) {
                  $record = explode( ',', $line );
                  if ( $this->rep == "Section 1" && $record[8] ) { $no_assigned++; }
                  elseif ( $this->rep == "Section 1" ) { $no_unassigned++; }
                  elseif ( $this->rep == $record[8] ) { 
                     $no_area++;
                     $assigned_list[ substr( $record[5], 1, 3 ) ]++;
                  }
                  $used_list[ substr( $record[5], 1, 3 ) ] = 1;
               }
            }
            fclose( $scheddata );
            
            $html = null;
            if ( $this->rep == 'Section 1' ) {
               $html .= "<h3 align=\"center\">Welcome $this->rep Scheduler</h3>\n";
               $html .= "<h3 align=\"center\"><font color=\"$this->colorAlert\">STATUS</font> - At this time:<br>\n";
               if ( $locked ) {
                  $html .= "The schedule is:&nbsp;<font color=\"$this->colorAlert\">Locked</font>&nbsp;-&nbsp;(<a href=\"/unlock\">Unlock</a> the schedule now)<br>\n";
               }
               else {
                  $html .= "The schedule is:&nbsp;<font color=\"$this->colorSuccess\">Unlocked</font>&nbsp;-&nbsp;(<a href=\"/lock\">Lock</a> the schedule now)<br>\n";
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
               $html .= "<h3 align=\"center\"><a href=\"/master\">Schedule All Referee Teams</a></h3>";
            }
            else {
               $html .= "<h3 align=\"center\"><a href=\"/sched\">Schedule $this->rep Referee Teams</a></h3>";
               $html .= "<h3 align=\"center\">Schedule 1 division: <a href=\"/sched?group=U10\">U10</a> - <a href=\"/sched?group=U12\">U12</a> - <a href=\"/sched?group=U14\">U14</a> - <a href=\"/sched?group=U16\">U16</a> - <a href=\"/sched?group=U19\">U19</a></h3>";
            }
            $html .= "<h3 align=\"center\"><a href=\"/full\">View the full game schedule</a></h3>";
            $html .= "<h3 align=\"center\"><a href=\"/refs\">Add/Modify Referee Names to Assigned Games</a></h3>";
   //         $html .= "<h3 align=\"center\"><a href=\"/summary.htm\">Summary of the playoffs</a></h3>";
            $html .= "<h3 align=\"center\"><a href=\"/end\">LOG OFF</a></h3>";
            $html .= "</center>";
        }
        elseif ( $logon_good < 0 ) {
           $html .=  "<center><h1>Logon Failure</h1></center>";
           $html .= "<h3 align=\"center\"><a href=\"/\">Return to Logon Page to Try Again.</a></h3>";
           //session_destroy();
        }
        else {
           $html .=  "<center><h1>You are not Logged On</h1></center>";
           $html .= "<h3 align=\"center\"><a href=\"/\">Logon Page</a></h3>";
           //session_destroy();
        }
    
        return $html;
          
    }
}


