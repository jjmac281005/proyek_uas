
-- Buat database baru
CREATE DATABASE IF NOT EXISTS cafedb;
USE cafedb;

-- Buat tabel cafes
CREATE TABLE cafes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    logo VARCHAR(255),
    address TEXT,
    rating FLOAT,
    cuisine VARCHAR(100)
);

-- Buat tabel foto-foto kafe
CREATE TABLE cafe_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cafe_id INT,
    photo_url VARCHAR(255),
    FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    cafe_id INT,
    FOREIGN KEY (cafe_id) REFERENCES cafes(id),
    weekdays_hours VARCHAR(50),
    weekend_hours VARCHAR(50),
    role ENUM('customer', 'owner', 'admin') NOT NULL
);

-- Isi data kafe (4 kafe contoh)
INSERT INTO cafes (id, name, logo, address, rating, cuisine) VALUES
(1, 'Koora Cafe', 'gambar/KOORA/69e5c73f-0143-497f-9bc8-f47b3657c628.png',
 'Komplek Centrium Industri Ruko A2 No.17-18, Medan', 4.3, 'Korean Cuisine'),

(2, 'Alligator Cafe', 'gambar/ALLIGATOR/WhatsApp Image 2025-03-03 at 17.55.10 (1).jpeg',
 'Jl. Kenanga No.17, Hamdan, Kec. Medan Maimun, Kota Medan, Sumatera Utara', 4.5, 'American Cuisine'),

(3, 'Omotesando Cafe', 'gambar/OMOTESANDO/WhatsApp Image 2025-03-03 at 18.14.15.jpeg',
 'Jl. Letjen Suprapto No.13, Medan Maimun', 4.8, 'Japanese Cuisine'),

(4, 'Potte Cafe', 'gambar/POTTE/WhatsApp Image 2025-03-03 at 18.20.32 (2).jpeg',
 'Jl. Dr. Mansyur No.98, Medan Selayang', 4.3, 'Italian Cuisine');

-- Isi data foto untuk galeri kafe
INSERT INTO cafe_photos (cafe_id, photo_url) VALUES
(1, 'gambar/KOORA/interior1.jpeg'),
(1, 'gambar/KOORA/interior2.jpeg'),

(2, 'gambar/ALLIGATOR/interior1.jpeg'),
(2, 'gambar/ALLIGATOR/interior2.jpeg'),
(2, 'gambar/ALLIGATOR/interior3.jpeg'),

(3, 'gambar/OMOTESANDO/interior1.jpeg'),
(3, 'gambar/OMOTESANDO/interior2.jpeg'),

(4, 'gambar/POTTE/interior1.jpeg'),
(4, 'gambar/POTTE/interior2.jpeg');


INSERT INTO admin (username, phone, email, password, cafe_id, role, weekdays_hours, weekend_hours) VALUES
('Koora Cafe', '123456',
 'koora@gmail.com', '1234', 1, 'owner', '9 AM - 10 PM', '9 AM - 11 PM'),

('Alligator Cafe', '123456',
 'alligator@gmail.com', '1234', 2, 'owner', '9 AM - 10 PM', '9 AM - 11 PM'),

('Omotesando Cafe', '123456',
 'omotesando@gmail.com', '1234', 3, 'owner', '9 AM - 10 PM', '9 AM - 11 PM'),

('Potte Cafe', '123456',
 'potte@gmail.com', '1234', 4, 'owner', '9 AM - 10 PM', '9 AM - 11 PM');
