<?php require("../cvol.php");require("../lib/dropdown.php");?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Admin-Log System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- JQuery -->
        <link type="text/css" href="../jquery/css/redmond/jquery-ui-1.8.21.custom.css" rel="stylesheet" >
        <script type="text/javascript" src="../jquery/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="../jquery/js/jquery-ui-1.8.21.custom.min.js"></script>
        <!-- Vanilla Code -->	
               
        <script type="text/javascript" src="../script/cookies.js"></script>
        <script type="text/javascript" src="../script/admin_all.js"></script>
        <link rel="stylesheet" type="text/css" href="../stylesheets/styles.css" >
        <!--<link rel="stylesheet" type="text/css" href="../stylesheets/links.css" >       Menu not needed-->
		<link rel="stylesheet" type="text/css" href="../stylesheets/admstyle.css" >
		<link rel="stylesheet" type="text/css" href="../stylesheets/horseshoe.css" >
	</head>
	<body>
        <!-- End Imports -->
        <noscript>
            <div id="ns">Enable <span id="ns-jscript">Javascript</span> to run this application</div>
        </noscript>
        <h1>Volunteer Login - Admin</h1>

        <div id="nav">
            <ul>
                <li><a href="#main">Home</a></li>
				<li><a href="#user">Users</a></li>
                <li><a href="#timestamps">Timestamps</a></li>
                <li><a href="#export">Export</a></li>
                <li><a href="#report">Report</a></li>
				<li><a href="#maintenance">Maintenance</a></li>
				<li><a href="#Logout">Logout</a></li>
            </ul>

            <!--*******************************************************************************************-->
            <!--								Start														-->
            <!--*******************************************************************************************-->

            <div id="main">
				<div id="sidelinks" style="float:left;">
                    <a href="http://www.ncride.com" target="_blank"><img alt="logo" src="../images/NCRIDElogo.png" style="top:-150px;"></a>
					<br>
				   <a href="javascript:logout();"><img alt="logout" src="../images/adm_logout.png"></a>
				 </div>
				 <div id="mainmsg" style="float:right;">
				<!--This is the admin interface for creating reports<br> and performing maintenance on the database.-->
				 </div>
				 <div class="clear"></div>
            </div>

			<div id="user">
				<form name="usermgmt" action="services/uac.php"	target="uout" method="GET">
				<div style="float:left">
				<table id="usumtable">
					<tr>
						<td>Sort By</td>
						<td>
							<select name="orderby">
								<option value="idx">NONE</option>
								<option value="fname">First Name</option>
								<option value="lname">Last Name</option>
								<option value="lastlog">Last Account Activity</option>
								<option value="sumhours">Total Hours</option>
								<option value="numrecords"># of Records</option>
							</select>
						</td>
					</tr><tr>
						<td>A-Z</td>
						<td>
							<input type="checkbox" name="az" id="azc" checked><label for="azc"></label>
						</td>
					</tr><tr>
						<td class="critical">Read Only</td>
						<td>
							<input type="checkbox" name="ro" id="dellock" checked><label for="dellock"></label>
						</td>
					</tr><tr>
						<td></td>
						<td><input type="submit" value="Generate User List"></td>
				</table>
				</div>
				</form>
				<iframe id="uout" name="uout" src=""></iframe>
				<div class="clear"></div>
			</div>
			
            <!--*******************************************************************************************-->
            <!--								Logout(Timestamp)										   -->
            <!--*******************************************************************************************-->
            <div id="timestamps">
				<fieldset>
				<legend>Unclosed Timestamps</legend>
                <form name="tso" target="curlogs" action="services/current_logins_per_person_v2.php" method="GET">
                   <!--<input type="hidden" name="submitted" value="true">-->
<?php echo genActiveLogVolunteerDropDown('vid',null,"submitTS();",false)?>
					<noscript><input type="submit" value="Update"></noscript>
                </form>
                <br>
                <iframe id="curlogs" name="curlogs" src=""></iframe>
				</fieldset>
				<fieldset>
					<legend>Batch</legend>
					<form name="tsmod" action="services/tsutils.php" method="GET">
						<fieldset class="subfield">
						<input type="radio" name="action" value="activate"> Activate<br>
						<input type="radio" name="action" value="deactivate" checked> Deactivate<br> 
						<input type="radio" name="action" value="delete" ><span class="critical"> Delete</span><br>
						</fieldset><br>
						<fieldset class="subfield">
						<input type="radio" name="filter" value="all" checked> All<br>
						<input type="radio" name="filter" value="old" > Before Today<br> 
						<input type="radio" name="filter" value="today"> Today<br>
						<input type="radio" name="filter" value="inactive"> Closed
						</fieldset><br>
						<input type="submit" value="Run" style="left:110px;position:relative;">
					</form>
				</fieldset>
            </div>


            <!--*******************************************************************************************-->
            <!--								Export(csv)										  		   -->
            <!--*******************************************************************************************-->

            <div id="export">
                <form name="ex" method="post" action="services/export.php">
                    <input type="hidden" name="export_type" value="csv" >
					<p id="exportres"></p>
                    <noscript>
					<br>
                    <input type="submit" value="Export" >
					</noscript>
                </form>
            </div>
            <!--*******************************************************************************************-->
            <!--								Report									  				   -->
            <!--*******************************************************************************************-->

			<div id="report">
				<form id="user_report" action="services/process_results.php" target="results" method="GET">
					<div id="FormHolder">
					<fieldset>
						<legend>Name</legend>
<?php 
					echo genActiveVolunteerDropDown('VID',-1000)?>
					</fieldset>
					<br>
					<fieldset>
						<legend>Date</legend>
						<label>Start:</label>
						<input id="StartDate" name="StartDate" type="text" size="9" maxlength="10" value="<?php echo date('Y-m-d');?>">
						<label>End:</label>
						<input id="EndDate" name="EndDate" type="text" size="9" maxlength="10" value="<?php echo date('Y-m-d');?>">
					<span class="note">Note: No date search if equal</span>
					</fieldset>
					<br>
					<fieldset>
					<legend>Tasks</legend>
					<table>
						<tr>
							<td>Class</td>
							<td><input type="checkbox" name="Class" id="c1"><label for="c1"></label></td>
						</tr><tr>
							<td>Office</td>
							<td><input type="checkbox" name="Office" id="c2"><label for="c2"></label></td>
						</tr><tr>
							<td>Maintenance</td>
							<td><input type="checkbox" name="Maintenance" id="c3"><label for="c3"></label></td>
						</tr><tr>
							<td>Conditioning</td>
							<td><input type="checkbox" name="Conditioning" id="c4"><label for="c4"></label></td>
						</tr><tr>
							<td>Horse Care</td>
							<td><input type="checkbox" name="HorseCare" id="c5"><label for="c5"></label></td>
						</tr><tr>
							<td>Committee</td>
							<td><input type="checkbox" name="Committee" id="c6"><label for="c6"></label></td>
						</tr><tr>
							<td>Board</td>
							<td><input type="checkbox" name="Board" id="c7"><label for="c7"></label></td>
						</tr><tr>
							<td>Jr. Volunteer</td>
							<td><input type="checkbox" name="JrVolunteer" id="c8"><label for="c8"></label></td>
						</tr><tr>
							<td>Special Olympics</td>
							<td><input type="checkbox" name="SpecialOlympics" id="c9"><label for="c9"></label></td>
						</tr><tr>
							<td>Other</td>
							<td><input type="checkbox" name="Other" id="c10"><label for="c10"></label></td>
						</tr>
					</table>
					<span class="note">Note: All searched if none are selected</span>
					</fieldset>
					<br>
					<fieldset>
						<legend>Comment</legend>
						<input type="radio" name="rcom" value="All" checked>Any
						<input type="radio" name="rcom" value="TS">From Timestamp
						<input type="radio" name="rcom" value="Blank">Empty
					</fieldset>
						<br>		
								<input type="checkbox" name="sum" id="csum" ><label for="csum"></label>Summary
								<input type="checkbox" name="ro" id="cro" checked><label for="cro" value="yes"></label><span class="critical">Read Only</span>
						<span id="bholder">
							<input type="reset" value="Reset"><input type="submit" value="Submit">
						</span>
					</div>
				</form>
				<iframe name="results" id="results"></iframe>
				<div class="clear"></div>
			</div>
			
			<!--*******************************************************************************************-->
            <!--								Maintenance										  		   -->
            <!--*******************************************************************************************-->

			<div id="maintenance">
				<fieldset>
					<legend>Database</legend>
					<a href="services/mutils.php?action=rtask"><img src="../images/remove_task.png" alt="Remove Unused Tasks"></a>
					<br>
					<a href="services/mutils.php?action=trep"><img src="../images/repair_tables.png" alt="Repair Tables"></a>
					<br>
					<fieldset class="subfield">
					<legend>Overhead</legend>
				<table>
<?php 			require("../lib/misc_mysql.php");
				$toh=findTableOverhead();
				foreach ($toh as $item){
					echo "\t\t\t\t<tr><td>{$item['Name']}</td><td>{$item['Overhead']}(bytes)</td></tr>\n";
				}
				unset($item,$toh);?>
				</table>
				<a href="services/mutils.php?action=topt"><img src="../images/optimize_tables.png" alt="Optimize Tables"></a>
				</fieldset>
				</fieldset>
				<br>
				<fieldset>
					<legend>Other</legend>
					<a href="services/mutils.php?action=excache"><img src="../images/clear_export_cache.png" alt="Clear Export Cache"></a>
				</fieldset>
				
				
			</div>

			<div id="Logout">
			<!--Intentionally Left Blank-->
			</div>
        </div><!-- End Nav -->
        <?php mysqli_close($vc); ?>
        </body>
</html>