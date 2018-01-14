<?php require("cvol.php");require("lib/ac_dropdown.php");//require("lib/dropdown.php");#no longer used 
/*Load Volunteer data*/genVolunteerList('all','json');genVolunteerList('activelog','json');?>
<!DOCTYPE HTML>
<html>
<head>
<title>Volunteer Input Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- JQuery -->
	<link type="text/css" href="jquery/css/redmond/jquery-ui-1.8.21.custom.css" rel="stylesheet" >
	<script type="text/javascript" src="jquery/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="jquery/js/jquery-ui-1.8.21.custom.min.js"></script>
<!-- Vanilla Code -->	
	<script type="text/javascript" src="script/main.js"></script>
	<script type="text/javascript" src="script/validation.js"></script>
	<script type="text/javascript" src="script/cookies.js"></script>
	<link rel="stylesheet" type="text/css" href="stylesheets/styles.css" >
	<link rel="stylesheet" type="text/css" href="stylesheets/links.css" >
	<link rel="stylesheet" type="text/css" href="stylesheets/horseshoe.css" >
<!-- End Imports -->
</head>
<body>
<noscript>
	<div id="ns">Enable <span id="ns-jscript">Javascript</span> to run this application</div>
	<style type="text/css" rel="stylesheet">#nav{display:none;}</style>
</noscript>
<h1>North Country R.I.D.E. Volunteer Sign-in</h1>

<div id="nav">
	<ul>
		<li><a href="#start">Start</a></li>
		<li><a href="#join">Join</a></li>
		<li><a href="#task">Task</a></li>
		<li><a href="#login">Login</a></li>
		<li><a href="#logout">Logout</a></li>
		<li><a href="#manual">Manual</a></li>
		<li><a href="#done">Done</a></li>
	</ul>

<!--*******************************************************************************************-->
<!--								Start														-->
<!--*******************************************************************************************-->

<div id="start">
<a href="http://www.ncride.com" target="_blank"><img alt="logo" src="images/NCRIDElogo.png" style="top:-150px;"></a>
	<ul id="menu">
		<li><a href="javascript:startLogin();">Login</a></li>
		<li><a href="javascript:startLogout();">Logout</a></li>
		<li><a href="javascript:startManualLog();">Manual</a></li>
		<li><a href="javascript:startNewVol();">Join</a></li>
		<li><a href="admin/">Admin</a></li>
	</ul>
	<div>
	<br><br><br><br>
	<h2>Info</h2>
	<p></p>
	<p class="info">Please enter your hours.<br>If possible, use "Login" and "Logout" for convenience, otherwise use "Manual".</p>
	</div>
</div>
	
<!--*******************************************************************************************-->
<!--								Add Volunteer											   -->
<!--*******************************************************************************************-->

<div id="join">
	<form name="nvol" method="post" action="services/new_volunteer.php" onsubmit="return(validateVolunteer());">
		<table>
			<tr>
				<td>First Name:</td>
				<td><input type="text" name="FirstName" maxlength="50" ></td>
				<td><span id="FirstNameErr" class="error"></span></td>
			</tr><tr>
				<td>Last Name:</td>
				<td><input type="text" name="LastName" maxlength="50" ></td>
				<td><span id="LastNameErr" class="error"></span></td>
			</tr>
		</table>
		<input type="submit" value="Join" style="left:253px;position:relative;">
	</form>
</div>

<!--*******************************************************************************************-->
<!--								TASK														-->
<!--*******************************************************************************************-->

<div id="task">
<h4>What did you do?</h4>
<form id="newtask" method="post" action="services/add_task.php" onsubmit="return validateTask();">

<fieldset>
<legend>Task:</legend>
<table>
	<tr>
		<td>Class</td>
		<td>
			<input type="checkbox" name="Class" id="c1"><label for="c1"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Office Work</td>
		<td>
			<input type="checkbox" name="Office" id="c2"><label for="c2"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Maintenance/Grounds</td>
		<td>
			<input type="checkbox" name="Maintenance" id="c3"><label for="c3"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Conditioning</td>
		<td>
            <input type="checkbox" name="Conditioning" id="c4"><label for="c4"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Horse Care</td>
		<td>
			<input type="checkbox" name="HorseCare" id="c5"><label for="c5"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Committee</td>
		<td>
			<input type="checkbox" name="Committee" id="c6"><label for="c6"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Board</td>
		<td>
			<input type="checkbox" name="Board" id="c7"><label for="c7"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Jr. Volunteer</td>
		<td>
			<input type="checkbox" name="JrVolunteer" id="c8"><label for="c8"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Special Olympics</td>
		<td>
			<input type="checkbox" name="SpecialOlympics" id="c9"><label for="c9"></label>
		</td><td></td>
	</tr>
	<tr>
		<td>Other</td>
		<td>
			<input type="checkbox" id="Other" name="Other" onchange="setOtherVis();"><label for="Other"></label>
			
		</td><td>
		<textarea id="odes" name="OtherDescription" rows="5" cols="30"></textarea>
		 <script type="text/javascript">setOtherVis();</script><!--Sets the "other" text field off on start-->
		</td>
	</tr>
	<tr>
		<td><input type="submit" value="Add Task" style="left:107px;position:relative;"></td><td></td><td></td><!--To even the column count-->
	</tr>
</table>

</fieldset>
</form>
</div>


<!--*******************************************************************************************-->
<!--								Login(Timestamp)											-->
<!--*******************************************************************************************-->

<div id="login">
<form name="ts" method="post" action="services/add_timestamp_ac.php" onsubmit="return(validateCheckin());">
<!--?php echo genVolunteerDropDown('vid',null,'')?
<input id="vid2" name="vid2">-->

<input id="vname-login" name="vname" title="Enter your name (first last)...">
<input type="submit" value="Sign In"><br>
<span>Not here, <a href="javascript:startNewVol();">Join</a></span><br>
<span id="TimestampErr" class="error"></span>
</form>
</div>


<!--*******************************************************************************************-->
<!--								Logout(Timestamp)										   -->
<!--*******************************************************************************************-->

<div id="logout">
	<form name="tso" method="post" action="services/sign_out_ac.php" onsubmit="return(validateCheckout());">
		<input type="hidden" name="TaskID" value="<?php 
		if(isset($_COOKIE['TaskID']))echo $_COOKIE['TaskID'];?>">
		<input id="vname-logout" name="vname" title="Enter your name (first last), you must have a checkin...">
<!--?php echo genActiveLogVolunteerDropDown('vid',null,null,true)?-->
		<input type="submit" value="Sign Out"><br>
		<p id="SignOutErr" class="error"></p>
	</form>
</div>

<!--*******************************************************************************************-->
<!--								Manual Login											-->
<!--*******************************************************************************************-->

<div id="manual">
<form id="mvol" action="services/add_log_ac.php" method="post" onsubmit="return validateVLOG();">
<input type="hidden" name="TaskID" value="<?php if(isset($_COOKIE['TaskID']))echo $_COOKIE['TaskID'];?>" >
<label>Name:</label>
<!--?php echo genVolunteerDropDown('vid');?-->
<input id="vname-manual" name="vname" title="Enter your name (first last)...">
<span>Not here, <a href="javascript:startNewVol();">Join</a></span><br>
<br>
<label>Date: </label>
<input id="Date" name="Date" type="text" size="9" maxlength="10" value="<?php echo date('Y-m-d');?>">
<br>
<fieldset>
<!--##Sticky time to current, currently disabled##
php //Generate Now, ibias for dropdowns
/*
$this_hour=date("h");
$this_tp=date("A");	
$this_minute=date("i");
*/
?-->
<legend>In:</legend>
<select name="HourIn">
	<option value="1" selected="selected">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	<option value="11">11</option>
	<option value="12">12</option>
</select>
<select name="MinuteIn">
	<option value="0" selected="selected">0</option>
	<option value="5">5</option>
	<option value="10">10</option>
	<option value="15">15</option>
	<option value="20">20</option>
	<option value="25">25</option>
	<option value="30">30</option>
	<option value="35">35</option>
	<option value="40">40</option>
	<option value="45">45</option>
	<option value="50">50</option>
	<option value="55">55</option>
</select>
<select name="AMIn">
	<option value="1" selected="selected">AM</option><!--?php //stickPM($this_tp,'AM');?-->
	<option value="0" >PM</option><!--?php //stickPM($this_tp,'PM');?-->
</select>
</fieldset>	

<fieldset>
<legend>Out:</legend>
<select name="HourOut">
	<option value="1" selected="selected">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	<option value="5">5</option>
	<option value="6">6</option>
	<option value="7">7</option>
	<option value="8">8</option>
	<option value="9">9</option>
	<option value="10">10</option>
	<option value="11">11</option>
	<option value="12">12</option>
</select>
<select name="MinuteOut">
	<option value="0" selected="selected">0</option>
	<option value="5">5</option>
	<option value="10">10</option>
	<option value="15">15</option>
	<option value="20">20</option>
	<option value="25">25</option>
	<option value="30">30</option>
	<option value="35">35</option>
	<option value="40">40</option>
	<option value="45">45</option>
	<option value="50">50</option>
	<option value="55">55</option>
</select>
<select name="AMOut">
	<option value="1" Selected="selected">AM</option><!--?php //stickPM($this_tp,'AM');?-->
	<option value="0" >PM</option><!--?php //stickPM($this_tp,'PM');?-->
</select>
<!--?php unset($this_hour,$this_minute,$this_tp);?-->
</fieldset>
<br>
<fieldset>
<legend>Comment:</legend>
<textarea name="Comment" rows="4" cols="50"></textarea>
</fieldset>
<br>
<input type="submit" value="Submit" style="left:416px;position:relative;">
</form>
</div>


<!--*******************************************************************************************-->
<!--								Done													   -->
<!--*******************************************************************************************-->

<div id="done">
	<div id="output_message">
		<img id="dmesg-icon" src="" alt="Icon">
		<p id="dmesg"></p>
	</div>
	<a href="./"><img alt="Back" src="images/back.png"></a>
</div>
<?php mysqli_close($vc);?>
</div>
</body>
</html>