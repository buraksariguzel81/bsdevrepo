

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hatalı İstek - 400</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color: white;
            text-align: center;
            padding: 50px;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            background-color: #323;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        h1 {
            font-size: 72px;
            margin: 0 0 20px;
            color: #9b59b6;
        }
        p {
            font-size: 18px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        .icon {
            font-size: 100px;
            color: #9b59b6;
            margin-bottom: 20px;
        }
        .error-type {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }
        .home-button {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .home-button:hover {
            background-color: #2980b9;
            text-decoration: none;
        }
        .refresh-button {
            display: inline-block;
            background-color: #2ecc71;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            margin-left: 10px;
            transition: background-color 0.3s ease;
        }
        .refresh-button:hover {
            background-color: #27ae60;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        <h1>400</h1>
        <div class="error-type">Hatalı İstek</div>
        
        
        <p>Üzgünüz, sunucu isteğinizi anlayamadı veya işleyemedi. Bu, hatalı bir URL, eksik veya bozuk parametreler veya geçersiz form verileri nedeniyle olabilir.</p>
        <p>Lütfen isteğinizi kontrol edin ve tekrar deneyin. Sorun devam ederse, site yöneticisiyle iletişime geçin.</p>
        <a href="../index.php" class="home-button"><i class="fas fa-home"></i> Ana Sayfa'ya Dön</a>
        <a href="javascript:location.reload();" class="refresh-button"><i class="fas fa-sync-alt"></i> Sayfayı Yenile</a>
    </div>
   
   

</body>
</html>