<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

<?php

// Database connection
try {
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
} catch (Exception $e) {
    die("Veritabanı bağlantısı kurulamadı: " . htmlspecialchars($e->getMessage()));
}

$kullanici_adi = $_SESSION['kullanici_adi'];
$mesaj = '';

// Fetch current privacy settings
$stmt = $vt->prepare("SELECT profil_gizliligi, eposta_gizliligi FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
$stmt->bindValue(':kullanici_adi', $kullanici_adi, SQLITE3_TEXT);
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);

$profil_gizliligi = $row['profil_gizliligi'] ?? 'herkese_acik';
$eposta_gizliligi = $row['eposta_gizliligi'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $yeni_profil_gizliligi = $_POST['profil_gizliligi'] ?? 'herkese_acik';
    $yeni_eposta_gizliligi = isset($_POST['eposta_gizliligi']) ? 1 : 0;

    $update_stmt = $vt->prepare("UPDATE kullanicilar SET profil_gizliligi = :profil_gizliligi, eposta_gizliligi = :eposta_gizliligi WHERE kullanici_adi = :kullanici_adi");
    $update_stmt->bindValue(':profil_gizliligi', $yeni_profil_gizliligi, SQLITE3_TEXT);
    $update_stmt->bindValue(':eposta_gizliligi', $yeni_eposta_gizliligi, SQLITE3_INTEGER);
    $update_stmt->bindValue(':kullanici_adi', $kullanici_adi, SQLITE3_TEXT);

    if ($update_stmt->execute()) {
        $mesaj = "Gizlilik ayarlarınız başarıyla güncellendi.";
        $profil_gizliligi = $yeni_profil_gizliligi;
        $eposta_gizliligi = $yeni_eposta_gizliligi;
    } else {
        $mesaj = "Gizlilik ayarları güncellenirken bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gizlilik Ayarları</title>
    <link rel="stylesheet" href="../../../bsd_yonetim/src/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-secret"></i> Gizlilik Ayarları</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($mesaj): ?>
                            <div class="alert alert-info" role="alert">
                                <?= htmlspecialchars($mesaj) ?>
                            </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="profil_gizliligi" class="form-label">Profil Gizliliği</label>
                                <select class="form-select" id="profil_gizliligi" name="profil_gizliligi">
                                    <option value="herkese_acik" <?= $profil_gizliligi === 'herkese_acik' ? 'selected' : '' ?>>Herkese Açık</option>
                                    <option value="sadece_arkadaslar" <?= $profil_gizliligi === 'sadece_arkadaslar' ? 'selected' : '' ?>>Sadece Arkadaşlar</option>
                                    <option value="gizli" <?= $profil_gizliligi === 'gizli' ? 'selected' : '' ?>>Gizli</option>
                                </select>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="eposta_gizliligi" name="eposta_gizliligi" <?= $eposta_gizliligi ? 'checked' : '' ?>>
                                <label class="form-check-label" for="eposta_gizliligi">E-posta adresimi gizle</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Ayarları Kaydet</button>
                        </form>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="../ayar_islemleri/ayarlar.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Ayarlara Dön</a>
                </div>
            </div>
        </div>
    </div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>