/* Selects total hours by date 
*  @deprecated Quirks, will not output properly
*/
DROP PROCEDURE IF EXISTS pHoursByDate;

CREATE PROCEDURE pHoursByDate(StartDate date,EndDate date) 
	LANGUAGE SQL  
	READS SQL DATA  
	COMMENT 'Check The hours by date.' 
		IF StartDate IS NULL OR EndDate IS NULL THEN
				/*START TRANSACTION;*/
				SELECT v.LastName,v.FirstName,SUM(vl.TotalHours)AS "Hours Worked"
				FROM volunteerlog vl
				JOIN Volunteer v on vl.VolunteerID=v.VolunteerID
				GROUP BY v.LastName
				ORDER BY v.LastName DESC,v.FirstName DESC;
				/*COMMIT;*/
		ELSE
				/*START TRANSACTION;*/
				SELECT v.LastName,v.FirstName,SUM(vl.TotalHours)AS "Hours Worked"
				FROM volunteerlog vl
				JOIN Volunteer v on vl.VolunteerID=v.VolunteerID
				WHERE vl.Date BETWEEN StartDate AND EndDate
				GROUP BY v.LastName
				ORDER BY v.LastName DESC,v.FirstName DESC;
				/*COMMIT;*/
		END IF;	

/*WHERE vl.Date BETWEEN '2012/07/10' AND '2012/07/11'*/

/*WHERE vl.Date BETWEEN '2012/07/10' AND '2012/07/11'*/
CALL pHoursByDate('2012/07/10','2012/07/11');