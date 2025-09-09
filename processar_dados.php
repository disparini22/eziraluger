<?php
<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    if ($nome && $cpf) {
        $_SESSION['dadosBasicos'] = [
            'nome' => $nome,
            'cpf' => $cpf
        ];
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dados incompletos']);
    }
}
?>