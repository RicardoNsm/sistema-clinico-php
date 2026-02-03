CREATE DATABASE sistema_clinica;
USE sistema_clinica;

-- Tabela para login no sistema
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabela de Pacientes (Antiga Alunos)
CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(20) NOT NULL UNIQUE, -- Troquei matricula por CPF
    data_nascimento DATE NOT NULL,
    email VARCHAR(100) NOT NULL,
    foto VARCHAR(255) DEFAULT 'profile.jpg', -- Caminho da imagem no storage
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabela de Médicos (Antiga Professores)
CREATE TABLE medicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    crm VARCHAR(20) NOT NULL UNIQUE, -- Registro médico
    especialidade VARCHAR(100) NOT NULL,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabela de Consultas/Agendas (Antiga Turmas)
CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    procedimento VARCHAR(100) NOT NULL, -- Ex: Consulta Geral, Exame
    horario DATETIME NOT NULL,
    medico_id INT,
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE SET NULL
);

-- Tabela Intermediária N-N (Vínculo Paciente x Consulta)
CREATE TABLE prontuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT,
    consulta_id INT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (consulta_id) REFERENCES consultas(id) ON DELETE CASCADE
);