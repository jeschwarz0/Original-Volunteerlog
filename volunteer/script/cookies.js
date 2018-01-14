/*General purpose javascript cookie library
 *@author http://www.quirksmode.org/js/cookies.html
 */
function createCookie(name,value,days){
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name){
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

/**My cookie functions
 *@author Jesse Schwarz
 */

/*Create a cookie from a form item, preferably a dropdown*/
function cookieFromDropdown(name,form_name){
    if(document.forms[form_name][name])
        createCookie(name,document.forms[form_name][name].value);
}

/*Clear all cookies*/
function clearCookies(){
    eraseCookie('vid');
    eraseCookie('TaskID');
    eraseCookie('LOGOUT_TYPE');
    eraseCookie('MSG');
    eraseCookie('MSG-Severity');
}

/*Gets a message from predefined message ids*/
function messageFromID(ID){
    var data=Array(
        "You have successfully checked in, don't forget to sign out when you are finished!",
        "There was a problem checking out, perhaps you did not check in today!",
        "You have been manually signed in!",
        "You have successfully checked out!",
        "There was a problem manually signing in!",
        "There was a problem checking in!",
        "You have successfully been added to the list!",
        "There was a problem adding you to the list, perhaps your name already exists!"
        );
    if(ID>=0&&ID<data.length)
        return data[ID];
}

/*
Current Cookies:
vid->Volunteer ID
TaskID->The taskID
MSG->Error or success message id
MSG-Severity -> 0=warning 1=error
LOGOUT_TYPE-> Numeric binary is Automatic or Manual
*/