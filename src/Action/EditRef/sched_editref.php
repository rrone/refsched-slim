<?php session_start();
   header("Cache-control: private");
   $url_ref = '/sched_master.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html><!-- InstanceBegin template="/Templates/schedule.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<link rel="SHORTCUT ICON" href="http://www.aysosection1.org/favicon.ico">
<link rel="stylesheet" type="text/css" href="/css/refsched.css">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>AYSO Section 1</title>
<meta name="description" content="Welcome to the Home Page of Section 1 of the American Youth Soccer Organizition ( AYSO ) serving Los Angeles, San Bernardino, and Riverside Counties in Southern California.">
<meta name="keywords" content="aysosection1, ayso section 1, ayso, section 1, section one, soccer, youth soccer, los angeles, riverside,san bernardino, american youth soccer organization">

</head>

<body>

<table width="98%"  class="maincontent">
<!-- InstanceBeginEditable name="mainContent" -->
  <tr>
    <td align="center" class="contentarea">
     
<?php
      $from_url = parse_url( $_SERVER['HTTP_REFERER']);
      $from = $from_url['path'];
//      echo "<p>$from</p>\n";
      $authed = $_SESSION['authed'];
      $rep = $_SESSION['unit'];
      $schedule_file = $_SESSION['eventfile'];
      $game_found = 0;
      if ( $authed && $_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) == 1) {
//         print_r($_POST);
         $key = array_keys( $_POST );
         $target_game = substr( $key[0], 4 );
//         echo "<p>$key[0] $target_game</p>\n";
         $fp = fopen( $schedule_file, "r" );
         $sched_no = fgets( $fp, 1024 );
         $sched_title = fgets( $fp, 1024 );
         $page_title = substr( $sched_title, 1);

         echo "<center><h1>$page_title</h1></center>";
         echo "<center><h2>Adding or editing referees for game number $target_game.</h2></center>";

         while ( $line = fgets( $fp, 1024 ) ) {
            if ( substr( $line, 0, 1 ) != '#' ) {
               $record = explode( ',', trim($line) );
               if ( $record[0] == $target_game && ($record[8] == $rep || $rep == 'Section 1') ) {
                  echo "      <form name=\"editref\" method=\"post\" action=\"sched_addref.php\">\n";
                  echo "      <table width=\"100%\">\n";
                  echo "        <tr align=\"center\">";
                  echo "            <td>Game<br>No.</td>";
                  echo "            <td>Day</td>";
                  echo "            <td>Time</td>";
                  echo "            <td>Location</td>";
                  echo "            <td>Div</td>";
                  echo "            <td>Referee<br>Team</td>";
                  echo "            <td>Center</td>";
                  echo "            <td>AR1</td>";
                  echo "            <td>AR2</td>";
                  echo "            <td>4thO</td>";
                  echo "            </tr>\n";
                  echo "            <tr align=\"center\" bgcolor=\"#00FF88\">";
                  echo "            <td>$record[0]</td>";
                  echo "            <td>$record[2]<br>$record[1]</td>";
                  echo "            <td>$record[4]</td>";
                  echo "            <td>$record[3]</td>";
                  echo "            <td>$record[5]</td>";
                  echo "            <td>$record[8]</td>";
                  echo "            <td><input type=\"text\" name=\"center\" size=\"15\" value=\"$record[9]\"></td>";
                  echo "            <td><input type=\"text\" name=\"ar1\" size=\"15\" value=\"$record[10]\"></td>";
                  echo "            <td><input type=\"text\" name=\"ar2\" size=\"15\" value=\"$record[11]\"></td>";
                  echo "            <td><input type=\"text\" name=\"4thO\" size=\"15\" value=\"$record[12]\"></td>";
                  echo "            </tr>\n";
                  echo "            </table>\n";
                  echo "            <input type=\"submit\" name=\"update$record[0]\" value=\"Update Referees\">\n";
                  echo "            </form>\n";
                  $game_found = 1;
               }
            }
        }
        if ( !$game_found ) {
            echo "<center><h2>The matching game was not found or your Area was not assigned to it.<br>You might want to check the schedule and try again.</h2></center>\n";
        }
        fclose ( $fp );

      }
      elseif ( $authed ) {
         echo "<center><h2>You seem to have gotten here by a different path<br>\n";
         echo "You should go to the <a href=\"sched_refs.php\">Referee Edit Page</a></h2></center>";
      }
      elseif ( !$authed ) {
         echo "<center><h2>You need to <a href=\"index.htm\">logon</a> first.</h2></center>";
      }
      else {
         echo "<center><h1>Something is not right</h1></center>";
      }

?>
      <h3 align="center"><a href="sched_greet.php">Return to main screen</a>&nbsp;-&nbsp;
      <a href="sched_master.php">Return to schedule</a>&nbsp;-&nbsp;
      <a href="sched_end.php">Logoff</a></h3>
</tr>
  <!-- InstanceEndEditable -->
</table>
<p align="center"><span class="foottext"><a href="index.htm">Home</a> - <a href="section_staff.htm">Contact&nbsp;Us</a>  - <a href="siteindex.htm" class="flinks">Site&nbsp;Index<br>
</a>Corrections or additions to this web page can be sent to: <a href="mailto:webmaster@aysosection1.org">webmaster@aysosection1.org</a><br>
&copy;2005  Section one, American Youth Soccer Organization <br />
AYSO name and AYSO initials, Logos and graphics copyright by the <a href="http://www.soccer.org" class="flinks">American Youth Soccer Organization</a> and used with permission.</span></p>
</body>
<!-- InstanceEnd --></html>
