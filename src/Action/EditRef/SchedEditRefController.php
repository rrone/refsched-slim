<?php
namespace App\Action\EditRef;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedEditRefController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule greet page action dispatched");
        
        $content = array(
            'view' => array (
                'content' => $this->renderEditRef(),
                'menu' => $this->menu(),
                'title' => $this->page_title
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);
;
    }
    private function renderEditRef()
    {
        $html = null;
        
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_HOST'];
        $from_url = parse_url( $referer );
        
        $from = $from_url['path'];
  //      echo "<p>$from</p>\n";

        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;

        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;
        $game_found = 0;
        if ( $this->authed && $_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) == 1) {
//           print_r($_POST);
            $key = array_keys( $_POST );
            $target_game = substr( $key[0], 4 );
   //         echo "<p>$key[0] $target_game</p>\n";
            $fp = fopen( $schedule_file, "r" );
            $sched_no = fgets( $fp, 1024 );
            $sched_title = fgets( $fp, 1024 );
            $this->page_title = substr( $sched_title, 1);
   
            $html .=  "<center><h2>Adding or editing referees for game number $target_game.</h2></center>";
   
            while ( $line = fgets( $fp, 1024 ) ) {
                if ( substr( $line, 0, 1 ) != '#' ) {
                    $record = explode( ',', trim($line) );
                    if ( $record[0] == $target_game && ($record[8] == $this->rep || $this->rep == 'Section 1') ) {
                    $html .=  "      <form name=\"editref\" method=\"post\" action=\"/addref\">\n";
                    $html .=  "      <table width=\"100%\">\n";
                    $html .=  "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                    $html .=  "            <th>Game<br>No.</th>";
                    $html .=  "            <th>Day</th>";
                    $html .=  "            <th>Time</th>";
                    $html .=  "            <th>Location</th>";
                    $html .=  "            <th>Div</th>";
                    $html .=  "            <th>Referee<br>Team</th>";
                    $html .=  "            <th>Center</th>";
                    $html .=  "            <th>AR1</th>";
                    $html .=  "            <th>AR2</th>";
                    $html .=  "            <th>4thO</th>";
                    $html .=  "            </tr>\n";
                    $html .=  "            <tr align=\"center\" bgcolor=\"#00FF88\">";
                    $html .=  "            <td>$record[0]</td>";
                    $html .=  "            <td>$record[2]<br>$record[1]</td>";
                    $html .=  "            <td>$record[4]</td>";
                    $html .=  "            <td>$record[3]</td>";
                    $html .=  "            <td>$record[5]</td>";
                    $html .=  "            <td>$record[8]</td>";
                    $html .=  "            <td><input type=\"text\" name=\"center\" size=\"15\" value=\"$record[9]\"></td>";
                    $html .=  "            <td><input type=\"text\" name=\"ar1\" size=\"15\" value=\"$record[10]\"></td>";
                    $html .=  "            <td><input type=\"text\" name=\"ar2\" size=\"15\" value=\"$record[11]\"></td>";
                    $html .=  "            <td><input type=\"text\" name=\"4thO\" size=\"15\" value=\"$record[12]\"></td>";
                    $html .=  "            </tr>\n";
                    $html .=  "            </table>\n";
                    $html .=  "            <input type=\"submit\" name=\"update$record[0]\" value=\"Update Referees\">\n";
                    $html .=  "            </form>\n";
                    $game_found = 1;
                }
            }
        }
        
        if ( !$game_found ) {
            $html .= "<center><h2>The matching game was not found or your Area was not assigned to it.<br>You might want to check the schedule and try again.</h2></center>\n";
        }
        fclose ( $fp );

        }
        elseif ( $this->authed ) {
           $html .= "<center><h2>You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"/refs\">Referee Edit Page</a></h2></center>";
        }
        elseif ( !$this->authed ) {
           $html .= $this->errorCheck();
        }
      
        return $html;
    
    }
    private function menu()
    {
        $html =
<<<EOD
      <h3 align="center"><a href="/greet">Return to main page</a>&nbsp;-&nbsp;
      <a href="/master">Return to schedule</a>&nbsp;-&nbsp;
      <a href="/end">Logoff</a></h3>
EOD;
        
        return $html;
    }
}


