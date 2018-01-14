<?php 
/**********************************************/
//			Essential Functions
/**********************************************/

/**
 *Gets a new key
 * @param mysqli_connect $DB The connection
 * @param string $TABLE The name of the table to access
 * @param string $ID_FIELD The name of the primary key
 * @return int|false One more than the max index or false on error
 */
function getNewKey(&$DB,$TABLE,$ID_FIELD){
	if($DB&&!empty($TABLE)&&!empty($ID_FIELD)){
		$res=mysqli_query($DB,"SELECT MAX($ID_FIELD) FROM $TABLE");
		if($res){
			$rval=mysqli_fetch_array($res);
			return($rval[0]+1);
		}else return false;
	}else return false;
}

/**
 *Trims unwanted whitespace, removes html tags, and prevents SQL Injection
 * @param mysqli_connect $DB The connection, needed for mysqli_real_escape_string
 * @param string $value The string to clense
 * @return string $value cleansed 
 */
function cleanString(&$DB,$value){
	return trim(htmlspecialchars(mysqli_real_escape_string($DB,$value)));
}

/**
 *Sets the message cookies for severity and id
 * @param int $MESSAGEID The index of a stored message
 * @param int $SEVERITY 0=warning 1=error
 */
function setMessageCookie($MESSAGEID,$SEVERITY=0){
	if(is_numeric($MESSAGEID)){
		setcookie('MSG',$MESSAGEID,0,"/");
	}
	if(is_numeric($MESSAGEID)){
		setcookie('MSG-Severity',$SEVERITY,0,"/");
	}
}

/**
 *Clears all cookies used in this application 
 */
function clearCookies(){
	setcookie('vid','',time()-500);
	setcookie('TaskID','',time()-500);
	setcookie('LOGOUT_TYPE','',time()-500);
	setcookie('MSG','',time()-500);
	setcookie('MSG-Severity','',time()-500);
	$_COOKIE=array();
}
?>