CREATE TABLE VolunteerTask(
TaskID INT NOT NULL,
Class bool NOT NULL,
Office bool NOT NULL,
MaINTenance bool NOT NULL,
Conditioning bool NOT NULL,
HorseCare bool NOT NULL,
Committee bool NOT NULL,
Board bool NOT NULL,
HorseLeading bool NOT NULL,
Sidewalking bool NOT NULL,
Other bool NOT NULL,
OtherDescription MEDIUMTEXT,
PRIMARY KEY(TaskID)
);