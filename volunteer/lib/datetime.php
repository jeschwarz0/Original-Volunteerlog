<?php
/**********************************************/
//			Date/Time Functions
/**********************************************/


/**
 *Merge the parts of the time into one time
 * @param int $HOUR The hour
 * @param int $MINUTE The minute
 * @param boolean $AM The AM/PM
 * @return string|false A formatted date string 24 hour or false on error
 */
function mergeTime($HOUR,$MINUTE,$AM){
	if(is_numeric($HOUR)&&is_numeric($MINUTE)&&is_bool($AM)){
		if($HOUR<=12&&$HOUR>=1&&$MINUTE<=59&&$MINUTE>=0){
			if(($AM==true&&$HOUR!=12)||($AM==false&&$HOUR==12))
				return $HOUR.':'.$MINUTE.':'.'00';
			elseif(($AM==false&&$HOUR!=12)||($AM==true&&$HOUR==12))
				return (12+$HOUR).':'.$MINUTE.':'.'00';
		}else return false;
	}else return false;
}

/**
 *Format the date from ##/##/#### to YYYY-MM-DD or vice versa
 * @param string $DATE The date to format
 * @return string A formatted or empty date 
 */
function formatDate($DATE,$FORMAT_ID=0){
	$FORMATS=array("Y\-d\-m","m\/d\/y");
	if(isset($FORMATS[$FORMAT_ID]))
		return date_format(new DateTime($DATE),$FORMATS[$FORMAT_ID]);
}

/**
 *Format a time object for display
 * @param string $time The time to display
 * @param boolean $USE_TODAY Show "Today" instead of
 * @return string|false 
 */
function ftime($date_time,$USE_TODAY=false){
	if($USE_TODAY==true&&isToday($date_time)){
		$rval= "Today";
	}else{
		$rval=date_format(new DateTime($date_time),"l, F j\<\s\u\p\>S\<\/\s\u\p\> Y");//superscripted
	}//end if
	$dt2=getTime12($date_time);
		if($dt2){
			$rval.=" - ".$dt2;
		}
		unset($dt2);
		return $rval;
}

/**
 *Format a date/time object for display
 * @param string $time The time to display
 * @param boolean $USE_TODAY Show "Today" instead of
 * @return string|false 
 */
function fdate($date_time,$USE_TODAY=false){
	if($USE_TODAY==true&&isToday($date_time)){
		$rval= "Today";
	}else{
		$rval=date_format(new DateTime($date_time),"l, F jS Y");//superscripted
	}//end if
	return $rval;
}

/**Convert a 24 hour time from a time string to 12 hour and timepart
* @param string $TIME24 The time string
* @return string A simple formatted string 12h:m AM/PM
*/
function getTime12($TIME24){
	return date_format(new DateTime($TIME24),"h\:i A");
}

/**
 *Checks if the date is today
 * @param string $date The date
 * @return boolean Is $dt today
 */
function isToday($date){
    $chk=date_parse($date);
	if($chk){
	//echo "<p>Today:".date("Y-m-d")."<br/>Date:".$date."</p>";
	$nw=date_parse(date("Y-m-d"));
		return($chk['day']==$nw['day']&&$chk['month']==$nw['month']&&$chk['year']==$nw['year']);
	}else return false;
}

/**
 *Checks if the value is a valid date using regex
 * @param string $value The input string to search
 * @return boolean Success match of the value against the regex
 */
function isDate($value){
	if(!empty($value)){
		return preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$value)==1;
	}else return false;
	
}

/**
 *Checks if the value is a valid date using regex
 * @param string $value The input string to search
 * @return boolean Success match of the value against the regex
 */
function isTime($value){
	return !empty($value)?preg_match('/^[0-2][0-9]\:[0-5][0-9]\:[0-5][0-9]$/',$value)==1:false;
}

function testIsTime(){
$arr=array('08:00:00','99:00:00','23:59:00','00:00:00');
	echo '<style type="text/css">span.g{color:green;}span.r{color:red;}</style>';
	foreach($arr as $a){
		printf("<span class=\"%s\">%s</span><br>\n",isTime($a)?'g':'r',$a);
	}
}

?>
