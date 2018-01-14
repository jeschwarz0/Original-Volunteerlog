/* @deprecated Using application checks instead
 *
 */

DROP TRIGGER IF EXISTS tInsertVolunteer;

delimiter ^

CREATE TRIGGER tInsertVolunteer BEFORE INSERT ON Volunteer
FOR EACH ROW
BEGIN
/*IF ((SELECT COUNT VolunteerID FROM Volunteer WHERE FirstName=NEW.FirstName AND LastName=NEW.LastName)>0)*/
IF (SELECT VolunteerID FROM Volunteer WHERE FirstName=NEW.FirstName AND LastName=NEW.LastName) IS NULL
	THEN
		INSERT IGNORE;
	END IF;
END^
delimiter ;