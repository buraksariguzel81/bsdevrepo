<?php






 
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sayfa Bulunamadı - 404</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

        h1 {
            font-size: 72px;
            margin: 0 0 20px;
            color: #e74c3c;
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
            color: #e74c3c;
            margin-bottom: 20px;
            animation: rotate 3s linear infinite;
        }
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

    <?php
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    ?>
<body class="bg-light">

  <div class="container py-5 text-center">

    <!-- 🔄 Simge ve Başlık -->
    <div class="mb-4">
      <i class="fas fa-question-circle fa-5x text-danger" style="animation: rotate 3s linear infinite;"></i>
    </div>

    <h1 class="display-4 text-danger">404</h1>
    <p class="fs-5 text-muted mb-3">Sayfa Bulunamadı</p>
    <p class="text-secondary mb-4">
      Üzgünüz, aradığınız sayfa bulunamadı. Sayfa kaldırılmış, adı değiştirilmiş veya geçici olarak kullanılamıyor olabilir.<br>
      Eğer bu bir hata olduğunu düşünüyorsanız, lütfen site yöneticisiyle iletişime geçin.
    </p>

    <!-- 🔗 Butonlar -->
    <a href="../index.php" class="btn btn-primary me-2">
      <i class="fas fa-home me-1"></i> Ana Sayfa'ya Dön
    </a>

    <?php if ($referer): ?>
      <a href="<?= htmlspecialchars($referer) ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Geri Dön
      </a>
    <?php endif; ?>

  </div>

  <!-- ✅ Animasyon Stili -->
  <style>
    @keyframes rotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .fa-question-circle:hover {
      animation: rotate 1s linear infinite;
    }
  </style>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>

</html>
