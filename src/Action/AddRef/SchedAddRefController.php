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
            'view' => array (
                'content' => $this->renderAddRef(),
                'title' => $this->page_title,
                'menu' => $this->menu()
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);

    }

    private function renderAddRef()
    {
        $html = null;
        
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_HOST'];
        $from_url = parse_url( $referer );

        $from = $from_url['path'];
   //      $html .= "<p>$from</p>\n";
   
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        
        $this->rep = $_SESSION['unit'];
        $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;
        $value = count( $_POST );
        
        if ( $this->authed && $_SERVER['REQUEST_METHOD'] == 'POST' && count( $_POST ) == 5 ) {
   //        print_r($_POST);
   //        $html .= "<p>$value</p>\n";
            copy( $schedule_file, $this->refdata . "temprefs.dat");
            $outfile = fopen( $schedule_file, "w");
            if (flock( $outfile, LOCK_EX )) {
   //            $html .= "<p>Got lock</p>\n<ul>\n";
               $tmpfile = fopen( $this->refdata . "temprefs.dat", "r");
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
                     $list_key = "update" . trim( $record[0] );
                     if ( array_key_exists( $list_key, $_POST ) && ($record[8] == $this->rep || $this->rep == 'Section 1') ) {
                           $record[9] = $_POST[ 'center' ];
                           $record[10] = $_POST[ 'ar1' ];
                           $record[11] = $_POST[ 'ar2' ];
                           $record[12] = $_POST[ '4th' ];
                     }
                     elseif ( array_key_exists( $list_key, $_POST ) && $record[8] != $this->rep ) {
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
                  if ( !$any_games && ( $this->rep == 'Section 1' || $this->rep == $record[8] )) {
                     $html .= "<center><h2>Current assignments</h2></center>\n";
                     $html .= "      <table width=\"100%\">\n";
                     $html .= "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";   
                     $html .= "            <th>Game No.</th>";
                     $html .= "            <th>Day</th>";
                     $html .= "            <th>Time</th>";
                     $html .= "            <th>Location</th>";
                     $html .= "            <th>Div</th>";
                     $html .= "            <th>Ref<br>Team</th>";
                     $html .= "            <th>Center</th>";
                     $html .= "            <th>AR1</th>";
                     $html .= "            <th>AR2</th>";
                     $html .= "            <th>4th</th>";
                     $html .= "            </tr>\n";
                     $any_games = 1;
                  }
                  if ( $record[8] == $this->rep || ( $record[8] && $this->rep == 'Section 1') ) {
                     $html .= "            <tr align=\"center\" bgcolor=\"#00FF88\">";
                  } 
                  elseif ( $this->rep == 'Section 1' ) {
                     $html .= "            <tr align=\"center\" bgcolor=\"#00FFFF\">";
                  } 
                  if ( $record[8] == $this->rep || $this->rep == 'Section 1' ) {
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
        elseif ( $this->authed && $this->rep == 'Section 1') {
           $html .= "<center><h2>You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"$this->masterPath\">Schedule Page</a></h2></center>";
        }
        elseif ( $this->authed ) {
           $html .= "<center><h2>You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"$this->schedPath\">Schedule Page</a></h2></center>";
        }
        elseif ( !$this->authed ) {
           $html .= $this->errorCheck();
        }  

        return $html;
          
    }
    private function menu()
    {
        $html =  "<h3 align=\"center\"><a href=\"$this->greetPath\">Return to main page</a>&nbsp;-&nbsp;\n";

        if ( $this->rep == 'Section 1' ) {
           $html .=  "<a href=\"$this->masterPath\">Return to schedule</a>&nbsp;-&nbsp;\n";
        }
        else {
           $html .=  "<a href=\"$this->schedPath\">Return to schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .= "<a href=\"$this->refsPath\">Add/Modify Referee assignments</a>&nbsp;-&nbsp;\n";
        
        $html .=  "<a href=\"$this->endPath\">Logoff</a></h3>\n";              

        return $html;
    }
}


