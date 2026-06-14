-- Create the main medicines table (already exists in database.php)
CREATE TABLE IF NOT EXISTS medicines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    generic_name VARCHAR(255),
    category VARCHAR(100),
    manufacturer VARCHAR(255),
    dosage VARCHAR(100),
    quantity INT NOT NULL DEFAULT 0,
    price DECIMAL(10,2) NOT NULL,
    reorder_level INT DEFAULT 10,
    expiry_date DATE,
    supplier VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create sales table for dispensing records
CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    prescription_number VARCHAR(100),
    total_amount DECIMAL(10,2) NOT NULL,
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create sale_items table for individual items in each sale
CREATE TABLE IF NOT EXISTS sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
);

-- Create stock_adjustments table for tracking stock changes
CREATE TABLE IF NOT EXISTS stock_adjustments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_id INT NOT NULL,
    old_quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    adjustment INT NOT NULL,
    reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
);

-- Create activity_log table for system activities
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create alerts table for managing alert notifications
CREATE TABLE IF NOT EXISTS alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('low_stock', 'expired', 'expiring_soon', 'out_of_stock') NOT NULL,
    medicine_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
);

-- Insert sample data for testing
INSERT INTO medicines (name, generic_name, category, manufacturer, dosage, quantity, price, reorder_level, expiry_date, supplier, description) VALUES
('Paracetamol', 'Acetaminophen', 'Pain Relief', 'PharmaCorp', '500mg', 150, 2.50, 20, '2025-12-31', 'MedSupply Inc', 'Pain and fever relief'),
('Amoxicillin', 'Amoxicillin', 'Antibiotics', 'BioMed', '250mg', 8, 15.75, 15, '2024-08-15', 'Global Pharma', 'Antibiotic for bacterial infections'),
('Vitamin C', 'Ascorbic Acid', 'Vitamins', 'HealthPlus', '1000mg', 200, 8.99, 25, '2026-03-20', 'Vitamin World', 'Immune system support'),
('Insulin', 'Human Insulin', 'Diabetes', 'DiabetCare', '100IU/ml', 5, 45.00, 10, '2024-06-30', 'Diabetes Solutions', 'Blood sugar control'),
('Aspirin', 'Acetylsalicylic Acid', 'Pain Relief', 'CardioMed', '81mg', 0, 3.25, 30, '2025-09-15', 'Heart Health Co', 'Low-dose aspirin for heart health');