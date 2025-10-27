<?php


include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>


<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Erişim Yasak - 403</title>

</head>

    <?php
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    ?>
    <body class="bg-light">

  <div class="container py-5">
    <div class="text-center">
      <div class="mb-4">
        <i class="fas fa-ban fa-4x text-danger"></i>
      </div>

      <h1 class="display-4 text-danger">403</h1>
      <p class="lead">Erişim Yasak</p>
      <p class="text-muted">
        Üzgünüz, bu sayfaya erişim izniniz yok. Gerekli yetkilere sahip olmadığınız bir kaynağa erişmeye çalışıyor olabilirsiniz.<br>
        Eğer bu bir hata olduğunu düşünüyorsanız, lütfen site yöneticisiyle iletişime geçin.
      </p>

      <div class="mt-4">
        <a href="../index.php" class="btn btn-primary me-2">
          <i class="fas fa-home me-1"></i> Ana Sayfa
        </a>
        <?php if ($referer): ?>
        <a href="<?= htmlspecialchars($referer) ?>" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i> Geri Dön
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>

</html>
