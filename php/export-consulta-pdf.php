<?php
require_once 'db.php';
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT consultas.*, medicos.nome AS medico_nome 
    FROM consultas 
    LEFT JOIN medicos ON consultas.medico_id = medicos.id 
    WHERE consultas.id = ?
");
$stmt->execute([$id]);
$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT pacientes.* FROM pacientes 
    INNER JOIN prontuarios ON pacientes.id = prontuarios.paciente_id 
    WHERE prontuarios.consulta_id = ?
");
$stmt->execute([$id]);
$pacientesVinculados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dompdf = new Dompdf();

$html = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório da Consulta</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 20px; color: #2c3e50; border-bottom: 2px solid #2c3e50; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; }
        .info { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Relatório de Agendamento</h1>
    <div class="info">
        <p><strong>ID da Consulta:</strong> ' . $consulta['id'] . '</p>
        <p><strong>Procedimento:</strong> ' . $consulta['procedimento'] . '</p>
        <p><strong>Data/Hora:</strong> ' . date('d/m/Y H:i', strtotime($consulta['horario'])) . '</p>
        <p><strong>Médico Responsável:</strong> ' . $consulta['medico_nome'] . '</p>
    </div>
    
    <h2>Pacientes Confirmados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome do Paciente</th>
                <th>CPF</th>
            </tr>
        </thead>
        <tbody>';
            foreach ($pacientesVinculados as $paciente) {
                $html .= '<tr>
                            <td>' . $paciente['id'] . '</td>
                            <td>' . $paciente['nome'] . '</td>
                            <td>' . $paciente['cpf'] . '</td>
                          </tr>';
            }
$html .= '
        </tbody>
    </table>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('consulta_' . $consulta['id'] . '.pdf', array("Attachment" => false));
?>