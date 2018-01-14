/*Add the column JrVolunteer*/
ALTER TABLE VolunteerTask
ADD COLUMN JrVolunteer boolean NOT NULL AFTER Sidewalking;


/*Check for JrVolunteer*/
ALTER TABLE VolunteerTask 
ADD CHECK(JrVolunteer=0 OR JrVolunteer=1);

/*##########Special Olympics##############*/
/*SpecialOlympics*/

ALTER TABLE VolunteerTask
ADD COLUMN SpecialOlympics boolean NOT NULL AFTER JrVolunteer;


/*Check for SpecialOlympics*/
ALTER TABLE VolunteerTask 
ADD CHECK(SpecialOlympics=0 OR SpecialOlympics=1);