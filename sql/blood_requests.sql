CREATE TABLE IF NOT EXISTS blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
    city VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    urgency ENUM('Urgent', 'Normal') NOT NULL,
    reason TEXT NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);