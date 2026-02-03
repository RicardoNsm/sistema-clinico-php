<?php
require_once 'db.php';
require_once 'authenticate.php';

$stmt = $pdo->query("SELECT consultas.*, medicos.nome AS medico_nome FROM consultas LEFT JOIN medicos ON consultas.medico_id = medicos.id");
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Consultas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Lista de Consultas</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>Pacientes: <a href="create-paciente.php">Add</a> | <a href="index-paciente.php">Listar</a></li>
                    <li>Médicos: <a href="create-medico.php">Add</a> | <a href="index-medico.php">Listar</a></li>
                    <li>Consultas: <a href="create-consulta.php">Add</a> | <a href="index-consulta.php">Listar</a></li>
                    <li><a href="logout.php">Logout (<?= $_SESSION['username'] ?>)</a></li>
                <?php else: ?>
                    <li><a href="user-login.php">Login</a></li>
                    <li><a href="user-register.php">Registrar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Procedimento</th>
                    <th>Data e Hora</th>
                    <th>Médico</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($consultas as $consulta): ?>
                    <tr>
                        <td><?= $consulta['id'] ?></td>
                        <td><?= $consulta['procedimento'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($consulta['horario'])) ?></td>
                        <td><?= $consulta['medico_nome'] ?></td>
                        <td>
                            <a href="read-consulta.php?id=<?= $consulta['id'] ?>">Visualizar</a>
                            <a href="update-consulta.php?id=<?= $consulta['id'] ?>">Editar</a>
                            <a href="gerar-pdf-consulta.php?id=<?= $consulta['id'] ?>" target="_blank">PDF</a>
                            <a href="delete-consulta.php?id=<?= $consulta['id'] ?>" onclick="return confirm('Excluir esta consulta?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>