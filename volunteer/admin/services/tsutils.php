<?php
/** 
 * Modifies timestamps
 * @author Jesse Schwarz 
 */
require("../../cvol.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if(tsBatch($_GET['action'],$_GET['filter'])){
		@header("Location:   ../index.php#timestamps");
	}else{
		die("Not a valid action(<br/><a href=\"../\">Back</a>");
	}
}else die("No form submitted<br/><a href=\"../\">Back</a>");
mysqli_close($vc);

/**********************************************/
//			Timestamp Batch Functions
/**********************************************/

/**
 *Sets all timestamps to inactive
 * @global mysqli_connect $vc The connection
 * @return The result of the transaction 
 */	
function deactivateAllTimestamps(){
	global $vc;
	$res=mysqli_query($vc,"UPDATE checkin SET Active=0;");//note: there is no WHERE
	if($res){
		return true;
	}else return false;
}

/**
 *Sets all timestamps to active
 * @global mysqli_connect $vc The connection
 * @return The result of the transaction 
 */
function activateAllTimestamps(){
	global $vc;
	$res=mysqli_query($vc,"UPDATE checkin SET Active=1;");//note: there is no WHERE
	if($res){
		return true;
	}else return false;
}

/**
 *deletes all timestamps permanently  
 * @global mysqli_connect $vc The connection
 * @return The result of the transaction 
 */
function purgeAllTimestamps(){
	global $vc;
	$res=mysqli_query($vc,"DELETE FROM checkin");//note: there is no WHERE
	if($res){
		return true;
	}else return false;
}

/**
 *Runs a timestamp batch command, altering the timestamp table
 * @global mysqli_connect $vc The connection
 * @param string $ACTION The action, values(activate,deactivate,delete/purge)
 * @param type $FILTER The filter, values(all,old,today,inactive,active)
 * @return boolean Success of transaction
 */
function tsBatch($ACTION,$FILTER){
global $vc;
if(!is_string($ACTION)||!is_string($FILTER)) {return false;}
$qry="";
	switch($ACTION){
		case 'activate':
			$qry="UPDATE checkin SET Active=1";
			break;
		case 'deactivate':
			$qry="UPDATE checkin SET Active=0";
			break;
		case 'delete':case 'purge':
			$qry="DELETE FROM checkin";
			break;
		default:
			return false;
	}//end switch
	//append filter
	switch($FILTER){
		case 'all':
			//do nothing
			break;
		case 'old':
			$qry.=" WHERE DATE(timein)<DATE(NOW())";
			break;
		case 'today':
			$qry.=" WHERE DATE(timein)=DATE(NOW())";
			break;
		case 'inactive':
			$qry.=" WHERE Active=0";
			break;
		case 'active':
			$qry.=" WHERE Active=1";
			break;
	}//end switch filter
	return mysqli_query($vc,$qry.";")!=false;
}
?>