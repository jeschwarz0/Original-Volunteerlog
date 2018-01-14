/*## System already dropped check or not visible##*/

ALTER TABLE VolunteerTask 
	DROP CHECK(Class,Office,Maintenance,Conditioning,HorseCare,Committee,Board,HorseLeading,Sidewalking,Other);