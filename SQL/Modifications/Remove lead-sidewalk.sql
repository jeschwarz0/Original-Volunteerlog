UPDATE VolunteerTask SET Class=1 WHERE HorseLeading=1 OR Sidewalking=1;

ALTER TABLE VolunteerTask
	DROP COLUMN HorseLeading,
	DROP COLUMN Sidewalking;