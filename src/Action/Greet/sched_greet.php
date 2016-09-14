<?php session_start();
   header("Cache-control: private");
   $url_ref = '/index.htm';
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
     
      <div>
        <div align="center">
          <h1>Section 1 Referee Scheduling</h1>
        </div>
      </div>
<?php
      $from_url = parse_url( $_SERVER['HTTP_REFERER']);
      $from = $from_url['path'];
//      for debugging
      //echo "$from";
      $authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
      if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
         $rep = $_POST['area'];
         
         $pass = crypt( $_POST['passwd'], 11);
//             for debugging
         //echo "<h3>$pass</h3>";
         //$pass = $_POST['passwd'];
         //echo "<h3>$pass</h3>";
         $event = $_POST['event'];
//
//   Be sure the string match fits the event title from from in index.htm
//    And the event schedule file name is correct
//
         if (!$event) {
            $event = 'Upper ';
         }
         if ( substr( $event, 0, 6 ) == 'League' ) {
            $schedule_file = 'refdata/sched160227.dat';
         }
         elseif ( substr( $event, 0, 6 ) == 'All St' ) {
            $schedule_file = 'refdata/sched160220.dat';
         }
	   elseif ( substr( $event, 0, 6 ) == 'Upper ' ) {
            $schedule_file = 'refdata/sched112115.dat';
         }
         elseif ( substr( $event, 0, 6 ) == 'Wester' ) {
            $schedule_file = 'refdata/sched160319_4.dat';
         }
         $logon_good = 0;
         $authdata = fopen( "auth.dat", "r");
         while (($line = fgetcsv($authdata, 1024)) && !$logon_good) {
            //print_r($line);
            if ($line[0] == $rep && $line[1] == $pass) { 
               $logon_good=1;
               $authed = 1;
               $_SESSION['authed'] = 1;
               $_SESSION['unit'] = $rep;
               if ( !$schedule_file ) { $schedule_file = 'refdata/sched1117.dat'; }
               $_SESSION['eventfile'] = $schedule_file;
            }
         }
         fclose( $authdata );
         if ( !$logon_good ) { $logon_good = -1; }
      }
      elseif ( $authed ) { 
         $logon_good = 1;
         $rep = $_SESSION['unit'];
         $schedule_file = $_SESSION['eventfile'];
      }
      else { 
         $logon_good = 0;
      }
      if ( $logon_good > 0 ) {
         if ( file_exists( "refdata/limit" ) ) {
            $fp = fopen( "refdata/limit", "r" );
            while ( $line = fgets( $fp, 1024 ) ) {
               $record = explode( ',', $line );
               $limit_list[ $record[ 0 ] ] = $record[1];
               $used_list[ $record[ 0 ] ] = 0;
               $assigned_list[ $record[ 0 ] ] = 0;
            }
            fclose( $fp );
         }
         else { $limit_list[ 'none' ] = 1; } 
         $no_assigned = 0;
         $no_unassigned = 0;
         $no_area = 0;
         $locked = 0;
         $oneatlimit = 0;
         $scheddata = fopen( $schedule_file, "r");
         $sched_no = fgets( $scheddata, 1024 );
         $sched_title = fgets( $scheddata, 1024 );
         $page_title = substr( $sched_title, 1);
         while ( $line = fgets( $scheddata, 1024 ) ) {
            if ( strtoupper( trim( $line ) ) == '#LOCKED' ) {
               $locked = 1;
            }
            elseif ( substr( $line, 0, 1 ) != '#' ) {
               $record = explode( ',', $line );
               if ( $rep == "Section 1" && $record[8] ) { $no_assigned++; }
               elseif ( $rep == "Section 1" ) { $no_unassigned++; }
               elseif ( $rep == $record[8] ) { 
                  $no_area++; 
                  $assigned_list[ substr( $record[5], 0, 3 ) ]++;
               }
               $used_list[ substr( $record[5], 0, 3 ) ] = 1;
            }
         }
         fclose( $scheddata );
         echo "<h2 align=\"center\">$page_title</h2>";
         if ( $rep == 'Section 1' ) {
            echo "<h3 align=\"center\">Welcome $rep  Scheduler</h3>\n";
            echo "<h3 align=\"center\"><font color=\"#CC0000\">STATUS</font> - At this time:<br>\n";
            if ( $locked ) {
               echo "The schedule is:&nbsp;<font color=\"#CC0000\">Locked</font>&nbsp;-&nbsp;(<a href=\"sched_unlock.php\">Unlock</a> the schedule now)<br>\n";
            }
            else {
               echo "The schedule is:&nbsp;<font color=\"#008800\">Unlocked</font>&nbsp;-&nbsp;(<a href=\"sched_lock.php\">Lock</a> the schedule now)<br>\n";
            }
            echo "<font color=\"#008800\">$no_assigned</font> games are assigned and <font color=\"#CC0000\">$no_unassigned</font> are unassigned.<br>\n";
            if ( array_key_exists( 'all', $limit_list ) ) {
               $tmplimit = $limit_list['all'];
               echo "There is a <font color=\"#CC00CC\">$tmplimit</font> game limit.</h3>\n";
            }
            elseif ( array_key_exists( 'none', $limit_list ) ) {
               echo "There is <font color=\"#CC00CC\">no</font> game limit.</h3>\n";
            }
            elseif ( !array_key_exists( 'all', $limit_list ) && count( $limit_list) ) {
               foreach ( $limit_list as $k => $v ) {
                  if ( $used_list[ $k ] ) { echo "There is a <font color=\"#CC00CC\">$v</font> game limit for $k.<br>\n"; }
               }
               echo "</h3>\n";
            }
            else {
               echo "There is <font color=\"#CC00CC\">no</font> game limit at this time.</h3>\n";
            }
               
         }
         else {
            echo "<h3 align=\"center\">Welcome $rep  Representative</h3>";
            echo "<h3 align=\"center\"><font color=\"#CC0000\">Status</font><br>";
            if ( $no_area == 0 ) { echo "$rep is not currently assigned to any games.<br>"; }
            elseif ( $no_area == 1 ) { echo "$rep is currently assigned to <font color=\"#008800\">$no_area</font> game.<br>"; }
            else { echo "$rep is currently assigned to <font color=\"#008800\">$no_area</font> games.<br>"; }
            if ( array_key_exists( 'all', $limit_list ) ) {
               $tmplimit = $limit_list[ 'all' ];
               echo "There is a limit of <font color=\"#CC00CC\">$tmplimit</font> Area assigned games at this time.</h3>\n";
            }
            elseif ( array_key_exists( 'none', $limit_list ) ) {
               echo "There is <font color=\"#CC00CC\">no</font> limit on Area assigned games at this time.</h3>\n";
            }
            elseif ( count( $limit_list ) ) {
               foreach ( $limit_list as $k => $v ) {
                  $tmpassigned = $assigned_list[ $k ];
                  if ( $used_list[ $k ] ) { 
                     echo "You have assigned <font color=\"#CC00CC\">$tmpassigned</font> of your <font color=\"#CC00CC\">$v</font> game limit for $k.<br>\n";
                     if ( $tmpassigned >= $v ) { $oneatlimit = 1; }
                  }
               }
               echo "</h3>\n";
            }
            else {
               echo "There is no game limit at this time.</h3>\n";
            }
            if ( $locked && !array_key_exists( 'none', $limit_list ) ) {
               echo "<h3 align=\"center\"><font color=\"#CC0000\">The schedule is presently locked.</font><br>\n";
               if ( !$oneatlimit ) {
                  echo "You may sign $rep teams up for games but not remove them.</h3>\n";
               }
               else {
                  echo "Since $rep is at or above the limit you will not be able to sign teams up for games.</h3>\n";
               }
            }
            
         }
         echo "<center><hr width=\"25%\"><h3><font color=\"#CC0000\">ACTIONS</font></h3>\n";
         if ( $rep == 'Section 1' ) {
            echo "<h3 align=\"center\"><a href=\"sched_master.php\">Schedule All Referee Teams</a></h3>";
         }
         else {
            echo "<h3 align=\"center\"><a href=\"sched_sched.php\">Schedule $rep Referee Teams</a></h3>";
            echo "<h3 align=\"center\">Schedule one division: <a href=\"sched_sched.php?group=U10\">U10</a> - <a href=\"sched_sched.php?group=U12\">U12</a> - <a href=\"sched_sched.php?group=U14\">U14</a> - <a href=\"sched_sched.php?group=U16\">U16</a> - <a href=\"sched_sched.php?group=U19\">U19</a></h3>";
         }
         echo "<h3 align=\"center\"><a href=\"sched_full.php\">View the full game schedule</a></h3>";
         echo "<h3 align=\"center\"><a href=\"sched_refs.php\">Add/Modify Referee Names to Assigned Games</a></h3>";
//         echo "<h3 align=\"center\"><a href=\"sched_summary.htm\">Summary of the playoffs</a></h3>";
         echo "<h3 align=\"center\"><a href=\"sched_end.php\">LOG OFF</a></h3>";
         echo "</center>";
      }
      elseif ( $logon_good < 0 ) {
         print "<center><h1>Logon Failure</h1></center>";
         echo "<h3 align=\"center\"><a href=\"index.htm\">Return to Logon Page to Try Again.</a></h3>";
         session_destroy();
      }
      else {
         print "<center><h1>You are not Logged On</h1></center>";
         echo "<h3 align=\"center\"><a href=\"index.htm\">Logon Page</a></h3>";
         session_destroy();
      }
?>
    </tr>
  <!-- InstanceEndEditable -->
</table>
<p align="center"><span class="foottext"><a href="index.htm">Home</a> - <a href="section_staff.htm">Contact&nbsp;Us</a>  - <a href="siteindex.htm" class="flinks">Site&nbsp;Index<br>
</a>Corrections or additions to this web page can be sent to: <a href="mailto:webmaster@aysosection1.org">webmaster@aysosection1.org</a><br>
&copy;2005  Section one, American Youth Soccer Organization <br />
AYSO name and AYSO initials, Logos and graphics copyright by the <a href="http://www.soccer.org" class="flinks">American Youth Soccer Organization</a> and used with permission.</span></p>
</body>
<!-- InstanceEnd --></html>
