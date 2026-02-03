<?php
// 1. Conexão e Dependências
require_once 'db.php';
// Caminho ajustado: sobe um nível para achar a pasta vendor na raiz
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 2. Captura e validação do ID
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id) {
    die("Erro: ID da consulta não fornecido ou inválido.");
}

// 3. Busca os dados da Consulta e do Médico (JOIN)
$stmt = $pdo->prepare("
    SELECT c.*, m.nome AS medico_nome 
    FROM consultas c 
    LEFT JOIN medicos m ON c.medico_id = m.id 
    WHERE c.id = ?
");
$stmt->execute([$id]);
$consulta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consulta) {
    die("Erro: Consulta não encontrada no banco de dados.");
}

// 4. Busca os Pacientes vinculados através da tabela de prontuários
$stmt = $pdo->prepare("
    SELECT p.id, p.nome, p.cpf 
    FROM pacientes p
    INNER JOIN prontuarios pr ON p.id = pr.paciente_id 
    WHERE pr.consulta_id = ?
");
$stmt->execute([$id]);
$pacientesVinculados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Configuração do DomPDF
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// 6. Montagem do HTML
$html = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório da Consulta</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.6; }
        h1 { font-size: 22px; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; text-align: center; }
        h2 { font-size: 18px; color: #2980b9; margin-top: 30px; border-left: 5px solid #3498db; padding-left: 10px; }
        .info-box { background: #f9f9f9; padding: 15px; border: 1px solid #eee; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <h1>Relatório de Agendamento Médico</h1>
    
    <div class="info-box">
        <p><strong>ID da Consulta:</strong> #' . $consulta['id'] . '</p>
        <p><strong>Procedimento:</strong> ' . ($consulta['procedimento'] ?? 'Não informado') . '</p>
        <p><strong>Data/Hora:</strong> ' . date('d/m/Y H:i', strtotime($consulta['horario'])) . '</p>
        <p><strong>Médico Responsável:</strong> ' . ($consulta['medico_nome'] ?? 'Não atribuído') . '</p>
    </div>
    
    <h2>Pacientes Confirmados</h2>
    <table>
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th width="60%">Nome do Paciente</th>
                <th width="30%">CPF</th>
            </tr>
        </thead>
        <tbody>';

        if (count($pacientesVinculados) > 0) {
            foreach ($pacientesVinculados as $paciente) {
                $html .= '<tr>
                            <td>' . $paciente['id'] . '</td>
                            <td>' . $paciente['nome'] . '</td>
                            <td>' . $paciente['cpf'] . '</td>
                          </tr>';
            }
        } else {
            $html .= '<tr><td colspan="3" style="text-align:center;">Nenhum paciente vinculado a esta consulta.</td></tr>';
        }

$html .= '
        </tbody>
    </table>
    <div class="footer">Gerado automaticamente pelo Sistema Clínico - ' . date('d/m/Y H:i') . '</div>
</body>
</html>';

// 7. Geração do PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 8. Saída para o Navegador
$dompdf->stream('relatorio_consulta_' . $id . '.pdf', array("Attachment" => false));
?>