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

//Create new mysql access object
$showWorkout = new database_mysql();
$showWorkout->connect();

$sql = 'SELECT `UsrWghtUsrID` , `UsrWghtRoutID` , `UsrWghtExerID` , `UsrWghtSessID` , `UsrWghtDate` , `MuscleGrpName` , `UsrExerName` , `UsrSetsQTY` , `UsrWghtSet1` , `UsrWghtSet2` , `UsrWghtSet3` , `UsrWghtSet4` , `UsrWghtSet5` , `UsrWghtSet6` , `UsrWghtSet7` , `UsrWghtSet8` , `UsrWghtSet9` , `UsrWghtSet10` , `UsrExerMuscleGrp` FROM `usrweighttbl` , `usrsetstbl` , `usrexercisetbl` , `musclegrouptbl` WHERE `usrwghtsetsid` = `usrsetsid` AND `usrwghtexerid` = `usrexerid` AND `usrexermusclegrp` = `musclegrpid` AND `usrwghtdate` = "2008-01-11" ORDER BY MuscleGrpName LIMIT 0, 30 ';

$showWorkout->query($sql);

$num = $showWorkout->num_rows();

$i = 0;
while($row = $showWorkout->fetch_array()) {
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

$sameGrp = $i - 1;
$maxSets = max($UsrSetsQTY) + 1;
$current_url = $_SERVER['PHP_SELF'];

//If we are in view mode display the following code.
if (!$modify) {

	 echo "<table border=1>";
	 echo "<tr><td><a href=\"$current_url?modify=1\">modify records</a></td></tr>";
	 for ($i=0; $i<$num; $i++) {
	 		 if ($i == 0 || $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp]) {
			 		echo "<tr><td colspan=$maxSets>$MuscleGrpName[$i]</td></tr>";
			 } 
			 echo "<tr><td colspan=$maxSets>$UsrExerName[$i]</td></tr>";
			 echo "<tr><td>weight</td>";
			 for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {
			 		echo "<td>".$UsrWghtSet[$i][$m]."</td>";
					}
			 echo "</tr>";
	 }
echo "</table>";
}		 //end view mode

if ($modify) {

	 echo "Entered modify mode";
	 }

?>



</body>
</html>
