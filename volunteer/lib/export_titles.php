<!--?php 
$ores=fopen("tasklist.json","w");
if($ores)
	fwrite($ores,json_encode(
	array('Class','Office','Maintenance','Conditioning','HorseCare','Committee','Board','Sidewalking','HorseLeading','JrVolunteer','SpecialOlympics','Other')));
	fclose($ores);
	?-->
	
<?php 
require('misc-mysql.php');
print_r(loadItemList(1));
?>