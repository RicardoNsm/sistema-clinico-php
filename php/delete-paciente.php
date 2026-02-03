<?php
require_once 'db.php';
require_once 'authenticate.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT imagens.path, pacientes.imagem_id 
    FROM pacientes 
    LEFT JOIN imagens ON pacientes.imagem_id = imagens.id 
    WHERE pacientes.id = ?
");
$stmt->execute([$id]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if ($resultado && $resultado['path']) {
    $caminhoArquivo = __DIR__ . '/../storage/' . $resultado['path'];
    if (file_exists($caminhoArquivo)) {
        unlink($caminhoArquivo);
    }

    $stmtImg = $pdo->prepare("DELETE FROM imagens WHERE id = ?");
    $stmtImg->execute([$resultado['imagem_id']]);
}

$stmt = $pdo->prepare("DELETE FROM pacientes WHERE id = ?");
$stmt->execute([$id]);

header('Location: index-paciente.php');
exit();
?>