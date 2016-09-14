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
            'sched' => array (
                'refs' => $this->renderRefs()
            )
        );        
        
        $this->view->render($response, 'sched.refs.html.twig', $content);
;
    }

    private function renderRefs()
    {
        $html = null;
        
        $authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        
        $rep = $_SESSION['unit'];
        $locked = isset($_SESSION['locked']) ? $_SESSION['locked'] : null;
        $schedule_file = $_SESSION['eventfile'];
  
        if ( $authed ) {
             $any_games = 0;
             $fp = fopen( $schedule_file, "r" );
             $sched_no = fgets( $fp, 1024 );
             $sched_title = fgets( $fp, 1024 );
             $page_title = substr( $sched_title, 1);

             $html = "<center><h1>$page_title</h1></center>";

             while ( $line = fgets( $fp, 1024 ) ) {
                if ( substr( $line, 0, 1 ) != '#' ) {
                   $record = explode( ',', trim($line) );
                   if ( $record[8] == $rep || $rep == 'Section 1') {
                      if ( !$any_games ) {
                         if ( $rep != 'Section 1') { $html .=  "<center><h2>You are currently scheduled for the following games</h2></center>\n"; }
                         $html .=  "      <form name=\"addref\" method=\"post\" action=\"/editref\">\n";
                         $html .=  "      <table width=\"100%\">\n";
                         $html .=  "        <tr align=\"center\">";
                         $html .=  "            <td>Game<br>No.</td>";
                         $html .=  "            <td>Day</td>";
                         $html .=  "            <td>Time</td>";
                         $html .=  "            <td>Location</td>";
                         $html .=  "            <td>Div</td>";
                         $html .=  "            <td>Area</td>";
                         $html .=  "            <td>CR</td>";
                         $html .=  "            <td>AR1</td>";
                         $html .=  "            <td>AR2</td>";
                         $html .=  "            <td>4thO</td>";
                         $html .=  "            <td>&nbsp;</td>";
                         $html .=  "            </tr>\n";
                         $any_games = 1;
                      }
                      if ( !$record[8] && $rep == 'Section 1' ) {
                         $html .=  "            <tr align=\"center\" bgcolor=\"#00FFFF\">";
                      }
                      else {
                         $html .=  "            <tr align=\"center\" bgcolor=\"#00FF88\">";
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
                $html .=  "  You should go to the <a href=\"/sched\">Schedule Page</a></h2></center>";
             }
        }
        elseif ( !$authed ) {
           $html .=  "<center><h2>You need to <a href=\"/\">logon</a> first.</h2></center>";
        }
        else {
           $html .=  "<center><h1>Something is not right</h1></center>";
        }
  
        $html .=  "<h3 align=\"center\"><a href=\"/greet\">Return to main screen</a>&nbsp;-&nbsp;\n";

        if ( $rep == 'Section 1' ) {
           $html .=  "<a href=\"/master\">Return to schedule</a>&nbsp;-&nbsp;\n";
        }
        else {
           $html .=  "<a href=\"/sched\">Return to schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .=  "<a href=\"/end\">Logoff</a></h3>\n";
      
        return $html;
          
    }
}


