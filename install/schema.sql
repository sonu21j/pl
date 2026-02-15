/*
==========================================
USERS TABLE
==========================================
*/

CREATE TABLE users (

id INT AUTO_INCREMENT PRIMARY KEY,

first_name VARCHAR(100) NOT NULL,

last_name VARCHAR(100) NOT NULL,

email VARCHAR(150) UNIQUE NOT NULL,

password VARCHAR(255) NOT NULL,

role ENUM('tester','manager','admin') NOT NULL,

department VARCHAR(100),

designation VARCHAR(100),

status ENUM('active','disabled') DEFAULT 'active',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



/*
==========================================
COMPANY SETTINGS
==========================================
*/

CREATE TABLE company_settings (

id INT AUTO_INCREMENT PRIMARY KEY,

company_name VARCHAR(150),

company_email VARCHAR(150),

timezone VARCHAR(100) DEFAULT 'UTC',

logo_path VARCHAR(255),

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



/*
==========================================
PROJECTS TABLE
Includes client allocated hours tracking
==========================================
*/

CREATE TABLE projects (

id INT AUTO_INCREMENT PRIMARY KEY,

project_name VARCHAR(150) NOT NULL,

project_code VARCHAR(100),

platform VARCHAR(100),

client_allocated_hours DECIMAL(10,2) DEFAULT 0,

warning_threshold_percent INT DEFAULT 80,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



/*
==========================================
BILLING RATES TABLE (CONFIDENTIAL)
Per project, platform, billing type
==========================================
*/

CREATE TABLE billing_rates (

id INT AUTO_INCREMENT PRIMARY KEY,

project_id INT NOT NULL,

platform VARCHAR(100) NOT NULL,

billing_type ENUM('Billed','Unbilled','Overtime') NOT NULL,

rate_per_hour DECIMAL(10,2) NOT NULL,

currency VARCHAR(10) DEFAULT 'USD',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

updated_at TIMESTAMP NULL,

UNIQUE KEY unique_rate (project_id, platform, billing_type),

FOREIGN KEY (project_id)
REFERENCES projects(id)
ON DELETE CASCADE

);



/*
==========================================
ALLOCATION BATCHES TABLE (PARENT)
One per project/date/shift
==========================================
*/

CREATE TABLE allocation_batches (

id INT AUTO_INCREMENT PRIMARY KEY,

project_id INT NOT NULL,

allocation_date DATE NOT NULL,

shift_time VARCHAR(100),

created_by INT NOT NULL,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (project_id)
REFERENCES projects(id)
ON DELETE CASCADE,

FOREIGN KEY (created_by)
REFERENCES users(id)
ON DELETE CASCADE

);



/*
==========================================
ALLOCATIONS TABLE (CHILD)
One record per tester
==========================================
*/

CREATE TABLE allocations (

id INT AUTO_INCREMENT PRIMARY KEY,

batch_id INT NOT NULL,

tester_id INT NOT NULL,

scope ENUM('FQA','QA','Anya') DEFAULT 'QA',

platform VARCHAR(100),

hours DECIMAL(6,2) DEFAULT 0,

billing_type ENUM('Billed','Unbilled','Overtime') DEFAULT 'Billed',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (batch_id)
REFERENCES allocation_batches(id)
ON DELETE CASCADE,

FOREIGN KEY (tester_id)
REFERENCES users(id)
ON DELETE CASCADE

);



/*
==========================================
CORRECTION REQUESTS TABLE
==========================================
*/

CREATE TABLE correction_requests (

id INT AUTO_INCREMENT PRIMARY KEY,

allocation_id INT NOT NULL,

tester_id INT NOT NULL,

comment TEXT,

status ENUM('pending','approved','rejected') DEFAULT 'pending',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (allocation_id)
REFERENCES allocations(id)
ON DELETE CASCADE,

FOREIGN KEY (tester_id)
REFERENCES users(id)
ON DELETE CASCADE

);



/*
==========================================
ACTIVITY LOGS TABLE
==========================================
*/

CREATE TABLE activity_logs (

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT,

action VARCHAR(150),

description TEXT,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (user_id)
REFERENCES users(id)
ON DELETE SET NULL

);
