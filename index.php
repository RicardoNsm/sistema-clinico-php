<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Clínico</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Sistema Clínico</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>Pacientes: 
                        <a href="php/create-paciente.php">Adicionar</a> | 
                        <a href="php/index-paciente.php">Listar</a>
                    </li>
                    <li>Médicos: 
                        <a href="php/create-medico.php">Adicionar</a> | 
                        <a href="php/index-medico.php">Listar</a>
                    </li>
                    <li>Consultas: 
                        <a href="php/create-consulta.php">Adicionar</a> | 
                        <a href="php/index-consulta.php">Listar</a>
                    </li>
                    <li><a href="php/logout.php">Logout (<?= $_SESSION['username'] ?>)</a></li>
                <?php else: ?>
                    <li><a href="php/user-login.php">Login</a></li>
                    <li><a href="php/user-register.php">Registrar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Bem-vindo ao Sistema de Gestão Clínica</h2>
        <p>Utilize o menu acima para navegar pelo sistema.</p>
        <br>
        <p>Esta versão inclui a exportação dos dados de consulta para PDF.</p>
    </main>

    <footer>
        <p>&copy; 2024 - Sistema de Gerenciamento Clínico</p>
    </footer>
</body>
</html>