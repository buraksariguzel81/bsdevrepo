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
    $stmt->bindParam(':kullanici_adi', $kullanici_adi, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Kullanıcı bilgilerini al
        $cinsiyet = $row['cinsiyet'] ?? '';

        // Eğer kadın kullanıcı değilse, erkek paneline yönlendir
        if ($cinsiyet != "kadin") {
            header("Location: ../erkekpanel/erkekpanel.php");
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
    <title>Kadın Sayfası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-female me-2"></i> Kadın Sayfasına Hoş Geldiniz!
            </h5>
        </div>

        <div class="card-body">
            <p>Bu sayfa sadece kadın kullanıcılar için hazırlanmıştır.</p>
            <p>İçerikler: Moda, Güzellik, Sağlık ve diğer kadın konuları burada yer alacak.</p>

            <div class="topic-section mb-4">
                <h6><i class="fas fa-tshirt me-2"></i> Moda</h6>
                <p>En son moda trendleri, stil önerileri ve giyim ipuçları burada.</p>
            </div>

            <div class="topic-section mb-4">
                <h6><i class="fas fa-spa me-2"></i> Güzellik</h6>
                <p>Cilt bakımı, makyaj teknikleri ve saç bakımı hakkında öneriler.</p>
            </div>

            <div class="topic-section mb-4">
                <h6><i class="fas fa-heartbeat me-2"></i> Sağlık</h6>
                <p>Kadın sağlığı, beslenme ve fitness konularında faydalı bilgiler.</p>
            </div>

            <div class="topic-section">
                <h6><i class="fas fa-utensils me-2"></i> Yemek Tarifleri</h6>
                <p>Lezzetli ve sağlıklı yemek tarifleri, mutfak ipuçları.</p>
            </div>
        </div>
    </div>

</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
