<?php
session_start();

// Veritabanı bağlantısı
try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
    // MySQL bağlantısını sağlayan dosya
    if (!$vt) {
        throw new Exception("Veritabanı bağlantısı kurulamadı.");
    }
} catch (Exception $e) {
    die("Veritabanına bağlanılamadı: " . $e->getMessage());
}

// Kullanıcı oturumu varsa çevrim içi durumunu güncelle
if (isset($_SESSION["kullanici_adi"])) {
    $kullanici = strtolower($_SESSION["kullanici_adi"]);
    
    // 'kullanicilar' tablosunun varlığını kontrol et
    $checkTableStmt = $vt->query("SHOW TABLES LIKE 'kullanicilar'");
    if ($checkTableStmt->rowCount() == 0) {
        die("'kullanicilar' tablosu bulunamadı.");
    }
    
    // 'cevrimici' sütununun varlığını kontrol et
    $checkColumnStmt = $vt->query("DESCRIBE kullanicilar");
    $cevrimiciColumnExists = false;
    while ($row = $checkColumnStmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['Field'] == 'cevrimici') {
            $cevrimiciColumnExists = true;
            break;
        }
    }
    if (!$cevrimiciColumnExists) {
        die("'cevrimici' sütunu bulunamadı.");
    }
    
    // Kullanıcının çevrim içi durumunu kapat
    $updateStmt = $vt->prepare("UPDATE kullanicilar SET cevrimici = 0 WHERE kullanici_adi = :kullanici");
    $updateStmt->bindValue(':kullanici', $kullanici, PDO::PARAM_STR);
    $result = $updateStmt->execute();
    if (!$result) {
        die("SQL yürütme hatası: " . $vt->errorInfo()[2]);
    }
    
    // Oturumu yok et
    session_destroy();
    
    // Başarı mesajını oturuma ekle
    session_start();
    $_SESSION['success_message'] = 'Başarıyla çıkış yaptınız. Yeniden bekleriz!';
}

// Veritabanı bağlantısını kapat
$vt = null;

// Ana sayfaya yönlendir
header("Location: ../../index.php");
exit();
?>