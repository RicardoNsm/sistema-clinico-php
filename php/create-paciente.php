<?php
require_once 'db.php';
require_once 'authenticate.php';

$stmt = $pdo->query("SELECT id, username FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $usuario_id = $_POST['usuario_id'];

    if (!empty($_FILES['imagem']['name'])) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novoNome = uniqid() . '.' . $extensao;
        $caminho = __DIR__ . '/../storage/' . $novoNome;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            $stmt = $pdo->prepare("INSERT INTO imagens (path) VALUES (?)");
            $stmt->execute([$novoNome]);
            $imagem_id = $pdo->lastInsertId();
        }
    } else {
        $imagem_id = null;
    }

    $stmt = $pdo->prepare("INSERT INTO pacientes (nome, cpf, data_nascimento, email, usuario_id, imagem_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $cpf, $data_nascimento, $email, $usuario_id, $imagem_id]);

    header('Location: index-paciente.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Paciente</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Adicionar Paciente</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>Pacientes: <a href="create-paciente.php">Add</a> | <a href="index-paciente.php">Listar</a></li>
                    <li>Médicos: <a href="create-medico.php">Add</a> | <a href="index-medico.php">Listar</a></li>
                    <li>Consultas: <a href="create-consulta.php">Add</a> | <a href="index-consulta.php">Listar</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <form method="POST" enctype="multipart/form-data">
            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>CPF:</label>
            <input type="text" name="cpf" required>

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nascimento" required>

            <label>E-mail:</label>
            <input type="email" name="email" required>

            <label>Usuário:</label>
            <select name="usuario_id" required>
                <option value="">Selecione o usuário</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>"><?= $usuario['username'] ?></option>
                <?php endforeach; ?>
            </select>

            <label>Imagem de Perfil:</label>
            <input type="file" name="imagem" accept="image/*">

            <button type="submit">Adicionar Paciente</button>
        </form>
    </main>
</body>
</html>