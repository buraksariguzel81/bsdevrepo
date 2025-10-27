<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Gerekli - 402</title>
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
            color: #f39c12;
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
            color: #f39c12;
            margin-bottom: 20px;
            animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both infinite;
            transform: translate3d(0, 0, 0);
            backface-visibility: hidden;
            perspective: 1000px;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
        .error-type {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #2980b9;
            text-decoration: none;
        }
        .button + .button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <?php
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    ?>
    <div class="container">
        <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
        <h1>402</h1>
        <div class="error-type">Ödeme Gerekli</div>
        <p>Bu içeriğe erişmek için ödeme yapmanız gerekmektedir. Ücretli bir hizmet veya abonelik gerektiren bir kaynağa erişmeye çalışıyor olabilirsiniz.</p>
        <p>Eğer zaten ödeme yaptıysanız veya bir aboneliğiniz varsa, lütfen hesabınızı kontrol edin veya müşteri hizmetleriyle iletişime geçin.</p>
        <a href="../index.php" class="button"><i class="fas fa-home"></i> Ana Sayfa'ya Dön</a>
        <?php if ($referer): ?>
        <a href="<?php echo htmlspecialchars($referer); ?>" class="button"><i class="fas fa-arrow-left"></i> Geri Dön</a>
        <?php endif; ?>
    </div>
    <script>
        // Animasyon için ek JavaScript kodu
        document.querySelector('.icon').addEventListener('mouseover', function() {
            this.style.animation = 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both infinite';
        });
        document.querySelector('.icon').addEventListener('mouseout', function() {
            this.style.animation = 'shake 0.82s cubic-bezier(.36,.07,.19,.97) both infinite';
        });
    </script>
</body>
</html>
