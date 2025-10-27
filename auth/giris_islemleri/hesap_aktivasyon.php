<?php
date_default_timezone_set('Europe/Istanbul');

    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
    // MySQL bağlantısını sağlayan dosya
    
// Eğer pasif kullanıcı bilgisi yoksa giriş sayfasına yönlendir
if (!isset($_SESSION['pasif_kullanici'])) {
    header("Location: giris.php");
    exit();
}

$pasif = $_SESSION['pasif_kullanici'];

// Veritabanı bağlantısını kur
try {
    $vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Veritabanına bağlanılamadı: " . $e->getMessage());
}

// Veritabanındaki hesap durumunu güncellemek
$query = $vt->prepare("SELECT hesap_durumu FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
$query->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
$query->execute();
$userStatus = $query->fetch(PDO::FETCH_ASSOC);

// Hesap durumunu güncelle
if ($userStatus) {
    // Veritabanındaki durumu oturuma kaydet
    $_SESSION['pasif_kullanici']['hesap_durumu'] = $userStatus['hesap_durumu'];
}

// Hesap aktivasyonu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["aktivasyon"])) {
        $vt->beginTransaction();
        try {
            // Kullanıcıyı aktif hale getir
            $updateStmt = $vt->prepare("UPDATE kullanicilar SET hesap_durumu = :hesap_durumu WHERE kullanici_adi = :kullanici_adi");
            $updateStmt->bindValue(':hesap_durumu', 'aktif', PDO::PARAM_STR);
            $updateStmt->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
            $updateStmt->execute();

            // Dondurulmuş hesap kaydı varsa sil
            $deleteStmt = $vt->prepare("DELETE FROM dondurulmus_hesaplar WHERE kullanici_adi = :kullanici_adi");
            $deleteStmt->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
            $deleteStmt->execute();

            // Silinmekte olan hesap kaydı varsa sil
            $deleteStmt = $vt->prepare("DELETE FROM silinecek_hesaplar WHERE kullanici_adi = :kullanici_adi");
            $deleteStmt->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
            $deleteStmt->execute();

            $vt->commit();

            // Oturum bilgilerini güncelle
            $_SESSION["kullanici_adi"] = $pasif["kullanici_adi"];
            $_SESSION['success_message'] = 'Hesabınız başarıyla aktifleştirildi ve giriş yaptınız.';

            // Oturum değişkenini temizle
            unset($_SESSION['pasif_kullanici']);

            header("Location: ../../../index.php");
            exit;
        } catch (Exception $e) {
            $vt->rollBack();
            die("Hesap aktivasyon işlemi sırasında bir hata oluştu: " . $e->getMessage());
        }
    }

    if (isset($_POST["iptal_et"])) {
        // Silme veya dondurma durumunu iptal et
        $vt->beginTransaction();
        try {
            $updateStmt = $vt->prepare("UPDATE kullanicilar SET hesap_durumu = 'aktif' WHERE kullanici_adi = :kullanici_adi");
            $updateStmt->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
            $updateStmt->execute();

            // Dondurulmuş veya silinmekte olan hesap kaydını sil
            $deleteStmt = $vt->prepare("DELETE FROM dondurulmus_hesaplar WHERE kullanici_adi = :kullanici_adi");
            $deleteStmt->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
            $deleteStmt->execute();

            $deleteStmt = $vt->prepare("DELETE FROM silinecek_hesaplar WHERE kullanici_adi = :kullanici_adi");
            $deleteStmt->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
            $deleteStmt->execute();

            $vt->commit();

            // Oturum bilgilerini güncelle ve giriş işlemi yap
            $_SESSION["kullanici_adi"] = $pasif["kullanici_adi"];
            $_SESSION['success_message'] = 'Hesap durumu başarıyla iptal edildi. Giriş yapıldı.';
            unset($_SESSION['pasif_kullanici']);

            header("Location: ../../../index.php");
            exit;
        } catch (Exception $e) {
            $vt->rollBack();
            die("Hesap durumu iptali sırasında bir hata oluştu: " . $e->getMessage());
        }
    }
}

// Hesap dondurulmuşsa veya silinmekteyse, tarihleri ve kalan süreyi hesapla
$remainingTime = '';
if ($pasif['hesap_durumu'] == 'dondurulmus') {
    $query = $vt->prepare("SELECT dondurulma_tarihi FROM dondurulmus_hesaplar WHERE kullanici_adi = :kullanici_adi");
    $query->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
    $query->execute();
    $dondurulmaTarihi = $query->fetchColumn();
    
    if ($dondurulmaTarihi) {
        $remainingTime = strtotime($dondurulmaTarihi) - time();
        $remainingTime = gmdate("H:i:s", $remainingTime); // Kalan süreyi saat:dakika:saniye formatında göster
    }
} elseif ($pasif['hesap_durumu'] == 'silinecek') {
    $query = $vt->prepare("SELECT silinme_tarihi FROM silinecek_hesaplar WHERE kullanici_adi = :kullanici_adi");
    $query->bindValue(':kullanici_adi', $pasif["kullanici_adi"], PDO::PARAM_STR);
    $query->execute();
    $silinmeTarihi = $query->fetchColumn();
    
    if ($silinmeTarihi) {
        $remainingTime = strtotime($silinmeTarihi) - time();
        $remainingTime = gmdate("H:i:s", $remainingTime); // Kalan süreyi saat:dakika:saniye formatında göster
    }
}
   // Normal sayfa görüntüleme

    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Hesap Durumu</title>

</head>
<body>
<h5>Hesap Durumu</h5>
<p><b>Kullanıcı Adı:</b> <?= htmlspecialchars($pasif['kullanici_adi']); ?></p>

<?php if ($pasif['hesap_durumu'] == 'dondurulmus'): ?>
    <div>
        <p>Hesabınız dondurulmuş durumdadır. Kalan süre: <?= $remainingTime ? $remainingTime : 'Bilinmiyor'; ?></p>
        <form method="post">
            <button type="submit" name="aktivasyon">Hesabımı Aktifleştir</button>
        </form>
    </div>
<?php elseif ($pasif['hesap_durumu'] == 'silinecek'): ?>
    <div>
        <p>Hesabınız silinme sürecindedir. Kalan süre: <?= $remainingTime ? $remainingTime : 'Bilinmiyor'; ?></p>
        <form method="post">
            <button type="submit" name="iptal_et">Silme Durumunu İptal Et</button>
        </form>
    </div>
<?php else: ?>
    <div>
        <p>Hesabınız aktif durumda.</p>
    </div>
<?php endif; ?>
<script>
// Sayfa yüklendikten hemen sonra sayfayı yenile
window.onload = function() {
    if (!sessionStorage.getItem('reloaded')) {
        // Sayfa ilk kez yüklendiğinde çalışacak
        sessionStorage.setItem('reloaded', 'true');
        location.reload();
    } else {
        // Sayfa ikinci kez yüklendiğinde 'reloaded' değeri sıfırlanır
        sessionStorage.removeItem('reloaded');
    }
};
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>