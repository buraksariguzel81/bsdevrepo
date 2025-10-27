<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');


?>
<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Yetkisiz Erişim - 401</title>

</head>

    <?php
    // Kullanıcının geldiği sayfayı al
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Bilinmeyen Sayfa';

    // Kullanıcı bilgilerini al (eğer oturum açıksa)

    $kullanici_adi = isset($_SESSION['kullanici_adi']) ? $_SESSION['kullanici_adi'] : 'Misafir';

    // Özel mesaj oluştur
  
    ?>
<body class="bg-light">

  <div class="container py-5 text-center">

    <!-- 🔐 Simge -->
    <div class="mb-4">
      <i class="fas fa-user-lock fa-5x text-warning"></i>
    </div>

    <!-- 🔔 Başlıklar -->
    <h1 class="display-4 text-danger">401</h1>
    <p class="fs-5 text-muted">Yetkisiz Erişim</p>
    <p class="mb-4 text-secondary">
      Bu sayfaya erişim yetkiniz bulunmuyor. Giriş yapmanız veya uygun izinlere sahip olmanız gerekiyor.<br>
      Eğer bu bir hata olduğunu düşünüyorsanız, lütfen site yöneticisiyle iletişime geçin.
    </p>

    <!-- 📎 Navigasyon -->
    <div class="d-flex justify-content-center gap-2 flex-wrap mb-4">
      <a href="../index.php" class="btn btn-primary">
        <i class="fas fa-home me-1"></i> Ana Sayfa'ya Dön
      </a>
      <a href="javascript:history.back()" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Geri Dön
      </a>
    </div>

    <!-- 👤 Kullanıcı Bilgisi -->
    <div class="small text-muted">
      <i class="fas fa-user me-1"></i> Kullanıcı: <strong><?= htmlspecialchars($kullanici_adi) ?></strong>
    </div>

  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>

</html>
