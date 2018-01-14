<!doctype html>
<html>
    <head>
        <title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
                img{border:none;}
        </style>
    </head>
    <body>
<?php
/**
*Page of timestamps for one volunteer
*@author Jesse Schwarz
*/
require("../../cvol.php");
require("../../lib/misc_mysql.php");
require("../../lib/datetime.php");

if(isset($_GET['vid'])){
$cnt=volunteerTimestampCount($_GET['vid'],true,false);//how many timestamps?

if($cnt>0){//1 or more
	$vname=getVolunteerFromID($_GET['vid']);//get the name of the volunteer
	if($vname){//if the name exists
		$vts=getVolunteerTimestamps($_GET['vid'],true,false);//get the timestamps
		if($vts){//if there is a timestamp
			foreach($vts as $vi){//add an item
				echo "<p>\n".
				"<a href=\"edit_ts.php?CheckID={$vi['CheckID']}\"><img alt=\"edit\" src=\"../../images/edit_timestamp.png\"/></a>\n ".
				"<a href=\"remove_timestamp.php?CheckID={$vi['CheckID']}\"><img alt=\"delete\" src=\"../../images/delete_timestamp.png\"/></a>\n ".
				"".ftime($vi['TimeIn'],true)."\n".
				"</p>\n";
			}
			unset ($vi,$vts);
		}
	}
}
}//end if isset
mysqli_close($vc);
?>
    </body>
</html>