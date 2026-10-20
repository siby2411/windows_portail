CREATE DATABASE IF NOT EXISTS `omega_restaurant` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `omega_restaurant`;

-- Tables d'utilisateurs & sessions caisse
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(100) NOT NULL,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'manager', 'cashier', 'waiter') DEFAULT 'cashier',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `cash_sessions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `opening_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `closing_amount` DECIMAL(12,2) NULL,
    `expected_amount` DECIMAL(12,2) NULL,
    `status` ENUM('open', 'closed') DEFAULT 'open',
    `opened_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `closed_at` DATETIME NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

-- Tables Clients & Tables/Salles
CREATE TABLE IF NOT EXISTS `customers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `email` VARCHAR(100) NULL,
    `loyalty_points` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `restaurant_tables` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `table_number` VARCHAR(20) NOT NULL UNIQUE,
    `capacity` INT DEFAULT 4,
    `status` ENUM('available', 'occupied', 'reserved') DEFAULT 'available'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `reservations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `table_id` INT NULL,
    `reservation_time` DATETIME NOT NULL,
    `guests_count` INT DEFAULT 2,
    `status` ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    `notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
    FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables`(`id`)
) ENGINE=InnoDB;

-- Ingrédients & Fournisseurs (Approvisionnement)
CREATE TABLE IF NOT EXISTS `suppliers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `company_name` VARCHAR(100) NOT NULL,
    `contact_name` VARCHAR(100) NULL,
    `phone` VARCHAR(30) NULL,
    `email` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `ingredients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `unit` VARCHAR(20) NOT NULL COMMENT 'kg, g, l, cl, piece',
    `stock_qty` DECIMAL(10,3) DEFAULT 0.000,
    `alert_qty` DECIMAL(10,3) DEFAULT 5.000,
    `unit_cost` DECIMAL(10,2) DEFAULT 0.00,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `stock_entries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ingredient_id` INT NOT NULL,
    `supplier_id` INT NULL,
    `quantity` DECIMAL(10,3) NOT NULL,
    `unit_price` DECIMAL(10,2) NOT NULL,
    `total_price` DECIMAL(12,2) NOT NULL,
    `entry_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`),
    FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`)
) ENGINE=InnoDB;

-- Menu, Plats & Fiches Techniques (Recettes)
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `icon` VARCHAR(50) DEFAULT 'bi-egg-fried'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `menu_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `image` VARCHAR(255) NULL,
    `is_available` TINYINT(1) DEFAULT 1,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `recipes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `menu_item_id` INT NOT NULL,
    `ingredient_id` INT NOT NULL,
    `quantity_needed` DECIMAL(10,3) NOT NULL COMMENT 'Quantité déstockée par portion',
    FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`)
) ENGINE=InnoDB;

-- Commandes POS & Facturation
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(50) UNIQUE NOT NULL,
    `cash_session_id` INT NULL,
    `table_id` INT NULL,
    `customer_id` INT NULL,
    `user_id` INT NOT NULL,
    `type` ENUM('dine_in', 'takeaway', 'delivery') DEFAULT 'dine_in',
    `status` ENUM('pending', 'kitchen', 'served', 'paid', 'cancelled') DEFAULT 'pending',
    `payment_method` ENUM('cash', 'card', 'mobile_money') NULL,
    `subtotal` DECIMAL(12,2) DEFAULT 0.00,
    `discount` DECIMAL(12,2) DEFAULT 0.00,
    `total` DECIMAL(12,2) DEFAULT 0.00,
    `paid_amount` DECIMAL(12,2) DEFAULT 0.00,
    `change_amount` DECIMAL(12,2) DEFAULT 0.00,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables`(`id`),
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `menu_item_id` INT NOT NULL,
    `unit_price` DECIMAL(10,2) NOT NULL,
    `quantity` INT NOT NULL,
    `total_price` DECIMAL(10,2) NOT NULL,
    `notes` VARCHAR(255) NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items`(`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `expenses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `category` VARCHAR(50) DEFAULT 'General',
    `amount` DECIMAL(12,2) NOT NULL,
    `expense_date` DATE NOT NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insertion d'un administrateur par défaut (Password: admin123)
INSERT INTO `users` (`fullname`, `username`, `password`, `role`) VALUES 
('Administrateur', 'admin', '$2y$10$e.wM0R9Xb3B2S2Bv4B5O0uI8U41U3r4N0P5V1Y3Y8A0P2K1L0N3S2', 'admin')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Insertion de données de test minimales
INSERT INTO `categories` (`id`, `name`, `icon`) VALUES 
(1, 'Entrées', 'bi-egg-fried'),
(2, 'Plats Principaux', 'bi-border-all'),
(3, 'Boissons', 'bi-cup-straw'),
(4, 'Desserts', 'bi-pie-chart')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `restaurant_tables` (`table_number`, `capacity`, `status`) VALUES
('Table 1', 2, 'available'),
('Table 2', 4, 'available'),
('Table 3', 6, 'available'),
('VIP 1', 8, 'available')
ON DUPLICATE KEY UPDATE `id`=`id`;