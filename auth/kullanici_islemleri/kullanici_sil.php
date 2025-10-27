<?php
session_start();

// Veritabanı bağlantısı
try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php'); // MySQL bağlantısını sağlayan dosya
} catch (Exception $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}

// Silme işlemi
if (isset($_GET['id'])) {
    $kullanici_id = intval($_GET['id']);

    // Silme işlemini bir transaction içinde gerçekleştir
    $vt->beginTransaction();

    try {
        // Tüm tabloları bul
        $tablolar = $vt->query("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()");

        while ($tablo = $tablolar->fetch(PDO::FETCH_ASSOC)) {
            $tablo_adi = $tablo['table_name'];

            // Kullanıcı ID'si ile ilişkili olan sütunları bul
            $sutunlar = $vt->query("DESCRIBE " . $tablo_adi);
            $kullanici_id_sutunu = null;
            while ($sutun = $sutunlar->fetch(PDO::FETCH_ASSOC)) {
                if (strpos(strtolower($sutun['Field']), 'kullanici_id') !== false) {
                    $kullanici_id_sutunu = $sutun['Field'];
                    break;
                }
            }

            // Eğer kullanıcı ID'sine ait bir sütun bulunursa, kullanıcıya ait veriyi sil
            if ($kullanici_id_sutunu !== null) {
                $stmt = $vt->prepare("DELETE FROM " . $tablo_adi . " WHERE " . $kullanici_id_sutunu . " = :kullanici_id");
                $stmt->bindValue(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        // Kullanıcıyı sil
        $stmt = $vt->prepare("DELETE FROM kullanicilar WHERE id = :kullanici_id");
        $stmt->bindValue(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
        $stmt->execute();

        // Transaction'ı onayla
        $vt->commit();

        $_SESSION['mesaj'] = "Kullanıcı ve ilgili tüm veriler başarıyla silindi.";
    } catch (Exception $e) {
        // Hata durumunda transaction'ı geri al
        $vt->rollBack();
        $_SESSION['hata'] = "Kullanıcı silinirken bir hata oluştu: " . $e->getMessage();
    }
} else {
    $_SESSION['hata'] = "Geçersiz kullanıcı ID'si.";
}

// Kullanıcı listesi sayfasına yönlendir
header("Location: kullanıcı_listesi.php");
exit();
?>