CREATE DATABASE IF NOT EXISTS monctondb;
USE monctondb;

-- Drop the table if it already exists
DROP TABLE IF EXISTS employee;

-- Create the employee table
CREATE TABLE employee (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  FirstName VARCHAR(50) NOT NULL,
  LastName VARCHAR(50) NOT NULL,
  Username VARCHAR(50) UNIQUE NOT NULL,
  Email VARCHAR(100) UNIQUE NOT NULL,
  Password VARCHAR(255) NOT NULL,
  Status ENUM('Active', 'Inactive') DEFAULT 'Inactive',
  Role ENUM('Admin', 'Member', 'Supervisor') DEFAULT 'Member',
  Code VARCHAR(6) DEFAULT '0',
  CodeCreate DATETIME DEFAULT NULL,
  CodeExpire DATETIME DEFAULT NULL,
  DateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO employee (FirstName, LastName, Username, Email, Password, Status, Role) 
VALUES 
('Alice', 'Admin', 'adminAlice', 'alice.admin@example.com', 'secure_admin_password', 'Active', 'Admin'),
('Bob', 'Member', 'memberBob', 'bob.member@example.com', 'hashed_member_password', 'Inactive', 'Member'),
('Charlie', 'Supervisor', 'superCharlie', 'charlie.super@example.com', 'hashed_super_password', 'Active', 'Supervisor');
