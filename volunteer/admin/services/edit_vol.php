<?php
/**
*Page of timestamps for one volunteer
*@author Jesse Schwarz
*/
require("../../cvol.php");
##	Condition:POST(Form Data Load) updates then shows blank page
if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_POST['VolunteerID'])){
	$uqry="";
	{#gs:get update statement
	$ORIG=getRecord($_POST['VolunteerID']);
	foreach(array_keys($_POST) as $ti){
		if(
			isset($ORIG[$ti])&&//If original variable is set
			$ti!='VolunteerID'&&$_POST[$ti]!=$ORIG[$ti]&&isValidChange($ti,$_POST[$ti])//check for unequal values, validate new value
		)	appendToUpdate($uqry,$ti,isset($_POST[$ti])&&($ti=='FirstName'||$ti=='LastName')?str_replace(' ','',$_POST[$ti]):"");//add to update statement
	}}//end foreach, groupingset
	require("../../lib/misc_mysql.php");
	$res=!empty($uqry)&&volunteerNE()?//check that there is no existing volunteer w/that name
		(mysqli_query($vc,"$uqry WHERE VolunteerID={$_POST['VolunteerID']};")&&mysqli_affected_rows($vc)==1?
			"Volunteer #{$_POST['VolunteerID']} has been updated!":
			"Error:".mysqli_error($vc)." in query $uqry WHERE VolunteerID={$_POST['VolunteerID']};")
		:'No Changes Made!';
	mysqli_close($vc);
	unset($vc);
	die("<html><head><script type=\"text/javascript\">window.setTimeout('window.location.replace(\"{$_POST['refurl']}\");',4000);</script></head><body><h3 style=\"padding-top:17%;text-align:center;\">$res</h3></body></html>");

##	Condition:GET(init load) shows form
}elseif($_SERVER['REQUEST_METHOD']=='GET'&&isset($_GET['VolunteerID'])){
	require("../../lib/dropdown.php");
	$dat=getRecord($_GET['VolunteerID']);
}else{##	Condition, Other(no form) returns to sender
	header("location: {$_SERVER['HTTP_REFERER']}");
}

function appendToUpdate(&$OUT,$FIELDNAME,$DATA){
	global $vc;
	$OUT.=(empty($OUT)?"UPDATE Volunteer SET":", ")." $FIELDNAME = '".trim(htmlspecialchars(mysqli_real_escape_string($vc,$DATA)))."'";
}
#@requires misc_mysql.php
function volunteerNE(){
	global $vc;
	return getVolunteerIDFromName(mysqli_real_escape_string($vc,$_POST['FirstName'].' '.$_POST['LastName']))==false;
}

function isValidChange($TITLE,$DATA){
	global $vc;
	switch($TITLE){
	case 'VolunteerID':
		return is_numeric($DATA)&&$DATA!=-1000;
	case 'FirstName':case 'LastName':
		return strlen($DATA)<=45&&strlen($DATA)>2;
	default:
		return false;
	}
}
function getRecord($VolunteerID){
	global $vc;
	$qry="SELECT * FROM Volunteer WHERE VolunteerID=$VolunteerID LIMIT 1;";
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
<input type="hidden" name="VolunteerID" value="<?php echo isset($_GET['VolunteerID'])?$_GET['VolunteerID']:'';?>">
<input type="hidden" name="refurl" value="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'about:blank';?>">
	<h3>Edit Log</h3>
	<hr>
	<table id="tEditVol">
		<tr>
			<td>First Name:</td>
			<td><input name="FirstName" type="text" size="12" maxlength="45" value="<?php echo isset($dat['FirstName'])?$dat['FirstName']:"";?>"></td>
		</tr><tr>
			<td>Last Name:</td>
			<td><input name="LastName" type="text" size="12" maxlength="45" value="<?php echo isset($dat['LastName'])?$dat['LastName']:"";?>"></td>
		</tr><tr>
			<td><input type="reset" value="Reset"></td>
			<td><input type="submit" value="Update"></td>
	</table>
</form>
</div>
</body>
</html>