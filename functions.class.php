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
					
}
?>