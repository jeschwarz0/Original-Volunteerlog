<?php
/** Add Volunteer
*@author Jesse Schwarz
*/
require("../cvol.php");
require("../lib/essentials.php");

if($_SERVER['REQUEST_METHOD']=='POST'){//must be submitted
	if(//insert volunteer if not exists
		volunteerExists($_POST['FirstName'],$_POST['LastName'])==false &&
		insertVolunteer($_POST['FirstName'],$_POST['LastName'])==true
	){
		setMessageCookie(6,0);//success
	}else
		setMessageCookie(7,1);//error
	}//end if
	mysqli_close($vc);
	header("Location: ../index.php#done");//go to done message
	
/**Trims a value of all spaces
*@param string $VAL The value to trim
*/
function spaceTrim(&$VAL){
	$VAL=str_replace($VAL," ","");
}
/**
 *Inserts a volunteer from the name
 * @global mysqli_connect $vc The connection
 * @param string $FirstName The first name
 * @param string $LastName The last name
 * @return boolean Success of the transaction
 */
function insertVolunteer($FirstName,$LastName){
	global $vc;
	if($vc&&!empty($FirstName)&&!empty($LastName)){
		$res=mysqli_query($vc,"INSERT INTO Volunteer(VolunteerID,FirstName,LastName) 
		VALUES(".getNewKey($vc,"Volunteer","VolunteerID").",'".cleanString($vc,str_replace(' ','',$FirstName))."','".cleanString($vc,str_replace(' ','',$LastName))."');");
			return($res&&mysqli_affected_rows($vc));
	}else return false;
}


/**
 *Checks if there is a volunteer with the same name existing in the database
 * @global mysqli_connect $vc The connection
 * @param string $FirstName The First Name
 * @param string $LastName The Last Name
 * @return boolean|null True if volunteer exists, false if volunteer doesn't exist, or null on error
 */
function volunteerExists($FirstName,$LastName){
	global $vc;
	$res=mysqli_query($vc,"SELECT COUNT(VolunteerID) FROM Volunteer WHERE FirstName='".cleanString($vc,$FirstName)."' AND LastName='".cleanString($vc,$LastName)."';");
		if($res){
			$data=mysqli_fetch_row($res);
			if($data[0]==0){
				return false;
			}else return true;
		}else return null;
}	
	
?>