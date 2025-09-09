<?php
header('Content-Type: application/json');

// Lê o JSON enviado
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Obtém o ID da transação enviado via JSON
$idTransaction = isset($data['idtransaction']) ? $data['idtransaction'] : null;

if ($idTransaction === null) {
    echo json_encode(['status' => 'error', 'message' => 'ID da transação não fornecido']);
    exit;
}

include './../conectarbanco.php';

$conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Conexão com o banco de dados falhou']);
    exit;
}

$stmt = $conn->prepare("SELECT status FROM confirmar_deposito WHERE externalreference = ?");
$stmt->bind_param('s', $idTransaction);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $status = $row['status'];
    echo json_encode(['status' => $status]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Transação não encontrada']);
}

$stmt->close();
$conn->close();
?>
