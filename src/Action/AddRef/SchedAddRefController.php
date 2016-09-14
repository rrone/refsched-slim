<?php
namespace App\Action\AddRef;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedAddRefController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule addref page action dispatched");
        
        $content = array(
            'sched' => array (
                'addref' => $this->renderAddRef()
            )
        );        
        
        $this->view->render($response, 'sched.greet.html.twig', $content);

    }

    private function renderAddRef()
    {
        $html = null;
        
        $from_url = parse_url( $_SERVER['HTTP_REFERER']);
        $from = $from_url['path'];
   //      $html .= "<p>$from</p>\n";
   
        $authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        
        $rep = $_SESSION['unit'];
        $schedule_file = $_SESSION['eventfile'];
        $value = count( $_POST );
        
        if ( $authed && $_SERVER['REQUEST_METHOD'] == 'POST' && count( $_POST ) == 5 ) {
   //        print_r($_POST);
   //        $html .= "<p>$value</p>\n";
            copy( $schedule_file, "refdata/temprefs.dat");
            $outfile = fopen( $schedule_file, "w");
            if (flock( $outfile, LOCK_EX )) {
   //            $html .= "<p>Got lock</p>\n<ul>\n";
               $tmpfile = fopen( "refdata/temprefs.dat", "r");
               $sched_no = fgets( $tmpfile, 1024 );
               fputs( $outfile, $sched_no );
               $sched_title = fgets( $tmpfile, 1024 );
               fputs( $outfile, $sched_title );
               $page_title = substr( $sched_title, 1);
   
               $html .= "<center><h1>$page_title</h1></center>";
   
               while ( $line = fgets( $tmpfile, 1024 ) ) {
                  if ( substr( $line, 0, 1 ) == '#' ) {
                     fputs( $outfile, $line );
                  }
                  else {
                     $record = explode( ',', trim($line) );
                     $list_key = "update" . trim( $record[0] );
                     if ( array_key_exists( $list_key, $_POST ) && ($record[8] == $rep || $rep == 'Section 1') ) {
                           $record[9] = $_POST[ 'center' ];
                           $record[10] = $_POST[ 'ar1' ];
                           $record[11] = $_POST[ 'ar2' ];
                           $record[12] = $_POST[ '4thO' ];
                     }
                     elseif ( array_key_exists( $list_key, $_POST ) && $record[8] != $rep ) {
                        $html .= "<center><h2>Sorry, you are not currently assigned to game number $record[0]</h2></center>\n";
                     }
                     $line = implode( ',', $record )."\n";
   //                  $html .= "<li>$line</li>\n";
                     fputs( $outfile, $line );
                  }
               }
   //            $html .= "</ul>";
               fclose ( $tmpfile );
               flock( $outfile, LOCK_UN );
            }
            fclose ( $outfile );
            $any_games = 0;
            $fp = fopen( $schedule_file, "r" );
            while ( $line = fgets( $fp, 1024 ) ) {
               if ( substr( $line, 0, 1 ) != '#' ) {
                  $record = explode( ',', trim($line) );
                  if ( !$any_games && ( $rep == 'Section 1' || $rep == $record[8] )) {
                     $html .= "<center><h2>Here are the current assignments</h2></center>\n";
                     $html .= "      <table width=\"100%\">\n";
                     $html .= "        <tr align=\"center\">";
                     $html .= "            <td>Game No.</td>";
                     $html .= "            <td>Day</td>";
                     $html .= "            <td>Time</td>";
                     $html .= "            <td>Location</td>";
                     $html .= "            <td>Div</td>";
                     $html .= "            <td>Ref<br>Team</td>";
                     $html .= "            <td>Center</td>";
                     $html .= "            <td>AR1</td>";
                     $html .= "            <td>AR2</td>";
                     $html .= "            <td>4thO</td>";
                     $html .= "            </tr>\n";
                     $any_games = 1;
                  }
                  if ( $record[8] == $rep || ( $record[8] && $rep == 'Section 1') ) {
                     $html .= "            <tr align=\"center\" bgcolor=\"#00FF88\">";
                  } 
                  elseif ( $rep == 'Section 1' ) {
                     $html .= "            <tr align=\"center\" bgcolor=\"#00FFFF\">";
                  } 
                  if ( $record[8] == $rep || $rep == 'Section 1' ) {
                     $html .= "            <td>$record[0]</td>";
                     $html .= "            <td>$record[2]<br>$record[1]</td>";
                     $html .= "            <td>$record[4]</td>";
                     $html .= "            <td>$record[3]</td>";
                     $html .= "            <td>$record[5]</td>";
                     $html .= "            <td>$record[8]</td>";
                     $html .= "            <td>$record[9]</td>";
                     $html .= "            <td>$record[10]</td>";
                     $html .= "            <td>$record[11]</td>";
                     $html .= "            <td>$record[12]</td>";
                     $html .= "            </tr>\n";
                  }
               }
            }
            if ( $any_games ) {
              $html .= "      </table>\n";
            }
            fclose( $fp );
        }
        elseif ( $authed && $rep == 'Section 1') {
           $html .= "<center><h2>You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"/master\">Schedule Page</a></h2></center>";
        }
        elseif ( $authed ) {
           $html .= "<center><h2>You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"/sched\">Schedule Page</a></h2></center>";
        }
        elseif ( !$authed ) {
           $html .= "<center><h2>You need to <a href=\"/\">logon</a> first.</h2></center>";
        }
        else {
           $html .= "<center><h1>Something is not right</h1></center>";
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


