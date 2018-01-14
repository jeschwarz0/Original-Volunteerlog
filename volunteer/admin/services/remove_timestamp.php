<?php 
/**Removes a Timestamp - Connects directly 
 * @author Jesse Schwarz
 */
require("../../cvol.php");

if($_SERVER['REQUEST_METHOD']=='GET'&&!empty($_GET['CheckID'])&&is_numeric($_GET['CheckID'])){//CheckID must exist, be numeric, must be sent by get
	if(mysqli_query($vc,"UPDATE CheckIn SET Active=0 WHERE CheckID={$_GET['CheckID']}"))//only deactivate item, if it doesn't exist, do nothing
		header("location: {$_SERVER['HTTP_REFERER']}");//go back to where you came from
}
mysqli_close($vc);
?>