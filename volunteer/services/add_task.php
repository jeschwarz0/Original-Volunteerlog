<?php
/**
*Add a Task
*Reuses index of existing record
*@author Jesse Schwarz
*/
require("../lib/essentials.php");
require("../cvol.php");

if($_SERVER['REQUEST_METHOD']=='POST'){//must be submitted
	/*echo "<p style=\"color:red\">";
	print_r($_POST);
	echo "</p>";*/
    $res=insertTask(loadItems(),$_POST['OtherDescription']);
	mysqli_close($vc);
	if($res){//insert task
			setTaskCookie($res);//set the global cookie of the current task
			unset($res);
			if($_COOKIE['LOGOUT_TYPE']==0)
				header("Location: ../index.php#logout");//you are using timestamps
			elseif($_COOKIE['LOGOUT_TYPE']==1)
				header("Location: ../index.php#manual");//you are using manual login
	}
}
//header("Location: ../index.php");//something went wrong :(


/**
*Inserts a task into the database
*@param $vc mysqli_connect The database connection
*@param $VALUES boolean array The boolean values
*@param $COMMENT String Optional, the comment
*@return int|false Index of existing or new task
*/
function insertTask($VALUES,$COMMENT){
	global $vc;
	if(empty($VALUES)||!$vc)
		return false;//values or database invalid
	$index=hasTask($VALUES,$COMMENT);
	if($index!=false&&is_numeric($index)){
		return $index;
	}else{//insert new record
		
	$index=getNewKey($vc,'volunteertask','TaskID');//Get the key for the index
	$qry="INSERT INTO volunteertask VALUES(".$index.',';
		foreach($VALUES as $item){//add item to query
			$qry.=$item.',';
		}
	unset($item);
	$qry.="'".cleanString($vc,$COMMENT)."');";
	$res=mysqli_query($vc,$qry);
	unset($qry);
		if($res&&mysqli_affected_rows($vc)==1){//if only one row is affected
			return $index;
		}else return false;
	}
}

/**
*Check if a task exists
*@param $vc mysqli_connect The database connection
*@param $VALUES boolean array The boolean values
*@param $COMMENT String Optional, the comment
*@return int|false Index of existing task or false on error
*/
function hasTask($VALUES,$COMMENT){
	global $vc;
	if($vc){
		$fval=$VALUES;
		$fval['OtherDescription']=$COMMENT;
		$qry="SELECT TaskID FROM VolunteerTask WHERE ".//compile values query
		getCheckLine('Class',$fval).getCheckLine('Office',$fval).getCheckLine('Maintenance',$fval).
		getCheckLine('Conditioning',$fval).getCheckLine('HorseCare',$fval).getCheckLine('Committee',$fval).
		getCheckLine('Board',$fval).
		getCheckLine('JrVolunteer',$fval).getCheckLine('SpecialOlympics',$fval).
		getCheckLine('Other',$fval).getCheckLine('OtherDescription',$fval,true,true).";";
		$res=mysqli_query($vc,$qry);
		unset($qry,$fval);
			if($res&&mysqli_num_rows($res)>0){//if there are rows
				$rv=mysqli_fetch_row($res);
				return $rv[0];//return first row index
			}else return false;//something went wrong:(
	}else return false;//something went wrong:(
}

/**
 *Gets a valid check line with item $NAME in $VALUES
 * @param string $NAME The display name and item identifier
 * @param array $VALUES The values to get the check line from
 * @param boolean $IS_STRING Is the item a string?
 * @param boolean $IS_LAST Is the item the last one?
 * @return string|false A Where clause check line with AND or false on error
 */
function getCheckLine($NAME,$VALUES,$IS_STRING=false,$IS_LAST=false){
	if(!empty($NAME)&&is_array($VALUES)){
		$rval="$NAME = ";
		if($IS_STRING==true)
			$rval.="'";
		$rval.=$VALUES[$NAME];
		if($IS_STRING==true)
			$rval.="'";
		if($IS_LAST==false)
			$rval.=" AND ";
		return $rval;
	}else return false;
}

/**
 *Sets a global cookie for the task
 * @param int $TaskID The new TaskID to set the cookie to
 * @return boolean True on success or false on error
 */
function setTaskCookie($TaskID){
	if(!empty($TaskID)&&is_numeric($TaskID)){
		setcookie('TaskID',$TaskID,0,"/");
		//$_COOKIE['TaskID']=$TaskID;
		return true;
	}else return false;
}

/**
 *Load the data from $_POST, requires getItem
 * @return array The state of each boolean value in volunteertask
 */
function loadItems(){
	$TITLES=array('Class','Office','Maintenance','Conditioning','HorseCare','Committee','Board','JrVolunteer','SpecialOlympics','Other');
	$rarray=array();
		foreach($TITLES as $iname){
			getItem($iname,$rarray);
		}
	return $rarray;
}

/**
 *Checks for the item with $ALIAS in $list
 * @param string $ALIAS The name of the item to search for
 * @param array $list Referenced array of data
 */
function getItem($ALIAS,&$list){
	if(!isset($_POST[$ALIAS])||($_POST[$ALIAS])=='off')
		$list[$ALIAS]=0;
	elseif(($_POST[$ALIAS])=='on')
		$list[$ALIAS]=1;
	else{
		$list[$ALIAS]=0;
	}
}

/**
 *Converts the value to binary 1 or 0
 * @param string $VALUE The value to convert
 * @return int|false 0 if off, 1 if on, otherwise false 
 */
function cBin($VALUE){
	if($VALUE=='off')
		return 0;
	elseif ($VALUE=='on')
		return 1;
	else return false;
}

//$FORMAT 0:Array 1:POST ARRAY
function loadItemList($FORMAT=0){
	$open=fopen("../lib/tasklist.json",'r');
	if($open){
		$dat=fread($open,99999);
		if($dat){
		fclose($open);
		unset($open);
		$rvaltemp=json_decode($dat);
		unset($dat);
			switch($FORMAT){
				case 0:default:
					return $rvaltemp;
				case 1:
					//**Start
					$rvarray2=array();
					foreach($rvaltemp as $item){
						$rvarray2[]="\$_POST['$item']";
					}
					unset($item,$rvaltemp);
					return $rvarray2;
					//**End
			}//end switch
		}else return false;
	}else return false;	
}
?>