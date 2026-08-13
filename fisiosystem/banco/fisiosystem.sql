CREATE DATABASE IF NOT EXISTS fisiosystem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fisiosystem;

CREATE TABLE IF NOT EXISTS pacientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  cpf VARCHAR(14),
  data_nascimento DATE NULL,
  telefone VARCHAR(20),
  profissao VARCHAR(100),
  endereco VARCHAR(255),
  status ENUM('avaliacao','tratamento','reavaliacao','alta') NOT NULL DEFAULT 'avaliacao',
  data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS avaliacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  paciente_id INT NOT NULL,
  queixa_principal TEXT,
  historia_doenca TEXT,
  antecedentes TEXT,
  medicamentos TEXT,
  diagnostico_medico TEXT,
  diagnostico_fisioterapeutico TEXT,
  dor_eva TINYINT UNSIGNED DEFAULT 0,
  inspecao TEXT,
  palpacao TEXT,
  amplitude_movimento TEXT,
  forca_muscular TEXT,
  objetivos TEXT,
  conduta TEXT,
  data_avaliacao DATE NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_avaliacao_paciente FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS evolucoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  paciente_id INT NOT NULL,
  data_sessao DATE NOT NULL,
  numero_sessao INT NOT NULL DEFAULT 1,
  dor_antes TINYINT UNSIGNED DEFAULT 0,
  dor_depois TINYINT UNSIGNED DEFAULT 0,
  procedimentos TEXT,
  evolucao TEXT NOT NULL,
  proxima_conduta TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_evolucao_paciente FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
) ENGINE=InnoDB;
