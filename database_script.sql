CREATE DATABASE GrainChainV2;
USE GrainChainV2;

CREATE TABLE User(
	UID INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    nic CHAR(12) NOT NULL,
    email VARCHAR(200) NOT NULL,
    gender ENUM('Male' , 'Female'),
    age INT NOT NULL,
    address VARCHAR(250) NOT NULL,
    contact_number1 CHAR(10) NOT NULL,
    contact_number2 CHAR(10) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(256) NOT NULL,
    register_date DATE NOT NULL
    );

CREATE TABLE Farmer(
	FID INT PRIMARY KEY,
    FOREIGN KEY (FID) REFERENCES User (UID),
    years_of_experience INT
    );
    
CREATE TABLE FieldOfficer(
	FOID INT PRIMARY KEY,
    FOREIGN KEY (FOID) REFERENCES User (UID),
    assign_division VARCHAR(100) NOT NULL,
    assign_district VARCHAR(100) NOT NULL,
    location VARCHAR(200) NOT NULL
    );
    
CREATE TABLE WarehouseOfficer(
	WOID INT PRIMARY KEY,
    FOREIGN KEY (WOID) REFERENCES User (UID),
    assign_district VARCHAR(100) NOT NULL,
    assign_division VARCHAR(100) NOT NULL,
    DOID INT,
    FOREIGN KEY (DOID) REFERENCES DeputyOfficer (DOID) ON DELETE CASCADE
    );
    
CREATE TABLE DeputyOfficer(
	DOID INT PRIMARY KEY,
    FOREIGN KEY (DOID) REFERENCES User (UID),
	appointment_date DATE,
    assign_district VARCHAR (100) NOT NULL
    );

CREATE TABLE Warehouse(
	WID INT AUTO_INCREMENT PRIMARY KEY,
    warehouse_name VARCHAR(200) NOT NULL,
    warehouse_location VARCHAR(200) NOT NULL,
    district VARCHAR(100) NOT NULL,
    current_stock_KG DOUBLE,
    capacity_KG DOUBLE NOT NULL,
    WOID INT, 
    FOREIGN KEY (WOID) REFERENCES WarehouseOfficer (WOID) ON DELETE CASCADE
    );

CREATE TABLE Land(
	LID INT AUTO_INCREMENT PRIMARY KEY,
    FID INT,
    land_reg_no VARCHAR(10),
    mahawali_region VARCHAR(100),
    district VARCHAR(200),
    aggricultural_service_area VARCHAR(200),
    regional_secretariat_division VARCHAR(200),
    location VARCHAR(200) NOT NULL,
    land_type ENUM ('Owned' , 'Rented' , 'Government Lease'),
    irrigation_type ENUM ('Rain-fed' , 'Canal' , 'Well' , 'Other'),
    land_size_in_acres DOUBLE NOT NULL,
    related_district VARCHAR(200) NOT NULL,
    FOREIGN KEY (FID) REFERENCES Farmer (FID) ON DELETE CASCADE
    );
    
CREATE TABLE SeasonalDetails(
	SDID INT AUTO_INCREMENT PRIMARY KEY,
    FID INT,
    LID INT,
    season_name ENUM ('Yala' , 'Maha' , 'Other'),
	year INT,
    paddy_type VARCHAR(200),
    plant_date DATE,
    expected_harvest_in_KG DOUBLE,
    harvest_date DATE,
    status ENUM ('Completed' , 'Ongoing'),
    FOREIGN KEY (FID) REFERENCES Farmer (FID) ON DELETE CASCADE,
    FOREIGN KEY (LID) REFERENCES Land (LID) ON DELETE CASCADE
    );
	
CREATE TABLE FertilizeSubsidy(
	FSID INT AUTO_INCREMENT PRIMARY KEY,
    SDID INT,
    LID INT,
    FID INT,
    amount_of_cultivated_land FLOAT,
    expected_fertilizer_quantity FLOAT,
    subsidy_amount DOUBLE, 
    application_date DATE,
    approval_date DATE,
    status ENUM ('Pending' , 'Approved' , 'Rejected'),
	FOREIGN KEY (FID) REFERENCES Farmer (FID) ON DELETE CASCADE,
    FOREIGN KEY (LID) REFERENCES Land (LID) ON DELETE CASCADE,
    FOREIGN KEY (SDID) REFERENCES SeasonalDetails (SDID) ON DELETE CASCADE
    );
    
CREATE TABLE SellRequest(
	SellRequestID INT AUTO_INCREMENT PRIMARY KEY,
    FID INT,
    request_date DATE,
    quantity_in_KG DOUBLE,
    FOREIGN KEY (FID) REFERENCES Farmer (FID) ON DELETE CASCADE
    );

CREATE TABLE Harvest(
	HID INT AUTO_INCREMENT PRIMARY KEY,
    LID INT,
    FOID INT,
    harvest_date DATE,
    harvest_paddy_quantity DOUBLE,
    harvest_paddy_type VARCHAR(200),
    status ENUM ('Verified' , 'Refuted'),
    note TEXT NULL,
    FOREIGN KEY (LID) REFERENCES Land (LID) ON DELETE CASCADE,
    FOREIGN KEY (FOID) REFERENCES FieldOfficer (FOID) ON DELETE CASCADE
    );
    
CREATE TABLE PurchasePaddy(
	PurchasedID INT AUTO_INCREMENT PRIMARY KEY,
    FID INT,
    FOID INT,
    WID INT,
    purchase_paddy_type VARCHAR(200),
    purchase_paddy_quantity DOUBLE,
    price_per_KG DOUBLE,
    purchase_date DATE,
    note TEXT NULL,
    FOREIGN KEY (FID) REFERENCES Farmer (FID) ON DELETE CASCADE,
    FOREIGN KEY (FOID) REFERENCES FieldOfficer (FOID) ON DELETE CASCADE,
    FOREIGN KEY (WID) REFERENCES Warehouse (WID) ON DELETE CASCADE
    );

CREATE TABLE SellPaddy(
	SellID INT AUTO_INCREMENT PRIMARY KEY,
    WOID INT,
    buyer_name VARCHAR(200),
    paddy_type VARCHAR(200),
    paddy_quantity DOUBLE,
    price_per_KG DOUBLE,
    sell_date DATE,
    note TEXT NULL,
    FOREIGN KEY (WOID) REFERENCES WarehouseOfficer (WOID) ON DELETE CASCADE
    );
    
CREATE TABLE Stock(
	ID INT AUTO_INCREMENT PRIMARY KEY,
    WID INT,
    HID INT,
    available_stock_KG DOUBLE,
    damage_stock_KG DOUBLE,
    updated_at TIMESTAMP,
    FOREIGN KEY (WID) REFERENCES Warehouse (WID) ON DELETE CASCADE,
    FOREIGN KEY (HID) REFERENCES Harvest (HID) ON DELETE CASCADE
    );