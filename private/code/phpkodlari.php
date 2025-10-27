<?php
// Diğer include'lar ve PHP kodu buraya gelecek
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/config.php');


?>
<?php 


// Kullanıcı giriş yapmamışsa ../siteharitasi.php sayfasına yönlendirme
if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: $index");
    exit();
}

// Veritabanı bağlantısı
try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php'); // MySQL bağlantısını sağlayan dosya
    $vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kullanıcının rollerini kontrol et
    $kullanici_adi = $_SESSION['kullanici_adi'];
    $stmt = $vt->prepare("SELECT rol_id FROM rollerplus WHERE kullanici_id = (SELECT id FROM kullanicilar WHERE kullanici_adi = :kullanici_adi)");
    $stmt->bindValue(':kullanici_adi', $kullanici_adi, PDO::PARAM_STR);
    $stmt->execute();
    $roller = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Admin rolü var mı kontrol et
    if (!in_array(3, $roller)) { // Admin rol ID'si 1 varsayılarak
        header('Location: ../siteharitasi.php');
        exit();
    }
} catch (PDOException $e) {
    echo "Veritabanı işlemi sırasında hata: " . $e->getMessage();
    exit();
}
?>
<html lang="tr">
<head>

    <title>Ana Sayfa</title>

</head>
<body class="bg-light">

  <div class="container py-4">

    <!-- 🧩 Kullanıcı Menüsü -->
    <div class="d-flex justify-content-end mb-4">
      <?php if (isset($kullanici_adi)): ?>
        <div class="dropdown">
          <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-user me-1"></i> <?= htmlspecialchars($kullanici_adi); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-id-card me-1"></i> Profilimi Gör</a></li>
            <li><a class="dropdown-item" href="auth/cikis_islemleri/logout.php"><i class="fas fa-sign-out-alt me-1"></i> Çıkış Yap</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="auth/giris_islemleri/giris.php" class="btn btn-primary me-2"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>
        <a href="auth/kayit_islemleri/kayit.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Kayıt Ol</a>
      <?php endif; ?>
    </div>

    <!-- 🎉 Karşılama -->
    <div class="mb-4">
      <h5 class="text-success"><i class="fas fa-star me-2"></i> Hoş Geldiniz</h5>
      <p class="text-muted">buraksariguzeldev.wuaze.com sitesine giriş yapabilir veya kayıt olabilirsiniz.</p>
    </div>

    <!-- 🎯 Ana Sayfa İçeriği -->
    <div class="mb-4">
      <h5><i class="fas fa-home me-2"></i> Ana Sayfa İçeriği</h5>
      <p>Sitemize giriş yapmasanız bile içeriklerimize erişebilirsiniz.</p>
      <a href="anasayfa.php" class="btn btn-outline-secondary"><i class="fas fa-eye"></i> İçeriklerimizi Görün</a>
    </div>

    <!-- 🔐 Özel İçerik -->
    <?php if (isset($kullanici_adi)): ?>
      <div class="mb-4">
        <h5><i class="fas fa-lock me-2"></i> Özel İçerik</h5>
        <?php if (in_array(1, $roller)): ?>
          <p class="text-success">Bu içerik yalnızca admin veya yönetici tarafından görülebilir.</p>
        <?php else: ?>
          <p class="text-danger">Bu içeriği görüntüleme izniniz bulunmuyor.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- 🔔 Giriş Bilgisi -->
    <div class="mb-4">
      <?php if (isset($kullanici_adi)): ?>
        <h5><i class="fas fa-info-circle me-2"></i> Giriş Bilgisi</h5>
        <p class="text-muted">Sisteme giriş yaptınız. Bu bölüm size özel bilgiler içerir.</p>
      <?php else: ?>
        <h5><i class="fas fa-exclamation-triangle me-2"></i> Giriş Gerekli</h5>
        <p class="text-muted">Bu içerik yalnızca giriş yapmış kullanıcılar için görünür.</p>
      <?php endif; ?>
    </div>

    <!-- 📊 Disk İstatistikleri -->
    <div class="mb-4">
      <h5><i class="fas fa-chart-pie me-2"></i> Disk İstatistikleri</h5>
      <ul class="list-unstyled">
        <li><a href="phpkodlari.php?page=htmlkodlari" class="bsd-navlink1"><i class="fab fa-html5 me-1"></i> HTML Kodları</a></li>
      </ul>

      <div class="mt-3">
        <?php
          if (isset($_GET['page']) && in_array($_GET['page'], ['htmlkodlari'])) {
            include $_GET['page'] . '.php';
          } else {
            echo "<p class='text-muted'>İçerik görmek için yukarıdaki bağlantılardan birine tıklayın.</p>";
          }
        ?>
      </div>
    </div>

    <!-- 💌 Özel Linkler -->
    <div class="mb-4">
      <?php if ($kullanici_adi === "buraksariguzeldev"): ?>
        <a href="../../mail/eposta_gonderme/eposta_gonderme_ms.php" class="btn btn-outline-info">
          <i class="fas fa-envelope"></i> E-posta Gönder
        </a>
      <?php endif; ?>

      <?php if (isset($rol_id) && ($rol_id == 1 || $rol_id == 3)): ?>
        <a href="adminpanel/adminpanel.php" class="btn btn-outline-warning ms-2">
          <i class="fas fa-user-shield"></i> Admin Panel
        </a>
      <?php endif; ?>

      <?php if (isset($cinsiyet) && $cinsiyet == 'erkek'): ?>
        <a href="erkekpanel/erkekpanel.php" class="btn btn-outline-primary mt-2">
          <i class="fas fa-male"></i> Erkek Panel
        </a>
      <?php elseif (isset($cinsiyet) && $cinsiyet == 'kadin'): ?>
        <a href="kadinpanel/kadinpanel.php" class="btn btn-outline-pink mt-2">
          <i class="fas fa-female"></i> Kadın Panel
        </a>
      <?php endif; ?>
    </div>

  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>