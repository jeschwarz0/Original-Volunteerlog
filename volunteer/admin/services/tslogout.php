<!doctype html>
<!-- //@DEPRECATED Not implemented due to time constraints -->
<html>
<head>
 <!-- JQuery -->
        <link type="text/css" href="../../jquery/css/redmond/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="../../jquery/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="../../jquery/js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript">
/*
code for timestamp logout manual - not implemented
tslogout.php?CheckID={$vi['CheckID']}
*/
$(function() {
$( "#ts_edit_task" ).dialog({
					height: 650,
					width: 650,
					autoOpen: true,
					modal: true
});
$( "#ts_edit_time" ).dialog({
					height: 650,
					width: 650,
					autoOpen: false,
					modal: true
});
});

function showEditTask(CheckID){
	$('#ts_edit_task').dialog('open');
}


function setOtherVis(){
		if(document.getElementById("Other").checked==true)
			$('#odes').css("display","inherit");
		else if(document.getElementById("Other").checked==false){
			$('#odes').css("display","none");
			$('#odes').html("");//blank the box
		}else
			alert("unusual visibility state :(");
	}
</script>
</head>
<body style="background-color:black;">		
<?php
/** Exports current logins to csv
 * @author Jesse Schwarz 
 */
require("../../cvol.php");
require("../../lib/essentials.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$cid=mysqli_real_escape_string($vc,$_GET['CheckID']);
	if(!empty($cid)&&is_numeric($cid)){
		
		
	}else die("Invalid CheckID");
}else die( "No form submitted");
	
?>




<div id="ts_edit_task" title="Add a task">
	<h4>What did you do?</h4>
<form id="newtask" method="post" action="services/add_task.php" onsubmit="return validateTask();">
<?php
if(!empty($_COOKIE['TaskID']))
	$curtask=getTaskFromID($vc,$_COOKIE['TaskID']);
?>

<fieldset>
<legend>Task:</legend>
<table>
	<tr>
		<td>Class</td>
		<td>
			<input type="checkbox" name="Class" <?php stickBool("Class",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Office Work</td>
		<td>
			<input type="checkbox" name="Office" <?php stickBool("Office",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Maintenance/Grounds</td>
		<td>
			<input type="checkbox" name="Maintenance" <?php stickBool("Maintenance",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Conditioning</td>
		<td>
			<input type="checkbox" name="Conditioning" <?php stickBool("Conditioning",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Horse Care</td>
		<td>
			<input type="checkbox" name="HorseCare" <?php stickBool("HorseCare",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Committee</td>
		<td>
			<input type="checkbox" name="Committee" <?php stickBool("Committee",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Board</td>
		<td>
			<input type="checkbox" name="Board" <?php stickBool("Board",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Side-walking</td>
		<td>
			<input type="checkbox" name="Sidewalking" <?php stickBool("Sidewalking",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Horse Leading</td>
		<td>
			<input type="checkbox" name="HorseLeading" <?php stickBool("HorseLeading",$curtask);?>/>
		</td>
	</tr>
	<tr>
		<td>Other</td>
		<td>
			<input type="checkbox" id="Other" name="Other" onchange="setOtherVis();" <?php stickBool("Other",$curtask);?>/>
			
		</td><td>
		<textarea id="odes" name="OtherDesc" rows="5" cols="30"><?php if(!empty($curtask['OtherDescription']))echo $curtask['OtherDescription'];unset($curtask)?></textarea>
		 <script type="text/javascript">setOtherVis();</script>
		</td>
	</tr>
	<tr>
		<td><input type="submit" value="Add Task"/></td>
	</tr>
</table>

</fieldset>
</form>				
</div>
<div id="ts_edit_time" title="Logout Time">
				
</div>

</body>
</html>