<?php
/**Timestamp Signout
 * @author Jesse Schwarz
 */
require("../lib/essentials.php");
require("../cvol.php");
require("../lib/datetime.php");
require("../lib/misc_mysql.php");

if($_SERVER['REQUEST_METHOD']=='POST'){//is submitted
	if(empty($_POST['vid'])||!is_numeric($_POST['vid'])||$_POST['vid']==-1000)//not default, is numeric and exists(VolunteerID)
		{echo "<p>The Volunteer ID({$_POST['vid']}) is invalid.</p>";
		setMessageCookie(1,1);}//error
	else if(empty($_POST['TaskID'])||!is_numeric($_POST['TaskID'])){//exists and is numeric(TaskID) Required
		{echo "<p>The TaskID({$_POST['TaskID']}) is invalid.</p>";
		setMessageCookie(1,1);}//error
	}else{
	$res=insertVolunteerLogFromTimestamp($_POST['vid'],$_POST['TaskID']);//insert and store result
		if($res==true){
			setMessageCookie(3,0);//success
		}else{
			setMessageCookie(1,1);//error
		}
	}
}
mysqli_close($vc);
@header("Location: ../index.php#done");//go back

/**
 *Inserts a volunteer log from a timestamp, closing that timestamp
 * @global mysqli_connect $vc The connection
 * @param int $VID The volunteer ID
 * @param int $TASK The task ID
 * @return boolean Success of the transaction
 */
function insertVolunteerLogFromTimestamp($VID,$TASK){
	global $vc;
	$vts=getVolunteerTimestamps($VID,true);
	$cnt=COUNT($vts);
	if($cnt>=1&&$vts[$cnt-1]['Active']==1&&isToday($vts[$cnt-1]['TimeIn'])){
		
	$qry="INSERT INTO VolunteerLog(LogID,VolunteerID,TaskID,Date,TimeIn,TimeOut,Comment)
	VALUES(".getNewKey($vc,'VolunteerLog','LogID')
	.",$VID,$TASK,DATE('{$vts[$cnt-1]['TimeIn']}'),TIME('{$vts[$cnt-1][TimeIn]}'),TIME(CURTIME()),'Generated via Timestamp');";
	$qr=mysqli_query($vc,$qry);
	unset($qry);
	return
		($qr!=false&&mysqli_affected_rows($vc)==1&&deleteTimestamp($vts[$cnt-1]['CheckID']));
		//truly delete timestamp once used
}else return false;
}

/**
 *Delete a timestamp with the given id
 * @global mysqli_connect $vc The connection
 * @param int $CheckID The index of checkin
 * @return boolean The success of the transaction
 */
function deleteTimestamp($CheckID){
	global $vc;
	if(!empty($CheckID)&&is_numeric($CheckID)){
		$res=mysqli_query($vc,"DELETE FROM Checkin WHERE CheckID=$CheckID;");
		return $res!=false;
	}else return false;
}
?>