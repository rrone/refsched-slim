<?php
namespace App\Action\Master;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedMasterController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule master page action dispatched");
        
        $content = array(
            'view' => array (
                'content' => $this->renderMaster(),
                'menu' => $this->menu(),
                'title' => $this->page_title
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);
    }

    private function renderMaster()
    {
        $html = null;
        
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;
        $select_list = array( "None", "Area 1B", "Area 1C", "Area 1D", "Area 1F", "Area 1G", "Area 1H", "Area 1N", "Area 1P", "Area 1R", "Area 1S", "Area 1U", "Section One", "Section 2", "Section 10", "Section 11", "Other" );

        if ( $this->authed && $this->rep == 'Section 1' ) {
             $infile = fopen( $schedule_file, "r");
             $sched_no = fgets( $infile, 1024 );
             $sched_title = fgets( $infile, 1024 );
             $this->page_title = substr( $sched_title, 1);
  
             $html .=  "  <form name=\"master_sched\" method=\"post\" action=\"/control\">\n";
             $html .=  "      <input class=\"right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
             $html .=  "      <div class='clear-fix'></div>";
  
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
                   if ( $record[8] == "" ) {
                      $html .=  "            <tr align=\"center\" bgcolor=\"$this->colorOpen\">";
                      $html .=  "            <td>$record[0]</td>";
                      $html .=  "            <td>$record[2]<br>$record[1]</td>";
                      $html .=  "            <td>$record[4]</td>";
                      $html .=  "            <td>$record[3]</td>";
                      $html .=  "            <td>$record[5]</td>";
                      $html .=  "            <td>$record[6]</td>";
                      $html .=  "            <td>$record[7]</td>";
                      $html .=  "            <td><select name=\"area$record[0]\">\n";
  //                    $html .=  "            <td><select name=\"$record[0]\">\n";
                      $html .=  "               <option selected>None</option>\n";
                      $html .=  "               <option>Area 1B</option>\n";
                      $html .=  "               <option>Area 1C</option>\n";
                      $html .=  "               <option>Area 1D</option>\n";
                      $html .=  "               <option>Area 1F</option>\n";
                      $html .=  "               <option>Area 1G</option>\n";
                      $html .=  "               <option>Area 1H</option>\n";
                      $html .=  "               <option>Area 1N</option>\n";
                      $html .=  "               <option>Area 1P</option>\n";
                      $html .=  "               <option>Area 1R</option>\n";
                      $html .=  "               <option>Area 1S</option>\n";
                      $html .=  "               <option>Area 1U</option>\n";
                      $html .=  "               <option>Section One</option>\n";
                      $html .=  "               <option>Section 2</option>\n";
                      $html .=  "               <option>Section 10</option>\n";
                      $html .=  "               <option>Section 11</option>\n";
                      $html .=  "               <option>Other</option>\n";
                      $html .=  "            </select></td>";
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
                      $html .=  "            <td><select name=\"area$record[0]\">\n";
                      foreach ( $select_list as $value ) {
                      $html .=  "               <option";
                      if ( $value == $record[8] ) { $html .=  " selected"; }
                      $html .=  ">$value</option>\n";
                      }
                      $html .=  "            </select></td>\n";
                      $html .=  "            </tr>\n";
                   }
                }
             }
             $html .=  "      </table>\n";
             $html .=  "      <input class=\"right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
             $html .=  "      <div class='clear-fix'></div>";
             $html .=  "      </form>\n";
             fclose( $infile );
        }
        elseif ( $this->authed ) {
           $html .=  "<center><h2>You probably want the <a href=\"/sched\">scheduling</a> page.</h2></center>";
        }
        elseif ( !$this->authed ) {
           $html .=  $this->errorCheck();
        }
      
        return $html;
          
    }
    private function menu()
    {
        $html =  "<h3 align=\"center\"><a href=\"/greet\">Return to main page</a>&nbsp;-&nbsp;\n";

        if ( $this->rep == 'Section 1' ) {
           $html .=  "<a href=\"/master\">Return to schedule</a>&nbsp;-&nbsp;\n";
        }
        else {
           $html .=  "<a href=\"/sched\">Return to schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .=  "<a href=\"/end\">Logoff</a></h3>\n";
      
        return $html;

    }
}


