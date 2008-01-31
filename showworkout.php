<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>Show Workout</title>
</head>
<body>

<center><h4>Show Workout </h4></center>

<?php

/*

DESCRIPTION: Displays the interface for viewing and modifying workout data.

NOTES: There are two parts to this file

First we pull the data from the database and display if there is any.

Second, when 'modify' is selected, we transfer the data into forms. (We 
do this in one file, since we only have to make one database call).

*/

require_once "db.mysql.php";					 //Main MySQL access Class

## Create new mysql access object ##
$showWorkout = new database_mysql();
$showWorkout->connect();

## MySQL SQL Data Pull ##
$sql = 'SELECT `UsrWghtID`, `UsrWghtUsrID` , `UsrWghtRoutID` , `UsrWghtExerID` , `UsrWghtSessID` , `UsrWghtDate` , `MuscleGrpName` , `UsrExerName` , `UsrSetsQTY` , `UsrWghtSet1` , `UsrWghtSet2` , `UsrWghtSet3` , `UsrWghtSet4` , `UsrWghtSet5` , `UsrWghtSet6` , `UsrWghtSet7` , `UsrWghtSet8` , `UsrWghtSet9` , `UsrWghtSet10` , `UsrExerMuscleGrp` FROM `usrweighttbl` , `usrsetstbl` , `usrexercisetbl` , `musclegrouptbl` WHERE `usrwghtsetsid` = `usrsetsid` AND `usrwghtexerid` = `usrexerid` AND `usrexermusclegrp` = `musclegrpid` AND `usrwghtdate` = "2008-01-11" ORDER BY MuscleGrpName LIMIT 0, 30 ';

## Execute Query ##
$showWorkout->query($sql);

$num = $showWorkout->num_rows();

## Populate our arrays ##
$i = 0;
while($row = $showWorkout->fetch_array()) {
     $UsrWghtID[$i] = $row['UsrWghtID'];
     $UsrWghtRoutID[$i] = $row['UsrWghtRoutID'];
     $UsrWghtExerID[$i] = $row['UsrWghtExerID'];
     $UsrWghtSessID[$i] = $row['UsrWghtSessID'];
     $UsrWghtDate[$i] = $row['UsrWghtDate'];
     $MuscleGrpName[$i] = $row['MuscleGrpName'];
     $UsrExerName[$i] = $row['UsrExerName'];
     $UsrSetsQTY[$i] = $row['UsrSetsQTY'];
     $UsrWghtSet[$i] = array($row['UsrWghtSet1'], $row['UsrWghtSet2'], $row['UsrWghtSet3'], 
     $row['UsrWghtSet4'], $row['UsrWghtSet5'], $row['UsrWghtSet6'], $row['UsrWghtSet7'], 
     $row['UsrWghtSet8'], $row['UsrWghtSet9'], $row['UsrWghtSet10']);
     $i++;
} 

$maxSets = max($UsrSetsQTY) + 1;
$current_url = $_SERVER['PHP_SELF'];

## If we are in view mode display the following code. ##
if (!$modify) {
     echo "<table border=1>";
		 echo "<tr><td><a href=\"$current_url?modify=1\">modify records</a></td></tr>\n";
		 echo "<tr>";

/*## Display the tabs on top for view all and all muscle groups related to the workout.
  This enables users to view all workout records. */##	 
     echo "<td><a href=\"$current_url\">View All</a></td>\n";
		 for ($i=0; $i<$num; $i++) {
          $sameGrp = $i - 1;
					if ($i == 0 || $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp]) {
			 		echo "<td><a href=\"$current_url?viewOnly=$MuscleGrpName[$i]\">$MuscleGrpName[$i]</a></td>\n";
			    }
     }
		 echo "</tr>";
		
/*## Display musclegroup headings.  Heading is only displayed once per muscle group */##		
     for ($i=0; $i<$num; $i++) {
     $sameGrp = $i - 1;
		 if (!$viewOnly) {
          if ($i == 0 || $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp]) {
					     echo "<tr><td colspan=$maxSets>$MuscleGrpName[$i]</td></tr>\n";
          }
     } else {
		      if ($viewOnly == $MuscleGrpName[$i] && $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp] ) {
          echo "<tr><td colspan=$maxSets>$MuscleGrpName[$i]</td></tr>\n";
          }
     }		 

/*##Display values for each set */##			 
     if (!$viewOnly) {
		      echo "<tr><td colspan=$maxSets>$UsrExerName[$i]</td></tr>\n";
					echo "<tr><td>weight</td>";
					for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {
					     echo "<td>".$UsrWghtSet[$i][$m]."</td>\n";
          }
     } elseif ($viewOnly == $MuscleGrpName[$i]) {
		      echo "<tr><td colspan=$maxSets>$UsrExerName[$i]</td></tr>\n";
					echo "<tr><td>weight</td>";
					for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {
					     echo "<td>".$UsrWghtSet[$i][$m]."</td>\n";
     }
		      echo "</tr>";
     } elseif ($viewOnly != $MuscleGrpName[$i]){
		      echo "";
     } 
     }
echo "</table>";
}		 
## end view mode ##

## Begin modify mode. This section displays the forms for updating our workout sets. ##
if ($modify) {
     echo "<form method=\"POST\" action=\"updateworkout.php\">";
		 echo "<table border=1>";
		 echo "<tr><td><a href=\"$current_url\">view records</a></td></tr>\n";
		 echo "<input name=\"num\" type=\"hidden\" value=\"$num\">";
		 for ($i=0; $i<$num; $i++) {
          if ($i == 0 || $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp]) {
					     echo "<tr><td colspan=$maxSets>$MuscleGrpName[$i]</td></tr>\n";
          } 
          echo "<tr><td colspan=$maxSets>$UsrExerName[$i]</td></tr>";
		      echo "<tr><td>weight</td>";
					echo "<input name=\"UsrWghtID[$i]\" type=\"hidden\" value=\"$UsrWghtID[$i]\">";
					echo "<input name=\"UsrSetsQTY[$i]\" type=\"hidden\" value=\"$UsrSetsQTY[$i]\">";
					
					for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {  //This loop is incorrect it will overwrite the array everytime the first loop comes around.
               echo "<td>";
					     echo "<input name=\"UsrWghtSetEntry[$i][$m]\" type=\"text\" size=\"3\" value=\"".$UsrWghtSet[$i][$m]."\"></td>\n";
     			     }
		      echo "</tr>";
          }
     echo "<tr><td align=\"right\" colspan=\"$maxSets\"><input type=\"submit\" value=\"update\" name=\"UsrWghtSetUpdate\"></td></tr>";	 
     echo "</table>";
     echo "</form>";
}

?>

</body>
</html>
