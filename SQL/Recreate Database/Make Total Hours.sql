/*
	Creates the field for calculated hours

*/
 /*		selects the calculated hours */
SELECT HOUR(SUBTIME(TimeOut,TimeIn))+(CAST(MINUTE(SUBTIME(TimeOut,TimeIn))/60 AS DECIMAL(2,2)))AS "Hours"  FROM volunteerlog
 

ALTER TABLE volunteerlog
MODIFY COLUMN TotalHours DECIMAL(5,2) NULL AFTER TimeOut;

/* Updates all current logs */
UPDATE volunteerlog SET TotalHours=(HOUR(SUBTIME(TimeOut,TimeIn))+(CAST(MINUTE(SUBTIME(TimeOut,TimeIn))/60 AS DECIMAL(2,2))));


/*****	Triggers	*****/
delimiter ^
CREATE TRIGGER tInsertVolunteerLog
BEFORE INSERT ON volunteerlog
FOR EACH ROW
BEGIN
 SET NEW.TotalHours = (HOUR(SUBTIME(NEW.TimeOut,NEW.TimeIn))+(CAST(MINUTE(SUBTIME(NEW.TimeOut,NEW.TimeIn))/60 AS DECIMAL(2,2))));
END^
delimiter ;

delimiter ^
CREATE TRIGGER tUpdateVolunteerLog
BEFORE UPDATE ON volunteerlog
FOR EACH ROW
BEGIN
 SET NEW.TotalHours = (HOUR(SUBTIME(NEW.TimeOut,NEW.TimeIn))+(CAST(MINUTE(SUBTIME(NEW.TimeOut,NEW.TimeIn))/60 AS DECIMAL(2,2))));
END^
delimiter ;