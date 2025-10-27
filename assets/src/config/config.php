<?php

// Oturum kontrolü
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
} catch (Exception $e) {
    die("Veritabanı bağlantısı sağlanamadı: " . $e->getMessage());
}

// Kullanıcı bilgilerini al
$kullanici_adi = $_SESSION['kullanici_adi'] ?? null;
$cinsiyet = null;
$rol_idler = []; // Kullanıcıya ait tüm roller
$menu_items = [];



if ($kullanici_adi) {
    // Kullanıcı ID ve cinsiyet bilgisini al
    $stmt = $vt->prepare("SELECT id, cinsiyet FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
    $stmt->bindParam(':kullanici_adi', $kullanici_adi, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $kullanici_id = $row['id'];
            $cinsiyet = $row['cinsiyet'];

            // Kullanıcının rollerini al
            $stmt = $vt->prepare("SELECT rol_id FROM rollerplus WHERE kullanici_id = :kullanici_id");
            $stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                while ($rol_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $rol_idler[] = $rol_row['rol_id']; // Rolleri listeye ekle
                }
            } else {
                die("Rol sorgusu çalıştırılamadı: " . $stmt->errorInfo()[2]);
            }
        }
    } else {
        die("Kullanıcı bilgisi sorgusu başarısız: " . $stmt->errorInfo()[2]);
    }
}

// Rolleri kontrol ederek işlem yap
if (in_array(1, $rol_idler)) {
    echo "";
    // Buraya rol 1'e özel işlemleri ekleyebilirsiniz
}

if (in_array(3, $rol_idler)) {
    echo "";
    // Buraya rol 3'e özel işlemleri ekleyebilirsiniz
}

if (in_array(2, $rol_idler)) {
    echo "";
    // Buraya rol 2'ye özel işlemleri ekleyebilirsiniz
}

// Eğer kullanıcıda başka roller de varsa, onları da burada kontrol edebilirsiniz
if (empty($rol_idler)) {
    echo "";
}
?>

<?php
// Oturum kontrolü


try {
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
} catch (Exception $e) {
    die("Veritabanı bağlantısı sağlanamadı: " . $e->getMessage());
}

// Kullanıcı bilgilerini al
$kullanici_adi = $_SESSION['kullanici_adi'] ?? null;
$cinsiyet = null;
$rol_id = null;

if ($kullanici_adi) {
    // Önce kullanıcı ID ve cinsiyet bilgisini alalım
    $stmt = $vt->prepare("SELECT id, cinsiyet FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");

    // Prepare başarısız olma durumunu kontrol et
    if ($stmt === false) {
        die("SQL hazırlama hatası: " . $vt->errorInfo()[2]);
    }

    // Parametreyi bağla
    $stmt->bindParam(':kullanici_adi', $kullanici_adi, PDO::PARAM_STR);
    
    // Sorguyu çalıştır
    if (!$stmt->execute()) {
        die("Sorgu çalıştırma hatası: " . $stmt->errorInfo()[2]);
    }

    // Sonuçları al
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $kullanici_id = $row['id'];
        $cinsiyet = $row['cinsiyet'];

        // Şimdi kullanıcının rolünü alalım
        $stmt = $vt->prepare("SELECT rol_id FROM rollerplus WHERE kullanici_id = :kullanici_id");

        // Parametreyi bağla
        $stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
        
        // Sorguyu çalıştır
        if (!$stmt->execute()) {
            die("Sorgu çalıştırma hatası: " . $stmt->errorInfo()[2]);
        }

        // Rol bilgisini al
        $rol_row = $stmt->fetch(PDO::FETCH_ASSOC);
        $rol_id = $rol_row['rol_id'] ?? null;
    }
}
?>