<?php
/**
Add Timestamp
@author Jesse Schwarz
*/
require("../lib/essentials.php");
require("../cvol.php");

if($_SERVER['REQUEST_METHOD']=='POST'){//must be submitted
	if(empty($_POST['vid'])||!is_numeric($_POST['vid'])||$_POST['vid']==-1000){//VolunteerID is numeric, not null ,and not default
		//echo "<p>The Volunteer ID({$_POST['vid']}) is invalid.</p>";
		setMessageCookie(5,1);//failure
	}else{
		$res=insertVolunteerTimestamp($_POST['vid']);//try insert
		if($res==true){
			setMessageCookie(0,0);//success
		}else{
			setMessageCookie(5,1);//failure
		}
	}
}
mysqli_close($vc);
header("Location: ../index.php#done");//go back

/**
 * Inserts a volunteer timestamp at the time of invocation as data
 * @global mysqli_connect $vc The connection
 * @param int $VID The volunteer ID for the timestamp
 * @return boolean Success of the transaction
 */
function insertVolunteerTimestamp($VID){
	global $vc;
	$qr=mysqli_query($vc,"INSERT INTO CheckIn(CheckID,VolunteerID) VALUES(".getNewKey($vc,'CheckIn','CheckID').",$VID);");
	if($qr){
		return (mysqli_affected_rows($vc)==1);
	}else return false;
}


?>