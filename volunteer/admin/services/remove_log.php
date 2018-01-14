<?php 
/**Removes a Log - Connects directly 
 * @author Jesse Schwarz
 */
require("../../cvol.php");
if($_SERVER['REQUEST_METHOD']=='GET'&&!empty($_GET['LogID'])&&is_numeric($_GET['LogID'])){//LogID must exist, be numeric, must be sent by get
	if(mysqli_query($vc,"DELETE FROM VolunteerLog WHERE LogID={$_GET['LogID']};"))
		header("location: {$_SERVER['HTTP_REFERER']}");//go back to where you came from
}
mysqli_close($vc);
?>