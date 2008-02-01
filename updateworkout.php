<?php

require_once "db.mysql.php";					 //Main MySQL access Class

## Capture the number of recurds we have to update.
$num = $_POST['num'];

## Capture the number of sets associated with each exercise in the workout.
for ($i=0; $i<$num; $i++) {
$UsrSetsQTY[$i] = $_POST['UsrSetsQTY'][$i];
}

## Capture the Key of the record we want to update
for ($i=0; $i<$num; $i++) {
$UsrWghtID[$i] = $_POST['UsrWghtID'][$i];
}

## Capture our actual set values for updating the database.
for ($i=0; $i<$num; $i++) {

     for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {
          $UsrWghtSetEntry[$i][$m] = $_POST['UsrWghtSetEntry'][$i][$m];
          echo "value = ".$UsrWghtSetEntry[$i][$m]."<br>";
     }

}

## Update the record set to the database.
$updateWorkout = new database_mysql();
$updateWorkout->connect();

for ($i=0; $i<$num; $i++) {

     for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {
		 		 $sql = "UPDATE `wolf`.`usrweighttbl` SET `UsrWghtSet1` = ".$UsrWghtSetEntry[$i][0].", `UsrWghtSet2` =".
				 " '".$UsrWghtSetEntry[$i][1]."', `UsrWghtSet3` = '".$UsrWghtSetEntry[$i][2]."', `UsrWghtSet4` =".
				 " '".$UsrWghtSetEntry[$i][3]."', `UsrWghtSet5` = '".$UsrWghtSetEntry[$i][4]."', `UsrWghtSet5` =".
				 " '".$UsrWghtSetEntry[$i][4]."', `UsrWghtSet6` = '".$UsrWghtSetEntry[$i][5]."', `UsrWghtSet7` =".
				 " '".$UsrWghtSetEntry[$i][6]."', `UsrWghtSet8` = '".$UsrWghtSetEntry[$i][7]."', `UsrWghtSet9` =".
				 " '".$UsrWghtSetEntry[$i][8]."', `UsrWghtSet10` = '".$UsrWghtSetEntry[$i][9]."' WHERE `usrweighttbl`.`UsrWghtID` = ".$UsrWghtID[$i]." LIMIT 1;";
				 $updateWorkout->query($sql);
				 }
}

?>


