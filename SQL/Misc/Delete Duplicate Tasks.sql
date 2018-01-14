/*Select Duplicate Tasks*/
SELECT TaskID
	FROM VolunteerTask
	WHERE TaskID NOT IN(
	SELECT DISTINCT TaskID FROM volunteerlog
);

/*Delete Duplicate Tasks*/
	DELETE
	FROM VolunteerTask
	WHERE TaskID NOT IN(
	SELECT DISTINCT TaskID FROM volunteerlog
);