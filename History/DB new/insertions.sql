-- 5 Deputy Officers (UIDs: 201–205)
INSERT INTO User (UID, full_name, nic, email, gender, age, address, contact_number1, contact_number2, username, password, register_date)
VALUES
(201, 'Deputy Officer A', '901234567V', 'doa@example.com', 'Male', 45, 'Colombo 01', '0771234567', '0711234567', 'doa', 'pass123', '2023-01-01'),
(202, 'Deputy Officer B', '901234568V', 'dob@example.com', 'Female', 42, 'Kandy 02', '0771234568', '0711234568', 'dob', 'pass123', '2023-01-01'),
(203, 'Deputy Officer C', '901234569V', 'doc@example.com', 'Male', 48, 'Galle 03', '0771234569', '0711234569', 'doc', 'pass123', '2023-01-01'),
(204, 'Deputy Officer D', '901234570V', 'dod@example.com', 'Female', 40, 'Kurunegala 04', '0771234570', '0711234570', 'dod', 'pass123', '2023-01-01'),
(205, 'Deputy Officer E', '901234571V', 'doe@example.com', 'Male', 43, 'Matara 05', '0771234571', '0711234571', 'doe', 'pass123', '2023-01-01');

-- 5 Warehouse Officers (UIDs: 301–305)
INSERT INTO User (UID, full_name, nic, email, gender, age, address, contact_number1, contact_number2, username, password, register_date)
VALUES
(301, 'Warehouse Officer A', '801234567V', 'woa@example.com', 'Male', 35, 'Colombo 06', '0772234567', '0712234567', 'woa', 'pass123', '2023-01-01'),
(302, 'Warehouse Officer B', '801234568V', 'wob@example.com', 'Female', 33, 'Kandy 07', '0772234568', '0712234568', 'wob', 'pass123', '2023-01-01'),
(303, 'Warehouse Officer C', '801234569V', 'woc@example.com', 'Male', 36, 'Galle 08', '0772234569', '0712234569', 'woc', 'pass123', '2023-01-01'),
(304, 'Warehouse Officer D', '801234570V', 'wod@example.com', 'Female', 34, 'Kurunegala 09', '0772234570', '0712234570', 'wod', 'pass123', '2023-01-01'),
(305, 'Warehouse Officer E', '801234571V', 'woe@example.com', 'Male', 37, 'Matara 10', '0772234571', '0712234571', 'woe', 'pass123', '2023-01-01');


INSERT INTO DeputyOfficer (DOID, appointment_date, assign_district)
VALUES
(201, '2023-02-01', 'Colombo'),
(202, '2023-02-01', 'Kandy'),
(203, '2023-02-01', 'Galle'),
(204, '2023-02-01', 'Kurunegala'),
(205, '2023-02-01', 'Matara');

INSERT INTO WarehouseOfficer (WOID, assign_district, assign_division, DOID)
VALUES
(301, 'Colombo', 'Colombo North', 201),
(302, 'Kandy', 'Kandy Central', 202),
(303, 'Galle', 'Galle South', 203),
(304, 'Kurunegala', 'Kurunegala East', 204),
(305, 'Matara', 'Matara West', 205);

INSERT INTO Warehouse (warehouse_name, warehouse_location, district, current_stock_KG, capacity_KG, WOID)
VALUES
('Colombo Central Warehouse', 'Colombo 10', 'Colombo', 2000, 10000, 301),
('Kandy Hills Warehouse', 'Peradeniya', 'Kandy', 3000, 12000, 302),
('Galle Coast Warehouse', 'Galle Fort', 'Galle', 1500, 8000, 303),
('Kurunegala Agro Store', 'Wehera', 'Kurunegala', 2200, 9000, 304),
('Matara Grain Depot', 'Nupe', 'Matara', 1800, 9500, 305);

INSERT INTO Warehouse (warehouse_name, warehouse_location, district, current_stock_KG, capacity_KG, WOID)
VALUES
('Kandy Central Warehouse', 'Mawanalla', 'Kandy', 2000, 10000, 302);
