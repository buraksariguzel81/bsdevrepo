<?php
session_start();
date_default_timezone_set('Europe/Istanbul');

try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
} catch (PDOException $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}

if (!isset($_SESSION['kullanici_adi'])) {
    header("Location: ../../../index.php");
    exit;
}

$kullanici_adi = $_SESSION['kullanici_adi'];
$mesaj = '';

$stmt = $vt->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
$stmt->bindParam(':kullanici_adi', $kullanici_adi);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Kullanıcı bulunamadı.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['hesap_dondur']) || isset($_POST['hesap_sil']))) {
    $vt->beginTransaction();

    if (isset($_POST['hesap_dondur'])) {
        $dondurulma_tarihi = date('Y-m-d H:i:s');

        $update_stmt = $vt->prepare("UPDATE kullanicilar SET hesap_durumu = 'dondurulmus', cevrimici = 0 WHERE kullanici_adi = :kullanici_adi");
        $update_stmt->bindParam(':kullanici_adi', $kullanici_adi);
        $update_stmt->execute();

        $insert_stmt = $vt->prepare("INSERT INTO dondurulmus_hesaplar (kullanici_adi, kullanici_id, dondurulma_tarihi) VALUES (:kullanici_adi, :kullanici_id, :dondurulma_tarihi)");
        $insert_stmt->bindParam(':kullanici_adi', $kullanici_adi);
        $insert_stmt->bindParam(':kullanici_id', $user['id']);
        $insert_stmt->bindParam(':dondurulma_tarihi', $dondurulma_tarihi);
        $insert_stmt->execute();

        $mesaj = "Hesabınız donduruldu. Tekrar aktifleştirmek için yöneticiyle iletişime geçin.";
    } elseif (isset($_POST['hesap_sil'])) {
        $silinme_tarihi = date('Y-m-d H:i:s', strtotime('+30 days'));

        $update_stmt = $vt->prepare("UPDATE kullanicilar SET hesap_durumu = 'silinecek', cevrimici = 0 WHERE kullanici_adi = :kullanici_adi");
        $update_stmt->bindParam(':kullanici_adi', $kullanici_adi);
        $update_stmt->execute();

        $insert_stmt = $vt->prepare("INSERT INTO silinecek_hesaplar (kullanici_adi, kullanici_id, silinme_tarihi) VALUES (:kullanici_adi, :kullanici_id, :silinme_tarihi)");
        $insert_stmt->bindParam(':kullanici_adi', $kullanici_adi);
        $insert_stmt->bindParam(':kullanici_id', $user['id']);
        $insert_stmt->bindParam(':silinme_tarihi', $silinme_tarihi);
        $insert_stmt->execute();

        $mesaj = "Hesabınız 30 gün içinde silinecek şekilde işaretlendi. Silinme tarihi: " . $silinme_tarihi . ". Bu süre içinde tekrar giriş yaparsanız hesabınız aktif kalacaktır.";
    }

    $vt->commit();
    session_destroy();
    header("Location: ../../../index.php");
    exit;
}

  include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hesap Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
      body {
        background-color: #f4f1ea; /* hafif sıcak bir arka plan */
      }
      .card {
        border-radius: 15px;
        box-shadow: 0 8px 15px rgba(163, 46, 56, 0.3);
      }
      .card-header {
        background-color: #a32e38; /* Galatasaray kırmızısı */
        color: white;
        font-weight: 600;
        font-size: 1.25rem;
      }
      .btn-dondur {
        background-color: #d4af37; /* sarı altın ton */
        color: #a32e38;
        font-weight: 700;
      }
      .btn-dondur:hover {
        background-color: #b8992c;
        color: #800000;
      }
      .btn-sil {
        background-color: #a32e38;
        color: white;
        font-weight: 700;
      }
      .btn-sil:hover {
        background-color: #7a2127;
      }
      .info-label {
        font-weight: 600;
        color: #5a4945;
      }
      .info-icon {
        color: #a32e38;
        margin-right: 0.5rem;
      }
    </style>
</head>
<body>

<div class="container py-5">
  <div class="card mx-auto" style="max-width: 600px;">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-user-cog me-2"></i> Hesap Yönetimi
    </div>
    <div class="card-body">
      <?php if (!empty($mesaj)): ?>
      <div class="alert alert-info" role="alert">
        <?= htmlspecialchars($mesaj) ?>
      </div>
      <?php endif; ?>

      <div class="mb-4">
        <p><i class="fas fa-user info-icon"></i> <span class="info-label">Kullanıcı Adı:</span> <?= htmlspecialchars($user['kullanici_adi']) ?></p>
        <p><i class="fas fa-envelope info-icon"></i> <span class="info-label">E-posta:</span> <?= htmlspecialchars($user['eposta']) ?></p>
        <p><i class="fas fa-user-clock info-icon"></i> <span class="info-label">Hesap Durumu:</span> <?= htmlspecialchars($user['hesap_durumu']) ?></p>
        <p><i class="fas fa-venus-mars info-icon"></i> <span class="info-label">Cinsiyet:</span> <?= htmlspecialchars($user['cinsiyet']) ?></p>
        <p><i class="fas fa-user-tag info-icon"></i> <span class="info-label">Rol:</span> <?= htmlspecialchars($user['rol'] ?? 'Bilinmiyor') ?></p>
        <p><i class="fas fa-calendar-plus info-icon"></i> <span class="info-label">Kayıt Tarihi:</span> <?= htmlspecialchars($user['kayit_tarihi']) ?></p>
        <p><i class="fas fa-globe info-icon"></i> <span class="info-label">Çevrimiçi Durumu:</span> <?= $user['cevrimici'] ? 'Çevrimiçi' : 'Çevrimdışı' ?></p>
        <p><i class="fas fa-clock info-icon"></i> <span class="info-label">Son Şifre Değişimi:</span> <?= htmlspecialchars($user['son_sifre_degisimi'] ?? 'Bilinmiyor') ?></p>
      </div>

      <form method="post" class="mb-3" onsubmit="return confirm('Hesabınızı dondurmak istediğinizden emin misiniz?');">
        <button type="submit" name="hesap_dondur" class="btn btn-dondur w-100"><i class="fas fa-snowflake me-2"></i> Hesabı Dondur</button>
      </form>

      <form method="post" onsubmit="return confirm('Hesabınızı silmek istediğinizden emin misiniz? Bu işlem 30 gün sonra tamamlanacaktır.');">
        <button type="submit" name="hesap_sil" class="btn btn-sil w-100"><i class="fas fa-user-times me-2"></i> Hesabı Sil</button>
      </form>
    </div>
  </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
