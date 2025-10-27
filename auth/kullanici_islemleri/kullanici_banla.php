<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php');


// Rol kontrolü yapın (örneğin, rol ID'si 3 için)
rol_kontrol(3);

// Veritabanı bağlantısı
try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');


} catch (PDOException $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}

// Bans tablosunu oluştur (eğer yoksa)
$vt->exec("CREATE TABLE IF NOT EXISTS bans (
    id INT PRIMARY KEY,
    ban_baslangic DATETIME,
    ban_sure DATETIME,
    ban_nedeni TEXT
)");

// Kullanıcı ID'sini alın
$kullanici_id = $_GET['id'] ?? null;

if (!$kullanici_id) {
    die("Kullanıcı ID'si belirtilmedi.");
}

// Kullanıcı adını al
$stmt = $vt->prepare("SELECT kullanici_adi FROM kullanicilar WHERE id = :kullanici_id");
$stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Kullanıcı bulunamadı.");
}

$banlanacak_kullanici = $row['kullanici_adi'];

// Kullanıcının mevcut ban durumunu kontrol et
$stmt = $vt->prepare("SELECT * FROM bans WHERE id = :kullanici_id");
$stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
$stmt->execute();
$mevcut_ban = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ban_sure = $_POST['ban_sure'] ?? '';
    $ozel_sure = $_POST['ozel_sure'] ?? '';
    $ban_nedeni = $_POST['ban_nedeni'] ?? '';

    if ($ban_sure === 'ozel' && !empty($ozel_sure)) {
        $ban_sure = $ozel_sure . ' days';
    }

    // İstanbul saati için zaman dilimini ayarla
    date_default_timezone_set('Europe/Istanbul');

    if ($mevcut_ban) {
        // Ban süresini güncelle
        $yeni_sure = strtotime($mevcut_ban['ban_sure']) + (strtotime('+' . $ban_sure) - time());
        $yeni_sure = max($yeni_sure, time());
        $ban_sure = date('Y-m-d H:i:s', $yeni_sure);

        $stmt = $vt->prepare("UPDATE bans SET ban_sure = :ban_sure, ban_nedeni = :ban_nedeni WHERE id = :kullanici_id");
        $stmt->bindParam(':ban_sure', $ban_sure, PDO::PARAM_STR);
        $stmt->bindParam(':ban_nedeni', $ban_nedeni, PDO::PARAM_STR);
        $stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
    } else {
        // Yeni ban ekle
        $stmt = $vt->prepare("INSERT INTO bans (id, ban_sure, ban_baslangic, ban_nedeni) 
                              VALUES (:kullanici_id, :ban_sure, :ban_baslangic, :ban_nedeni)");
        $stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
        $stmt->bindParam(':ban_sure', date('Y-m-d H:i:s', strtotime('+' . $ban_sure)), PDO::PARAM_STR);
        $stmt->bindParam(':ban_baslangic', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindParam(':ban_nedeni', $ban_nedeni, PDO::PARAM_STR);
    }

    if ($stmt->execute()) {
        // Kullanıcının hesap durumunu "banli" olarak güncelle
        $stmt = $vt->prepare("UPDATE kullanicilar SET hesap_durumu = 'banli' WHERE id = :kullanici_id");
        $stmt->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Ban işlemi başarıyla gerçekleştirildi ve hesap durumu güncellendi.";
        } else {
            echo "Ban işlemi başarılı ancak hesap durumu güncellenirken bir hata oluştu.";
        }
    } else {
        echo "Ban işlemi sırasında bir hata oluştu.";
    }
} else {
  ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Banlama</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../bsd_yonetim/src/css/main.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <h2 class="card-title mb-4">
                        <i class="fas fa-user-slash"></i> <?= htmlspecialchars($banlanacak_kullanici) ?> (ID: <?= $kullanici_id ?>) kullanıcısını banla
                    </h2>
                    <?php if ($mevcut_ban): ?>
                        <p class="mb-3">
                            <i class="fas fa-clock"></i> Kullanıcı şu anda banlı. Ban bitiş tarihi: <?= htmlspecialchars($mevcut_ban['ban_sure']) ?>
                        </p>
                        <?php if (!empty($mevcut_ban['ban_nedeni'])): ?>
                            <p class="mb-3">
                                <i class="fas fa-exclamation-circle"></i> Mevcut ban nedeni: <?= htmlspecialchars($mevcut_ban['ban_nedeni']) ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="ban_sure" class="form-label">
                            <i class="fas fa-ban"></i> Ban süresi:
                        </label>
                        <select class="form-select" id="ban_sure" name="ban_sure">
                            <option value="7 days">1 Hafta</option>
                            <option value="30 days">30 Gün</option>
                            <option value="60 days">60 Gün</option>
                            <option value="90 days">90 Gün</option>
                            <option value="ozel">Özel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ozel_sure" class="form-label">
                            <i class="fas fa-calendar-plus"></i> Özel süre (gün):
                        </label>
                        <input type="number" class="form-control" id="ozel_sure" name="ozel_sure" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="ban_nedeni" class="form-label">
                            <i class="fas fa-exclamation-circle"></i> Ban nedeni:
                        </label>
                        <textarea class="form-control" id="ban_nedeni" name="ban_nedeni" rows="3" required></textarea>
                    </div>
                    <p class="mb-3">
                        <i class="fas fa-clock"></i> Şu anki İstanbul saati: <?= date('d.m.Y H:i:s') ?>
                    </p>
                    <button type="submit" class="bsd-btn1">
                        <i class="fas fa-gavel"></i> Banla
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>
<?php
}
?>