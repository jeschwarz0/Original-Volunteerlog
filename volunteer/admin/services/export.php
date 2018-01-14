<?php
/**
 *Exports current logins to csv
 * @author Jesse Schwarz 
 */
require("../../cvol.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['export_type']=='csv') {
			$cres=exportCSV();
            if ($cres!=false) {
                header("Location: ".$cres);
            }
    }
}
mysqli_close($vc);
/**********************************************/
//			CSV Export Functions
/**********************************************/

/**
 *Export data as csv using fputcsv
 * @since 6/25/12 CSV was generated manually before
 * @return string|false The name of the generated file or false 
 */
function exportCSV() {
    $data=getFlatLog();
	//switchBooleans($data);@deprecated since 1.2.1, MySQL now does this
    //perform validation
    if (empty($data)||!is_array($data))
        return false;
    //actually write csv
	if(!is_dir('reportcache'))
		mkdir('reportcache');//make directory if not existing
	$fname="reportcache/Volunteer_Log(".date("d-m-Y").").csv";
	$file = fopen($fname, "w");
	if($file){
		foreach($data as $dataitem){
			if(fputcsv($file,$dataitem)==false){
				$rval=false;
				break;
			}
		}
		if(!isset($rval))
			$rval=$fname;
	}
		fclose($file);
		unset($file,$data,$fname);
		return $rval;
}

/**
 *Gets a flat(normalized) version of all login data from vLogins
 * @global msqli_connect $vc The connection
 * @return array|false The full data or false on error 
 */
function getFlatLog() {
    global $vc;
    $res = mysqli_query($vc, "SELECT * FROM vLogs;");
    if ($res) {
        $rval = array(array());
        $fds = mysqli_fetch_fields($res);
        for ($i = 0; $i < mysqli_num_fields($res); $i++) {
            $rval[0][$i] = $fds[$i]->name;
        }
		unset($i);
        for ($j = 1; $j < mysqli_num_rows($res)+1; $j++) {
            $rval[$j]=mysqli_fetch_row($res);
        }
		unset($j);
        mysqli_free_result($res);
        return $rval;
    }else
        return false;
}

/**
 *Set the binary data to boolean from $MIN to $MAX
 * @param array $DATA The data to change
 * @deprecated Since 1.2.1 MySQL now adds this feature to the View 'vlogs'
 */
function switchBooleans(&$DATA){
	$MIN=7;//First Field to switch
	$MAX=19;//Last Field to switch
	if(!empty($DATA)&&is_array($DATA)&&is_array($DATA[0])){
		for($l1=1;$l1<count($DATA);$l1++){
			for($i=$MIN;$i<$MAX;$i++){
				if($DATA[$l1][$i]==0)
					$DATA[$l1][$i]='false';
				else if($DATA[$l1][$i]==1)
					$DATA[$l1][$i]='true';
			}
		}
	}
}
?>