<?php
namespace App\Action\Control;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedControlController extends AbstractController
{
    private $url_ref;
    
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule control page action dispatched");
        
        $this->url_ref = $this->masterPath;
        
        $content = array(
            'view' => array (
                'content' => $this->renderControl(),
                'menu' => $this->menu(),
                'title' => $this->page_title
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);

    }

    private function renderControl()
    {
        $html = null;
        
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_HOST'];
        $from_url = parse_url( $referer );
        $from = $from_url['path'];

  //      $html .= "<p>$from</p>\n";
        
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        $schedule_file = isset($_SESSION['eventfile']) ? $_SESSION['eventfile'] : null;

        if ( $this->authed && $_SERVER['REQUEST_METHOD'] == 'POST' && $from == $this->url_ref  && $this->rep == 'Section 1') {
  //         print_r($_POST);
            copy( $schedule_file, $this->refdata . "tempsec.dat");
            $outfile = fopen( $schedule_file, "w");
            if (flock( $outfile, LOCK_EX )) {
   //            $html .= "<p>Got lock</p>\n<ul>\n";
                $tmpfile = fopen( $this->refdata . "tempsec.dat", "r");
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
                        $list_key = "area" . trim( $record[0] );
                        if ( array_key_exists( $list_key, $_POST ) ) {
                            if ( $_POST[ $list_key ] == "None" ) {
                               $record[8] = "";
                               $record[9] = "";
                               $record[10] = "";
                               $record[11] = "";
                            }
                            elseif ( $_POST[ $list_key ] != $record[8] ) {
                               $record[8] = $_POST[ $list_key ];
                               $record[9] = "";
                               $record[10] = "";
                               $record[11] = "";
                            }
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
                    if ( !$any_games ) {
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
                    if ( $record[8] ) {
                        $html .= "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
                    } 
                    else {
                        $html .= "            <tr align=\"center\" bgcolor=\"$this->colorOpen\">";
                    } 
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
            if ( $any_games ) {
              $html .= "      </table>\n";
            }
            fclose( $fp );
        }
        else {
            $html .= $this->errorCheck();
        }

        return $html;
          
    }
    private function menu()
    {
        $html =
<<<EOT
      <h3 align="center"><a href="$this->greetPath">Return to main page</a>&nbsp;-&nbsp;
      <a href="$this->masterPath">Return to schedule</a>&nbsp;-&nbsp;
      <a href="$this->endPath">Logoff</a></h3>
EOT;
        
        return $html;
    }
}


