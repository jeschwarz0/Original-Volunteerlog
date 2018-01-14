/*@deprecated Do not use*/
ALTER TABLE VolunteerTask 
ADD CHECK(Class=0 OR Class=1),
ADD CHECK(Office=0 OR Office=1),
ADD CHECK(Maintenance=0 OR Maintenance=1),
ADD CHECK(Conditioning=0 OR Conditioning=1),
ADD CHECK(HorseCare=0 OR HorseCare=1),
ADD CHECK(Committee=0 OR Committee=1),
ADD CHECK(Board=0 OR Board=1),
ADD CHECK(HorseLeading=0 OR HorseLeading=1),
ADD CHECK(Sidewalking=0 OR Sidewalking=1),
ADD CHECK(Other=0 OR Other=1);