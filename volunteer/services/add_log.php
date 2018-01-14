<?php
/**Adds a volunteer log
 *@author Jesse Schwarz
 */
require("../cvol.php");
require("../lib/essentials.php");
require("../lib/datetime.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(empty($_POST['vid'])||!is_numeric($_POST['vid'])||$_POST['vid']==-1000){
		//echo "<p>The Volunteer ID({$_POST['vid']}) is invalid.</p>";
		setMessageCookie(4,1);
	}else if(empty($_POST['TaskID'])||!is_numeric($_POST['TaskID'])){
		//echo "<p>The TaskID({$_POST['TaskID']}) is invalid.</p>";
		setMessageCookie(4,1);
	}else{
	#echo "<p>In: ".$_POST['AMIn']."</p>";
	#echo "<p>Out: ".$_POST['AMOut']."</p>";
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
		if($qr){
			if(mysqli_affected_rows($vc)==1){
				return true;
			}
		}else return false;
	}else return false;
}

?>