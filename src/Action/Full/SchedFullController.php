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
            'sched' => array (
                'full' => $this->renderFull(),
                'event' => array (
                    'name' => 'Western States Championships, Carson City, NV',
                    'date' => 'March 25-26, 2016'
                )
            )
        );        
        
        $this->view->render($response, 'sched.full.html.twig', $content);
;
    }

    private function renderFull()
    {
        $html = null;
        
        $authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        
  //         $html .=  "$authed";    //debug
        $rep = $_SESSION['unit'];
        $schedule_file = $_SESSION['eventfile'];

        if ( $authed ) {
             $infile = fopen( $schedule_file, "r");
             $sched_no = fgets( $infile, 1024 );
             $sched_title = fgets( $infile, 1024 );
             $page_title = substr( $sched_title, 1);
  
             $html = "<center><h1>$page_title</h1></center>";
  
             $html .=  "      <table width=\"100%\">\n";
             $html .=  "        <tr align=\"center\">";
             $html .=  "            <td>Game No.</td>";
             $html .=  "            <td>Day</td>";
             $html .=  "            <td>Time</td>";
             $html .=  "            <td>Location</td>";
             $html .=  "            <td>Div</td>";
             $html .=  "            <td>Home</td>";
             $html .=  "            <td>Away</td>";
             $html .=  "            <td>Referee<br>Team</td>";
             $html .=  "            </tr>\n";
             while ( $line = fgets( $infile, 1024 ) ) {
                if ( substr( $line, 0, 1 ) != '#' ) {
                   $record = explode( ',', $line );
                   if ( $record[8] == $rep ) {
                      $html .=  "            <tr align=\"center\" bgcolor=\"#FF8888\">";
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
                      $html .=  "            <tr align=\"center\" bgcolor=\"#00FFFF\">";
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
                      $html .=  "            <tr align=\"center\" bgcolor=\"#00FF88\">";
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
            $html .=  "      <h3 align=\"center\"><a href=\"greet\">Return to main screen</a>&nbsp;-&nbsp\n";
             if ( $rep == 'Section 1' ) {
                $html .=  "      <a href=\"/master\">Return to schedule</a>&nbsp;-&nbsp\n";
             }
             else {
                $html .=  "      <a href=\"/sched\">Return to schedule</a>&nbsp;-&nbsp\n";
             }
            $html .=  "      <a href=\"/end.php\">Logoff</a></h3>\n";
        }
        elseif ( !$authed ) {
           $html .=  "<center><h2>You need to <a href=\"/\">logon</a> first.</h2></center>";
        }
        else {
           $html .=  "<center><h1>Something is not right</h1></center>";
        }    

        return $html;
          
    }
}


