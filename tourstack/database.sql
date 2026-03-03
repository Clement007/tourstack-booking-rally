-- ============================================================
-- TOURSTACK DATABASE SETUP
-- Run this file once in phpMyAdmin or MySQL CLI to create
-- the database and tables needed for TourStack.
--
-- How to use:
--   Option 1: Open phpMyAdmin → SQL tab → paste this → click Go
--   Option 2: In terminal: mysql -u root -p < database.sql
-- ============================================================


-- Step 1: Create the database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS tourstack_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Step 2: Switch to using that database
USE tourstack_db;


-- ============================================================
-- TABLE: properties
-- Stores all available home-stay properties
-- ============================================================
CREATE TABLE IF NOT EXISTS properties (
  id          INT AUTO_INCREMENT PRIMARY KEY,   -- unique ID for each property
  name        VARCHAR(150)   NOT NULL,          -- e.g. "Lake View Cottage"
  location    VARCHAR(150)   NOT NULL,          -- e.g. "Kivu, Rwanda"
  price_night DECIMAL(10,2)  NOT NULL,          -- price per night in RWF
  image_url   VARCHAR(300)   DEFAULT '',        -- photo URL
  rating      DECIMAL(2,1)   DEFAULT 4.5,       -- star rating out of 5
  created_at  TIMESTAMP      DEFAULT CURRENT_TIMESTAMP
);

-- Insert 3 demo properties
INSERT INTO properties (name, location, price_night, image_url, rating) VALUES
('Lake View Cottage',   'Kivu, Rwanda',    12000, 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80', 4.9),
('Mountain Breeze Home','Musanze, Rwanda',  9500, 'https://images.unsplash.com/photo-1510798831971-661eb04b3739?w=600&q=80', 4.7),
('City Garden Suite',   'Kigali, Rwanda',  15000, 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=600&q=80', 5.0);


-- ============================================================
-- TABLE: bookings
-- Stores every booking request submitted by tourists
-- ============================================================
CREATE TABLE IF NOT EXISTS bookings (
  id              INT AUTO_INCREMENT PRIMARY KEY,

  -- Guest information
  guest_name      VARCHAR(150)   NOT NULL,           -- tourist's full name
  guest_phone     VARCHAR(30)    NOT NULL,           -- contact phone number
  special_requests TEXT          DEFAULT '',         -- any special notes

  -- Property & dates
  property_id     INT            NOT NULL,           -- links to properties table
  property_name   VARCHAR(150)   NOT NULL,           -- stored for quick display
  check_in        DATE           NOT NULL,           -- arrival date
  check_out       DATE           NOT NULL,           -- departure date
  nights          INT            NOT NULL,           -- number of nights

  -- Cost breakdown
  price_per_night DECIMAL(10,2)  NOT NULL,           -- price at time of booking
  service_fee     DECIMAL(10,2)  NOT NULL,           -- 5% service fee
  total_cost      DECIMAL(10,2)  NOT NULL,           -- total including fee

  -- Status: pending → confirmed OR cancelled
  -- Only the owner can change this from the dashboard
  status          ENUM('pending','confirmed','cancelled') DEFAULT 'pending',

  -- Timestamps
  created_at      TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  -- Foreign key: booking must link to a real property
  FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);


-- ============================================================
-- TABLE: owner_settings (optional, for future use)
-- Could store owner name, contact, notification email, etc.
-- ============================================================
CREATE TABLE IF NOT EXISTS owner_settings (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  setting_key  VARCHAR(100) NOT NULL UNIQUE,   -- e.g. "owner_email"
  setting_value VARCHAR(300) NOT NULL           -- e.g. "owner@gmail.com"
);

-- Insert default owner settings
INSERT INTO owner_settings (setting_key, setting_value) VALUES
('owner_name',  'TourStack Owner'),
('owner_email', 'owner@tourstack.rw');


-- Done! Your database is ready.
-- ============================================================
