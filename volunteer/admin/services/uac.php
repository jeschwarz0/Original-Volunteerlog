<!doctype html>
<html>
    <head>
        <title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
                img{border:none;}
				#main_report th{
					font-size:.9em;
					/*white-space: nowrap;*/
				}
				td.dt{
					color:rgb(108,63,13);/*DarkBrown*/
				}
        </style>
		<script type="text/javascript">
			function removeVol(VID){
				if(VID!=null&&confirm("Are you sure you want to remove this Volunteer and all associated records?"))
					window.location.replace("remove_vol.php?VID="+Number(VID));
			}
		</script>
    </head>
    <body>
<?php
/**
*Page of timestamps for one volunteer
*@author Jesse Schwarz
*/
require("../../cvol.php");
require("../../lib/datetime.php");
writeSummaryTable(//Write the table to iframe
	getRecords(//get the records from the database
		getOrderBy(//get the "ORDER BY" clause
			isset($_GET['orderby'])?$_GET['orderby']:"",//pass reference orderby
			isset($_GET['az'])?$_GET['az']=='on':false//pass reference a-z
		)
	),
	!(isset($_GET['ro'])&&$_GET['ro']=='on'),
	((isset($_GET['ro'])&&$_GET['ro']=='on')&&
	($_GET['orderby']=='sumhours'||$_GET['orderby']=='numrecords')?
		isset($_GET['az'])&&$_GET['az']=='on'?'az':'za':false)
	);//pass reference

mysqli_close($vc);

function writeSummaryTable($DETAIL_DATA,$INCLUDE_DELETE=true,$INCLUDE_NUMBERING=false){
	if(!empty($DETAIL_DATA)&&is_array($DETAIL_DATA)&&is_array($DETAIL_DATA[0])&&!empty($DETAIL_DATA)){
	printf(
		"\t<table id=\"main_report\">\n\t\t<tr>\n%s%s\t\t\t<th>Last Name</th>\n\t\t\t<th>First Name</th>\n\t\t\t<th>Last Account Activity</th>\n\t\t\t<th>Total Hours</th>\n\t\t</tr>\n",
		 $INCLUDE_DELETE?"\t\t\t<th class=\"button\"></th>\n\t\t\t<th class=\"button\"></th>\n":"",$INCLUDE_NUMBERING?"\t\t<th>Rank</th>\n":"" );
	$i=$INCLUDE_NUMBERING?$INCLUDE_NUMBERING=='az'?count($DETAIL_DATA):1:null;
		foreach($DETAIL_DATA as $d1){
			printf("\t\t<tr>\n%s%s\t\t\t<td>%s</td>\n\t\t\t<td>%s</td>\n\t\t\t<td class=\"dt\" title=\"%s\">%s</td>\n\t\t\t<td>%.2f/%d</td>\n\t\t</tr>\n",
			$INCLUDE_DELETE?
				"\t\t\t<td class=\"button\"><a href=\"edit_vol.php?VolunteerID={$d1['VolunteerID']}\"><img alt=\"edit\" src=\"../../images/edit_timestamp.png\"/></a></td>\n".
				"\t\t\t<td class=\"button\"><a href=\"javascript:removeVol({$d1['VolunteerID']});\"><img alt=\"delete\" src=\"../../images/delete_timestamp.png\"/></a></td>\n"
			:"",
			$INCLUDE_NUMBERING?"\t\t\t<td>".($INCLUDE_NUMBERING=='az'?$i--:$i++)."</td>\n":"",
			$d1['LastName'],$d1['FirstName'],fdate($d1['LastActivity'],true),formatDate($d1['LastActivity'],1),isset($d1['SumHours'])?$d1['SumHours']:0,$d1['NumLogs']);
		}//END FOREACH
	unset($d1,$i);
	echo "\t</table>\n";
	}else echo "<h4>No Records</h4>";
}

function getRecords($ORDERBY){
	global $vc;
	$qry="SELECT v.VolunteerID,v.LastName,v.FirstName,MAX(vl.Date) AS \"LastActivity\",SUM(vl.TotalHours) as \"SumHours\",COUNT(vl.LogID) as \"NumLogs\" FROM Volunteer v LEFT OUTER JOIN volunteerlog vl on v.VolunteerID=vl.VolunteerID GROUP BY v.VolunteerID $ORDERBY;";
	//fwrite(fopen("../query.sql","w"),$qry);//Print Query to file for debugging
	$res=mysqli_query($vc,$qry);
	unset($qry);//clean up space
	if($res){
			$rval=array();
			for($i=0;$i<mysqli_num_rows($res);$i++)
				$rval[$i]=mysqli_fetch_assoc($res);	
				mysqli_free_result($res);
			return($rval);
	}else return false;
}

function getOrderBy($ORDERBY_CODE,$AZ){
	switch($ORDERBY_CODE){
		case 'fname':
			return "ORDER BY FirstName ".($AZ==true?"ASC":"DESC");
		case 'lname':
			return "ORDER BY LastName ".($AZ==true?"ASC":"DESC");
		case 'lastlog':
			return "ORDER BY LastActivity ".($AZ==true?"ASC":"DESC");
		case 'sumhours':
			return "ORDER BY SumHours ".($AZ==true?"ASC":"DESC");
		case 'numrecords':
			return "ORDER BY NumLogs ".($AZ==true?"ASC":"DESC");
		case 'idx':
			return "ORDER BY VolunteerID ".($AZ==true?"ASC":"DESC");
		default:
			return "";
	}
}?>
</body>
</html>