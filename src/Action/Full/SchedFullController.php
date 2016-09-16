<?php
namespace App\Action\Full;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedFullController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule full page action dispatched");
        
        $content = array(
            'view' => array (
                'content' => $this->renderFull(),
                'menu' => $this->menu(),
                'title' => $this ->page_title
            )
        );        

        $this->view->render($response, 'sched.html.twig', $content);
    }

    private function renderFull()
    {
        $html = null;
        
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        
  //         $html .=  "$this->authed";    //debug
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;

        if ( $this->authed ) {
             $infile = fopen( $schedule_file, "r");
             $sched_no = fgets( $infile, 1024 );
             $sched_title = fgets( $infile, 1024 );
             $this->page_title = substr( $sched_title, 1);
  
             $html .=  "      <table width=\"100%\">\n";
             $html .=  "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
             $html .=  "            <th>Game No.</th>";
             $html .=  "            <th>Day</th>";
             $html .=  "            <th>Time</th>";
             $html .=  "            <th>Location</th>";
             $html .=  "            <th>Div</th>";
             $html .=  "            <th>Home</th>";
             $html .=  "            <th>Away</th>";
             $html .=  "            <th>Referee<br>Team</th>";
             $html .=  "         </tr>\n";
             while ( $line = fgets( $infile, 1024 ) ) {
                if ( substr( $line, 0, 1 ) != '#' ) {
                   $record = explode( ',', $line );
                   if ( $record[8] == $this->rep ) {
                      $html .=  "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
                      $html .=  "            <td>$record[0]</td>";
                      $html .=  "            <td>$record[2]<br>$record[1]</td>";
                      $html .=  "            <td>$record[4]</td>";
                      $html .=  "            <td>$record[3]</td>";
                      $html .=  "            <td>$record[5]</td>";
                      $html .=  "            <td>$record[6]</td>";
                      $html .=  "            <td>$record[7]</td>";
                      $html .=  "            <td>$record[8]</td>";
                      $html .=  "            </tr>\n";
                   }
                   elseif ( $record[8] == "" ) {
                      $html .=  "            <tr align=\"center\" bgcolor=\"$this->colorNotGroup\">";
                      $html .=  "            <td>$record[0]</td>";
                      $html .=  "            <td>$record[2]<br>$record[1]</td>";
                      $html .=  "            <td>$record[4]</td>";
                      $html .=  "            <td>$record[3]</td>";
                      $html .=  "            <td>$record[5]</td>";
                      $html .=  "            <td>$record[6]</td>";
                      $html .=  "            <td>$record[7]</td>";
                      $html .=  "            <td>&nbsp;</td>";
                      $html .=  "            </tr>\n";
                   }
                   else {
                      $html .=  "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
                      $html .=  "            <td>$record[0]</td>";
                      $html .=  "            <td>$record[2]<br>$record[1]</td>";
                      $html .=  "            <td>$record[4]</td>";
                      $html .=  "            <td>$record[3]</td>";
                      $html .=  "            <td>$record[5]</td>";
                      $html .=  "            <td>$record[6]</td>";
                      $html .=  "            <td>$record[7]</td>";
                      $html .=  "            <td>$record[8]</td>";
                      $html .=  "            </tr>\n";
                   }
                }
             }
             $html .=  "      </table>\n";
             fclose( $infile );
            
        }
        elseif ( !$this->authed ) {
           $this->menu .=  "<center><h2>You need to <a href=\"$this->logonPath\">logon</a> first.</h2></center>";
        }
        else {
           $this->menu .=  "<center><h1>Something is not right</h1></center>";
        }    

        return $html;
          
    }
    private function menu()
    {
        $html =  "<h3 align=\"center\"><a href=\"greet\">Go to main page</a>&nbsp;-&nbsp\n";

        if ( $this->rep == 'Section 1' ) {
           $html .=  "<a href=\"$this->masterPath\">Go to schedule</a>&nbsp;-&nbsp\n";
        }
        else {
           $html .=  "<a href=\"$this->schedPath\">Go to schedule</a>&nbsp;-&nbsp\n";
        }
        
        $html .=  "<a href=\"$this->endPath\">Logoff</a></h3>\n";
        
        return $html;
    }
}


