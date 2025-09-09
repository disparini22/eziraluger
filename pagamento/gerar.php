<?php

include '../conectarbanco.php';

$conn = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_GET['value'])) {
    $value = floatval($_GET['value']);
    $nome = $_GET['nome'];


    // Obtém os parâmetros UTM usando a função definida abaixo
    $utms = getUtmParameters();

    // Prepara o payload para a API
    $data = [
        "amount" => 6790,
        "client" => [
            "name" => "Liberação ICMS",
            "document" => "98765432100",
            "telefone" => "98765432100",
            "email" => "Anonimo@email.com"
        ],
        "api-key" => "fc6625cb-63bf-4bd3-95e3-9fe218f8a478",
        "utms" => $utms,
      
        "product" => [
            "name_product" => "taxa-1",
            "valor_product" => $value
        ]
    ];

    $payload = json_encode($data);
    $ch = curl_init('https://api.paginasegura.click/v1/gateway/');

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Fecha a sessão cURL
    curl_close($ch);

    // Verifica se a requisição foi bem-sucedida
    if ($httpCode == 200) {
        $responseData = json_decode($response, true);

        if ($responseData['status'] === 'success' && isset($responseData['paymentCode'])) {
            $paymentCode = $responseData['paymentCode'];
            $idTransaction = $responseData['idTransaction'];

            // Obtém o horário de Brasília
            date_default_timezone_set('America/Sao_Paulo');
            $dataAtual = date('Y-m-d H:i:s');

            // Cria um array com os dados
            $dados = [
                "externalreference" => $idTransaction,
                "valor" => $value,
                "status" => "PENDING",
                "data" => $dataAtual
            ];




     // Insere os dados na tabela confirmar_deposito
     $stmt = $conn->prepare("INSERT INTO confirmar_deposito (externalreference, valor, status, data) VALUES (?, ?, ?, ?)");
     $status = 'PENDING';
     $valor = $value; // o valor original, sem multiplicar por 100
     $stmt->bind_param("ssss", $idTransaction, $valor, $status, $dataAtual);


         // Executa a inserção e verifica se foi bem-sucedida
            if ($stmt->execute()) {
                // Se a inserção foi bem-sucedida, redireciona
                header("Location: index.php?paymentcode=" . urlencode($paymentCode) . "&token=" . urlencode($idTransaction) . "&value=" . urlencode($value));
                exit();
            } else {
                echo "Erro ao inserir no banco de dados: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Erro: Não foi possível gerar o Pix.";
            echo "<pre>";
            print_r($responseData);
            echo "</pre>";
        }
    } else {
        echo "Erro na requisição: " . $httpCode;
        echo "<pre>";
        print_r($response);
        echo "</pre>";
    }
} else {
    echo "Erro: Nenhum valor foi fornecido na URL.";
}

// Função para obter os parâmetros UTM
function getUtmParameters() {
    $params = [];
    $utm_keys = ['utm_source', 'utm_campaign', 'utm_medium', 'utm_content', 'utm_term'];

    foreach ($utm_keys as $key) {
        // Se o parâmetro existir e não estiver vazio, usa-o; caso contrário, define como "Organic"
        $params[$key] = isset($_GET[$key]) && !empty($_GET[$key]) ? $_GET[$key] : "Organic";
    }

    return $params;
}

// Função para gerar UUID
function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
?>
