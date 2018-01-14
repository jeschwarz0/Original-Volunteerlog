$(function() {//startup code
    //Cookie Check
    if (navigator.cookieEnabled == 0) {
        $('#nav').html("<h1>Cookies are disabled :(</h1>");
    }
    $("#nav").tabs({
        show: function(event, ui) {
            onTabChange();
        }//sets the tab change event
    });
    $("#StartDate").datepicker({
        dateFormat: "yy-mm-dd"
    });
    $("#EndDate").datepicker({
        dateFormat: "yy-mm-dd"
    });
});

			
/*Refreshes the timestamp frame*/
function refreshTS(){
    if(document.getElementById("curlogs"))
        document.getElementById("curlogs").contentDocument.location.reload(true);//reloads the iframe
}
//@deprecated Using direct updates
function submitTS(){
    if(document.forms["tso"])
        document.forms["tso"].submit();
}
/*code to handle tab changes*/		
function onTabChange(){
    switch($('#nav').tabs('option','selected')){
        case 3:
            document.forms["ex"].submit();
            $('#exportres').html('The data has been exported!');
            break;
        case 6:
            logout();
            break;
    }
}
/**Sends fake login data to properly logout*/
function logout(){
    if($.browser.msie){
        document.execCommand("ClearAuthenticationCache");
    } else{
        try{
            var xh=createXMLObject();
            xh.open("POST","index.php",true,"logout","logout");
            xh.send("");
            xh.abort();
        }catch(exception){
            alert(exception);
        }
    }
    window.location.replace('../');//go to home page
}
/*General purpose xml object creation for xmlhttp*/		
function createXMLObject(){
    try{
        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        return xmlhttp;
    }catch(exception){
       
        return false;
    }
}
			
//@deprecated
function confirmAction(message){
    if(!confirm(message))
        return false;
    else return true;
}
			
//Timestamp Admin Buttons//
//@deprecated directly from form
/*Ask if you want to activate all timestamps*/			
function ts_activate(){
    if(confirm('Do you really want to activate all outstanding timestamps?'))
        window.location.replace('services/tsutils.php?action=activate');
}
/*Ask if you want to deactivate all timestamps*/		
function ts_deactivate(){
    if(confirm('Do you really want to deactivate all outstanding timestamps?'))
        window.location.replace('services/tsutils.php?action=deactivate');
}
/*Ask if you want to purge all timestamps*/			
function ts_purge(){
    if(confirm('Do you really want to purge all outstanding timestamps?'))
        window.location.replace('services/tsutils.php?action=purge&filter=all');
}			