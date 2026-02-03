<?php
require_once 'db.php';
require_once 'authenticate.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id) {
    header("Location: index-consulta.php");
    exit();
}

// Seleciona a consulta específica para preencher o formulário
$stmt = $pdo->prepare("SELECT * FROM consultas WHERE id = ?");
$stmt->execute([$id]);
$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consulta) {
    die("Consulta não encontrada.");
}

// Obter todos os médicos para a listagem do Select
$stmt = $pdo->query("SELECT id, nome FROM medicos ORDER BY nome ASC");
$medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $procedimento = $_POST['procedimento'];
    $horario = $_POST['horario'];
    $medico_id = $_POST['medico_id'];

    // Atualiza a consulta no banco de dados
    $stmt = $pdo->prepare("UPDATE consultas SET procedimento = ?, horario = ?, medico_id = ? WHERE id = ?");
    $stmt->execute([$procedimento, $horario, $medico_id, $id]);

    header('Location: read-consulta.php?id=' . $id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Consulta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Editar Agendamento</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index-consulta.php">Listar Consultas</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <form method="POST">
                <label for="procedimento">Procedimento / Motivo:</label>
                <input type="text" id="procedimento" name="procedimento" 
                       value="<?= htmlspecialchars($consulta['procedimento']) ?>" required>

                <label for="horario">Data e Hora:</label>
                <input type="datetime-local" id="horario" name="horario" 
                       value="<?= date('Y-m-d\TH:i', strtotime($consulta['horario'])) ?>" required>

                <label for="medico_id">Médico Responsável:</label>
                <select id="medico_id" name="medico_id" required>
                    <option value="">Selecione o profissional</option>
                    <?php foreach ($medicos as $medico): ?>
                        <option value="<?= $medico['id'] ?>" 
                            <?= $medico['id'] == $consulta['medico_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($medico['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="button-group">
                    <button type="submit" class="btn-save">Salvar Alterações</button>
                    <a href="read-consulta.php?id=<?= $id ?>" class="btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>