/* Deletes records associated with a volunteer upon deletion, [ON UPDATE DELETE] Fails :(
 *
 */

DROP TRIGGER IF EXISTS tDeleteVolunteer;

delimiter $$
CREATE TRIGGER tDeleteVolunteer AFTER DELETE ON Volunteer
FOR EACH ROW
BEGIN
 DELETE FROM VolunteerLog WHERE VolunteerID=OLD.VolunteerID;
 DELETE FROM CheckIn WHERE VolunteerID=OLD.VolunteerID;
END$$
delimiter ;