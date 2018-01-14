<?php
	@$vc=mysqli_connect("127.0.0.1","appuser","w7txXWST37GZTvCf","VolunteerLog");
	if(!$vc){//database problems
		die(genErrorPage(mysqli_connect_errno($vc)==2003));
	}
	
	/**Generates an error page, telling the user there is a database problem
	*@param $NO_DB Boolean Is the problem #2003/Database not found? Prints a special message if that problem is encountered
	*/
	function genErrorPage($NO_DB){
		global $vc;
		return 
'<!DOCTYPE html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link type="text/css" href="/jquery/css/redmond/jquery-ui-1.8.21.custom.css" rel="stylesheet" >
		<script type="text/javascript" src="/jquery/js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/jquery/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript">
			$(function() {
				$(\'#nav\').tabs();
			});//end function(startup when ready)
		</script>
		<!--the main stylesheet-->
		<link type="text/css" href="/stylesheets/styles.css" rel="stylesheet">
	</head>
	<body>
		<div id="nav">
			<ul>
				<li><a href="#err">Error</a></li>
			</ul>
		
		<div id="err">
		<h2>
		'.($NO_DB?'No Database':'Other Database Problem(#'.mysqli_connect_errno($vc).')').'</h2>
		<p>
		'.($NO_DB?'The database is shut down and cannot store data. To fix this problem, open "services.msc" and start "mysql"':mysqli_connect_error($vc)).'.'.
		'</p>
		</div>
		</div>
	</body>
</html>';
	}
?>