$(function() {
    //Cookie Check
    if (navigator.cookieEnabled == 0) {
        $('#nav').html("<h1>Cookies are disabled :(</h1>");
    }
    //JQUERY INIT
    $("#Date").datepicker({
        dateFormat: "yy-mm-dd"
    });
    $("#nav").tabs({
        show: function(event, ui) {
            onTabChange();
        }//sets the tab change event
    });
	// Gets the data, then makes autocomplete with it(login-timestamp)
	$.get("script/usercache/users-all.json", function(data){
		//AUTOCOMPLETE DIRECTLY TAKING DATA
		$("#vname-login").autocomplete({
			source: data
			,mustMatch: true,
			matchContains: true
		});	
	});	
	//LOGOUT TIMESTAMP AUTOCOMPLETE
	$.get("script/usercache/users-activelog.json", function(data){
		//AUTOCOMPLETE DIRECTLY TAKING DATA
		$("#vname-logout").autocomplete({
			source: data
			,mustMatch: true,
			matchContains: true
		});	
	});	
	//	MANUAL LOGIN AUTOCOMPLETE
	$.get("script/usercache/users-all.json", function(data){
		//AUTOCOMPLETE DIRECTLY TAKING DATA
		$("#vname-manual").autocomplete({
			source: data
			,mustMatch: true,
			matchContains: true
		});	
	});	
});

	
/*Handles the tab change events, enabling/disabling tabs and setting the done page output*/	
function onTabChange(){
    var selected=$('#nav').tabs('option','selected');
    switch(selected){
        case 0:
            showWelcome();
            clearCookies();
            break;
        case 4:
            showAutoLogout();
            break;
        case 5:
            showManualLogin();
            break;
        case 6:
            showDonePage();
            if(readCookie('MSG')!="")
                $('#dmesg').html(messageFromID(readCookie('MSG')));
            if(readCookie('MSG-Severity')==0)
                $('#dmesg-icon').attr('src','images/information_message.png');
            else if(readCookie('MSG-Severity')==1)
                $('#dmesg-icon').attr('src','images/error_message.png');
			//4 second refresh
			window.setTimeout('window.location.replace("./");',4000);
            break;
    }
}

//	Tab Starts
	
/*Activates the new volunteer tab*/
function startNewVol(){
    $('#nav').tabs("option","disabled",[2,3,4,5,6]);
    $("#nav").tabs('select',1);
    $('#nav').tabs("option","disabled",[2,3,4,5,6]);//to disable origin tab
}

/*Activates the timestamp login tab*/
function startLogin(){
    $('#nav').tabs("option","disabled",[1,2,4,5,6]);
    $("#nav").tabs('select',3);
}

/*Activates the logout tab*/
function startLogout(){
    $('#nav').tabs("option","disabled",[1,3,4,5,6]);
    $("#nav").tabs('select',2);
    //Logout type
    //0=Automatic-timestamp
    //1=Manual
    createCookie('LOGOUT_TYPE',0,1);
}

/*Activates the manual log tab*/
function startManualLog(){
    $('#nav').tabs("option","disabled",[1,3,4,5,6]);
    $("#nav").tabs( 'select' , 2 );
    createCookie('LOGOUT_TYPE',1,1);
}

function mg(RECIPIENT_ID,MESSAGE){
	if(document.forms['ts']['vid'].value==RECIPIENT_ID)
		alert(MESSAGE);
}
//	Tab Other
/*Activates the welcome tab*/
function showWelcome(){
    $('#nav').tabs("option","disabled",[1,2,3,4,5,6]);
}

/*Activates the timestamp logout tab*/
function showAutoLogout(){
    $('#nav').tabs("option","disabled",[1,2,3,5,6]);
}

/*Activates the manual login tab*/
function showManualLogin(){
    $('#nav').tabs("option","disabled",[1,2,3,4,5,6]);
}

/*Activates the completion output tab*/
function showDonePage(){
    $('#nav').tabs("option","disabled",[1,2,3,4,5,6]);
}
	
//	Other

/*Sets the task "OtherDescription" textbox to visible or invisible based on the
 check status of "Other"*/
function setOtherVis(){
    if(document.getElementById("Other").checked==true)
        $('#odes').css("display","inherit");
    else if(document.getElementById("Other").checked==false){
        $('#odes').css("display","none");
        $('#odes').html("");//blank the box
    }else
        alert("unusual visibility state :(");
}

/**
 * Refresh the timestamp page
 * @deprecated No longer implemented in the main page
 */
function refreshTS(){
    document.getElementById("curlogs").contentDocument.location.reload(true);//reloads the iframe
}

/**
 * Convert a 12 hour to 24 hour
 * Note: 1=AM|0=PM
 */	
function do24Hour(HOUR,PART){
		if((PART==1&&HOUR!=12)||(HOUR==12&&PART==0)){
			return HOUR;
		}else if((PART==0&&HOUR!=12)||(HOUR==12&&PART==1)){
			return Number(HOUR)+12;
		}else{
			return HOUR;
		}
}