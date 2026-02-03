<?php
require_once 'db.php';
require_once 'authenticate.php';

$stmt = $pdo->query("SELECT id, username FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    $crm = $_POST['crm'];
    $usuario_id = $_POST['usuario_id'];

    $stmt = $pdo->prepare("INSERT INTO medicos (nome, especialidade, crm, usuario_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $especialidade, $crm, $usuario_id]);

    header('Location: index-medico.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Médico</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Adicionar Médico</h1>
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
            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>Especialidade:</label>
            <input type="text" name="especialidade" required>

            <label>CRM:</label>
            <input type="text" name="crm" required>

            <label>Usuário:</label>
            <select name="usuario_id" required>
                <option value="">Selecione o usuário</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>"><?= $usuario['username'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Adicionar Médico</button>
        </form>
    </main>
</body>
</html>