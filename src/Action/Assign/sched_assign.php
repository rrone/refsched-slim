<?php session_start();
   header("Cache-control: private");
   $url_ref = '/sched_sched.php';
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
//      echo "$from";
      $authed = $_SESSION['authed'];
      $rep = $_SESSION['unit'];
      $locked = $_SESSION['locked'];
      $schedule_file = $_SESSION['eventfile'];
      if ( file_exists( "refdata/limit" ) ) {
         $fp = fopen( "refdata/limit", "r" );
         while ( $line = fgets( $fp, 1024 ) ) {
            $record = explode( ',', $line );
            $limit_list[ $record[0] ] = $record[1];   //The limit on each div
            $used_list[ $record[0] ] = 0;           //Yes-no divisions used
            $assigned_list[ $record[0] ] = 0;       //Yes-no divisions for this user
            $no_posted[ $record[0] ] = 0;           //Number of games counted as posted
            $games_requested[ $record[0] ] = 0;
            $games_now[ $record[0] ] = 0;
            $games_both[ $record[0] ] = 0;
         }
         fclose( $fp );
         if ( !count( $limit_list ) ) { $limit_list[ 'none' ] = 1; }
      }
     else { $limit_list[ 'none' ] = 1; }

      if ( $authed && $_SERVER['REQUEST_METHOD'] == 'POST' && $rep != 'Section 1' ) {
//         print_r($_POST);
         $array_of_keys = array_keys( $_POST );
//         echo "<p>";
//         print_r($array_of_keys);
//         echo "</p>";
         $num_mod = count($array_of_keys)-1;
         if ( $num_mod > 0 || $locked ) {
           for ( $kount = 0; $kount < $num_mod; $kount++ ) {
              $array_of_keys[$kount] = substr( $array_of_keys[$kount], 4 );
           }
//           echo "<p>";
//           print_r($array_of_keys);
//           echo "</p>";
           $fp = fopen( $schedule_file, "r");
              while ( $line = fgets( $fp, 1024 ) ) {
                 if ( substr( $line, 0, 1 ) != '#' ) {
                    $record = explode( ',', $line );
                    if ( in_array( $record[0], $array_of_keys ) ) { 
                       $games_requested[ substr( $record[5], 0, 3 ) ]++;
                       if ( $record[8] == $rep ) {
                          $games_both[ substr( $record[5], 0, 3 ) ]++;
                       }
                    }
                    if ( $record[8] == $rep ) {
                       $games_now[ substr( $record[5], 0, 3 ) ]++;
                       if ( array_key_exists( 'all', $limit_list ) ) { $games_now[ 'all' ]++; }
                    }
                 }
              }
            fclose( $fp );
//  Debugging output
//            print_r( $games_requested );
//            echo "\n";
//            print_r( $games_now );
//            echo "\n";
//            print_r( $games_both );
//            echo "\n";

//   Begin the file rewrite loop
           copy( $schedule_file, "refdata/temp.dat");
           $outfile = fopen( $schedule_file, "w");
           if (flock( $outfile, LOCK_EX )) {
//              echo "<p>Got lock</p>\n<ul>\n";
              $tmpfile = fopen( "refdata/temp.dat", "r");
              $sched_no = fgets( $tmpfile, 1024 );
              fputs( $outfile, $sched_no );
              $sched_title = fgets( $tmpfile, 1024 );
              fputs( $outfile, $sched_title );
              $page_title = substr( $sched_title, 1);

              echo "<center><h1>$page_title</h1></center>";

              while ( $line = fgets( $tmpfile, 1024 ) ) {
                 if ( substr( $line, 0, 1 ) == '#' ) {
                              //  Pass through a comment line
                    fputs( $outfile, $line );
                 }
                 else {
                              //  Process anything else
                    $record = explode( ',', trim($line) );
                    $tempdiv = substr( $record[5], 0, 3 );
                    if ( array_key_exists( 'all', $limit_list ) ) { $tempdiv = 'all'; }
                    if ( array_key_exists( 'none', $limit_list ) && in_array( $record[0], $array_of_keys ) && $record[8] == "" ) {
                              //  No limits in place - Game number match - game not taken
                       $record[8] = $rep;
                       $line = implode( ',', $record )."\n";
                       echo "<p>You have <strong>scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
                    }
                    elseif ( in_array( $record[0], $array_of_keys ) && $record[8] == "" && $games_now[ $tempdiv ] < $limit_list[ $tempdiv ] ) {
                              //  Game number match - game not taken - below limit
                       $record[8] = $rep;
                       $no_posted[$tempdiv]++;
                       $games_now[$tempdiv]++;
                       $line = implode( ',', $record )."\n";
                       echo "<p>You have <strong>scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
                    }
                    elseif ( in_array( $record[0], $array_of_keys ) && $record[8] == "" && $games_now[ $tempdiv ] >= $limit_list[ $tempdiv ] ) {
                             //   Game number match - game not taken - at or over limit
                       $line = implode( ',', $record )."\n";
                       echo "<p>You have <strong>not scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3] because you are at your game limit!</p>\n";
                    }
                    elseif ( !in_array( $record[0], $array_of_keys ) && $record[8] == $rep && !$locked ) {
                             //   No game number match - game was reserved - no locked - game to be removed
                       $record[8] = "";
                       $record[9] = "";
                       $record[10] = "";
                       $record[11] = "";
                       $games_now[$tempdiv]--;
                       echo "<p>You have <strong>removed</strong> your referee team from Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
                       $line = implode( ',', $record )."\n";
                    }
                    elseif ( array_key_exists( 'none', $limit_list ) && in_array( $record[0], $array_of_keys ) && $record[8] == $rep ) {
                       $line = implode( ',', $record )."\n";
                    }
                    elseif ( in_array( $record[0], $array_of_keys ) && $record[8] == $rep && $games_now[ $tempdiv ] < $limit_list[ $tempdiv ]) {
                       $no_posted[ $tempdiv ]++;
                       $line = implode( ',', $record )."\n";
                    }
//                    elseif ( in_array( $record[0], $array_of_keys ) && $record[8] == $rep && !$locked && $games_now[ $tempdiv] >= $limit_list[ $tempdiv ]) {
//                       $record[8] = "";
//                       $record[9] = "";
//                       $record[10] = "";
//                       $record[11] = "";
//                       $games_now[$tempdiv]--;
//                       echo "<p>Your referee team has been <strong>removed</strong> from Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3] because you are over the game limit.</p>\n";
//                       $line = implode( ',', $record )."\n";
//                    }
                    elseif ( in_array( $record[0], $array_of_keys ) && $record[8] != $rep && $record[8] != "") {
                       echo "<p>I'm sorry, game no. $record[0] has been taken.</p>";
                       $line = implode( ',', $record )."\n";
                    }
                    elseif ( $record[8] == $rep ) {
                       $no_posted[ $tempdiv ]++;
                       $line = implode( ',', $record )."\n";
                    }
                    else {
                       $line = implode( ',', $record )."\n";
                    }
//                    echo "<li>$line</li>\n";
                    fputs( $outfile, $line );
                 }
              }
//              echo "</ul>";
              fclose ( $tmpfile );
              flock( $outfile, LOCK_UN );
           }
           fclose ( $outfile );
           $any_games = 0;
           $fp = fopen( $schedule_file, "r" );
           while ( $line = fgets( $fp, 1024 ) ) {
              if ( substr( $line, 0, 1 ) != '#' ) {
                 $record = explode( ',', trim($line) );
                 if ( $record[8] == $rep ) {
                    if ( !$any_games ) {
                       echo "<center><h2>You are currently scheduled for the following games</h2></center>\n";
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
                    echo "            <tr align=\"center\" bgcolor=\"#00FF88\">";
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
           }
           if ( $any_games ) {
             echo "      </table>\n";
           }
           fclose( $fp );
         }
         else {
           copy( $schedule_file, "refdata/temp.dat");
           $outfile = fopen( $schedule_file, "w");
           if (flock( $outfile, LOCK_EX )) {
//              echo "<p>Got lock</p>\n<ul>\n";
              $tmpfile = fopen( "refdata/temp.dat", "r");
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
                    if ( $record[8] == $rep ) {
                       $record[8] = "";
                       $record[9] = "";
                       $record[10] = "";
                       $record[11] = "";
                       echo "<p>You have <strong>removed</strong> your referee team from Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
                       $line = implode( ',', $record )."\n";
                    }
//                    echo "<li>$line</li>\n";
                    fputs( $outfile, $line );
                 }
              }
//              echo "</ul>";
              fclose ( $tmpfile );
              flock( $outfile, LOCK_UN );
           }
           fclose ( $outfile );
           echo "<center><h2>You do not currently have any games scheduled.</h2></center>\n";
         }
      }
      elseif ( $authed && $rep == 'Section 1' ) {
         echo "<center><h2>You seem to have gotten here by a different path<br>";
         echo "  You should go to the <a href=\"sched_master.php\">Schedule Page</a></h2></center>";
      }
      elseif ( $authed ) {
         echo "<center><h2>You seem to have gotten here by a different path<br>";
         echo "  You should go to the <a href=\"sched_sched.php\">Schedule Page</a></h2></center>";
      }
      elseif ( !$authed ) {
         echo "<center><h2>You need to <a href=\"index.htm\">logon</a> first.</h2></center>";
      }
      else {
         echo "<center><h1>Something is not right</h1></center>";
      }

?>
      <h3 align="center"><a href="sched_greet.php">Return to main screen</a>&nbsp;-&nbsp;
      <a href="sched_sched.php">Return to schedule</a>&nbsp;-&nbsp;
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
