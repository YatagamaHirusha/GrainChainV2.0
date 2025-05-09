INSERT INTO User (full_name, nic, email, gender, age, address, contact_number1, contact_number2, username, password, register_date)
VALUES ('Kamal Perera', '199012345678', 'kamal@example.com', 'Male', 35, '123 Green Road, Kandy', '0711234567', '0771234567', 'kamalp', 'hashed_password_1', '2025-04-01');

INSERT INTO User (full_name, nic, email, gender, age, address, contact_number1, contact_number2, username, password, register_date)
VALUES ('Nimali Silva', '199545678901', 'nimali@example.com', 'Female', 30, '456 Blue Street, Kurunegala', '0722345678', '0762345678', 'nimalis', 'hashed_password_2', '2025-04-02');

INSERT INTO User (full_name, nic, email, gender, age, address, contact_number1, contact_number2, username, password, register_date)
VALUES ('Saman Jayasuriya', '198845678912', 'saman.j@example.com', 'Male', 42, '789 Lotus Lane, Anuradhapura', '0773456789', '0713456789', 'samjay', 'hashed_password_3', '2025-04-05');

INSERT INTO User (full_name, nic, email, gender, age, address, contact_number1, contact_number2, username, password, register_date)
VALUES ('Dilani Fernando', '199234567890', 'dilani.f@example.com', 'Female', 28, '321 Sunflower Road, Galle', '0709876543', '0759876543', 'dilanif', 'hashed_password_4', '2025-04-06');

INSERT INTO Farmer (FID, years_of_experience)
VALUES (1, 10);

INSERT INTO Farmer (FID, years_of_experience)
VALUES (2, 5);

INSERT INTO FieldOfficer (FOID, assign_division, assign_district, location)
VALUES (3, 'Division A', 'Kandy', 'Field Office, Peradeniya');

INSERT INTO FieldOfficer (FOID, assign_division, assign_district, location)
VALUES (4, 'Division B', 'Kurunegala', 'Field Office, Wariyapola');

INSERT INTO Land (FID, land_reg_no, mahawali_region, district, aggricultural_service_area, regional_secretariat_division, location, land_type, irrigation_type, land_size_in_acres, related_district)
VALUES (1, 'LR001', 'Region B', 'Kandy', 'Service Area X', 'RS Division 1', 'Galaha', 'Owned', 'Canal', 2.5, 'Kandy');

INSERT INTO Land (FID, land_reg_no, mahawali_region, district, aggricultural_service_area, regional_secretariat_division, location, land_type, irrigation_type, land_size_in_acres, related_district)
VALUES (2, 'LR002', 'Region A', 'Kurunegala', 'Service Area Y', 'RS Division 2', 'Pannala', 'Rented', 'Well', 3.0, 'Kurunegala');

INSERT INTO SeasonalDetails (FID, LID, season_name, year, paddy_type, plant_date, expected_harvest_in_KG, harvest_date, status)
VALUES (1, 1, 'Yala', 2025, 'BG300', '2025-03-15', 1500, '2025-07-15', 'Ongoing');

INSERT INTO SeasonalDetails (FID, LID, season_name, year, paddy_type, plant_date, expected_harvest_in_KG, harvest_date, status)
VALUES (2, 2, 'Maha', 2024, 'Samba', '2024-10-01', 1800, '2025-02-15', 'Completed');

INSERT INTO Notification (FID, FOID, message)
VALUES (1, 3, 'Your fertilizer subsidy application has been received.');

INSERT INTO Notification (FID, FOID, message)
VALUES (2, 4, 'Field inspection scheduled for your land next week.');

