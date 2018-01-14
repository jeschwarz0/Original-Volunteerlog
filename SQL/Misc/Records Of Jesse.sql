SELECT * FROM Volunteer v
JOIN volunteerlog vl ON v.VolunteerID=vl.VolunteerID
JOIN volunteertask vt ON vl.TaskID=vt.TaskID
WHERE v.FirstName='Jesse';