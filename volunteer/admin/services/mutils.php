<?php
/**
 * Utilities for maintenance
 * @author Jesse Schwarz 
 */
require("../../cvol.php");

if($_SERVER['REQUEST_METHOD']=='GET'){
	$res=false;
	switch($_GET['action']){
		case 'rtask':
			$res=removeUnusedTasks();
			break;
		case 'topt':
			$res=optimizeTables();
			break;
		case 'trep':
			$res=repairTables();
			break;
		case 'excache':
			$res=clearExportCache();
			break;
		default:
			die("Invalid Option<br/><a href=\"../\">Back</a>");
	}
	if($res==true)
		header("location: {$_SERVER['HTTP_REFERER']}#maintenance");//go back to where you came from
}else die("No form submitted<br/><a href=\"../\">Back</a>");
mysqli_close($vc);


/**
 *Clears the csv files generated
 * @return boolean The success of the operation
 */
function clearExportCache(){
if (is_dir("reportcache/")) {
chdir("reportcache/");
$dh = opendir("./");
    if ($dh) {
        while (($file = readdir($dh)) !== false) {
			if(substr($file,strlen($file)-4,4)==".csv")//if the filename ends with .csv
				unlink($file);
        }
		unset($file);
        closedir($dh);
		return true;
    }else return false;
}else return false;
}


/**********************************************/
//			Maintenance Functions
/**********************************************/

/**
 *removes the unused tasks, which could be left over from incomplete or deleted logins
 * @global mysqli_connect $vc The connection
 * @return boolean The success of the operation 
 */
function removeUnusedTasks(){
	global $vc;
	return mysqli_query($vc,"DELETE FROM VolunteerTask WHERE TaskID NOT IN(SELECT DISTINCT TaskID FROM volunteerlog);")!=false;
}

/**
 *Repairs the tables used in this application, removing overhead
 * @global mysqli_connect $vc The connection
 * @return boolean The success of the operation 
 */
function repairTables(){
	global $vc;
	return mysqli_query($vc,"REPAIR TABLE checkin,volunteer,volunteerlog,volunteertask;")!=false;
}

/**
 *Optimizes the tables used in this application
 * @global mysqli_connect $vc The connection
 * @return boolean The success of the operation 
 */
function optimizeTables(){
	global $vc;
	return mysqli_query($vc,"OPTIMIZE TABLE checkin,volunteer,volunteerlog,volunteertask;")!=false;
}
?>