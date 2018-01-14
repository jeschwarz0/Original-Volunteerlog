<?php
/**
*Page of timestamps for one volunteer
*@author Jesse Schwarz
*/
require("../../cvol.php");
##	Condition:POST(Form Data Load) updates then shows blank page
if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_POST['CheckID'])){
	require("../../lib/datetime.php");//required for isValidChange pregs
	$uqry=getUpdateQuery();
	$res=!empty($uqry)?
		(mysqli_query($vc,$uqry)&&mysqli_affected_rows($vc)==1?
			"Timestamp has been updated!":
			"Error:".mysqli_error($vc)."\n<br>Query:$uqry")
		:"No Changes Made!";
	mysqli_close($vc);
	unset($vc);//close database connection
	die("<html><head><script type=\"text/javascript\">window.setTimeout('window.location.replace(\"{$_POST['refurl']}\");',4000);</script></head><body><h3 style=\"padding-top:0%;text-align:center;\">$res</h3></body></html>");

##	Condition:GET(init load) shows form
}elseif($_SERVER['REQUEST_METHOD']=='GET'&&isset($_GET['CheckID'])){
	require("../../lib/dropdown.php");
	$dat=getRecord($_GET['CheckID']);
	//print_r($dat);
}else{##	Condition, Other(no form) returns to sender
	header("location: {$_SERVER['HTTP_REFERER']}");
}

function getUpdateQuery(){
	$ORIG=getRecord($_POST['CheckID']);
	$rval;
	$_POST['Active']=isset($_POST['Active'])&&$_POST['Active']=='on';
	foreach(array_keys($_POST) as $ti){
		//if($ti=='Date'&&isset($ORIG[$ti])||($_POST[$ti]!=$ORIG[$ti]&&isValidChange($ti,$_POST[$ti])))
		switch($ti){
			case 'LogID':default:
				break;
			case 'VolunteerID':case 'Active':
				if($_POST[$ti]!=$ORIG[$ti])
					appendToUpdate($rval,$ti,$_POST[$ti]);
				break;
			case 'Date':
				if(
					isset($_POST['Time'])&&(
						($_POST['Date']!=$ORIG['Date']&&isValidChange('Date',$_POST['Date']))
						||
						($_POST['Time']!=$ORIG['Time']&&isValidChange('Time',$_POST['Time'])))
					)	appendToUpdate($rval,'TimeIn',$_POST['Date']." ".$_POST['Time']);
				break;
		}//end switch
	}
	return empty($rval)? false : $rval." WHERE CheckID={$_POST['CheckID']};";
}

function appendToUpdate(&$OUT,$FIELDNAME,$DATA){
	$OUT.=(empty($OUT)?"UPDATE checkin SET":", ")." $FIELDNAME = ".(is_numeric($DATA)?"":"'").$DATA.(is_numeric($DATA)?"":"'");
}
#@requires datetime.php
function isValidChange($TITLE,$DATA){
	switch($TITLE){
	case 'VolunteerID':
		return is_numeric($DATA)&&$DATA!=-1000;
	case 'Date':
		return isDate($DATA);
	case 'Time':
		return isTime($DATA);
	case 'Active':
		return true;
	default:
		return false;
	}
}
function getRecord($CheckID){
	global $vc;
	$qry="SELECT CheckID,VolunteerID,DATE(TimeIn)AS \"Date\",TIME(TimeIn) AS \"Time\",IF(Active,1,0) AS \"Active\" FROM checkin WHERE CheckID=$CheckID LIMIT 1;";
	//fwrite(fopen("query.sql","w"),$qry);//Print Query to file for debugging
	$res=mysqli_query($vc,$qry);
	unset($WHERE,$qry);//clean up space
	if($res){
		$rval=mysqli_fetch_assoc($res);	
		mysqli_free_result($res);
		return($rval);
	}else return false;
}
?>
<!doctype html>
<html>
    <head>
        <title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
<div id="manual">
<form id="editvlog" action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
<input type="hidden" name="CheckID" value="<?php echo isset($_GET['CheckID'])?$_GET['CheckID']:'';?>">
<input type="hidden" name="refurl" value="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'about:blank';?>">
	<table id="tEditVlog">
		<tr>
			<td>Volunteer:</td>
			<td><?php echo genVolunteerDropDown('VolunteerID',isset($dat['VolunteerID'])?$dat['VolunteerID']:null);?></td>
		</tr><tr>
			<td>Date: </td>
			<td><input name="Date" type="text" size="9" maxlength="10" title="yy-mm-dd" value="<?php if(isset($dat['Date']))echo $dat['Date'];?>"></td>
		</tr><tr>
			<td>Time In:</td>
			<td><input name="Time" type="text" size="8" maxlength="8" title="hour(1-24):minute(0-59):second(0-59)" value="<?php if(isset($dat['Time']))echo $dat['Time'];?>"></td>
		</tr><tr>
			<td>Active:</td>
			<td><input type="checkbox" name="Active" <?php if(isset($dat['Active'])&&$dat['Active']==1)echo "checked";unset($dat);?>></td>
		</tr><tr>
			<td><input type="reset" value="Reset"></td>
			<td><input type="submit" value="Update"></td>
	</table>
</form>
</div>
</body>
</html>