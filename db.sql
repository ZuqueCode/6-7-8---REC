CREATE DATABASE IF NOT EXISTS gestao_brinquedos CHARACTER SET utf8mb4;
USE gestao_brinquedos;

CREATE TABLE IF NOT EXISTS brinquedos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  categoria VARCHAR(50) NOT NULL,
  faixa_etaria VARCHAR(30) NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  estoque INT NOT NULL DEFAULT 0
  ed int not null default 0
);

INSERT INTO brinquedos (nome, categoria, faixa_etaria, preco, estoque) VALUES
('Blocos de Montar', 'Educativo', '3 a 5 anos', 59.90, 25),
('Boneca Articulada', 'Bonecas', '6 a 8 anos', 89.90, 12);