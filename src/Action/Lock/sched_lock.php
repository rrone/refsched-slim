<?php session_start();
   header("Cache-control: private");
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
      if ( $authed && $rep == 'Section 1') {
         $lock_yes = 0;
//         print_r($_POST);
         copy( $schedule_file, "refdata/temp_lock.dat");
         $outfile = fopen( $schedule_file, "w");
         if (flock( $outfile, LOCK_EX )) {
            $tmpfile = fopen( "refdata/temp_lock.dat", "r");
            $sched_no = fgets( $tmpfile, 1024 );
            fputs( $outfile, $sched_no );
            $sched_title = fgets( $tmpfile, 1024 );
            fputs( $outfile, $sched_title );
            $page_title = substr( $sched_title, 1);

            echo "<center><h1>$page_title</h1></center>";
            $lock_line = "#Locked\n";
            fputs( $outfile, $lock_line );

            while ( $line = fgets( $tmpfile, 1024 ) ) {
               if ( substr( $line, 0, 1 ) == '#' ) {
                  if ( strtoupper( trim( $line ) ) == '#LOCKED' ) {
                     $lock_yes = 1;
                  }
                  else {
                     fputs( $outfile, $line );
                  }
               }
               else {
                  fputs( $outfile, $line );
               }
            }
            fclose ( $tmpfile );
            flock( $outfile, LOCK_UN );
         }
         fclose ( $outfile );
         if ( $lock_yes ) {
            echo "<h2 align=\"center\">The schedule was already locked!</h2>\n";
         }
         else {
            echo "<h2 align=\"center\">The schedule has been locked!</h2>\n";
         }
         echo "<h2 align=\"center\"><a href=\"sched_greet.php\">Return to the main page</a></h2>\n";
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
