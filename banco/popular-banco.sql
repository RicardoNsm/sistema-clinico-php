USE sistema_clinica;

-- 1. Criação de usuários (Login padrão: 123)
INSERT INTO usuarios (username, password) VALUES
('alexandre_med', '$2y$10$dMEM0XJ6xijr7r0G7OpYWOkNEjmJ.5Uvh0MJiS2GVmDZGoK8PMYD2'),
('gustavo_med', '$2y$10$dMEM0XJ6xijr7r0G7OpYWOkNEjmJ.5Uvh0MJiS2GVmDZGoK8PMYD2'),
('allan_med', '$2y$10$dMEM0XJ6xijr7r0G7OpYWOkNEjmJ.5Uvh0MJiS2GVmDZGoK8PMYD2');

-- 2. Inserir Médicos (Antigos Professores)
INSERT INTO medicos (nome, crm, especialidade, usuario_id) VALUES
('Dr. Alexandre', 'CRM/PE 12345', 'Clínico Geral', 1),
('Dr. Gustavo', 'CRM/PE 67890', 'Cardiologista', 2),
('Dr. Allan', 'CRM/PE 54321', 'Pediatra', 3);

-- 3. Inserir Consultas (Antigas Turmas)
INSERT INTO consultas (procedimento, horario, medico_id) VALUES
('Consulta de Rotina', '2024-06-01 08:00:00', 1),
('Exame Cardiovascular', '2024-06-01 14:00:00', 1),
('Atendimento Pediátrico', '2024-06-02 19:00:00', 3),
('Check-up Geral', '2024-06-03 15:00:00', 2);

-- 4. Criar usuários para 20 Pacientes
INSERT INTO usuarios (username, password) VALUES
('paciente1', '$2y$10$dMEM0XJ6xijr7r0G7OpYWOkNEjmJ.5Uvh0MJiS2GVmDZGoK8PMYD2'),
('paciente2', '$2y$10$dMEM0XJ6xijr7r0G7OpYWOkNEjmJ.5Uvh0MJiS2GVmDZGoK8PMYD2'),
('paciente3', '$2y$10$dMEM0XJ6xijr7r0G7OpYWOkNEjmJ.5Uvh0MJiS2GVmDZGoK8PMYD2');
-- (Repita o padrão para os outros usuários se necessário)

-- 5. Inserir Pacientes (Antigos Alunos)
-- Note: Removi a tabela 'imagens' pois no seu SQL anterior a foto é um campo direto na tabela pacientes
INSERT INTO pacientes (nome, cpf, data_nascimento, email, usuario_id, foto) VALUES
('Paciente 1', '111.111.111-11', '2000-01-01', 'paciente1@email.com', 4, 'profile.jpg'),
('Paciente 2', '222.222.222-22', '2000-02-01', 'paciente2@email.com', 5, 'profile.jpg'),
('Paciente 3', '333.333.333-33', '2000-03-01', 'paciente3@email.com', 6, 'profile.jpg');

-- 6. Vincular Pacientes às Consultas (Tabela Prontuarios)
INSERT INTO prontuarios (paciente_id, consulta_id) VALUES
(1, 1), (1, 2), (2, 1), (3, 1);