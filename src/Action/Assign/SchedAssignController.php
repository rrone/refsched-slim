<?php
namespace App\Action\Assign;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedAssignController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule greet page action dispatched");
        
        $content = array(
            'view' => array (
                'content' => $this->renderAssign(),
                'title' => $this->page_title,
                'menu' => $this->menu()
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);
;
    }

    private function renderAssign()
    {
        $html = null;
        
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_HOST'];
        $from_url = parse_url( $referer );

        $from = $from_url['path'];
  //        $html .= "$from";

        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;

        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        $locked = isset($_SESSION['locked']) ? $_SESSION['locked'] : null;
        $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;
  
        $games_now = [];
        $games_requested = [];
        
        if ( file_exists( $this->authdat . "limit" ) ) {
            $fp = fopen( $this->authdat . "limit", "r" );
            while ( $line = fgets( $fp, 1024 ) ) {
                $record = explode( ',', $line );
                $limit_list[ $record[0] ] = $record[1];   //The limit on each div
                $used_list[ $record[0] ] = 0;           //Yes-no divisions used
                $assigned_list[ $record[0] ] = 0;       //Yes-no divisions for this user
                $no_posted[ $record[0] ] = 0;           //Number of games counted as posted
                $games_requested[ $record[0] ] = 0;
                $games_now[ $record[0] ] = 0;
                $games_both[ $record[0] ] = 0;
            }
            fclose( $fp );
            if ( !count( $limit_list ) ) { $limit_list[ 'none' ] = 1; }
        }
        else { $limit_list[ 'none' ] = 1; }
  
  
        if ( $this->authed && $_SERVER['REQUEST_METHOD'] == 'POST' && $this->rep != 'Section 1' ) {
  //         print_r($_POST);
            $array_of_keys = array_keys( $_POST );
   //         $html .= "<p>";
   //         print_r($array_of_keys);
   //         $html .= "</p>";
            $num_mod = count($array_of_keys)-1;
            if ( $num_mod > 0 || $locked ) {
                for ( $kount = 0; $kount < $num_mod; $kount++ ) {
                    $array_of_keys[$kount] = substr( $array_of_keys[$kount], 4 );
                }
   //           $html .= "<p>";
   //           print_r($array_of_keys);
   //           $html .= "</p>";
                $fp = fopen( $schedule_file, "r");
                    while ( $line = fgets( $fp, 1024 ) ) {
                        if ( substr( $line, 0, 1 ) != '#' ) {
                            $record = explode( ',', $line );
                            if ( in_array( $record[0], $array_of_keys ) ) { 
                                $games_requested[ substr( $record[5], 1, 3 ) ]++;
                                if ( $record[8] == $this->rep ) {
                                    $games_both[ substr( $record[5], 1, 3 ) ]++;
                                }
                            }
                            if ( $record[8] == $this->rep ) {
                                $games_now[ substr( $record[5], 1, 3 ) ]++;
                                if ( array_key_exists( 'all', $limit_list ) ) { $games_now[ 'all' ]++; }
                            }
                        }
                    }
                   
                fclose( $fp );
       //  Debugging output
       //            print_r( $games_requested );
       //            $html .= "\n";
       //            print_r( $games_now );
       //            $html .= "\n";
       //            print_r( $games_both );
       //            $html .= "\n";
       
       //   Begin the file rewrite loop
                copy( $schedule_file, $this->refdata . "temp.dat");
                $outfile = fopen( $schedule_file, "w");
                if (flock( $outfile, LOCK_EX )) {
      //              $html .= "<p>Got lock</p>\n<ul>\n";
                    $tmpfile = fopen( $this->refdata . "temp.dat", "r");
                    $sched_no = fgets( $tmpfile, 1024 );
                    fputs( $outfile, $sched_no );
                    $sched_title = fgets( $tmpfile, 1024 );
                    fputs( $outfile, $sched_title );
                    $this->page_title = substr( $sched_title, 1);
      
                    while ( $line = fgets( $tmpfile, 1024 ) ) {
                        if ( substr( $line, 0, 1 ) == '#' ) {
                                     //  Pass through a comment line
                            fputs( $outfile, $line );
                        }
                        else {
                                     //  Process anything else
                            $record = explode( ',', trim($line) );
                            $tempdiv = substr( $record[5], 1, 3 );
                            if ( array_key_exists( 'all', $limit_list ) ) { $tempdiv = 'all'; }
                            if ( array_key_exists( 'none', $limit_list ) && in_array( $record[0], $array_of_keys ) && $record[8] == "" ) {
                                      //  No limits in place - Game number match - game not taken
                                $record[8] = $this->rep;
                                $line = implode( ',', $record )."\n";
                                $html .= "<p>You have <strong>scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
                            }
                            elseif ( in_array( $record[0], $array_of_keys ) && $record[8] == "" && $games_now[ $tempdiv ] < $limit_list[ $tempdiv ] ) {
                                      //  Game number match - game not taken - below limit
                                $record[8] = $this->rep;
                                $no_posted[$tempdiv]++;
                                $games_now[$tempdiv]++;
                                $line = implode( ',', $record )."\n";
                                $html .= "<p>You have <strong>scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
                            }
                            elseif ( in_array( $record[0], $array_of_keys ) && $record[8] == "" && $games_now[ $tempdiv ] >= $limit_list[ $tempdiv ] ) {
                                     //   Game number match - game not taken - at or over limit
                                $line = implode( ',', $record )."\n";
                                $html .= "<p>You have <strong>not scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3] because you are at your game limit!</p>\n";
                            }
                            elseif ( !in_array( $record[0], $array_of_keys ) && $record[8] == $this->rep && !$locked ) {
                                     //   No game number match - game was reserved - no locked - game to be removed
                               $record[8] = "";
                               $record[9] = "";
                               $record[10] = "";
                               $record[11] = "";
                               $games_now[$tempdiv]--;
                               $html .= "<p>You have <strong>removed</strong> your referee team from Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
                               $line = implode( ',', $record )."\n";
                            }
                            elseif ( array_key_exists( 'none', $limit_list ) && in_array( $record[0], $array_of_keys ) && $record[8] == $this->rep ) {
                                $line = implode( ',', $record )."\n";
                            }
                            elseif ( in_array( $record[0], $array_of_keys ) && $record[8] == $this->rep && $games_now[ $tempdiv ] < $limit_list[ $tempdiv ]) {
                                $no_posted[ $tempdiv ]++;
                                $line = implode( ',', $record )."\n";
                            }
        //                    elseif ( in_array( $record[0], $array_of_keys ) && $record[8] == $this->rep && !$locked && $games_now[ $tempdiv] >= $limit_list[ $tempdiv ]) {
        //                       $record[8] = "";
        //                       $record[9] = "";
        //                       $record[10] = "";
        //                       $record[11] = "";
        //                       $games_now[$tempdiv]--;
        //                       $html .= "<p>Your referee team has been <strong>removed</strong> from Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3] because you are over the game limit.</p>\n";
        //                       $line = implode( ',', $record )."\n";
        //                    }
                            elseif ( in_array( $record[0], $array_of_keys ) && $record[8] != $this->rep && $record[8] != "") {
                                $html .= "<p>I'm sorry, game no. $record[0] has been taken.</p>";
                                $line = implode( ',', $record )."\n";
                            }
                            elseif ( $record[8] == $this->rep ) {
                                $no_posted[ $tempdiv ]++;
                                $line = implode( ',', $record )."\n";
                            }
                            else {
                                $line = implode( ',', $record )."\n";
                            }
        //                    $html .= "<li>$line</li>\n";
                            fputs( $outfile, $line );
                        }
                    }
      //              $html .= "</ul>";
                    fclose ( $tmpfile );
                    flock( $outfile, LOCK_UN );
                }
                fclose ( $outfile );
                
                $any_games = 0;
                $fp = fopen( $schedule_file, "r" );
                while ( $line = fgets( $fp, 1024 ) ) {
                    if ( substr( $line, 0, 1 ) != '#' ) {
                        $record = explode( ',', trim($line) );
                        if ( $record[8] == $this->rep ) {
                            if ( !$any_games ) {
                                 $html .= "<center><h2>You are currently scheduled for the following games</h2></center>\n";
                                    $html .= "      <table width=\"100%\">\n";
                                    $html .= "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                                    $html .= "            <th>Game No.</th>";
                                    $html .= "            <th>Day</th>";
                                    $html .= "            <th>Time</th>";
                                    $html .= "            <th>Location</th>";
                                    $html .= "            <th>Div</th>";
                                    $html .= "            <th>Home</th>";
                                    $html .= "            <th>Away</th>";
                                    $html .= "            <th>Referee<br>Team</th>";
                                    $html .= "            </tr>\n";
                                    $any_games = 1;
                            }
                            $html .= "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
                            $html .= "            <td>$record[0]</td>";
                            $html .= "            <td>$record[2]<br>$record[1]</td>";
                            $html .= "            <td>$record[4]</td>";
                            $html .= "            <td>$record[3]</td>";
                            $html .= "            <td>$record[5]</td>";
                            $html .= "            <td>$record[6]</td>";
                            $html .= "            <td>$record[7]</td>";
                            $html .= "            <td>$record[8]</td>";
                            $html .= "            </tr>\n";
                        }
                    }
                }
                if ( $any_games ) {
                  $html .= "      </table>\n";
                }
                fclose( $fp );
            }
            else {
                copy( $schedule_file, $this->refdata . "temp.dat");
                $outfile = fopen( $schedule_file, "w");
                if (flock( $outfile, LOCK_EX )) {
    //              $html .= "<p>Got lock</p>\n<ul>\n";
                $tmpfile = fopen( $this->refdata . "temp.dat", "r");
                $sched_no = fgets( $tmpfile, 1024 );
                fputs( $outfile, $sched_no );
                $sched_title = fgets( $tmpfile, 1024 );
                fputs( $outfile, $sched_title );
                $this->page_title = substr( $sched_title, 1);
    
                while ( $line = fgets( $tmpfile, 1024 ) ) {
                    if ( substr( $line, 0, 1 ) == '#' ) {
                       fputs( $outfile, $line );
                    }
                    else {
                        $record = explode( ',', trim($line) );
                        if ( $record[8] == $this->rep ) {
                            $record[8] = "";
                            $record[9] = "";
                            $record[10] = "";
                            $record[11] = "";
                            $html .= "<p>You have <strong>removed</strong> your referee team from Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
                            $line = implode( ',', $record )."\n";
                        }
      //                    $html .= "<li>$line</li>\n";
                        fputs( $outfile, $line );
                    }
                }
    //              $html .= "</ul>";
                fclose ( $tmpfile );
                flock( $outfile, LOCK_UN );
             }
             fclose ( $outfile );
             $html .= "<center><h2>You do not currently have any games scheduled.</h2></center>\n";
           }
        }
        else {
            $html .= $this->errorCheck();
        }
      
        return $html;
          
    }
    private function menu()
    {
        $html = 
<<<EOD
      <h3 align="center"><a href="/greet">Return to main page</a>&nbsp;-&nbsp;
      <a href="/sched">Return to schedule</a>&nbsp;-&nbsp;
      <a href="/end">Logoff</a></h3>
EOD;
        return $html;   
    }
}


