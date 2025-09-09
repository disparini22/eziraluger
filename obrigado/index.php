<!DOCTYPE html>
<html lang="pt-BR">
<head>




    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Pagamento</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">


  

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
            display: flex;
            flex-direction: column;
        }

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
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-bottom: 16px;
            text-align: center;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 16px;
            color: #FA0000;
        }

        .success-icon {
            font-size: 4rem;
            color: #FA0000;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .message {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 24px;
        }

        .upsell-title {
            font-size: 20px;
            font-weight: bold;
            color: #FA0000;
            margin-bottom: 16px;
        }

        .upsell-text {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .donation-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .donation-button {
            width: 100px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #FA0000;
            color: white;
            font-weight: 500;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
            text-align: center;
        }

        .donation-button:hover {
            background-color: #059669;
        }

        .cta-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #FA0000;
            color: white;
            font-weight: 500;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .cta-button:hover {
            background-color: #059669;
        }

        .story-section {
            text-align: left;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
        }

        .story-title {
            font-size: 18px;
            font-weight: bold;
            color: #FA0000;
            margin-bottom: 12px;
        }

        .story-text {
            margin-bottom: 16px;
        }

        .story-image {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-top: 177.77777777777777%; /* Proporção do vídeo */
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .video-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .video-container .backdrop {
            position: absolute;
            top: 0;
            height: 100%;
            width: 100%;
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
        }

        .footer {
            margin-top: 32px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            padding: 16px;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }
    </style>



    
</head>
<body>
    <!-- Barra superior -->
    <div class="top-bar">
        <div class="top-bar-content">
            <img src="../pagamento/eZ5DPjd.png" alt="Logo" class="logo">
            <div class="secure-badge">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12l2 2 4-4"></path>
                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Ambiente seguro</span>
            </div>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="container">
        <!-- Card de confirmação -->
        <div class="card">
            <i class="fa-solid fa-circle-check success-icon animate__animated animate__pulse"></i>
            <h1 class="title">Pagamento confirmado</h1>
           <br>
       </div>

    
    </div>

 
</body>
</html>