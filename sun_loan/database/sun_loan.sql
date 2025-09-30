-- Création de la base de données
CREATE DATABASE IF NOT EXISTS sun_loan DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE sun_loan;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    country VARCHAR(50) NOT NULL,
    birth_date DATE NOT NULL,
    id_number VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_admin BOOLEAN DEFAULT FALSE
);

-- Table des prêts
CREATE TABLE loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    duration INT NOT NULL, -- en mois
    interest_rate DECIMAL(5,2) NOT NULL,
    monthly_payment DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    purpose TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'paid') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des paiements
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loan_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (loan_id) REFERENCES loans(id) ON DELETE CASCADE
);

-- Insertion d'un admin par défaut
INSERT INTO users (first_name, last_name, email, password, phone, address, city, zip_code, country, birth_date, id_number, is_admin)
VALUES ('Admin', 'SUNLOAN', 'admin@sunloan.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0123456789', '123 Admin Street', 'Admin City', '00000', 'Admin Country', '1990-01-01', 'ADMIN123', TRUE);