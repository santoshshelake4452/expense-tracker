CREATE DATABASE IF NOT EXISTS `expense_tracker`;
USE `expense_tracker`;

CREATE TABLE IF NOT EXISTS `expenses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `description` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `date` DATE NOT NULL
);

CREATE TABLE IF NOT EXISTS `profile` (
    `id` INT PRIMARY KEY DEFAULT 1,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `budget` DECIMAL(10, 2) NOT NULL DEFAULT 15000.00
);