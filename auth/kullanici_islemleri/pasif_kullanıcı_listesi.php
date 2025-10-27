<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>
<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php');

// Rol kontrolü yapın (örneğin, rol ID'si 3 için)
rol_kontrol(3);

// Sayfa içeriği buradan devam eder...

?>

<?php
// Veritabanı bağlantısı ve diğer gerekli ayarlar
date_default_timezone_set('Europe/Istanbul');

try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php'); // MySQL bağlantısını sağlayan dosya
    $vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}

// Cron işlemi
function cron_isleri() {
    global $vt;
    
    // Son çalışma zamanını kontrol et
    $stmt = $vt->query("SELECT son_calisma_zamani FROM cron_isleri WHERE id = 1");
    $son_calisma = $stmt->fetchColumn();
    
    $simdi = date('Y-m-d H:i:s');
    if ($son_calisma && strtotime($simdi) - strtotime($son_calisma) < 86400) {
        // Son 24 saat içinde çalıştıysa, işlemi atla
        return "Son 24 saat içinde çalıştırıldı, atlandı.";
    }
    
    // 30 günden eski pasif hesapları sil
    $otuz_gun_once = date('Y-m-d H:i:s', strtotime('-30 days'));
    $stmt = $vt->prepare("DELETE FROM pasif_kullanicilar WHERE hesap_durumu = 'Silinmiş' AND hesap_silinme_tarihi <= :otuz_gun_once");
    $stmt->execute(['otuz_gun_once' => $otuz_gun_once]);
    
    // Silinen kullanıcıların rollerplus tablosundaki kayıtlarını sil
    $stmt = $vt->prepare("DELETE FROM rollerplus WHERE kullanici_id IN (SELECT id FROM pasif_kullanicilar WHERE hesap_durumu = 'Silinmiş' AND hesap_silinme_tarihi <= :otuz_gun_once)");
    $stmt->execute(['otuz_gun_once' => $otuz_gun_once]);
    
    // Cron çalışma zamanını güncelle
    $stmt = $vt->prepare("UPDATE cron_isleri SET son_calisma_zamani = :simdi WHERE id = 1");
    $stmt->execute(['simdi' => $simdi]);

    return "Cron işlemi başarıyla çalıştırıldı.";
}

// Cron işleri tablosunu oluştur (eğer yoksa)
$vt->exec("CREATE TABLE IF NOT EXISTS cron_isleri (
    id INTEGER PRIMARY KEY,
    son_calisma_zamani TEXT
)");

// İlk kaydı ekle (eğer yoksa)
$stmt = $vt->query("SELECT COUNT(*) FROM cron_isleri");
if ($stmt->fetchColumn() == 0) {
    $vt->exec("INSERT INTO cron_isleri (id, son_calisma_zamani) VALUES (1, NULL)");
}

// Komut satırından çalıştırılıyorsa cron işlemini yap
if (php_sapi_name() === 'cli') {
    echo cron_isleri();
    exit;
}

// Web arayüzü için kod

// Pasif kullanıcıları al
$stmt = $vt->query("SELECT * FROM pasif_kullanicilar");
$pasifKullanicilar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tarih formatını düzenleyen yardımcı fonksiyon
function formatTarih($tarih) {
    if ($tarih === null || $tarih === '') {
        return 'Belirtilmemiş';
    }
    return date('d.m.Y H:i:s', strtotime($tarih));
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Pasif Kullanıcı Yönetimi</title>

</head>
<body>
    <h5><i class="fas fa-users"></i> Pasif Kullanıcı Yönetimi</h5>

    <?php if (isset($_SESSION['mesaj'])): ?>
        <div id="message" class="message"><i class="fas fa-info-circle"></i> <?php echo $_SESSION['mesaj']; ?></div>
        <?php unset($_SESSION['mesaj']); ?>
    <?php endif; ?>

    <br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kullanıcı Adı</th>
                <th>E-posta</th>
                <th>Hesap Durumu</th>
                <th>Silinme Tarihi</th>
                <th>Dondurulma Tarihi</th>
                <th>Rol</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pasifKullanicilar as $kullanici): ?>
                <tr>
                    <td><?php echo htmlspecialchars($kullanici['id']); ?></td>
                    <td><?php echo htmlspecialchars($kullanici['kullanici_adi']); ?></td>
                    <td><?php echo htmlspecialchars($kullanici['eposta']); ?></td>
                    <td><?php echo htmlspecialchars($kullanici['hesap_durumu']); ?></td>
                    <td><?php echo htmlspecialchars(formatTarih($kullanici['hesap_silinme_tarihi'])); ?></td>
                    <td><?php echo htmlspecialchars(formatTarih($kullanici['hesap_dondurulma_tarihi'])); ?></td>
                    <td><?php echo htmlspecialchars($kullanici['rol']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2><i class="fas fa-info-circle"></i> Cron İşlemi Hakkında</h2>
    <p>
        Cron işlemi hakkında detaylı bilgi için <a
        href="../ayar_islemleri/cronbilgi.php"  class="bsd-navlink1">buraya tıklayın</a>.
    </p>

    <script>
        // Mesajı 20 saniye sonra gizle
        setTimeout(function() {
            var message = document.getElementById('message');
            if (message) {
                message.style.display = 'none';
            }
        }, 20000);
    </script>
    
    
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>
