-- Create database
CREATE DATABASE IF NOT EXISTS sk_crackers;
USE sk_crackers;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(500) DEFAULT '/placeholder.svg?height=200&width=280',
    original_price DECIMAL(10,2) NOT NULL,
    current_price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    subcategory VARCHAR(255) NOT NULL,
    is_sold_out BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    mobile_no VARCHAR(15) NOT NULL,
    customer_address TEXT NOT NULL,
    product_list JSON NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('Pending Payment', 'Confirmed', 'Shipped', 'Dispatched', 'Delivered', 'Cancelled') DEFAULT 'Pending Payment',
    date_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample products
INSERT INTO products (name, image, original_price, current_price, category, subcategory) VALUES
('2 3/4" Kuruvi', './img/kuruvi1.jpg', 30.00, 24.00, 'bombs', 'ONE SOUND CRACKERS'),
('3 1/2" Redbull / Chotta bheem', './img/chotta_beema.jpg', 60.00, 48.00, 'bombs', 'ONE SOUND CRACKERS'),
('5" Vikram/Tiger/Jallikattu', './img/jallikattu.jpg', 200.00, 160.00, 'bombs', 'ONE SOUND CRACKERS'),
('2 Sound', './img/2sound.jpg', 145.00, 116.00, 'bombs', 'TWO SOUND CRACKERS'),
('Red Bijili (100 Pcs)', './img/redbijili.webp', 135.00, 108.00, 'bombs', 'BIJILI'),
('Bullet Bomb', './img/bulletbomb.jpeg', 145.00, 116.00, 'bombs', 'BOMBS (NAACHIAR/SRI VIJAI/RAMCO)'),
('Hydro Bomb', './img/hyderbomb.webp', 325.00, 260.00, 'bombs', 'BOMBS (NAACHIAR/SRI VIJAI/RAMCO)'),
('King of King Bomb', './img/kingofking.webp', 395.00, 316.00, 'bombs', 'BOMBS (NAACHIAR/SRI VIJAI/RAMCO)'),
('Mega Flash', './img/megaflash.png', 625.00, 500.00, 'bombs', 'BOMBS (NAACHIAR/SRI VIJAI/RAMCO)'),
('555 Bomb', './img/555bomb.jpg', 550.00, 440.00, 'bombs', 'BOMBS (NAACHIAR/SRI VIJAI/RAMCO)'),
('Flower Pots Small', './img/flowerpotsmall.jpg', 275.00, 220.00, 'fountains', 'FLOWER POTS'),
('Flower Pots Big', './img/flowerpotbig.jpeg', 350.00, 280.00, 'fountains', 'FLOWER POTS'),
('Flower Pots Asoka', './img/flowerpotashok.webp', 725.00, 580.00, 'fountains', 'FLOWER POTS'),
('Colour Koti', './img/colourkoti.jpg', 1050.00, 840.00, 'fountains', 'FLOWER POTS'),
('Baby Rocket', './img/babyrocket.png', 200.00, 160.00, 'rockets', 'ROCKETS'),
('Colour Rocket', './img/colorrocket.webp', 300.00, 240.00, 'rockets', 'ROCKETS'),
('Electric Stone', './img/eletricstone.webp', 50.00, 40.00, 'novelties', 'CHILDREN NOVELTIES'),
('TIM TAM (Kit Kat)', './img/timtim.webp', 350.00, 280.00, 'novelties', 'CHILDREN NOVELTIES'),
('10Cm Electric Sparklers', './img/10CmElectricSparklers.jpg', 85.00, 68.00, 'sparklers', 'SPARKLERS'),
('15Cm Electric Sparklers', './img/15CmElectricSparklers.jpg', 180.00, 144.00, 'sparklers', 'SPARKLERS');
