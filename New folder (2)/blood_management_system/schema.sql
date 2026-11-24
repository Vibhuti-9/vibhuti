CREATE DATABASE IF NOT EXISTS blood_bank;
USE blood_bank;

-- Users Table (Handles Doctor, Patient, and Admin accounts)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('doctor', 'patient', 'admin') NOT NULL,
    phone VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Doctor Portfolio & Details
CREATE TABLE IF NOT EXISTS doctor_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    hospital_name VARCHAR(150) NOT NULL,
    experience_years INT NOT NULL,
    bio TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Donor Registration Table
CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    blood_group ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Bombay Blood Group', 'Rh-null', 'Lutheran', 'Duffy') NOT NULL,
    blood_category ENUM('Common', 'Rare') NOT NULL,
    phone VARCHAR(15) NOT NULL,
    city VARCHAR(50) NOT NULL,
    last_donated_date DATE NULL,
    status ENUM('Available', 'Pending', 'Unavailable') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blood Inventory Table (Common & Rare)
CREATE TABLE IF NOT EXISTS blood_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blood_group VARCHAR(20) UNIQUE NOT NULL,
    category ENUM('Common', 'Rare') NOT NULL,
    units_available INT DEFAULT 0
);

-- Seed Initial Inventory Data
INSERT INTO blood_inventory (blood_group, category, units_available) VALUES
('A+', 'Common', 15), ('A-', 'Common', 5),
('B+', 'Common', 22), ('B-', 'Common', 4),
('AB+', 'Common', 12), ('AB-', 'Common', 2),
('O+', 'Common', 30), ('O-', 'Common', 8),
('Bombay Blood Group', 'Rare', 1),
('Rh-null', 'Rare', 0),
('Lutheran', 'Rare', 2),
('Duffy', 'Rare', 1)
ON DUPLICATE KEY UPDATE units_available=units_available;

-- Blood Requests & Dispatch Table
CREATE TABLE IF NOT EXISTS blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT NOT NULL,
    patient_name VARCHAR(100) NOT NULL,
    blood_group VARCHAR(20) NOT NULL,
    units_needed INT NOT NULL,
    hospital VARCHAR(150) NOT NULL,
    urgency ENUM('Normal', 'Critical') DEFAULT 'Normal',
    status ENUM('Pending', 'Approved', 'Dispatched', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE
);