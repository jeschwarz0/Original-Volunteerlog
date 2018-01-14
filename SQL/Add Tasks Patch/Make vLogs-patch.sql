/*
Alters the view vlogs
Replace "Create" with "Alter" if view exists
*/
CREATE VIEW vlogs AS
SELECT v.FirstName,v.LastName,vl.Date,vl.TimeIn,vl.TimeOut,TotalHours,vl.Comment,
IF(vt.Class,'TRUE','FALSE')as "Class",
IF(vt.Office,'TRUE','FALSE')as "Office",
IF(vt.Maintenance,'TRUE','FALSE')as "Maintenance",
IF(vt.Conditioning,'TRUE','FALSE')as "Conditioning",
IF(vt.HorseCare,'TRUE','FALSE')as "HorseCare",
IF(vt.Committee,'TRUE','FALSE')as "Committee",
IF(vt.Board,'TRUE','FALSE')as "Board",
IF(vt.HorseLeading,'TRUE','FALSE')as "HorseLeading",
IF(vt.Sidewalking,'TRUE','FALSE')as "Sidewalking",
IF(JrVolunteer,'TRUE','FALSE')as "JrVolunteer",
IF(SpecialOlympics,'TRUE','FALSE')as "SpecialOlympics",
IF(vt.Other,'TRUE','FALSE')as "Other",
vt.OtherDescription
FROM VolunteerLog vl
JOIN Volunteer v ON v.VolunteerID = vl.VolunteerID
JOIN volunteertask vt ON vt.TaskID = vl.TaskID;