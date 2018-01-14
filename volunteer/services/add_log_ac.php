<?php
/**Adds a volunteer log
 *@author Jesse Schwarz
 */
require("../cvol.php");
require("../lib/essentials.php");
require("../lib/datetime.php");
require("../lib/misc_mysql.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
	$_POST['vid']=getVolunteerIDFromName($_POST['vname']);
    unset($_POST['vname']);
	if(empty($_POST['vid'])||!is_numeric($_POST['vid'])){
		setMessageCookie(4,1);
	}else if(empty($_POST['TaskID'])||!is_numeric($_POST['TaskID'])){
		setMessageCookie(4,1);
	}else{
	$timepartin = mergeTime($_POST['HourIn'],$_POST['MinuteIn'],(bool)($_POST['AMIn']));
	$timepartout = mergeTime($_POST['HourOut'],$_POST['MinuteOut'],(bool)($_POST['AMOut']));
	
	$res=insertVolunteerLog($_POST['vid'],$_POST['TaskID'],$_POST['Date'],$timepartin,$timepartout,$_POST['Comment']);
	unset($timepartin,$timepartout);
		if($res==true){
			setMessageCookie(2,0);
		}else{
			setMessageCookie(4,1);
		}
	}
}
mysqli_close($vc);
@header("Location: ../index.php#done");


/**
 *Inserts a volunteer log item
 * @global mysqli_connect $vc The Connection
 * @param int $VID The Volunteer ID
 * @param int $TASK The Task ID
 * @param string $DATE The Date
 * @param string $IN The in time
 * @param string $OUT The out time
 * @param string $COMMENT Optional comment
 * @return boolean Success of the transaction
 */
function insertVolunteerLog($VID,$TASK,$DATE,$IN,$OUT,$COMMENT){
	global $vc;
	if($vc&&isDate($DATE)){//check for date
		$qry="INSERT INTO VolunteerLog(LogID,VolunteerID,TaskID,Date,TimeIn,TimeOut,Comment) VALUES(".
			getNewKey($vc,'VolunteerLog','LogID').",$VID,$TASK,'".$DATE."','$IN','$OUT','".cleanString($vc,$COMMENT)."');";
		$qr=mysqli_query($vc,$qry);
		unset($qry);
		return($qr!=false&&mysqli_affected_rows($vc)==1);
	}else return false;
}

?>