<?php

Class commonFunctions {
	
	function commonFunctions() {
		//constructor
	}
	
		/* ----------------------------------------------------------
	'' Function: displaySets
	'' Usage: $object->displaySets($i, $UsrSetsQTY, $Data)
	'' Description: Used for rotating through data in showworkout
	'' This keeps our showworkout page clean as we use this multiple 
	'' times.
	-----------------------------------------------------------*/
	function displaySets($i, $UsrSetsQTY, $Data, $entry) {
					echo "<tr><td>$entry</td>";
					for ($m=0; $m<$UsrSetsQTY[$i]; $m++) {
					 		 echo "<td>".$Data[$i][$m]."</td>\n";
          		 }
					echo "</tr>";
					}
					

  function displayMuscGrpSelect($MuscleGrpName, $current_url, $num) {
		 for ($i=0; $i<$num; $i++) {
          $sameGrp = $i - 1;
					if ($i == 0 || $MuscleGrpName[$i] != $MuscleGrpName[$sameGrp]) {
			 		echo "<td><a href=\"$current_url?viewOnly=$MuscleGrpName[$i]\">$MuscleGrpName[$i]</a></td>\n";
			    		 }
     			}
		 }
}
?>