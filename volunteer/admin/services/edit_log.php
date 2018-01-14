<?php
/**
*Page of timestamps for one volunteer
*@author Jesse Schwarz
*/
require("../../cvol.php");
##	Condition:POST(Form Data Load) updates then shows blank page
if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_POST['LogID'])){
	$uqry="";
	{#gs:get update statement
	$ORIG=getRecord($_POST['LogID']);
	require("../../lib/datetime.php");//required for isValidChange pregs
	foreach(array_keys($_POST) as $ti){
		if(
			isset($ORIG[$ti])&&//If original variable is set
			$ti!='LogID'&&$_POST[$ti]!=$ORIG[$ti]&&isValidChange($ti,$_POST[$ti])//check for unequal values, validate new value
		)	appendToUpdate($uqry,$ti,isset($_POST[$ti])?$_POST[$ti]:"");//add to update statement
	}}//end foreach, groupingset
	$res=!empty($uqry)?
		(mysqli_query($vc,"$uqry WHERE LogID={$_POST['LogID']};")&&mysqli_affected_rows($vc)==1?
			"Log #{$_POST['LogID']} has been updated!":
			"Error:".mysqli_error($vc)." in query $uqry WHERE LogID={$_POST['LogID']};")
		:'No Changes Made!';
	mysqli_close($vc);
	unset($vc);//close database connection
	die("<html><head><script type=\"text/javascript\">window.setTimeout('window.location.replace(\"{$_POST['refurl']}\");',4000);</script></head><body><h3 style=\"padding-top:50%;text-align:center;\">$res</h3></body></html>");

##	Condition:GET(init load) shows form
}elseif($_SERVER['REQUEST_METHOD']=='GET'&&isset($_GET['LogID'])){
	require("../../lib/dropdown.php");
	$dat=getRecord($_GET['LogID']);
}else{##	Condition, Other(no form) returns to sender
	header("location: {$_SERVER['HTTP_REFERER']}");
}

function appendToUpdate(&$OUT,$FIELDNAME,$DATA){
	global $vc;
	$OUT.=(empty($OUT)?"UPDATE VolunteerLog SET":", ")." $FIELDNAME = '".htmlspecialchars(mysqli_real_escape_string($vc,$DATA))."'";
}
#@requires datetime.php
function isValidChange($TITLE,$DATA){
	switch($TITLE){
	case 'VolunteerID':
		return is_numeric($DATA)&&$DATA!=-1000;
	case 'Date':
		return isDate($DATA);
	case 'TimeIn':case 'TimeOut':
		return isTime($DATA);
	case 'Comment':
		return strlen($DATA)<=16777215;
	default:
		return false;
	}
}
function getRecord($LogID){
	global $vc;
	$qry="SELECT LogID,VolunteerID,Date,TimeIn,TimeOut,Comment FROM volunteerlog WHERE LogID=$LogID LIMIT 1;";
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
		<!-- JQuery -->
        <link type="text/css" href="../../jquery/css/redmond/jquery-ui-1.8.21.custom.css" rel="stylesheet" >
        <script type="text/javascript" src="../../jquery/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="../../jquery/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript">$(function(){$("#dtpick").datepicker({dateFormat: "yy-mm-dd"});});</script>
    </head>
    <body>
<div id="manual">
<form id="editvlog" action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
<input type="hidden" name="LogID" value="<?php echo isset($_GET['LogID'])?$_GET['LogID']:'';?>">
<input type="hidden" name="refurl" value="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'about:blank';?>">
	<h3>Edit Log</h3>
	<hr>
	<table id="tEditVlog">
		<tr>
			<td>Name:</td>
			<td><?php echo genVolunteerDropDown('VolunteerID',isset($dat['VolunteerID'])?$dat['VolunteerID']:null);?></td>
		</tr><tr>
			<td>Date: </td>
			<td><input id="dtpick" name="Date" type="text" size="9" maxlength="10" value="<?php echo isset($dat['Date'])?$dat['Date']:"";?>"></td>
		</tr><tr>
			<td>Time In:</td>
			<td><input id="TimeIn" name="TimeIn" type="text" size="8" maxlength="8" value="<?php echo isset($dat['TimeIn'])?$dat['TimeIn']:"";?>"></td>
		</tr><tr>
			<td>Time Out:</td>
			<td><input id="TimeOut" name="TimeOut" type="text" size="8" maxlength="8" value="<?php echo isset($dat['TimeOut'])?$dat['TimeOut']:"";?>"></td>
		</tr><tr>
			<td>Comment:</td>
			<td><textarea name="Comment" maxlength="16777215" rows="4" cols="10"><?php echo isset($dat['Comment'])?$dat['Comment']:"";unset($dat);?></textarea></td>
		</tr><tr>
			<td><input type="reset" value="Reset"></td>
			<td><input type="submit" value="Update"></td>
	</table>
</form>
</div>
</body>
</html>