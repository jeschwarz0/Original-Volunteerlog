<!doctype html>
<html>
    <head>
        <title></title><!--Used in iframe-->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
		img{border:none;}
            div.rsum{
                font-family: "System","Consolas","Lucida Console",serif;
                /*font-weight:bold;*/
            }
            #label{
                font-family: "Book Antiqua",serif;
            }
            #main_report{
                font-size:11px;
				font-family:"Courier New","Tunga","Times New Roman",serif;
				 white-space: nowrap;
            }
            #main_report th:not(.button){
                text-align:justify;
				font-family:"System","Tunga",serif;
                /*background-color:lightgrey;
                border:2px solid black;*/
            }
            #main_report td:not(.button){
				padding-right:4px;
            }
			td.button,th.button{
				width:auto;
				padding-right:0px;
				padding-left:0px;
			}
			span.po,span.poc{
				font-weight:bold;
				color:purple;
			}
			td.dt{
				color:rgb(108,63,13);/*DarkBrown*/
			}
        </style>
		<script type="text/javascript">
			function removeLog(LOGID){
				if(LOGID!=null&&confirm("Are you sure you want to remove this log?"))
					window.location.replace("remove_log.php?LogID="+Number(LOGID));
			}
		</script>
    </head>
<body>
<?php
/**
 *Processes the form request to give hours worked feedback
 *@author Jesse Schwarz
 */

require("../../cvol.php");
require("../../lib/datetime.php");

if($_SERVER['REQUEST_METHOD']=='GET'){
	$filter=getFilter($_GET['VID'],$_GET['StartDate'],$_GET['EndDate']);
	writeTable2(
		getRecords($filter,'detail'),
		isset($_GET['sum'])&&$_GET['sum']=='on'?getRecords($filter,'summary'):null,
		!(isset($_GET['ro'])&&$_GET['ro']=='on')
	);
}//else do nothing
mysqli_close($vc);

/**********************************************/
//			Report Functions
/**********************************************/
/**
 * Writes a report table directly
 * @param array $DETAIL_DATA A 2 dimension array
 * @param boolean $INCLUDE_DELETE Add a delete button to the table
 */
function writeTable2($DETAIL_DATA,$SHOW_SUMMARY=true,$INCLUDE_DELETE=true){
	if(!empty($DETAIL_DATA)&&is_array($DETAIL_DATA)&&is_array($DETAIL_DATA[0])&&!empty($DETAIL_DATA)){
	
		if(isset($SHOW_SUMMARY)&&$SHOW_SUMMARY==true)//show record/hour row
			printf(
			"\t<div class=\"rsum\">\n\t\t%s Hour%s&nbsp;%s Record%s\n\t</div><hr>\n",
			$SHOW_SUMMARY,($SHOW_SUMMARY==1?"":"s"),count($DETAIL_DATA),(count($DETAIL_DATA)==1?"":"s"));
		printf( //detail records
			"\t<table id=\"main_report\">\n\t\t<tr>\n%s\t\t\t<th>Name/Task</th>\n\t\t\t<th>Date</th>\n\t\t\t<th>In</th>\n\t\t\t<th>Out/Cmt</th>%s\n\t\t</tr>\n",
			 $INCLUDE_DELETE?"\t\t\t<th class=\"button\"></th>\n\t\t\t<th class=\"button\"></th>\n":"",!$INCLUDE_DELETE?"\n\t\t\t<th>Hours</th>":""
		 );//end table header
		foreach($DETAIL_DATA as $d1){
			#Recreated with printf
			printf("\t\t<tr>\n%s\t\t\t<td>%s</td>\n\t\t\t<td class=\"dt\" title=\"%s\">%s</td>\n\t\t\t<td>%s</td>\n\t\t\t<td>%s</td>\n\t\t</tr>\n\t\t<tr>\n%s\t\t\t<td colspan=\"3\">&nbsp;&nbsp;&nbsp;%s</td>\n\t\t\t<td>%s</td>\n%s\t\t</tr>\n",
			//data below
			$INCLUDE_DELETE?
			"\t\t\t<td class=\"button\"><a href=\"edit_log.php?LogID={$d1['LogID']}\"><img alt=\"edit\" src=\"../../images/edit_timestamp.png\"/></a></td>\n".
			"\t\t\t<td class=\"button\"><a href=\"javascript:removeLog({$d1['LogID']});\"><img alt=\"delete\" src=\"../../images/delete_timestamp.png\"/></a></td>\n"
			:"",
			$d1['Name'],fdate($d1['Date'],true),formatDate($d1['Date'],1),getTime12($d1['TimeIn']),getTime12($d1['TimeOut']),
			$INCLUDE_DELETE==true?"\t\t\t<td></td>\n\t\t\t<td></td>\n":"",genTaskList($d1),
			isset($d1['Comment'])&&!empty($d1['Comment'])&&$d1['Comment']!='Generated via Timestamp'?
				"<span class=\"poc\" title=\"{$d1['Comment']}\">Yes</span>":($d1['Comment']=='Generated via Timestamp'?"TS":"No"),
			$INCLUDE_DELETE==false?"\t\t\t<td>{$d1['TotalHours']}</td>\n":""
			);#ENDPRINTF
		}//end foreach
	unset($d1);
	echo "\t</table>\n";
	
	}else echo "<h4>No Records</h4>";
}

/**
 * Writes a generic table directly to the page
 * @param array $DATA  A 2 diminsioned array
 */
function writeTable($DATA){
	if(is_array($DATA)&&is_array($DATA[0])&&!empty($DATA)){
	echo "\t<table>\n";
		foreach($DATA as $d1){
			echo "\t\t<tr>\n";
			foreach($d1 as $d2){
				echo "\t\t\t<th>$d2</td>\n";
			}
			echo "\t\t</tr>\n";
		}
	echo "\t</table>\n";
	unset($d1,$d2);
	}
}

function getFilter($VID=-1000,$START=null,$END=null){
//Date tristate, True - Normal, False
	if(!isDate($START)||!isDate($END)||$START==$END){
		$use_date_tristate=false;//don't filter date
	}elseif($START<$END){
		$use_date_tristate=true;
	}elseif($START>$END){
		$use_date_tristate='rev';
	}
	//---------------------------------
	//		Build WHERE Clause
	//---------------------------------
	$where="";
	if($VID!=0&&$VID!=-1000)
		appendToWhere("vl.VolunteerID=$VID",$where);
	//-- Add date
	switch($use_date_tristate){
		case true: 
			appendToWhere("vl.Date BETWEEN '$START' AND '$END'",$where);
			break;
		case 'rev':
			appendToWhere("vl.Date BETWEEN '$END' AND '$START'",$where);
			break;	
	}//end switch
	//-- Items 
		$TITLES=array('Class','Office','Maintenance','Conditioning','HorseCare','Committee','Board','JrVolunteer','SpecialOlympics','Other');//Titles for the array
		$subwhere="";
		for($item_idx=0;$item_idx<count($TITLES);$item_idx++){
			if(isset($_GET[$TITLES[$item_idx]])&&$_GET[$TITLES[$item_idx]]=='on')
				appendToSubWhere("vt.".$TITLES[$item_idx]."=1",$subwhere,$where!='');
			}
			if(!empty($subwhere)){
				appendToSubWhere('/close',$subwhere,$where!='');
				$where.=$subwhere;
			}
			unset($item_idx,$TITLES,$subwhere);//clean up space
			//-Set Comment Filter
	switch($_GET['rcom']){
		case 'TS':
			appendToWhere("vl.Comment='Generated via Timestamp'",$where);
			break;
		case 'Blank':
			appendToWhere("vl.Comment=''",$where);
			break;
		case 'All':default:
			//do nothing
			break;
	}
return $where;
}


/**
 *Gets records of volunteer hours, either summary or full
 * @global mysqli_connect $vc The connection
 * @param int $VID The volunteer id
 * @param string|null $START The search start date
 * @param string|null $END The search end date
 * @param array $BoolItems The task items to add to the search
 * @param string $REPORT_TYPE 'summary' or 'detail' report
 * @return double|array|false Sum of hours or an array of records or false on error 
 */
function getRecords($WHERE="",$REPORT_TYPE='detail'){
	global $vc;
	if($REPORT_TYPE=='summary'){
		$qry="SELECT SUM(vl.TotalHours)AS \"Hours\" FROM volunteerlog vl JOIN volunteertask vt ON vl.TaskID=vt.TaskID $WHERE LIMIT 1;";
	}elseif($REPORT_TYPE=='qty')
		$qry="SELECT COUNT(vl.LogID)AS \"Records\" FROM volunteerlog vl JOIN volunteertask vt ON vl.TaskID=vt.TaskID $WHERE LIMIT 1;";
	elseif($REPORT_TYPE=='detail'){
		$qry="SELECT CONCAT(v.LastName,', ',v.FirstName)as \"Name\",vl.LogID,vl.Date,vl.TimeIn,vl.TimeOut,vl.TotalHours,vl.Comment,vt.* FROM volunteerlog vl JOIN volunteertask vt ON vl.TaskID=vt.TaskID JOIN Volunteer v on v.VolunteerID=vl.VolunteerID $WHERE ORDER BY vl.Date DESC, Name ASC, vl.TimeIn ASC;";
	}else return false;
	//fwrite(fopen("query.sql","w"),$qry);//Print Query to file for debugging
	$res=mysqli_query($vc,$qry);
	unset($WHERE,$qry);//clean up space
	if($res){
		if($REPORT_TYPE=='summary'||$REPORT_TYPE=='qty'){
			$rval=mysqli_fetch_row($res);
			mysqli_free_result($res);
			return($rval[0]);
		}elseif($REPORT_TYPE=='detail'){
			$rval=array();
			for($i=0;$i<mysqli_num_rows($res);$i++)
				$rval[$i]=mysqli_fetch_assoc($res);	
				mysqli_free_result($res);
			return($rval);
		}
	}else return false;
}

/**
 *Adds a string to a WHERE Clause
 * @param string $item The item that should be assumed = 1
 * @param string $list The WHERE statement list
 */
function appendToWhere($item,&$list){
	if(is_string($list)&&!empty($item)){
		if(strlen($list)<=0){
			$list.="WHERE ";
		}else{//elseif item exists
			$list.="AND ";
		}
		$list.=$item.' ';
	}
}

/**
 *Appends a string to a subcategory of a WHERE clause allowing OR to be used
 * @param string $item The item to add such that (item=1)
 * @param string $list The list to append
 * @param boolean $has_previous Is there a previous item?
 * @return null Terminated with /close 
 */
function appendToSubWhere($item,&$list,$has_previous=true){
	if(is_string($list)&&!empty($item)){
		if($item=='/close'){
			if($has_previous==true)
				$list.=')';
			return;
		}
		if(strlen($list)<=0){
			if($has_previous==true){
				$list.="AND(";//Open if first time
			}elseif($has_previous==false){//no existing where data
				$list.="WHERE ";//Open if first time
			}
		}else{//elseif item exists
			$list.="OR ";
		}
		$list.=$item.' ';
	}
}

/**
 *Generates a paragraph containing the Number of rows and columns in a 
 * @param type $result 
 * @return string|false A HTML formatted string containing the number of rows and columns of a result
 */
function rowInfo(&$result){
if($result)
	return("<p>Rows: ".mysqli_num_rows($result)."|Columns: ".mysqli_num_fields($result)."</p>\n");
else return false;
}

/**
 *Generate a task list, a comma separated list of all active tasks
 * @param array $DATA A one dimension array of data
 * @return string|false A comma separated list or "No Task" or false on error
 */
function genTaskList($DATA){
	$TASK_HEADERS=array('Class','Office','Maintenance','Conditioning','HorseCare','Committee','Board','JrVolunteer','SpecialOlympics','Other');//Titles for the array
	if(!(empty($DATA)||empty($TASK_HEADERS)||!is_array($DATA)||!is_array($TASK_HEADERS))){
		$rval="";
		$is_first=true;
			for($i=0;$i<count($TASK_HEADERS);$i++){
				if($DATA[$TASK_HEADERS[$i]]==1){
					if($is_first)
						$is_first=false;
					else
						$rval.=',';
						
						$rval.=($TASK_HEADERS[$i]=='Other'&&!empty($DATA['OtherDescription'])?"<span class=\"po\" title=\"{$DATA['OtherDescription']}\">{$TASK_HEADERS[$i]}</span>":$TASK_HEADERS[$i]);//tooltip or just text
						
				}
		}if(empty($rval))
			$rval="No Task";
		return $rval;
	}else return false;
}
?>
</body>
</html>