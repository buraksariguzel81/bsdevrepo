<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İzin Verilmeyen Metod - 405</title>
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
            color: #3498db;
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
            color: #3498db;
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
        .contact-button {
            display: inline-block;
            background-color: #2ecc71;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            margin-left: 10px;
            transition: background-color 0.3s ease;
        }
        .contact-button:hover {
            background-color: #27ae60;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon"><i class="fas fa-ban"></i></div>
        <h1>405</h1>
        <div class="error-type">İzin Verilmeyen Metod</div>
        <p>Üzgünüz, bu kaynağa erişmek için kullandığınız HTTP metoduna izin verilmiyor. Sunucu, isteğinizi tanıyor ancak kabul etmiyor.</p>
        <p>Bu genellikle bir API'nin belirli bir endpoint'i için desteklenmeyen bir HTTP metodu kullanıldığında ortaya çıkar. Lütfen doğru HTTP metodunu kullandığınızdan emin olun.</p>
        <a href="../index.php" class="home-button"><i class="fas fa-home"></i> Ana Sayfa'ya Dön</a>
        <a href="../contact.php" class="contact-button"><i class="fas fa-envelope"></i> Bize Ulaşın</a>
    </div>
</body>
</html>