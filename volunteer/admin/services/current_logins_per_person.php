<?php
/**
*Page of timestamps for one volunteer
*@deprecated Replaced by 2
*@author Jesse Schwarz
*/
require("../../lib/utils.php");

$cnt=volunteerTimestampCount($_COOKIE['vid']);//how many timestamps?

if($cnt>0){//1 or more
	$vname=getVolunteerFromID($_COOKIE['vid']);//get the name of the volunteer
	if($vname){//if the name exists
		echo "$cnt current login";
		if($cnt>1)//plural
			echo "s";
		echo " for ".$vname['FirstName'].' '.$vname['LastName'];//show name
		$vts=getVolunteerTimestamps($_COOKIE['vid'],true);//get the timestamps
		if($vts){//if there is a timestamp
			echo "<ol>\n";
		
			foreach($vts as $vi){//add an item
				echo "\t<li>".ftime($vi['TimeIn'])."\t<a href=\"../../services/remove_timestamp.php?CheckID={$vi['CheckID']}\">Remove</a></li>\n";
			}
			unset ($vi,$vts);
			echo "</ol>\n";
		}
	}
}
?>