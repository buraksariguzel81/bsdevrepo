<?php
session_start(); // Oturum başlat

// Veritabanına bağlan
try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php'); // MySQL bağlantısını sağlayan dosya
} catch (Exception $e) {
    die("Veritabanı bağlantısı sağlanamadı: " . $e->getMessage());
}

// Kullanıcı adını oturumdan al
$kullanici_adi = $_SESSION['kullanici_adi'] ?? null;

if ($kullanici_adi) {
    // Kullanıcı bilgilerini sorgula
    $stmt = $vt->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
    $stmt->bindValue(':kullanici_adi', $kullanici_adi, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Kullanıcı bilgilerini al
        $cinsiyet = $row['cinsiyet'] ?? '';

        // Eğer kadın kullanıcı ise hemen yönlendir
        if ($cinsiyet != "erkek") {
            header("Location: ../kadinpanel/kadinpanel.php");
            exit();
        }
    } else {
        die("Kullanıcı bilgileri bulunamadı.");
    }
} else {
    die("Oturum bilgileri eksik.");
}

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
   <title>Erkek Sayfası</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
   <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-male me-2"></i> Erkek Sayfasına Hoş Geldiniz!
            </h5>
        </div>

        <div class="card-body">
            <p>Bu sayfa sadece erkek kullanıcılar için hazırlanmıştır.</p>
            <p>İçerikler: Spor, Teknoloji, Araçlar ve diğer erkek konuları burada yer alacak.</p>

            <div class="sport-section mb-4">
                <h6><i class="fas fa-futbol me-2"></i> Futbol</h6>
                <p>En son futbol haberleri, maç sonuçları ve transfer dedikoduları burada.</p>
            </div>

            <div class="sport-section mb-4">
                <h6><i class="fas fa-basketball-ball me-2"></i> Basketbol</h6>
                <p>NBA ve Avrupa basketbolu hakkında güncel bilgiler ve analizler.</p>
            </div>

            <div class="sport-section">
                <h6><i class="fas fa-dumbbell me-2"></i> Fitness</h6>
                <p>Etkili egzersiz rutinleri, beslenme önerileri ve vücut geliştirme ipuçları.</p>
            </div>
        </div>
    </div>

</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
