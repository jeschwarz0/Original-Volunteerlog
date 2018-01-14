<?php
/**********************************************/
//			MySQL -> HTML Functions
/**********************************************/

/**
 *Generates a dropdown of volunteers
 * @global mysqli_connect $vc The connection
 * @param string $NAME The identifier of the dropdown
 * @param int $IBIAS The Index BIAS, or preselected index
 * @param string $ONCHANGE Javascript "OnChange" code 
 * @return string|false A html dropdown or false on error
 */
function genVolunteerDropDown($NAME,$IBIAS=null,$ONCHANGE=null){
	global $vc;
	if(!$vc)
		return false;
	$qc=mysqli_query($vc,"SELECT CONCAT(FirstName,\" \",LastName)as \"Name\",VolunteerID FROM volunteer ORDER BY Name ASC;");
	if($qc){
	$rval="<select id=\"$NAME\" name=\"$NAME\"";
		if(!empty($ONCHANGE))
			$rval.=" onchange=\"$ONCHANGE\"";
	$rval.=">\n";
	$rval.=getOptionLine(-1000,"NO VOLUNTEER SELECTED",(!empty($IBIAS)&&is_numeric($IBIAS)&&$IBIAS<0));
		while($qa=mysqli_fetch_row($qc)){
			$rval.=getOptionLine($qa[1],$qa[0],($qa[1]==$IBIAS));
		}
	$rval.="</select>\n";
	return $rval;
	}
}

/**
 *Generates a dropdown of volunteers having active checkins
 * @global mysqli_connect $vc The connection
 * @param string $NAME The identifier of the dropdown
 * @param int $IBIAS The Index BIAS, or preselected index
 * @param string $ONCHANGE Javascript "OnChange" code 
 * @param boolean $ONLY_TODAY Only show names who have a timestamp today
 * @return string|false A html dropdown or false on error
 */
function genActiveLogVolunteerDropDown($NAME,$IBIAS=null,$ONCHANGE=null,$ONLY_TODAY=true){
	global $vc;
	if(!$vc)
		return false;
	$query="SELECT CONCAT(FirstName,\" \",LastName)as \"Name\",VolunteerID from Volunteer WHERE VolunteerID IN (SELECT DISTINCT VolunteerID FROM Checkin WHERE Active=1";
	if($ONLY_TODAY==true)
		$query.=" AND DATE(TimeIn)=DATE(NOW())";
	$query.=")ORDER BY Name ASC;";
	$qc=mysqli_query($vc,$query);
	unset($query);
	if($qc!=false){
	$rval="<select id=\"$NAME\" name=\"$NAME\"";
		if(!empty($ONCHANGE))
			$rval.=" onchange=\"$ONCHANGE\"";
	$rval.=">\n";
	$rval.=getOptionLine(-1000,"NO VOLUNTEER SELECTED",(!empty($IBIAS)&&is_numeric($IBIAS)&&$IBIAS<0));
		while($qa=mysqli_fetch_row($qc)){
			$rval.=getOptionLine($qa[1],$qa[0],($qa[1]==$IBIAS));
		}
	$rval.="</select>\n";
	return $rval;
	}
}

/**
 *Generates a dropdown of volunteers having actual logs
 * @global mysqli_connect $vc The connection
 * @param string $NAME The identifier of the dropdown
 * @param int $IBIAS The Index BIAS, or preselected index
 * @param string $ONCHANGE Javascript "OnChange" code 
 * @return string|false A html dropdown or false on error
 */
function genActiveVolunteerDropDown($NAME,$IBIAS=null,$ONCHANGE=null){
	global $vc;
	if(!$vc)
		return false;
	$query="SELECT CONCAT(FirstName,\" \",LastName)as \"Name\",VolunteerID from Volunteer WHERE VolunteerID IN (SELECT DISTINCT VolunteerID FROM volunteerlog)ORDER BY Name ASC;";
	$qc=mysqli_query($vc,$query);
	unset($query);
	if($qc){
	$rval="<select id=\"$NAME\" name=\"$NAME\"";
		if(!empty($ONCHANGE))
			$rval.=" onchange=\"$ONCHANGE\"";
	$rval.=">\n";
	$rval.=getOptionLine(-1000,"UNSPECIFIED(ALL VOLUNTEERS)",(!empty($IBIAS)&&is_numeric($IBIAS)&&$IBIAS<0));
		while($qa=mysqli_fetch_row($qc)){
			$rval.=getOptionLine($qa[1],$qa[0],($qa[1]==$IBIAS));
		}
	$rval.="</select>\n";
	return $rval;
	}
}

/**
 *Gets a numeric dropdown
 * @param string $NAME The name of the dropdown
 * @param int $MAX The max integral value
 * @param int $MIN The min integral value
 * @param int $IBIAS The Index BIAS, or preselected index
 * @return string|false A html dropdown or false on error
 */
function getNumDropdown($NAME,$MAX,$MIN=0,$IBIAS=0){
	if(!$NAME||!is_numeric($MAX)||$MAX<$MIN)
		return false;
	$rval="<select name=\"$NAME\">\n";
		for($i=$MIN;$i<=$MAX;$i++){
			$rval.=getOptionLine($i,$i,($i==$IBIAS));
		}
	$rval.="</select>\n";
	return $rval;
}

/**
 *Gets an option line for generating menus
 * @param int|string $VALUE The passed value such that value="$VALUE"
 * @param string $DISPLAY The text displayed to the user
 * @param boolean $IS_SELECTED Select this item(note: there should only be one item selected)
 * @return string "Option" line or empty string
 */
function getOptionLine($VALUE,$DISPLAY,$IS_SELECTED){
if(!empty($VALUE)&&!empty($DISPLAY)){//
	$rval="\t<option value=\"$VALUE\"";
	if($IS_SELECTED==true){ 
		$rval.=' selected="selected"';
	}
	$rval.=">$DISPLAY</option>\n";
	return $rval;
}
	return "";
}

/**********************************************/
//			Misc. HTML Functions
/**********************************************/

/**
 *Create a sticky checkbox add-in if the item is 1(on)
 * @param string $NEEDLE The identifier of the field
 * @param array $HAYSTACK The values to check 
 */
function stickBool($NEEDLE,&$HAYSTACK){
	if(!empty($HAYSTACK)&&is_array($HAYSTACK)){
		if($HAYSTACK[$NEEDLE]==1)
			echo 'checked="checked"';
	}
}

/**
 *Sticky functionality for the AM/PM box
 * @param string(2) $IDX Current PM/AM
 * @param string(2) $BOX The AM/PM of this box
 */
function stickPM($IDX,$BOX){
	if($IDX==$BOX)
		echo 'Selected="selected"';
}

?>