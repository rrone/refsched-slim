<?php
namespace App\Action\Refs;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedRefsController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule refs page action dispatched");
        
        $content = array(
            'view' => array (
                'content' => $this->renderRefs(),
                'menu' => $this->menu(),
                'title' => $this->page_title
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);

    }

    private function renderRefs()
    {
        $html = null;
        
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        $locked = isset($_SESSION['locked']) ? $_SESSION['locked'] : null;
        $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;
  
        if ( $this->authed ) {
             $any_games = 0;
             $fp = fopen( $schedule_file, "r" );
             $sched_no = fgets( $fp, 1024 );
             $sched_title = fgets( $fp, 1024 );
             $this->page_title = substr( $sched_title, 1);

             while ( $line = fgets( $fp, 1024 ) ) {
                if ( substr( $line, 0, 1 ) != '#' ) {
                   $record = explode( ',', trim($line) );
                   if ( $record[8] == $this->rep || $this->rep == 'Section 1') {
                      if ( !$any_games ) {
                         if ( $this->rep != 'Section 1') { $html .=  "<center><h2>You are currently scheduled for the following games</h2></center>\n"; }
                         $html .=  "      <form name=\"addref\" method=\"post\" action=\"$this->editrefPath\">\n";
                         $html .=  "      <table width=\"100%\">\n";
                         $html .=  "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                         $html .=  "            <th>Game<br>No.</th>";
                         $html .=  "            <th>Day</th>";
                         $html .=  "            <th>Time</th>";
                         $html .=  "            <th>Location</th>";
                         $html .=  "            <th>Division</th>";
                         $html .=  "            <th>Area</th>";
                         $html .=  "            <th>CR</th>";
                         $html .=  "            <th>AR1</th>";
                         $html .=  "            <th>AR2</th>";
                         $html .=  "            <th>4th</th>";
                         $html .=  "            <th>&nbsp;</th>";
                         $html .=  "            </tr>\n";
                         $any_games = 1;
                      }
                      if ( !$record[8] && $this->rep == 'Section 1' ) {
                         $html .=  "            <tr align=\"center\" bgcolor=\"$this->colorOpen\">";
                      }
                      else {
                         $html .=  "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
                      }
                      $html .=  "            <td>$record[0]</td>";
                      $html .=  "            <td>$record[2]<br>$record[1]</td>";
                      $html .=  "            <td>$record[4]</td>";
                      $html .=  "            <td>$record[3]</td>";
                      $html .=  "            <td>$record[5]</td>";
                      $html .=  "            <td>$record[8]</td>";
                      $html .=  "            <td>$record[9]</td>";
                      $html .=  "            <td>$record[10]</td>";
                      $html .=  "            <td>$record[11]</td>";
                      $html .=  "            <td>$record[12]</td>";
                      if ( $record[8] ) {
                         $html .=  "            <td><input type=\"submit\" name=\"game$record[0]\" value=\"Edit Refs\"></td>";
                      }
                      else {
                         $html .=  "            <td>&nbsp;</td>\n";
                      }
                      $html .=  "            </tr>\n";
                   }
                }
             }
             if ( $any_games ) {
               $html .=  "      </table>\n";
               $html .=  "      </form>\n";
             }
             fclose( $fp );
             if (!$any_games ) {
                $html .=  "<center><h2>You do not currently have any games scheduled.</h2>\n";
                $html .=  "  You should go to the <a href=\"$this->schedPath\">Schedule Page</a></h2></center>";
             }
        }
        elseif ( !$this->authed ) {
           $html .=  $this->errorCheck();
        }
  
        return $html;
          
    }
    private function menu()
    {
        $html =  "<h3 align=\"center\"><a href=\"$this->greetPath\">Go to main page</a>&nbsp;-&nbsp;\n";

        if ( $this->rep == 'Section 1' ) {
           $html .=  "<a href=\"$this->masterPath\">Go to schedule</a>&nbsp;-&nbsp;\n";
        }
        else {
           $html .=  "<a href=\"$this->schedPath\">Go to schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .=  "<a href=\"$this->endPath\">Logoff</a></h3>\n";
      
        return $html;
    }
}


