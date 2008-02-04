<?php

/*

DESCRIPTION: Displays the interface for viewing and modifying workout data.

NOTES: There are two parts to this file

First we pull the data from the database and display if there is any.

Second, when 'modify' is selected, we transfer the data into forms. (We 
do this in one file, since we only have to make one database call).

*/

require_once "db.mysql.php";					 //Main MySQL access Class
require_once "functions.class.php";    //Common functions used in application

## Create new mysql access object ##
$showWorkout = new database_mysql();
$showWorkout->connect();

## SQL Statement
$sql = "SELECT * FROM `usrweighttbl`, `usrreptbl`, `usrsetstbl` , `usrexercisetbl` , `musclegrouptbl`".
" WHERE `usrwghtsetsid` = `usrsetsid` AND `usrwghtexerid` = `usrexerid` AND `usrexermusclegrp` = `musclegrpid` ".
"AND `usrwghtdate` = \"2008-01-11\" AND `usrrepsessid` = `usrwghtsessid` ORDER BY MuscleGrpName LIMIT 0, 30";

## Execute Query ##
$showWorkout->query($sql);

## Number of recurds found in query
$num = $showWorkout->num_rows();

## If there are no records returned, redirect to the enter workout page
$myurl = $_SERVER['HTTP_HOST'];
if ($num == 0) {
header("Location:http://$myurl/workout-tracker/showworkout.php?enter=1");
}

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
     $UsrRepSet[$i] = array($row['UsrRepSet1'], $row['UsrRepSet2'], $row['UsrRepSet3'], 
     $row['UsrRepSet4'], $row['UsrRepSet5'], $row['UsrRepSet6'], $row['UsrRepSet7'], 
     $row['UsrRepSet8'], $row['UsrRepSet9'], $row['UsrRepSet10']);
		 $i++;
} 

## Find highest number in UsrSetsQTY column, add 1 
$maxSets = max($UsrSetsQTY) + 1;

$current_url = $_SERVER['PHP_SELF'];

## Start HTML page
echo "<html><head>".
		 "<title>show workout</title>".
		 "</head>".
		 "<body>".
		 "<center><h4>Show Workout</h4></center>";

## There are two modes view (for viewing data) and modify for modifing data

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
		 
     ## Create new instance of common functions ##
     $displayData = new commonFunctions();

		 /*## Display musclegroup headings.  Heading is only displayed once per muscle group */##		
     for ($i=0; $i<$num; $i++) {
     $sameGrp = $i - 1;
		 $g = 1;
		 $setNum = max($UsrSetsQTY);
		 if (!$viewOnly) {
          if ($i == 0 || $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp]) {
					     ## Display the Muscle Group Name, only once per section of workouts 
							 echo "<tr><td>$MuscleGrpName[$i]</td>\n";
							 ## Display the Set Num heading ##
							 for ($m=0; $m<$setNum; $m++) {
							 		 echo "<td>Set $g</td>\n";
									 $g++;
									 }
							 echo "</tr>\n";
          }
     } else {
		      if ($viewOnly == $MuscleGrpName[$i] && $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp] ) {
          ## Display the Muscle Group Name, only once per section of workouts 
					echo "<tr><td>$MuscleGrpName[$i]</td>\n";
					/*## Display the Set # heading ##*/
					for ($m=0; $m<$setNum; $m++) {
				 			echo "<td>Set $g</td>\n";
							$g++;
							}
				  echo "</tr>\n";
          }
     }		 

/*##Display values for each set */##			 	 
		 if (!$viewOnly) {
		      echo "<tr><td colspan=$maxSets>$UsrExerName[$i]</td></tr>\n";
					$displayData->displaySets($i, $UsrSetsQTY, $UsrWghtSet, "weight");  //function pulled from functions.class.php
					$displayData->displaySets($i, $UsrSetsQTY, $UsrRepSet, "reps");
     } elseif ($viewOnly == $MuscleGrpName[$i]) {
		      echo "<tr><td colspan=$maxSets>$UsrExerName[$i]</td></tr>\n";
					$displayData->displaySets($i, $UsrSetsQTY, $UsrWghtSet, "weight");
					$displayData->displaySets($i, $UsrSetsQTY, $UsrRepSet, "reps");
     } elseif ($viewOnly != $MuscleGrpName[$i]){
		      echo "";
		 } 
		 if (!$viewOnly) {
		 		echo "<tr><td colspan=$maxSets height=10></td></tr>";
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
		 
		 $setNum = max($UsrSetsQTY);
		 for ($i=0; $i<$num; $i++) {
		 $g = 1;
          if ($i == 0 || $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp]) {
					     echo "<tr><td>$MuscleGrpName[$i]</td>";
							 /*## Display the Set # heading ##*/
							 for ($m=0; $m<$setNum; $m++) {
							 		 echo "<td>Set $g</td>\n";
									 $g++;
									 }
							 echo "</tr>\n";
          } 
          echo "<tr><td colspan=$maxSets>$UsrExerName[$i]</td>".
					"<td><a href=\"updateworkout.php?delete=$UsrWghtID[$i]\">delete</a></td></tr>\n";
		      echo "<tr><td>weight</td>";
					echo "<input name=\"UsrWghtID[$i]\" type=\"hidden\" value=\"$UsrWghtID[$i]\">";
					echo "<input name=\"UsrSetsQTY[$i]\" type=\"hidden\" value=\"$UsrSetsQTY[$i]\">";
					echo "<input name=\"UsrWghtSessID[$i]\" type=\"hidden\" value=\"$UsrWghtSessID[$i]\">";
										
					for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {  //This loop is incorrect it will overwrite the array everytime the first loop comes around.
               echo "<td>";
					     echo "<input name=\"UsrWghtSetEntry[$i][$m]\" type=\"text\" size=\"3\" value=\"".$UsrWghtSet[$i][$m]."\"></td>\n";
     			     }
		      echo "</tr>";
					echo "<tr><td>reps</td>";
					for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {  //This loop is incorrect it will overwrite the array everytime the first loop comes around.
               echo "<td>";
					     echo "<input name=\"UsrRepSetEntry[$i][$m]\" type=\"text\" size=\"3\" value=\"".$UsrRepSet[$i][$m]."\"></td>\n";
     			     }
		      echo "</tr>";
					
          echo "<tr><td colspan=$maxSets height=10></td></tr>";
					}
     echo "<tr><td align=\"right\" colspan=\"$maxSets\"><input type=\"submit\" value=\"update\" name=\"UsrWghtSetUpdate\"></td></tr>";	 
     echo "</table>";
     echo "</form>";
}

?>

</body>
</html>
