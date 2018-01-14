/*Finds duplicate volunteers*/

SELECT Count(CONCAT(FirstName," ",LastName)) AS "Records",VolunteerID,LastName,FirstName FROM volunteer
GROUP BY CONCAT(FirstName," ",LastName)
HAVING Records>1;
--Selects all Fields with duplicate names
Select VolunteerID,FirstName,LastName FROM volunteer where FirstName IN
(SELECT FirstName FROM volunteer
GROUP BY CONCAT(FirstName," ",LastName)
HAVING COUNT(CONCAT(FirstName," ",LastName))>1)
AND LastName IN
(SELECT LastName FROM volunteer
GROUP BY CONCAT(FirstName," ",LastName)
HAVING COUNT(CONCAT(FirstName," ",LastName))>1)