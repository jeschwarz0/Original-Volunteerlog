/*
Stored via export
Replace "Create" with "Alter" if view exists
*/
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vcheckins` AS select `v`.`FirstName` AS `FirstName`,`v`.`LastName` AS `LastName`,`c`.`TimeIn` AS `TimeIn`,`c`.`Active` AS `Active` from (`volunteer` `v` join `checkin` `c` on((`c`.`VolunteerID` = `v`.`VolunteerID`)));