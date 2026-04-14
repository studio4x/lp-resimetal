-- Script de Criação do Banco de Dados - Painel Resimetal

CREATE TABLE IF NOT EXISTS site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section VARCHAR(50) NOT NULL,
    content_key VARCHAR(50) NOT NULL,
    content_value TEXT,
    content_type ENUM('text', 'image') DEFAULT 'text',
    UNIQUE KEY section_key (section, content_key)
);

CREATE TABLE IF NOT EXISTS gallery_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    value TEXT
);

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS site_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metric_date DATE NOT NULL UNIQUE,
    page_views INT DEFAULT 0,
    unique_visitors INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS visitor_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    visit_date DATE NOT NULL,
    INDEX (ip_address, visit_date)
);

-- Usuário Inicial: admin / Senha: admin123
INSERT INTO admin_users (username, password_hash) 
VALUES ('admin', '$2y$10$wEuB6UeUoFj2oIq7.0x7m.tF9.L1MhM/xZ9Q6G5iZ5n8g.I3w.i6m');

-- Exemplo de configurações iniciais de marketing
INSERT INTO site_content (section, content_key, content_value, content_type) VALUES
('hero', 'title', 'Excelência em moagem e fundição de resíduos industriais', 'text'),
('hero', 'subtitle', 'Atuamos na moagem e beneficiamento de terras e borras de zinco, transformando resíduos industriais em lingotes de alto valor agregado.', 'text');
