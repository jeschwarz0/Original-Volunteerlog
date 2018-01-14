<?php
/**Misc MySQL Functions
 *@author Jesse Schwarz 
 */

/**********************************************/
//			MySQL Functions
/**********************************************/

/**
 *Gets a task from the specified id
 * @global mysqli_connect $vc The connection
 * @param int $TaskID The id of the task being used
 * @return associative array|false The task info matching the task id 
 */
function getTaskFromID($TaskID){
	global $vc;
	if($vc&&!empty($TaskID)&&is_numeric($TaskID)){
		$res=mysqli_query($vc,"SELECT * FROM VolunteerTask WHERE TaskID=$TaskID;");
			if($res&&mysqli_num_rows($res)>0){
				return mysqli_fetch_assoc($res);
			} else return false;
	}else return false;
}

/**
 *Gets a volunteer's name based on id
 * @global mysqli_connect $vc The connection
 * @param int $VolunteerID The volunteer filter id
 * @return associative array|false The FirstName and LastName associated with the $VolunteerID or false
 */
function getVolunteerFromID($VolunteerID){
	global $vc;
	if($vc&&!empty($VolunteerID)&&is_numeric($VolunteerID)){
		$res=mysqli_query($vc,"SELECT FirstName,LastName FROM Volunteer WHERE VolunteerID=$VolunteerID;");
			if($res&&mysqli_num_rows($res)>0){
				return mysqli_fetch_assoc($res);
			} else return false;
	}else return false;
}

/**
 *Gets the Volunteer ID from the Volunteer Name, used for autocomplete.
 * @global mysqli_connect $vc The Connection
 * @param int|string $NAME The name or ID of the user
 * @return int|boolean Volunteer ID or false
 */
function getVolunteerIDFromName($NAME){
	global $vc;
	if(empty($NAME)||is_numeric($NAME)||!$vc||!is_string($NAME))
		return false;
	$nsep=split(" ",mysqli_real_escape_string($vc,$NAME));
	$res=mysqli_query($vc,"SELECT VolunteerID FROM Volunteer WHERE FirstName='{$nsep['0']}'".(isset($nsep['1'])?"AND LastName='{$nsep['1']}'":"AND TRUE")." LIMIT 1;");
	unset($nsep);
	if($res){
		$rval=mysqli_fetch_row($res);
		return $rval[0];
	}else return false;
}

/**
 *Gets the count of timestamps for a Volunteer 
 * @global mysqli_connect $vc The connection
 * @param int $VID The volunteer id
 * @param boolean $ACTIVE_ONLY Default true Show only timestamps that have active set to true
 * @return int|false The count of timestamps or false
 */
function volunteerTimestampCount($VID,$ACTIVE_ONLY=true,$ONLY_TODAY=true){
	global $vc;
	if($vc&&!empty($VID)&&is_numeric($VID)){
	$qry="SELECT COUNT(CheckID) AS \"Checkins\" FROM Checkin WHERE VolunteerID=$VID";
	if($ACTIVE_ONLY==true)
		$qry.=' AND Active=1';
	if($ONLY_TODAY==true)
			$qry.=" AND DATE(TimeIn)=DATE(NOW())";
	$qry.=";";
		$res=mysqli_query($vc,$qry);
		unset($qry);
			if($res&&mysqli_num_rows($res)==1){
				$resv=mysqli_fetch_row($res);
					return (int)($resv[0]);
			} else return false;
	}else return false;
}

/**
 *Gets the timestamps for a volunteer
 * @global mysqli_connect $vc The connection
 * @param int $VID The volunteer id
 * @param boolean $ACTIVE_ONLY Default false Show only timestamps that have active set to true
 * @return array|false The timestamps or false on error
 */
function getVolunteerTimestamps($VID,$ACTIVE_ONLY=false,$ONLY_TODAY=true){
	global $vc;
	if($vc&&!empty($VID)&&is_numeric($VID)){
		$qry="SELECT * FROM Checkin WHERE VolunteerID=$VID";
		if($ACTIVE_ONLY==true)
			$qry.=" AND Active=1";
		if($ONLY_TODAY==true)
			$qry.=" AND DATE(TimeIn)=DATE(NOW())";
		$qry.=" ORDER BY CheckID DESC;";
		$res=mysqli_query($vc,$qry);
		
		unset($qry);
			if($res){
				$rval=array();
                for($i=0;$i<mysqli_num_rows($res);$i++)
                    $rval[count($rval)]=mysqli_fetch_assoc($res);
                       return $rval;
			}else return false;
	}else return false;
}

/**
 *Check the tables for overhead
 * @global mysqli_connect $vc The connection
 * @return array|false an array containing all table names and overhead values
 */
function findTableOverhead(){
	global $vc;
	$res=mysqli_query($vc,'SHOW TABLE STATUS;');
	if($res==false)return false;
	$arval=array(array());
	$cnt=0;
	while($item=mysqli_fetch_assoc($res)){
		if($item['Engine']!=null){
			$arval[$cnt]['Name']=$item['Name'];
			$arval[$cnt++]['Overhead']=$item['Data_free'];
		}
	}
	mysqli_free_result($res);
	unset($item);
	return $arval;
}
?>