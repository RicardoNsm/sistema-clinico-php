<?php
require_once 'db.php';
require_once 'authenticate.php';

$stmt = $pdo->query("SELECT id, nome FROM medicos");
$medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $procedimento = $_POST['procedimento'];
    $horario = $_POST['horario'];
    $medico_id = $_POST['medico_id'];

    $stmt = $pdo->prepare("INSERT INTO consultas (procedimento, horario, medico_id) VALUES (?, ?, ?)");
    $stmt->execute([$procedimento, $horario, $medico_id]);

    header('Location: index-consulta.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendar Consulta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Agendar Consulta</h1>
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
        <form method="POST">
            <label>Procedimento:</label>
            <input type="text" name="procedimento" required>

            <label>Data e Hora:</label>
            <input type="datetime-local" name="horario" required>

            <label>Médico:</label>
            <select name="medico_id" required>
                <option value="">Selecione</option>
                <?php foreach ($medicos as $medico): ?>
                    <option value="<?= $medico['id'] ?>"><?= $medico['nome'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Agendar</button>
        </form>
    </main>
</body>
</html>