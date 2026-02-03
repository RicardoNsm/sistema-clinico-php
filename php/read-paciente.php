<?php
require_once 'db.php';
require_once 'authenticate.php';

// Sanitização básica do ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index-paciente.php");
    exit();
}

// Busca o paciente, sua imagem e o usuário do sistema associado
$stmt = $pdo->prepare("
    SELECT pacientes.*, imagens.path, usuarios.username 
    FROM pacientes 
    LEFT JOIN imagens ON pacientes.imagem_id = imagens.id 
    LEFT JOIN usuarios ON pacientes.usuario_id = usuarios.id 
    WHERE pacientes.id = ?
");
$stmt->execute([$id]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

// Lógica da imagem de perfil
$imagemPath = ($paciente && $paciente['path']) 
    ? '../storage/' . $paciente['path'] 
    : '../storage/default-avatar.png';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prontuário de Paciente</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .profile-img { width: 150px; height: 150px; border-radius: 8px; object-fit: cover; border: 2px solid #ddd; }
        .info-group { margin-bottom: 15px; }
    </style>
</head>
<body>
    <header>
        <h1>Detalhes do Paciente</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index-paciente.php">Lista de Pacientes</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if ($paciente): ?>
            <div class="card">
                <img src="<?= $imagemPath ?>" alt="Foto de <?= htmlspecialchars($paciente['nome']) ?>" class="profile-img">
                
                <div class="info-group">
                    <p><strong>Nome Completo:</strong> <?= htmlspecialchars($paciente['nome']) ?></p>
                    <p><strong>CPF:</strong> <?= htmlspecialchars($paciente['cpf']) ?></p>
                    <p><strong>Data de Nascimento:</strong> <?= date('d/m/Y', strtotime($paciente['data_nascimento'])) ?></p>
                    <p><strong>E-mail:</strong> <?= htmlspecialchars($paciente['email']) ?></p>
                    <p><strong>Usuário do Sistema:</strong> <?= $paciente['username'] ?? '<em>Não possui</em>' ?></p>
                </div>

                <div class="acoes">
                    <a href="update-paciente.php?id=<?= $paciente['id'] ?>" class="btn-primary">Editar Cadastro</a>
                    <a href="delete-paciente.php?id=<?= $paciente['id'] ?>" class="btn-danger" onclick="return confirm('Excluir este prontuário?')">Remover Paciente</a>
                </div>
            </div>
        <?php else: ?>
            <p>Paciente não encontrado no banco de dados.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2026 - Sistema de Gestão Clínica</p>
    </footer>
</body>
</html>