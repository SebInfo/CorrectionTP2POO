CREATE DATABASE IF NOT EXISTS boutique CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE boutique;

CREATE TABLE IF NOT EXISTS produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL
);

INSERT INTO produit (nom, prix, stock) VALUES
    ('Clavier mecanique', 79.90, 10),
    ('Souris USB', 24.90, 25),
    ('Ecran 24 pouces', 159.90, 8),
    ('Casque audio', 49.90, 15),
    ('Webcam', 39.90, 12);
