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
            'sched' => array (
                'greet' => $this->renderGreet(),
                'event' => array (
                    'name' => 'Western States Championships, Carson City, NV',
                    'date' => 'March 25-26, 2016'
                )
            )
        );        
        
        $this->view->render($response, 'sched.greet.html.twig', $content);

    }

    private function renderGreet()
    {
        $html = null;
   
        $from_url = parse_url( $_SERVER['HTTP_REFERER']);
        $from = $from_url['path'];
  //      for debugging
        //$html .= "$from";
        $authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
            $rep = $_POST['area'];
           
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
            $logon_good = 0;
            $authdata = fopen( $this->authdat . "auth.dat", "r");
            while (($line = fgetcsv($authdata, 1024)) && !$logon_good) {
                //print_r($line);
                if ($line[0] == $rep && $line[1] == $pass) { 
                    $logon_good=1;
                    $authed = 1;
                    $_SESSION['authed'] = 1;
                    $_SESSION['unit'] = $rep;
                    if ( !$schedule_file ) { $schedule_file = $this->refdata . 'sched1117.dat'; }
                    $_SESSION['eventfile'] = $schedule_file;
                }
            }
            fclose( $authdata );
            if ( !$logon_good ) { $logon_good = -1; }
        }
        elseif ( $authed ) { 
            $logon_good = 1;
            $rep = $_SESSION['unit'];
            $schedule_file = $_SESSION['eventfile'];
        }
        else { 
           $logon_good = 0;
        }
        if ( $logon_good > 0 ) {
            if ( file_exists( $this->authdat . "limit" ) ) {
                $fp = fopen( $this->refdata . "limit", "r" );
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
            $page_title = substr( $title, 1);
            while ( $line = fgets( $scheddata, 1024 ) ) {
               if ( strtoupper( trim( $line ) ) == '#LOCKED' ) {
                  $locked = 1;
               }
               elseif ( substr( $line, 0, 1 ) != '#' ) {
                  $record = explode( ',', $line );
                  if ( $rep == "Section 1" && $record[8] ) { $no_assigned++; }
                  elseif ( $rep == "Section 1" ) { $no_unassigned++; }
                  elseif ( $rep == $record[8] ) { 
                     $no_area++; 
                     $assigned_list[ substr( $record[5], 0, 3 ) ]++;
                  }
                  $used_list[ substr( $record[5], 0, 3 ) ] = 1;
               }
            }
            fclose( $scheddata );
            $html .= "<h2 align=\"center\">$page_title</h2>";
            if ( $rep == 'Section 1' ) {
               $html .= "<h3 align=\"center\">Welcome $rep Scheduler</h3>\n";
               $html .= "<h3 align=\"center\"><font color=\"#CC0000\">STATUS</font> - At this time:<br>\n";
               if ( $locked ) {
                  $html .= "The schedule is:&nbsp;<font color=\"#CC0000\">Locked</font>&nbsp;-&nbsp;(<a href=\"/unlock\">Unlock</a> the schedule now)<br>\n";
               }
               else {
                  $html .= "The schedule is:&nbsp;<font color=\"#008800\">Unlocked</font>&nbsp;-&nbsp;(<a href=\"/lock\">Lock</a> the schedule now)<br>\n";
               }
               $html .= "<font color=\"#008800\">$no_assigned</font> games are assigned and <font color=\"#CC0000\">$no_unassigned</font> are unassigned.<br>\n";
               if ( array_key_exists( 'all', $limit_list ) ) {
                  $tmplimit = $limit_list['all'];
                  $html .= "There is a <font color=\"#CC00CC\">$tmplimit</font> game limit.</h3>\n";
               }
               elseif ( array_key_exists( 'none', $limit_list ) ) {
                  $html .= "There is <font color=\"#CC00CC\">no</font> game limit.</h3>\n";
               }
               elseif ( !array_key_exists( 'all', $limit_list ) && count( $limit_list) ) {
                  foreach ( $limit_list as $k => $v ) {
                     if ( $used_list[ $k ] ) { $html .= "There is a <font color=\"#CC00CC\">$v</font> game limit for $k.<br>\n"; }
                  }
                  $html .= "</h3>\n";
               }
               else {
                  $html .= "There is <font color=\"#CC00CC\">no</font> game limit at this time.</h3>\n";
               }
                  
            }
            else {
               $html .= "<h3 align=\"center\">Welcome $rep  Representative</h3>";
               $html .= "<h3 align=\"center\"><font color=\"#CC0000\">Status</font><br>";
               if ( $no_area == 0 ) { $html .= "$rep is not currently assigned to any games.<br>"; }
               elseif ( $no_area == 1 ) { $html .= "$rep is currently assigned to <font color=\"#008800\">$no_area</font> game.<br>"; }
               else { $html .= "$rep is currently assigned to <font color=\"#008800\">$no_area</font> games.<br>"; }
               if ( array_key_exists( 'all', $limit_list ) ) {
                  $tmplimit = $limit_list[ 'all' ];
                  $html .= "There is a limit of <font color=\"#CC00CC\">$tmplimit</font> Area assigned games at this time.</h3>\n";
               }
               elseif ( array_key_exists( 'none', $limit_list ) ) {
                  $html .= "There is <font color=\"#CC00CC\">no</font> limit on Area assigned games at this time.</h3>\n";
               }
               elseif ( count( $limit_list ) ) {
                  foreach ( $limit_list as $k => $v ) {
                     $tmpassigned = $assigned_list[ $k ];
                     if ( $used_list[ $k ] ) { 
                        $html .= "You have assigned <font color=\"#CC00CC\">$tmpassigned</font> of your <font color=\"#CC00CC\">$v</font> game limit for $k.<br>\n";
                        if ( $tmpassigned >= $v ) { $oneatlimit = 1; }
                     }
                  }
                  $html .= "</h3>\n";
               }
               else {
                  $html .= "There is no game limit at this time.</h3>\n";
               }
               if ( $locked && !array_key_exists( 'none', $limit_list ) ) {
                  $html .= "<h3 align=\"center\"><font color=\"#CC0000\">The schedule is presently locked.</font><br>\n";
                  if ( !$oneatlimit ) {
                     $html .= "You may sign $rep teams up for games but not remove them.</h3>\n";
                  }
                  else {
                     $html .= "Since $rep is at or above the limit you will not be able to sign teams up for games.</h3>\n";
                  }
                }
              
            }
            $html .= "<center><hr width=\"25%\"><h3><font color=\"#CC0000\">ACTIONS</font></h3>\n";
            if ( $rep == 'Section 1' ) {
               $html .= "<h3 align=\"center\"><a href=\"/master\">Schedule All Referee Teams</a></h3>";
            }
            else {
               $html .= "<h3 align=\"center\"><a href=\"/sched\">Schedule $rep Referee Teams</a></h3>";
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


