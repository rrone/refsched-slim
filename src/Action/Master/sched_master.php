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
  <tr><td>
     
<?php
      $authed = $_SESSION['authed'];
      $rep = $_SESSION['unit'];
      $schedule_file = $_SESSION['eventfile'];
      $select_list = array( "None", "Area 1B", "Area 1C", "Area 1D", "Area 1F", "Area 1G", "Area 1H", "Area 1N", "Area 1P", "Area 1R", "Area 1S", "Area 1U", "Section One", "Section 2", "Section 10", "Section 11", "Other" );
      if ( $authed && $rep == 'Section 1' ) {
           $infile = fopen( $schedule_file, "r");
           $sched_no = fgets( $infile, 1024 );
           $sched_title = fgets( $infile, 1024 );
           $page_title = substr( $sched_title, 1);

           echo "<center><h1>$page_title</h1></center>\n";

           echo "  <form name=\"master_sched\" method=\"post\" action=\"sched_control.php\">\n";

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
           while ( $line = fgets( $infile, 1024 ) ) {
              if ( substr( $line, 0, 1 ) != '#' ) {
                 $record = explode( ',', $line );
                 if ( $record[8] == "" ) {
                    echo "            <tr align=\"center\" bgcolor=\"#00FFFF\">";
                    echo "            <td>$record[0]</td>";
                    echo "            <td>$record[2]<br>$record[1]</td>";
                    echo "            <td>$record[4]</td>";
                    echo "            <td>$record[3]</td>";
                    echo "            <td>$record[5]</td>";
                    echo "            <td>$record[6]</td>";
                    echo "            <td>$record[7]</td>";
                    echo "            <td><select name=\"area$record[0]\">\n";
//                    echo "            <td><select name=\"$record[0]\">\n";
                    echo "               <option selected>None</option>\n";
                    echo "               <option>Area 1B</option>\n";
                    echo "               <option>Area 1C</option>\n";
                    echo "               <option>Area 1D</option>\n";
                    echo "               <option>Area 1F</option>\n";
                    echo "               <option>Area 1G</option>\n";
                    echo "               <option>Area 1H</option>\n";
                    echo "               <option>Area 1N</option>\n";
                    echo "               <option>Area 1P</option>\n";
                    echo "               <option>Area 1R</option>\n";
                    echo "               <option>Area 1S</option>\n";
                    echo "               <option>Area 1U</option>\n";
                    echo "               <option>Section One</option>\n";
                    echo "               <option>Section 2</option>\n";
                    echo "               <option>Section 10</option>\n";
                    echo "               <option>Section 11</option>\n";
                    echo "               <option>Other</option>\n";
                    echo "            </select></td>";
                    echo "            </tr>\n";
                 }
                 else {
                    echo "            <tr align=\"center\" bgcolor=\"#00FF88\">";
                    echo "            <td>$record[0]</td>";
                    echo "            <td>$record[2]<br>$record[1]</td>";
                    echo "            <td>$record[4]</td>";
                    echo "            <td>$record[3]</td>";
                    echo "            <td>$record[5]</td>";
                    echo "            <td>$record[6]</td>";
                    echo "            <td>$record[7]</td>";
                    echo "            <td><select name=\"area$record[0]\">\n";
                    foreach ( $select_list as $value ) {
                    echo "               <option";
                    if ( $value == $record[8] ) { echo " selected"; }
                    echo ">$value</option>\n";
                    }
                    echo "            </select></td>\n";
                    echo "            </tr>\n";
                 }
              }
           }
           echo "      </table>\n";
           echo "      <input type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
           echo "      </form>\n";
           fclose( $infile );
      }
      elseif ( $authed ) {
         echo "<center><h2>You probably want the <a href=\"sched_sched.php\">scheduling</a> page.</h2></center>";
      }
      elseif ( !$authed ) {
         echo "<center><h2>You need to <a href=\"index.htm\">logon</a> first.</h2></center>";
      }
      else {
         echo "<center><h1>Something is not right</h1></center>";
      }

?>
      <h3><p align="center"><a href="sched_greet.php">Return to main screen</a>&nbsp;-&nbsp;
      <a href="sched_sched.php">Return to schedule</a>&nbsp;-&nbsp;
      <a href="sched_end.php">Logoff</a></h3>
</td></tr>
  <!-- InstanceEndEditable -->
</table>
<p align="center"><span class="foottext"><a href="index.htm">Home</a> - <a href="section_staff.htm">Contact&nbsp;Us</a>  - <a href="siteindex.htm" class="flinks">Site&nbsp;Index<br>
</a>Corrections or additions to this web page can be sent to: <a href="mailto:webmaster@aysosection1.org">webmaster@aysosection1.org</a><br>
&copy;2005  Section one, American Youth Soccer Organization <br />
AYSO name and AYSO initials, Logos and graphics copyright by the <a href="http://www.soccer.org" class="flinks">American Youth Soccer Organization</a> and used with permission.</span></p>
</body>
<!-- InstanceEnd --></html>
