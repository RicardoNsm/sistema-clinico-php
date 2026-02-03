<?php
require_once 'db.php';
require_once 'authenticate.php';

// Obtém o ID da URL de forma segura
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id) {
    header("Location: index-medico.php");
    exit();
}

// Seleciona o médico específico pelo ID, trazendo também o nome de usuário associado
$stmt = $pdo->prepare("
    SELECT medicos.*, usuarios.username 
    FROM medicos 
    LEFT JOIN usuarios ON medicos.usuario_id = usuarios.id 
    WHERE medicos.id = ?
");
$stmt->execute([$id]);
$medico = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Médico</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Detalhes do Profissional</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index-medico.php">Voltar para Lista</a></li>
                <li><a href="logout.php">Sair (<?= $_SESSION['username'] ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="card-detalhes">
            <?php if ($medico): ?>
                <h2>Informações de <?= htmlspecialchars($medico['nome']) ?></h2>
                <hr>
                <p><strong>ID do Registro:</strong> <?= $medico['id'] ?></p>
                <p><strong>Nome Completo:</strong> <?= htmlspecialchars($medico['nome']) ?></p>
                <p><strong>Especialidade:</strong> <?= htmlspecialchars($medico['especialidade']) ?></p>
                <p><strong>CRM:</strong> <?= htmlspecialchars($medico['crm']) ?></p>
                <p><strong>Usuário de Sistema:</strong> 
                    <span class="badge"><?= $medico['username'] ? htmlspecialchars($medico['username']) : 'Nenhum usuário vinculado' ?></span>
                </p>
                
                <div class="acoes-detalhes" style="margin-top: 30px;">
                    <a href="update-medico.php?id=<?= $medico['id'] ?>" class="btn-editar">Editar Dados</a>
                    <a href="delete-medico.php?id=<?= $medico['id'] ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir este médico?');" 
                       class="btn-excluir">Excluir Registro</a>
                </div>
            <?php else: ?>
                <p class="erro">Médico não encontrado no sistema.</p>
                <a href="index-medico.php">Voltar</a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 - Gestão Hospitalar</p>
    </footer>
</body>
</html>