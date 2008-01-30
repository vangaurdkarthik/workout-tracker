<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>Show Workout</title>
</head>
<body>

<center><h4>Show Workout </h4></center>

<?php

require_once "db.mysql.php";					 //Main MySQL access Class
require_once "sql.statements.php";  	 //Holds all of the sql statements, so we don't clutter this file

//Create new mysql access object
$showWorkout = new database_mysql();
$showWorkout->connect();

$sql = showWorkout_sql();

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
$maxSets = max($UsrSetsQTY);

echo "<table border=1>";
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


?>



</body>
</html>
