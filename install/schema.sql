CREATE TABLE users (

id INT AUTO_INCREMENT PRIMARY KEY,

first_name VARCHAR(100),

last_name VARCHAR(100),

email VARCHAR(150) UNIQUE,

password VARCHAR(255),

role ENUM('tester','manager','admin'),

department VARCHAR(100),

designation VARCHAR(100),

status ENUM('active','disabled'),

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



CREATE TABLE projects (

id INT AUTO_INCREMENT PRIMARY KEY,

project_name VARCHAR(150),

project_code VARCHAR(100),

platform VARCHAR(100),

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



/*
Parent table
One record per project per date per shift
*/

CREATE TABLE allocation_batches (

id INT AUTO_INCREMENT PRIMARY KEY,

project_id INT NOT NULL,

allocation_date DATE NOT NULL,

shift_time VARCHAR(100),

created_by INT NOT NULL,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (project_id) REFERENCES projects(id),

FOREIGN KEY (created_by) REFERENCES users(id)

);



/*
Child table
One record per tester
*/

CREATE TABLE allocations (

id INT AUTO_INCREMENT PRIMARY KEY,

batch_id INT NOT NULL,

tester_id INT NOT NULL,

scope ENUM('FQA','QA','Anya'),

platform VARCHAR(100),

hours DECIMAL(4,2),

billing_type ENUM('Billed','Unbilled','Overtime'),

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (batch_id) REFERENCES allocation_batches(id),

FOREIGN KEY (tester_id) REFERENCES users(id)

);



CREATE TABLE correction_requests (

id INT AUTO_INCREMENT PRIMARY KEY,

allocation_id INT,

tester_id INT,

comment TEXT,

status ENUM('pending','approved','rejected'),

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (allocation_id) REFERENCES allocations(id),

FOREIGN KEY (tester_id) REFERENCES users(id)

);



CREATE TABLE company_settings (

id INT AUTO_INCREMENT PRIMARY KEY,

company_name VARCHAR(150),

company_email VARCHAR(150),

timezone VARCHAR(100),

logo_path VARCHAR(255),

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



CREATE TABLE activity_logs (

id INT AUTO_INCREMENT PRIMARY KEY,

user_id INT,

action VARCHAR(150),

description TEXT,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (user_id) REFERENCES users(id)

);
