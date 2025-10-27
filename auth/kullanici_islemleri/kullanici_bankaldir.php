<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php');

// Rol kontrolü yapın (örneğin, rol ID'si 3 için)
rol_kontrol(3);

// Sayfa içeriği buradan devam eder...
?>

<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<?php


// Veritabanı bağlantısı
try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php'); // MySQL bağlantısını sağlayan dosya
} catch (Exception $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}

// Kullanıcı ID'sini veya kullanıcı adını alın
$bankaldirilacak_id = $_GET['id'] ?? null;
$bankaldirilacak_kullanici = $_GET['kullanici_adi'] ?? null;

if (!$bankaldirilacak_id && !$bankaldirilacak_kullanici) {
    die("Kullanıcı ID'si veya kullanıcı adı belirtilmedi.");
}

// Kullanıcı bilgilerini al
if ($bankaldirilacak_id) {
    $stmt = $vt->prepare("SELECT id, kullanici_adi FROM kullanicilar WHERE id = :id");
    $stmt->bindValue(':id', $bankaldirilacak_id, PDO::PARAM_INT);
} else {
    $stmt = $vt->prepare("SELECT id, kullanici_adi FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
    $stmt->bindValue(':kullanici_adi', $bankaldirilacak_kullanici, PDO::PARAM_STR);
}

$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Kullanıcı bulunamadı.");
}

$kullanici_id = $row['id'];
$kullanici_adi = $row['kullanici_adi'];

// Kullanıcının mevcut ban durumunu kontrol et
$stmt = $vt->prepare("SELECT * FROM bans WHERE id = :id");
$stmt->bindValue(':id', $kullanici_id, PDO::PARAM_INT);
$stmt->execute();
$mevcut_ban = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($mevcut_ban) {
        // Banı kaldır
        $stmt = $vt->prepare("DELETE FROM bans WHERE id = :id");
        $stmt->bindValue(':id', $kullanici_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            // Hesap durumunu aktif olarak güncelle
            $stmt = $vt->prepare("UPDATE kullanicilar SET hesap_durumu = 'aktif' WHERE id = :id");
            $stmt->bindValue(':id', $kullanici_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo "Ban başarıyla kaldırıldı ve hesap durumu aktif olarak güncellendi.";
            } else {
                echo "Ban kaldırıldı ancak hesap durumu güncellenirken bir hata oluştu.";
            }
        } else {
            echo "Ban kaldırılırken bir hata oluştu.";
        }
    } else {
        echo "Bu kullanıcı zaten banlı değil.";
    }
} else {
    // Ban kaldırma form sayfasını göster
    ?>
    <!DOCTYPE html>
    <html lang="tr">
    <head>

        <title>Kullanıcı Ban Kaldırma</title>

    </head>
    <body>
        <div class="container mt-5">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">
                        <i class="fas fa-user-check"></i> <?= htmlspecialchars($kullanici_adi) ?> kullanıcısının banını kaldır
                    </h2>
                    <?php if ($mevcut_ban): ?>
                        <p class="mb-3">
                            <i class="fas fa-clock"></i> Kullanıcı şu anda banlı. Ban bitiş tarihi: <?= htmlspecialchars($mevcut_ban['ban_sure']) ?>
                        </p>
                        <p class="mb-3">
                            <i class="fas fa-exclamation-circle"></i> Ban nedeni: <?= htmlspecialchars($mevcut_ban['ban_nedeni']) ?>
                        </p>
                        <form method="post">
                            <button type="submit" class="bsd-btn1">
                                <i class="fas fa-user-check"></i> Banı Kaldır
                            </button>
                        </form>
                    <?php else: ?>
                        <p class="mb-3">Bu kullanıcı şu anda banlı değil.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
    </html>
    


<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>