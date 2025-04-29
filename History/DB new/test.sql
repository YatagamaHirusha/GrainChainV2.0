INSERT INTO Land (
  FID, land_reg_no, mahawali_region, district, 
  aggricultural_service_area, regional_secretariat_division, location, 
  land_type, irrigation_type, land_size_in_acres, related_district
) 
VALUES 
(1, 'LR006', 'Region E', 'Badulla', 'Service Area A1', 'Division A', 'Bandarawela', 'Owned', 'Well', 2.0, 'Badulla'),
(2, 'LR007', 'Region F', 'Monaragala', 'Service Area B1', 'RS Division 7', 'Bibila', 'Rented', 'Rain-fed', 3.2, 'Monaragala'),
(1, 'LR008', 'Region E', 'Badulla', 'Service Area A2', 'RS Division 8', 'Hali-Ela', 'Owned', 'Canal', 1.5, 'Badulla');


INSERT INTO SeasonalDetails (
  LID, FOID, season_name, year, paddy_type, 
  plant_date, expected_harvest_in_KG, paddy_status, verification_status, note
) 
VALUES 
(1, 3, 'Yala', 2025, 'BG352', '2025-03-20', 1600, 'Ongoing', 'Pending', 'Inspection completed successfully.'),
(3, 4, 'Maha', 2024, 'Samba', '2024-10-10', 1800, 'Completed', 'Pending', 'Mismatch in reported and actual planted area.');

alter table Land rename column is_verifiy to is_verify;



INSERT INTO Land (
  FID, land_reg_no, mahawali_region, district, 
  aggricultural_service_area, regional_secretariat_division, location, 
  land_type, irrigation_type, land_size_in_acres, is_verify
) 
VALUES 
(2, 'LR010', 'Region E', 'Kandy', 'Service Area A1', 'Division A', 'Haputhale', 'Rented', 'Canal', 2.7, false);



SELECT DISTINCT U.full_name, U.contact_number1, U.contact_number2, U.email, F.FID 
        FROM User U 
        JOIN Farmer F ON F.FID = U.UID
        JOIN Land L ON L.FID = F.FID
        WHERE L.is_verify = 0 AND L.aggricultural_service_area = 'Division A';
        
        
select * from user;

select * from user u
join Farmer F ON F.FID = U.UID
join Land L ON L.FID = F.FID
where L.is_verify = false;



