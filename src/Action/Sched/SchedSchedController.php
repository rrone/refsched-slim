<?php
namespace App\Action\Sched;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedSchedController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule schedule page action dispatched");
        
        $content = array(
            'view' => array (
                'content' => $this->renderSched(),
                'menu' => $this->menu(),
                'title' => $this->page_title
            )
        );        
      
        $this->view->render($response, 'sched.html.twig', $content);
    }

    private function renderSched()
    {
        $html = null;
        
        $this->authed = isset($_SESSION['authed']) ?  $_SESSION['authed'] : false;
        $showgroup = null;
        
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;
        if ( count( $_GET ) && array_key_exists( 'group', $_GET ) ) {
           $showgroup = $_GET[ 'group' ];
        }
        $locked = 0;
        $thiscolor = '#00FFFF';
        $othercolor = '#FFFacd';
        $allatlimit = 1;
        $oneatlimit = 0;
        $showavailable = 1;
        $a_init = substr( $this->rep, -1 );
        $used_list[ 'none' ] = null;
        $assigned_list = null;
        
        if ( $this->authed && $this->rep != 'Section 1') {
            if ( file_exists( $this->refdata . "limit" ) ) {
                $fp = fopen( $this->refdata . "limit", "r" );
                while ( $line = fgets( $fp, 1024 ) ) {
                   $record = explode( ',', $line );
                   $limit_list[ $record[0] ] = $record[1];
                   $used_list[ $record[0] ] = 0;
                   $assigned_list[ $record[0] ] = 0;
                }
                fclose( $fp );
                if ( !count( $limit_list ) ) { $limit_list[ 'none' ] = 1; }
            }
            else { $limit_list[ 'none' ] = 1; }
            $no_assigned = 0;
            $scheddata = fopen( $schedule_file, "r");
            $sched_no = fgets( $scheddata, 1024 );
            $sched_title = fgets( $scheddata, 1024 );
            $this->page_title = substr( $sched_title, 1);
            $kount = 0;
            $kant = 0;
            $testtime = null;
            while ( $line = fgetcsv( $scheddata, 1024 ) ) {
                if ( strtoupper( trim( $line[ 0 ] ) ) == '#LOCKED' ) { 
                   $locked = 1;
                   $_SESSION['locked'] = 1;
                }
                elseif (substr( $line[0], 0, 1 ) != '#' ) {
                    $game_no[ $kount ] = $line[ 0 ];
                    $date[ $kount ] = $line[ 1 ];
                    $day[ $kount ] = $line[ 2 ];
                    $field[ $kount ] = $line[ 3 ];
                    $time[ $kount ] = $line[ 4 ];
                    $div[ $kount ] = $line[ 5 ];
                    $home[ $kount ] = $line[ 6 ];
                    $visitor[ $kount ] = $line[ 7 ];
                    $ref_team[ $kount ] = $line[ 8 ];
                    if ( $ref_team[ $kount ] == $this->rep ) { 
                        $no_assigned++;
                        if (isset($assigned_list[ substr( $line[5], 0, 3 ) ])) {
                            $assigned_list[ substr( $line[5], 0, 3 ) ]++;
                        }
                   }
                   $used_list[ substr( $line[5], 0, 3 ) ] = 1;
                   $cr[ $kount ] = $line[ 9 ];
                   $ar1[ $kount ] = $line[ 10 ];
                   $ar2[ $kount++ ] = $line[ 11 ];
                }
            }
            fclose ( $scheddata );

            $html = "<h2 align=\"center\">$this->rep Schedule</h2>";
            
            //$free_board = $limit - $no_assigned;
            if ( $locked && array_key_exists( 'none', $limit_list ) ) { 
                $html .= "<center><h3><font color=\"#CC0000\">The schedule has been locked.<br>You may sign up for games but not unassign yourself.</font></h3></center>\n"; 
                $allatlimit = 0;
            }
            elseif ( $locked && array_key_exists( 'all', $limit_list ) && $no_assigned < $limit_list[ 'all' ] ) { 
                $html .= "<center><h3><font color=\"#CC0000\">The schedule has been locked.<br>You may sign up for games but not unassign yourself.</font></h3></center>\n"; 
                $allatlimit = 0;
            }
            elseif ( $locked && array_key_exists( 'all', $limit_list ) && $no_assigned == $limit_list[ 'all' ] ) { 
                $html .= "<center><h3><font color=\"#CC0000\">The schedule has been locked and you are at your game limit.<br>\nYou will not be able to unassign yourself from games to sign up for others.<br>\nThe submit button on this page has been disabled and available games are not shown.<br>\nYou probably want to <a href=\"$this->greetPath\">Go to the Main Page</a> or <a href=\"$this->endPath\">Log Off</a></font></h3></center>\n";
                $showavailable = 0;
            }
            elseif ( $locked && array_key_exists( 'all', $limit_list ) && $no_assigned > $limit_list[ 'all' ] ) { 
                $html .= "<center><h3><font color=\"#CC0000\">The schedule has been locked and you are above your game limit.<br>\nThe extra games were probably assigned by the Section staff.<br>\nYou will not be able to unassign yourself from games to sign up for others.<br>\nThe Submit button has been disabled and available games are not shown.<br>\nYou probably want to <a href=\"$this->greetPath\">Go to the Main Page</a> or <a href=\"$this->endPath\">Log Off</a></font></h3></center>\n";
                $showavailable = 0; 
            }
            elseif ( !$locked && array_key_exists( 'all', $limit_list ) && $no_assigned < $limit_list['all'] ) { 
                $tmplimit = $limit_list['all'];
                $html .= "<center><h3>You are currently assigned to <font color=\"#CC00CC\">$no_assigned</font> of your <font color=\"#CC00CC\">$tmplimit</font> games.</h3></center>\n"; 
            }
            elseif ( !$locked && array_key_exists( 'all', $limit_list ) && $no_assigned == $limit_list['all'] ) { $html .= "<center><h3><font color=\"#CC0000\">You are at your game limit.<br>You will have to unassign yourself from games to sign up for others.</font></h3></center>\n"; }
            elseif ( !$locked && array_key_exists( 'all', $limit_list ) && $no_assigned > $limit_list['all'] ) { $html .= "<center><h3><font color=\"#CC0000\">You are above your game limit.<br>\nThe extra games were probably assigned by the Section staff.<br>\nIf you continue from here you will not be able to keep all the games you are signed up for and may lose some of the games you already have.<br>\nIf you want to keep these games and remain over the game limit it is recommended that you do not hit submit but do something else instead.<br>\n<a href=\"$this->greetPath\">Go to the Main Page</a></font></h3></center>\n"; }
            elseif ( $locked && count( $limit_list ) ) {
                $html .= "<center><h3><font color=\"#CC0000\">The system is locked.<br>You can add games to divisions that are below the limit but not unassign your Area from games.</font><br><br>\n";
                    foreach ( $limit_list as $k => $v ) {
                        $tempassign = $assigned_list[$k];
                        if ( $used_list[ $k ] ) { 
                            $html .= "For $k you are assigned to <font color=\"#CC00CC\">$tempassign</font> with a limit of <font color=\"#CC00CC\">$v</font> games.<br>\n"; 
                            if ( $assigned_list[$k] < $limit_list[$k] ) { $allatlimit = 0;}
                        }
                    }
                if ( $allatlimit ) { 
                   $html .= "<br><font color=\"#CC0000\">All of your divisions are at or above their limits.<br>Because the system is locked, games can not be unassigned to select new ones.<br>No changes are possible: Available games are not shown and the Submit button has been disabled.</font>\n";
                   $showavailable = 0;
                } 
                $html .= "</h3></center>\n";
            }
            elseif ( !$locked && count( $limit_list ) ) {
                $html .= "<center><h3>\n";
                foreach ( $limit_list as $k => $v ) {
                    $tempassign = $assigned_list[$k];
                    if ( $used_list[ $k ] ) { 
                        $html .= "For $k you are assigned to <font color=\"#CC00CC\">$tempassign</font> with a limit of <font color=\"#CC00CC\">$v</font> games.<br>\n"; 
                        if ( $assigned_list[$k] >= $limit_list[$k] ) { $oneatlimit = 1;}
                    }
                }
                if ( $oneatlimit ) { 
                   $html .= "<br><font color=\"#CC0000\">One or more of your divisions are at or above their limits.<br>You will need to unassign games in that division before you can select additional games.</font>\n";
                } 
                $html .= "</h3></center>\n";
            }
      
            $html .= "<form name=\"form1\" method=\"post\" action=\"$this->assignPath\">\n";

            $html .= "  <div align=\"left\">";
       
            if ( (!$locked || !$allatlimit) && !empty($assigned_list) || $showavailable ) {
                $html .=  "      <input class=\"right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
                $html .=  "      <div class='clear-fix'></div>";
            }
            $html .= "    <h3>Available games - Color change indicates different start times.</h3>\n";
            if ( !$showavailable ) {
                $html .= "		<tr align=\"center\" bgcolor=\"$this->colorHighlight\">";   
                $html .= "		<td>No other games available.</td>";
                $html .= "		</tr>\n";
            } else {
                $html .= "      <table>\n";
                $html .= "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                $html .= "        <th>Game No.</th>";
                $html .= "        <th>Assigned</th>";
                $html .= "		  <th>Day</th>";
                $html .= "		  <th>Time</th>";
                $html .= "		  <th>Location</th>";
                $html .= "    	  <th>Div</th>";
                $html .= "		  <th>Home</th>";
                $html .= "	      <th>Away</th>";
                $html .= "		  <th>Referee<br>Team</th>";
                $html .= "		</tr>";
      
                for ( $kant=0; $kant < $kount; $kant++ ) {
                    if ( ( $showgroup && $showgroup == substr( $div[$kant], 0, 3 ) ) || !$showgroup ) {
                        if ( substr( $game_no[$kant], 0, 1 ) != "#" && $a_init != substr( $home[$kant], 0, 1) && $a_init != substr( $visitor[$kant], 0, 1) && !$ref_team[$kant] && $showavailable ) {
               
                            if ( !$testtime ) { $testtime = $time[$kant]; }
                            elseif ( $testtime != $time[$kant] ) {
                                $testtime = $time[$kant];
                                $tempcolor = $thiscolor;
                                $thiscolor = $othercolor;
                                $othercolor = $tempcolor;
                            }

                            $html .= "		<tr align=\"center\" bgcolor=\"$thiscolor\">";
                            $html .= "		<td>$game_no[$kant]</td>";
                            $html .= "		<td><input type=\"checkbox\" name=\"game$game_no[$kant]\" value=\"assign$game_no[$kant]\"></td>";
                            $html .= "		<td>$day[$kant]<br>$date[$kant]</td>";
                            $html .= "		<td>$time[$kant]</td>";
                            $html .= "		<td>$field[$kant]</td>";
                            $html .= "		<td>$div[$kant]</td>";
                            $html .= "		<td>$home[$kant]</td>";
                            $html .= "		<td>$visitor[$kant]</td>";
                            $html .= "		<td>&nbsp;</td>";
                            $html .= "		</tr>\n";
                        }
                    }
                }
            }
            $html .= "            </table>";
      
            $html .= "	  <h3>Assigned games</h3>\n";
            if ( empty($kount) ) {
                $html .= "	  <table>\n";
                $html .= "		<tr align=\"center\" bgcolor=\"$this->colorHighlight\">";   
                $html .= "		<td>$this->rep has no games assigned.</td>";
                $html .= "		</tr>\n";
            } else {            
                $html .= "	  <table>\n";
                $html .= "	    <tr align=\"center\" bgcolor=\"$this->colorTitle\">\n";
                $html .= "		<th>Game No.</th>\n";
                $html .= "		<th>Assigned</th>\n";
                $html .= "		<th>Day</th>\n";
                $html .= "		<th>Time</th>\n";
                $html .= "		<th>Location</th>\n";
                $html .= "		<th>Div</th>\n";
                $html .= "		<th>Home</th>\n";
                $html .= "		<th>Away</th>\n";
                $html .= "		<th>Referee<br>Team</th>\n";
                $html .= "          </tr>\n";
          
                for ( $kant=0; $kant < $kount; $kant++ ) {
                   if ( $this->rep == $ref_team[$kant]) {
                        $html .= "		<tr align=\"center\" bgcolor=\"$this->colorGroup\">";
                        $html .= "		<td>$game_no[$kant]</td>";
                        if ( $locked ) {
                           $html .= "		<td>Locked</td>";
                        }
                        else {
                           $html .= "		<td><input name=\"game$game_no[$kant]\" type=\"checkbox\" value=\"assign$game_no[$kant]\" checked></td>";
                        }
                        $html .= "		<td>$day[$kant]<br>$date[$kant]</td>";
                        $html .= "		<td>$time[$kant]</td>";
                        $html .= "		<td>$field[$kant]</td>";
                        $html .= "		<td>$div[$kant]</td>";
                        $html .= "		<td>$home[$kant]</td>";
                        $html .= "		<td>$visitor[$kant]</td>";
                        $html .= "		<td>$ref_team[$kant]</td>";
                        $html .= "		</tr>\n";
                    }
                }
                }
            $html .= "            </table>";
            if ( $locked && $allatlimit ) {
                $html .= "<h3>Submit Disabled</h3>\n";
            }
            else {
                if ( (!$locked || !$allatlimit) && !empty($assigned_list) || $showavailable ) {
                    $html .=  "      <input class=\"right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
                    $html .=  "      <div class='clear-fix'></div>";
                }
            }
            $html .= "            </form>\n";      
            $_SESSION['locked'] = $locked;
         }
        elseif ( !$this->authed ) {
            $html .=  "<center><h1>You are not Logged On</h1></center>";
            $html .= "<p align=\"center\"><a href=\"$this->logonPath\">Logon Page</a></p>";
            session_destroy();
        }
        elseif ( $this->authed && $this->rep == 'Section 1' ) {
            $html .=  "<center><h1>You should be on this<br>";
            $html .= "<a href=\"$this->masterPath\">Schedule Page</a></h1>";
        }
        else {
            $html .=  $this->errorCheck();
        }
      
        return $html;
          
    }
    private function menu()
    {
        $html =
<<<EOD
    <h3 align="center"><a href="$this->greetPath">Go to main page</a>&nbsp;-&nbsp;
    <a href="$this->fullPath">Go to full schedule</a>&nbsp;-&nbsp;
    <a href="$this->endPath">Logoff</a></h3>
EOD;
        
        return $html;
    }
}
