<?php

function showWorkout_sql() {

$sql = 'SELECT `UsrWghtUsrID` , `UsrWghtRoutID` , `UsrWghtExerID` , `UsrWghtSessID` , `UsrWghtDate` , `MuscleGrpName` , `UsrExerName` , `UsrSetsQTY` , `UsrWghtSet1` , `UsrWghtSet2` , `UsrWghtSet3` , `UsrWghtSet4` , `UsrWghtSet5` , `UsrWghtSet6` , `UsrWghtSet7` , `UsrWghtSet8` , `UsrWghtSet9` , `UsrWghtSet10` , `UsrExerMuscleGrp` FROM `usrweighttbl` , `usrsetstbl` , `usrexercisetbl` , `musclegrouptbl` WHERE `usrwghtsetsid` = `usrsetsid` AND `usrwghtexerid` = `usrexerid` AND `usrexermusclegrp` = `musclegrpid` AND `usrwghtdate` = "2008-01-11" ORDER BY MuscleGrpName LIMIT 0, 30 ';
return $sql;
}

?>