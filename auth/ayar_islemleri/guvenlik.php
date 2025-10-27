<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');

// Kimliği doğrulanmamış kullanıcıları kontrol et
if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: ../../../index.php");
    exit;
}

// Veritabanı bağlantısı
try {

} catch (Exception $e) {
    die("Veritabanı bağlantısı kurulamadı: " . htmlspecialchars($e->getMessage()));
}

// 'son_sifre_degisimi' sütununu kontrol et ve yoksa ekle
$checkColumnQuery = "SELECT COUNT(*) AS count FROM INFORMATION_SCHEMA.COLUMNS 
                     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'kullanicilar' AND COLUMN_NAME = 'son_sifre_degisimi'";
$result = $vt->query($checkColumnQuery);
$row = $result->fetch(PDO::FETCH_ASSOC);

if ($row['count'] == 0) {
    $addColumnQuery = "ALTER TABLE kullanicilar ADD COLUMN son_sifre_degisimi DATETIME";
    $vt->exec($addColumnQuery);
}



$kullanici_adi = $_SESSION['kullanici_adi'];
$mesaj = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eski_sifre = $_POST['eski_sifre'] ?? '';
    $yeni_sifre = $_POST['sifre'] ?? '';

    // Eski şifreyi doğrula
    $stmt = $vt->prepare("SELECT sifre FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
    $stmt->bindValue(':kullanici_adi', $kullanici_adi, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row && password_verify($eski_sifre, $row['sifre'])) {
        if (isset($_POST['sifre']) && !empty($_POST['sifre'])) {
            // Yeni şifreyi güncelle
            $yeni_sifre_hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
            $stmt = $vt->prepare("UPDATE kullanicilar SET sifre = :yeni_sifre, son_sifre_degisimi = datetime('now') WHERE kullanici_adi = :kullanici_adi");
            $stmt->bindValue(':yeni_sifre', $yeni_sifre_hash, SQLITE3_TEXT);
            $stmt->bindValue(':kullanici_adi', $kullanici_adi, SQLITE3_TEXT);
            
            if ($stmt->execute()) {
                $mesaj = "Şifre başarıyla güncellendi.";
            } else {
                $mesaj = "Şifre güncellenirken bir hata oluştu.";
            }
        } else {
            $mesaj = "Yeni şifre boş olamaz.";
        }
    } else {
        $mesaj = "Eski şifre yanlış.";
    }

    // JSON olarak yanıt döndür
    header('Content-Type: application/json');
    echo json_encode(['mesaj' => $mesaj]);
    exit;
}

$stmt = $vt->prepare("SELECT son_sifre_degisimi FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
$stmt->bindValue(':kullanici_adi', $kullanici_adi, PDO::PARAM_STR);

if ($stmt->execute()) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $son_sifre_degisimi = $row['son_sifre_degisimi'] ?? null;
} else {
    die("Sorgu hatası: " . htmlspecialchars($stmt->errorInfo()[2]));
}
// Tarihi formatla
if ($son_sifre_degisimi !== null) {
    $utc_date = new DateTime($son_sifre_degisimi, new DateTimeZone('UTC'));
    $utc_date->setTimezone(new DateTimeZone('Europe/Istanbul'));
    $son_sifre_degisimi = $utc_date->format('d.m.Y H:i:s T');
} else {
    $son_sifre_degisimi = 'Bilinmiyor';
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Güvenlik Ayarları</title>

</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <div class="card shadow-sm rounded">
          <div class="card-body">

            <h5 class="card-title mb-4 text-danger">
              <i class="fas fa-shield-alt me-2"></i> Güvenlik Ayarları
            </h5>

            <!-- Bilgilendirme ve kullanıcı -->
            <div class="mb-3">
              <p><i class="fas fa-user me-2"></i> Mevcut Kullanıcı:
                <strong><?= htmlspecialchars($kullanici_adi) ?></strong>
              </p>
              <p><i class="fas fa-clock me-2"></i> Son Şifre Değişimi:
                <span class="text-muted"><?= htmlspecialchars($son_sifre_degisimi) ?></span>
              </p>
            </div>

            <!-- Geri bildirim mesajı -->
            <div id="mesaj" class="alert alert-info" style="display: none;"></div>

            <!-- Şifre Güncelleme Formu -->
            <form method="post" id="sifreForm">

              <!-- Eski Şifre -->
              <div class="mb-3">
                <label for="eski_sifre" class="form-label">
                  <i class="fas fa-key me-2"></i> Eski Şifre
                </label>
                <input type="password" class="form-control" id="eski_sifre" name="eski_sifre" required>
              </div>

              <!-- Yeni Şifre -->
              <div class="mb-3">
                <label for="sifre_reg" class="form-label">
                  <i class="fas fa-lock me-2"></i> Yeni Şifre
                </label>
                <div class="input-group">
                  <input type="password" class="form-control" id="sifre_reg" name="sifre" required minlength="6">
                  <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="fas fa-eye" id="eye-icon"></i>
                  </button>
                </div>
                <small id="sifre_durumu" class="form-text text-muted"></small>
              </div>

              <!-- Gönder Butonu -->
              <button type="submit" class="btn btn-success w-100">
                <i class="fas fa-save me-1"></i> Şifreyi Güncelle
              </button>

              <hr>

              <!-- Ek Bağlantılar -->
              <div class="text-center">
                <a href="../../mail/sifre_sifirla.php" class="text-decoration-none text-danger">
                  <i class="fas fa-question-circle me-1"></i> Şifremi Unuttum
                </a>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>

</html>
