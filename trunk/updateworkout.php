<?php

/*

DESCRIPTION: Used by showworkout to update the database after submit.

NOTES: There are two parts to this file.  We handle deleting items from
the database as well as updating items in the database with this file.

*/

require_once "db.mysql.php";					 //Main MySQL access Class 

## Capture the number of recurds we have to update.
$num = $_POST['num'];

## Caputure the Workout Session ID
for ($i=0; $i<$num; $i++) {
$UsrWghtSessID[$i] = $_POST['UsrWghtSessID'][$i];
}

## Delete workout section.  Delete workout and go back.
$myurl = $_SERVER['HTTP_HOST'];
if ($_GET['delete']) {
	 header("Location:http://$myurl/workout-tracker/showworkout.php?modify=1");
	 }

## Capture the number of sets associated with each exercise in the workout.
for ($i=0; $i<$num; $i++) {
$UsrSetsQTY[$i] = $_POST['UsrSetsQTY'][$i];
}

## Capture the Key of the record we want to update
for ($i=0; $i<$num; $i++) {
$UsrWghtID[$i] = $_POST['UsrWghtID'][$i];
}

## ERROR CHECK: If we don't have a least one ID go back.
if (!$UsrWghtID[0]) {
	 header("Location:http://$myurl/workout-tracker/showworkout.php?modify=1");
	 }

## Capture our actual set values for updating the database.
for ($i=0; $i<$num; $i++) {

     for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {
          $UsrWghtSetEntry[$i][$m] = $_POST['UsrWghtSetEntry'][$i][$m];
					//echo "WGHT Value = ".$UsrWghtSetEntry[$i][$m]."<BR>";
     }

}
for ($i=0; $i<$num; $i++) {
     for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {
          $UsrRepSetEntry[$i][$m] = $_POST['UsrRepSetEntry'][$i][$m];
					//echo "REP Value = ".$UsrRepSetEntry[$i][$m]."<BR>";
     }

}

## Update the record set to the database.
$updateWorkout = new database_mysql();
$updateWorkout->connect();

for ($i=0; $i<$num; $i++) {

     for ($m=0; $m<1; $m++) {
		 		 $sql = "UPDATE `wolf`.`usrweighttbl` SET `UsrWghtSet1` = ".$UsrWghtSetEntry[$i][0].", `UsrWghtSet2` =".
				 " '".$UsrWghtSetEntry[$i][1]."', `UsrWghtSet3` = '".$UsrWghtSetEntry[$i][2]."', `UsrWghtSet4` =".
				 " '".$UsrWghtSetEntry[$i][3]."', `UsrWghtSet5` = '".$UsrWghtSetEntry[$i][4]."', `UsrWghtSet5` =".
				 " '".$UsrWghtSetEntry[$i][4]."', `UsrWghtSet6` = '".$UsrWghtSetEntry[$i][5]."', `UsrWghtSet7` =".
				 " '".$UsrWghtSetEntry[$i][6]."', `UsrWghtSet8` = '".$UsrWghtSetEntry[$i][7]."', `UsrWghtSet9` =".
				 " '".$UsrWghtSetEntry[$i][8]."', `UsrWghtSet10` = '".$UsrWghtSetEntry[$i][9]."' WHERE `usrweighttbl`.`UsrWghtID` = ".$UsrWghtID[$i]." LIMIT 1;";
				 $updateWorkout->query($sql);
				 }
		 for ($m=0; $m<1; $m++) {
				 $sql = "UPDATE `wolf`.`usrreptbl` SET `UsrRepSet1` = ".$UsrRepSetEntry[$i][0].", `UsrRepSet2` =".
				 " '".$UsrRepSetEntry[$i][1]."', `UsrRepSet3` = '".$UsrRepSetEntry[$i][2]."', `UsrRepSet4` =".
				 " '".$UsrRepSetEntry[$i][3]."', `UsrRepSet5` = '".$UsrRepSetEntry[$i][4]."', `UsrRepSet5` =".
				 " '".$UsrRepSetEntry[$i][4]."', `UsrRepSet6` = '".$UsrRepSetEntry[$i][5]."', `UsrRepSet7` =".
				 " '".$UsrRepSetEntry[$i][6]."', `UsrRepSet8` = '".$UsrRepSetEntry[$i][7]."', `UsrRepSet9` =".
				 " '".$UsrRepSetEntry[$i][8]."', `UsrRepSet10` = '".$UsrRepSetEntry[$i][9]."' WHERE `usrreptbl`.`UsrRepSessID` = ".$UsrWghtSessID[$i]." LIMIT 1;";
				 $updateWorkout->query($sql);
				 }
}
header("Location:http://$myurl/workout-tracker/showworkout.php?modify=1");
?>
