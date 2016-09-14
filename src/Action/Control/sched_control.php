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
      if ( $authed && $_SERVER['REQUEST_METHOD'] == 'POST' && $from == $url_ref  && $rep == 'Section 1') {
//         print_r($_POST);
         copy( $schedule_file, "refdata/tempsec.dat");
         $outfile = fopen( $schedule_file, "w");
         if (flock( $outfile, LOCK_EX )) {
//            echo "<p>Got lock</p>\n<ul>\n";
            $tmpfile = fopen( "refdata/tempsec.dat", "r");
            $sched_no = fgets( $tmpfile, 1024 );
            fputs( $outfile, $sched_no );
            $sched_title = fgets( $tmpfile, 1024 );
            fputs( $outfile, $sched_title );
            $page_title = substr( $sched_title, 1);

            echo "<center><h1>$page_title</h1></center>";

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
//                  echo "<li>$line</li>\n";
                  fputs( $outfile, $line );
               }
            }
//            echo "</ul>";
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
                  echo "<center><h2>Here is the current scheduled</h2></center>\n";
                  echo "      <table width=\"100%\">\n";
                  echo "        <tr align=\"center\">";
                  echo "            <td>Game No.</td>";
                  echo "            <td>Day</td>";
                  echo "            <td>Time</td>";
                  echo "            <td>Location</td>";
                  echo "            <td>Div</td>";
                  echo "            <td>Home</td>";
                  echo "            <td>Away</td>";
                  echo "            <td>Referee<br>Team</td>";
                  echo "            </tr>\n";
                  $any_games = 1;
               }
               if ( $record[8] ) {
                  echo "            <tr align=\"center\" bgcolor=\"#00FF88\">";
               } 
               else {
                  echo "            <tr align=\"center\" bgcolor=\"#00FFFF\">";
               } 
               echo "            <td>$record[0]</td>";
               echo "            <td>$record[2]<br>$record[1]</td>";
               echo "            <td>$record[4]</td>";
               echo "            <td>$record[3]</td>";
               echo "            <td>$record[5]</td>";
               echo "            <td>$record[6]</td>";
               echo "            <td>$record[7]</td>";
               echo "            <td>$record[8]</td>";
               echo "            </tr>\n";
            }
         }
         if ( $any_games ) {
           echo "      </table>\n";
         }
         fclose( $fp );
      }
      elseif ( $authed && $rep == 'Section 1') {
         echo "<center><h2>You seem to have gotten here by a different path<br>\n";
         echo "You should go to the <a href=\"sched_master.php\">Schedule Page</a></h2></center>";
      }
      elseif ( $authed ) {
         echo "<center><h2>You seem to have gotten here by a different path<br>\n";
         echo "You should go to the <a href=\"sched_sched.php\">Schedule Page</a></h2></center>";
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
