<?php 
/**Removes a Volunteer - Connects directly 
 * @author Jesse Schwarz
 */
require("../../cvol.php");
if($_SERVER['REQUEST_METHOD']=='GET'&&!empty($_GET['VID'])&&is_numeric($_GET['VID'])){//VID must exist, be numeric, must be sent by get
	if(mysqli_query($vc,"DELETE FROM Volunteer WHERE VolunteerID={$_GET['VID']};"))
		header("location: {$_SERVER['HTTP_REFERER']}");//go back to where you came from
}
mysqli_close($vc);
?>