<?php 

/**
 *Generates a list of volunteers, used for autocomplete dropdown and encodes to json
 * @global mysqli_connect $vc The connection
 * @return string|false JSON encoded string or false on error
 */
function genVolunteerList($FILTER='all',$OUTPUT_TYPE='json'){
	global $vc;
	if(!$vc)
		return false;
	//Where Filter generation
	$where="";
	switch($FILTER){
		case 'all':Default:
			$where="";
			break;
		case 'activelog':
			$where="WHERE VolunteerID IN (SELECT DISTINCT VolunteerID FROM Checkin WHERE Active=1 AND DATE(TimeIn)=DATE(NOW()))";
			break;
		case 'activevolunteer':
			$where="WHERE VolunteerID IN (SELECT DISTINCT VolunteerID FROM volunteerlog)";
			break;
	}//end switch
	$qc=mysqli_query($vc,"SELECT CONCAT(FirstName,\" \",LastName)as \"Name\" FROM volunteer $where ORDER BY Name ASC;");
	if($qc){
		$rval=array();
		for($i=0;$i<mysqli_num_rows($qc);$i++){
			$rw=mysqli_fetch_row($qc);
				$rval[]=$rw[0];
			unset($rw);
		}
		mysqli_free_result($qc);
		unset($qc);
		switch($OUTPUT_TYPE){
			case 'array':default:
				return $rval;
			case 'json':
				@$open=fopen("script/usercache/users-".$FILTER.".json",'w');
				if($open)
					@fwrite($open,json_encode($rval));
				fclose($open);
				break;
			case 'json-data':
				return json_encode($rval);
			case 'xml-data':
				return(xmlrpc_encode($rval));
		}
            return true;
	}else return false;
}
?>