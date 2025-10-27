<?php
// Oturum başlatma
session_start();
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php-error.log');
// Gelen sayfayı takip et
if (!isset($_SESSION['return_to'])) {
    $_SESSION['return_to'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../index.php';
}

// Kullanıcı zaten giriş yapmışsa ana sayfaya yönlendir

// Veritabanı bağlantısını dahil et
try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
    // MySQL bağlantısını sağlayan dosya
} catch (Exception $e) {
    die("Veritabanına bağlanılamadı: " . $e->getMessage());
}

// Giriş formu gönderildiğinde işlem yapma
$hata = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["giris"])) {
    // Formdan gelen verileri al ve temizle
    $kullanici = strtolower(trim($_POST["kullanici"]));
    $sifre = $_POST["sifre"];

    try {
        // Kullanıcıyı kontrol et
        $stmt = $vt->prepare("SELECT id, kullanici_adi, sifre FROM kullanicilar WHERE kullanici_adi = :kullanici");
        $stmt->bindValue(':kullanici', $kullanici, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            // Şifre doğrulama
         // Şifre doğrulama if bloğunun içinde, başarılı giriş kısmında değişiklik yapacağız
if (password_verify($sifre, $userData["sifre"])) {
    // Hesap durumunu kontrol et
    $hesapDurumuStmt = $vt->prepare("SELECT hesap_durumu FROM kullanicilar WHERE id = :id");
    $hesapDurumuStmt->bindValue(':id', $userData["id"], PDO::PARAM_INT);
    $hesapDurumuStmt->execute();
    $hesapDurumu = $hesapDurumuStmt->fetch(PDO::FETCH_ASSOC);

    if ($hesapDurumu['hesap_durumu'] === 'banli') {
        // Ban bilgilerini al
        $banStmt = $vt->prepare("SELECT ban_baslangic, ban_sure, ban_nedeni FROM bans WHERE id = :id ORDER BY ban_baslangic DESC LIMIT 1");
        $banStmt->bindValue(':id', $userData["id"], PDO::PARAM_INT);
        $banStmt->execute();
        $banBilgisi = $banStmt->fetch(PDO::FETCH_ASSOC);

        // Ban bilgilerini oturumda sakla
        $_SESSION['ban_bilgisi'] = $banBilgisi;
        header("Location: banli_kullanici.php");
        exit();
    } 
    else if ($hesapDurumu['hesap_durumu'] === 'dondurulmus') {
        // Pasif kullanıcı bilgilerini oturuma kaydet
        $_SESSION['pasif_kullanici'] = $userData;
        header("Location: hesap_aktivasyon.php");
        exit();
    }
    else if ($hesapDurumu['hesap_durumu'] === 'silinecek') {
        // Silinmeye aday kullanıcı bilgilerini oturuma kaydet
        $_SESSION['pasif_kullanici'] = $userData;
        header("Location: hesap_aktivasyon.php");
        exit();
    }
    else {
        // Normal giriş işlemleri
        $_SESSION["kullanici_adi"] = $userData["kullanici_adi"];
        $_SESSION["user_id"] = $userData["id"];

        // Çevrimiçi durumu güncelle
        $updateStmt = $vt->prepare("UPDATE kullanicilar SET cevrimici = 1 WHERE id = :id");
        $updateStmt->bindValue(':id', $userData["id"], PDO::PARAM_INT);
        $updateStmt->execute();

        // "Beni hatırla" seçeneği işleme
        if (isset($_POST["benihatirla"])) {
            setcookie("kullanici_adi", $userData["kullanici_adi"], time() + 30 * 24 * 3600, "/", "", true, true);
        }

        // Giriş sonrası yönlendirme
        $return_url = $_SESSION['return_to'];
        unset($_SESSION['return_to']); 
        header("Location: " . $return_url);
        exit();
    }
} else {
    $hata = "Hatalı şifre.";
}


        } else {
            $hata = "Kullanıcı bulunamadı.";
        }
    } catch (Exception $e) {
        $hata = "Bir hata oluştu: " . $e->getMessage();
    }
}

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../../bsd_yonetim/src/css/main.css">
</head>
<body class="bg-light">

  <div class="container py-5">

    <div class="row justify-content-center">
      <div class="col-md-6">

        <div class="card shadow-sm rounded">
          <div class="card-body">

            <h5 class="mb-4 text-primary">
              <i class="fas fa-sign-in-alt me-2"></i> Giriş Yap
            </h5>

            <!-- PROJE İPTAL UYARISI -->
            <div class="alert alert-danger mb-4">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>⚠️ PROJE İPTAL EDİLMİŞTİR ⚠️</strong><br>
              Bu proje iptal edilmiştir. Giriş yapmak yasaktır.
            </div>

            <!-- Hata Mesajı -->
            <?php if (!empty($hata)): ?>
              <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($hata) ?>
              </div>
            <?php endif; ?>

            <!-- Giriş Formu (Devre Dışı) -->
            <form method="post">
              <div class="mb-3">
                <label for="kullanici" class="form-label">
                  <i class="fas fa-user me-2"></i> Kullanıcı Adı
                </label>
                <input type="text" class="form-control" id="kullanici" name="kullanici" required maxlength="50" disabled>
              </div>

              <div class="mb-3">
                <label for="sifre" class="form-label">
                  <i class="fas fa-lock me-2"></i> Şifre
                </label>
                <input type="password" class="form-control" id="sifre" name="sifre" required minlength="6" disabled>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="benihatirla" name="benihatirla" disabled>
                <label class="form-check-label" for="benihatirla">
                  <i class="fas fa-memory me-2"></i> Beni Hatırla
                </label>
              </div>

              <button type="submit" name="giris" class="btn btn-secondary w-100" disabled>
                <i class="fas fa-ban me-1"></i> Giriş Yasak
              </button>
            </form>

            <hr>

            <div class="text-center">
              <p class="mb-1">
                <i class="fas fa-user-plus me-1"></i> Henüz üye değil misiniz?
                <a href="../kayit_islemleri/kayit.php" class="text-decoration-none text-primary">Kayıt Olun</a>
              </p>
              <p>
                <i class="fas fa-key me-1"></i> Şifrenizi mi unuttunuz?
                <a href="../../mail/Sifre_Sifirla/sifre_sifirla.php" class="text-decoration-none text-danger">Şifre Sıfırla</a>
              </p>
            </div>

          </div>
        </div>

      </div>
    </div>

  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>

</html>
