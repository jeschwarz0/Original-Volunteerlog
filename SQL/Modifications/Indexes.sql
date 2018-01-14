/*
*	Indexes
*/

/*Volunteer*/
CREATE INDEX idxVName
ON Volunteer(FirstName,LastName);

/*VolunteerLog*/
CREATE INDEX idxLogData
ON VolunteerLog(VolunteerID,TaskID,Date,TotalHours);

/*Checkin*/

CREATE INDEX idxCheckinData
ON CheckIn(VolunteerID,Active);

/*Task @since >1.2.1*/
CREATE INDEX idxTask
ON VolunteerTask(Class,Office,Maintenance,Conditioning,HorseCare,Committee,Board,JrVolunteer,SpecialOlympics,Other);

