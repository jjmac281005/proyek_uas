CREATE DATABASE cafe_reservation;
USE cafe_reservation;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'owner', 'admin') NOT NULL
);

CREATE TABLE dashboard_owner (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    cafe_name VARCHAR(255) NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    time_from TIME NOT NULL,
    time_to TIME NOT NULL,
    date_reservation DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    cafe_name VARCHAR(255) NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    time_from TIME NOT NULL,
    time_to TIME NOT NULL,    
    date_reservation DATE NOT NULL,
    status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
