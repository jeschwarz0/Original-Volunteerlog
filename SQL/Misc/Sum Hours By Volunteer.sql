SELECT v.FirstName,v.LastName,SUM(TotalHours)as "Sum Hours" FROM volunteerlog vl
JOIN Volunteer v on v.VolunteerID=vl.VolunteerID
GROUP BY vl.VolunteerID
LIMIT 100