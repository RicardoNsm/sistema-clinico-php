<?php
require_once 'db.php';
require_once 'authenticate.php';

$id = $_GET['id'];

// Seleciona a consulta específica e o médico responsável
$stmt = $pdo->prepare("
    SELECT consultas.*, medicos.nome AS medico_nome 
    FROM consultas 
    LEFT JOIN medicos ON consultas.medico_id = medicos.id 
    WHERE consultas.id = ?
");
$stmt->execute([$id]);
$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

// Seleciona os pacientes que AINDA NÃO estão vinculados a esta consulta
$stmt = $pdo->prepare("
    SELECT * FROM pacientes 
    WHERE id NOT IN (SELECT paciente_id FROM prontuarios WHERE consulta_id = ?)
");
$stmt->execute([$id]);
$pacientesDisponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Seleciona os pacientes JÁ VINCULADOS a esta consulta
$stmt = $pdo->prepare("
    SELECT pacientes.* FROM pacientes 
    INNER JOIN prontuarios ON pacientes.id = prontuarios.paciente_id 
    WHERE prontuarios.consulta_id = ?
");
$stmt->execute([$id]);
$pacientesVinculados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lógica de Vinculação (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['vincular'])) {
        $paciente_id = $_POST['paciente_id'];
        $stmt = $pdo->prepare("INSERT INTO prontuarios (paciente_id, consulta_id) VALUES (?, ?)");
        $stmt->execute([$paciente_id, $id]);
    } elseif (isset($_POST['desvincular'])) {
        $paciente_id = $_POST['paciente_id'];
        $stmt = $pdo->prepare("DELETE FROM prontuarios WHERE paciente_id = ? AND consulta_id = ?");
        $stmt->execute([$paciente_id, $id]);
    }

    header("Location: read-consulta.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Consulta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Gestão de Prontuário / Consulta</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index-consulta.php">Listar Consultas</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php if ($consulta): ?>
            <div class="card">
                <h2>Informações Gerais</h2>
                <p><strong>ID da Consulta:</strong> <?= $consulta['id'] ?></p>
                <p><strong>Procedimento:</strong> <?= $consulta['procedimento'] ?></p>
                <p><strong>Data/Hora:</strong> <?= date('d/m/Y H:i', strtotime($consulta['horario'])) ?></p>
                <p><strong>Médico Responsável:</strong> <?= $consulta['medico_nome'] ?></p>
            </div>
            
            <hr>

            <h3>Vincular Paciente à Consulta</h3>
            <form method="POST">
                <label for="paciente_id">Paciente:</label>
                <select id="paciente_id" name="paciente_id" required>
                    <option value="">Selecione o paciente</option>
                    <?php foreach ($pacientesDisponiveis as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['nome'] ?> (CPF: <?= $p['cpf'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="vincular">Confirmar Presença</button>
            </form>

            <h3>Pacientes Confirmados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientesVinculados as $p): ?>
                        <tr>
                            <td><?= $p['nome'] ?></td>
                            <td><?= $p['cpf'] ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="paciente_id" value="<?= $p['id'] ?>">
                                    <button type="submit" name="desvincular" class="btn-danger">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <a href="gerar-pdf-consulta.php?id=<?= $consulta['id'] ?>" target="_blank" class="btn-pdf">Imprimir Relatório (PDF)</a>
            </div>
        <?php else: ?>
            <p>Consulta não encontrada.</p>
        <?php endif; ?>
    </main>
</body>
</html>