<?php
require_once 'db.php';
require_once 'authenticate.php';

// Consulta pacientes trazendo o caminho da imagem se ela existir
$query = "
    SELECT pacientes.*, imagens.path 
    FROM pacientes 
    LEFT JOIN imagens ON pacientes.imagem_id = imagens.id
";
$stmt = $pdo->query($query);
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Pacientes</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .foto-perfil { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Gerenciamento Clínico</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>Pacientes: <a href="create-paciente.php">Add</a> | <a href="index-paciente.php">Listar</a></li>
                    <li>Médicos: <a href="create-medico.php">Add</a> | <a href="index-medico.php">Listar</a></li>
                    <li>Consultas: <a href="create-consulta.php">Add</a> | <a href="index-consulta.php">Listar</a></li>
                    <li><a href="logout.php">Sair (<?= $_SESSION['username'] ?>)</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Lista de Pacientes</h2>
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Nascimento</th>
                    <th>E-mail</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                        <td>
                            <?php if ($paciente['path']): ?>
                                <img src="../storage/<?= $paciente['path'] ?>" class="foto-perfil">
                            <?php else: ?>
                                <img src="../css/default-user.png" class="foto-perfil" alt="Sem foto">
                            <?php endif; ?>
                        </td>
                        <td><?= $paciente['nome'] ?></td>
                        <td><?= $paciente['cpf'] ?></td>
                        <td><?= date('d/m/Y', strtotime($paciente['data_nascimento'])) ?></td>
                        <td><?= $paciente['email'] ?></td>
                        <td>
                            <a href="update-paciente.php?id=<?= $paciente['id'] ?>">Editar</a>
                            <a href="delete-paciente.php?id=<?= $paciente['id'] ?>" onclick="return confirm('Excluir paciente?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2026 - Clínica Médica Vida</p>
    </footer>
</body>
</html>