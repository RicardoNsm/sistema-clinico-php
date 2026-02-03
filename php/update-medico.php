<?php
require_once 'db.php';
require_once 'authenticate.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id) {
    header("Location: index-medico.php");
    exit();
}

// Seleciona o médico específico para preencher os campos do formulário
$stmt = $pdo->prepare("SELECT * FROM medicos WHERE id = ?");
$stmt->execute([$id]);
$medico = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    die("Profissional não encontrado.");
}

// Obter todos os usuários para permitir a troca de vínculo, se necessário
$stmt = $pdo->query("SELECT id, username FROM usuarios ORDER BY username ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    $crm = $_POST['crm'];
    $usuario_id = $_POST['usuario_id'];

    // Atualiza os dados do médico
    $stmt = $pdo->prepare("UPDATE medicos SET nome = ?, especialidade = ?, crm = ?, usuario_id = ? WHERE id = ?");
    $stmt->execute([$nome, $especialidade, $crm, $usuario_id, $id]);

    header('Location: read-medico.php?id=' . $id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Médico</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Editar Cadastro de Médico</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index-medico.php">Listar Médicos</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <form method="POST">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" 
                       value="<?= htmlspecialchars($medico['nome']) ?>" required>

                <label for="especialidade">Especialidade:</label>
                <input type="text" id="especialidade" name="especialidade" 
                       value="<?= htmlspecialchars($medico['especialidade']) ?>" required>

                <label for="crm">CRM:</label>
                <input type="text" id="crm" name="crm" 
                       value="<?= htmlspecialchars($medico['crm']) ?>" required>

                <label for="usuario_id">Usuário Associado:</label>
                <select id="usuario_id" name="usuario_id" required>
                    <option value="">Selecione o usuário</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario['id'] ?>" 
                            <?= $usuario['id'] == $medico['usuario_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($usuario['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="actions" style="margin-top: 20px;">
                    <button type="submit" class="btn-update">Atualizar Dados</button>
                    <a href="read-medico.php?id=<?= $id ?>" class="btn-link">Voltar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>