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
$showNewWorkout = new database_mysql();
$showNewWorkout->connect();

## SQL Statement
$sql = "SELECT UsrSetsID , UsrSetsUsrID , UsrSetsRoutID , UsrSetsQty , UsrExerName , UsrRoutName , MuscleGrpName FROM `usrsetstbl` ".
"RIGHT JOIN `usrexercisetbl` on UsrSetsExerID = UsrExerID LEFT JOIN `usrroutinetbl` on UsrSetsUsrID = UsrRoutID ".
"INNER JOIN `musclegrouptbl` on UsrExerMuscleGrp = MuscleGrpID WHERE UsrSetsUsrID = 1 ORDER BY MuscleGrpName LIMIT 0, 30 ";

## Execute Query ##
$showNewWorkout->query($sql);

## Number of recurds found in query
$num = $showNewWorkout->num_rows();

## If there are no records returned, redirect to the enter workout page
$myurl = $_SERVER['HTTP_HOST'];
if ($num == 0) {
header("Location:http://$myurl/workout-tracker/showworkout.php?enter=1");
}

## Populate our arrays ##
$i = 0;
while($row = $showNewWorkout->fetch_array()) {
     $UsrSetsID[$i] = $row['UsrSetsID'];
     $UsrSetsUsrID[$i] = $row['UsrSetsUsrID'];
     $UsrSetsRoutID[$i] = $row['UsrSetsRoutID'];
     $UsrSetsQty[$i] = $row['UsrSetsQty'];
     $UsrExerName[$i] = $row['UsrExerName'];
     $UsrRoutName[$i] = $row['UsrRoutName'];
		 $MuscleGrpName[$i] = $row['MuscleGrpName'];
		 $i++;
} 

## Find highest number in UsrSetsQTY column, add 1 
$maxSets = max($UsrSetsQty) + 1;

$current_url = $_SERVER['PHP_SELF'];

## Create new instance of common functions ##
$displayData = new commonFunctions();

## Start HTML page
echo "<html><head>".
		 "<title>Insert Workout</title>".
		 "</head>".
		 "<body>".
		 "<center><h4>Insert Workout</h4></center>";

	   echo "<form method=\"POST\" action=\"updateworkout.php\">";
	
		 echo "<table border=\"1\">".
		 "<tr>";
		 ## Display Header Information, part of this commns from our functions class ##
		 echo "<td><a href=\"$current_url\">View All</a></td>\n";
		 $displayData->displayMuscGrpSelect($MuscleGrpName, $current_url, $num);
		 echo "</tr>";

/*## Display musclegroup headings.  Heading is only displayed once per muscle group */##
for ($i=0; $i<$num; $i++) {
		 $sameGrp = $i - 1;
		 $g = 1;
		 $setNum = max($UsrSetsQty);
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
			echo "<tr><td colspan=$maxSets>$UsrExerName[$i]</td></tr>".
			"<tr><td>weight</td>";
			for ($m=0; $m<$UsrSetsQty[$i]; $m++) {  //This loop is incorrect it will overwrite the array everytime the first loop comes around.
               echo "<td>";
					     echo "<input name=\"UsrWghtSetEntry[$i][$m]\" type=\"text\" size=\"3\" value=\"0\"></td>\n";
     			     }
		  echo "</tr>";
			echo "<tr><td>reps</td>";
			for ($m=0; $m<$UsrSetsQty[$i]; $m++) {  //This loop is incorrect it will overwrite the array everytime the first loop comes around.
					     echo "<td><input name=\"UsrRepSetEntry[$i][$m]\" type=\"text\" size=\"3\" value=\"0\"></td>\n";
     			     }
		  echo "</tr>\n";
}
echo "<tr><td colspan=$maxSets align=\"right\"><input type=\"submit\" value=\"insert\" name=\"UsrWghtSetInsert\"></td></tr>";
echo "</table>";
echo "</form>";

echo "</body></html>";


?>