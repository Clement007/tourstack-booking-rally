-- TourStack Booking Database Schema
-- INES Ruhengeri | Project C

CREATE DATABASE IF NOT EXISTS tourstack;
USE tourstack;

CREATE TABLE IF NOT EXISTS bookings (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    guest_name      VARCHAR(100)   NOT NULL,
    phone           VARCHAR(20)    NOT NULL,
    checkin_date    DATE           NOT NULL,
    nights          INT            NOT NULL CHECK (nights BETWEEN 1 AND 30),
    price_per_night DECIMAL(10,2)  NOT NULL,
    total_cost      DECIMAL(10,2)  NOT NULL,
    service_fee     DECIMAL(10,2)  NOT NULL,
    num_guests      INT            DEFAULT 1,
    special_requests TEXT,
    status          ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample seed data
INSERT INTO bookings (guest_name, phone, checkin_date, nights, price_per_night, total_cost, service_fee, num_guests, status)
VALUES
  ('Jean Claude Habimana', '0788123456', '2025-06-10', 3, 15000, 47250, 2250, 2, 'confirmed'),
  ('Amina Uwera',          '0722987654', '2025-06-15', 5, 12000, 63000, 3000, 1, 'pending'),
  ('Patrick Nkurunziza',   '0733445566', '2025-06-20', 2, 20000, 42000, 2000, 3, 'cancelled');
