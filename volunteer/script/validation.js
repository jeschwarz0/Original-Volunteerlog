/**Validation functions for main site forms
 * @author Jesse Schwarz
 */
 
/*Validates a volunteer log(manual) */
function validateVLOG(){
    var a=document.forms["mvol"]["TaskID"].value;
    var b=document.forms["mvol"]["vname"].value;
    var c=document.forms["mvol"]["Comment"].value;
    //Time In
    var tih=Number(do24Hour(a=document.forms["mvol"]["HourIn"].value,document.forms["mvol"]["AMIn"].value));
    var tim=Number(document.forms["mvol"]["MinuteIn"].value);

	
    //Time Out
    var toh=Number(do24Hour(a=document.forms["mvol"]["HourOut"].value,document.forms["mvol"]["AMOut"].value));
    var tom=Number(document.forms["mvol"]["MinuteOut"].value);
	
    //alert("$tih="+tih+", toh="+toh);	For debugging purposes
	
    if (a==null || a=="" ||a==0){
        alert("The Task form must be filled out");
        return false;
    }
    if (b==null || b==""){
        alert("Volunteer name must be filled out");
        return false;
    }
    //time check
    if((tih>toh)||(tih==toh&&tim>tom)){
        alert("You left before you arrived?");
        return false;
    }
	
    if(c.length>16777215){
        alert("Comment is too long");
        return false;
    }
//MEDIUMTEXT MAX LENGTH 16777215
}

/*Validates a volunteer timestamp creation */
function validateCheckin(){
	var a=document.forms["ts"]["vname"].value;
	 if (a==null || a==""){
        alert("Volunteer name must be filled out");
        return false;
    }
}

/*Validates a volunteer log(timestamp) */
function validateCheckout(){
    var a=document.forms["tso"]["vname"].value;
    var b=document.forms["tso"]["TaskID"].value;
    if (a==null || a==""){
        alert("Volunteer name must be filled out");
        return false;
    }
	
    if (b==null || b=="" || b==0){
        alert("No task selected");
        return false;
    }
}

/*Validates the entry of a new user*/
function validateVolunteer(){
    var a=document.forms["nvol"]["FirstName"].value;
    var b=document.forms["nvol"]["LastName"].value;
    if (a==null || a==""){
        alert("Volunteer first name must be filled out");
        return false;
    }
    if (b==null || b==""){
        alert("Volunteer last name must be filled out");
        return false;
    }
}

/*Validates the entry of a task*/
function validateTask(){
    var a=document.forms["newtask"]["OtherDescription"].value;

    if(a.length>16777215){
        alert("Task Description is too long");
        return false;
    }
}
