<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<?php
session_start();

// Eğer ban bilgisi yoksa ana sayfaya yönlendir
if (!isset($_SESSION['ban_bilgisi'])) {
    header("Location: ../../index.php");
    exit();
}

$ban_bilgisi = $_SESSION['ban_bilgisi'];

?>

<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Hesap Banlandı</title>

</head>
<body>
    <div class="container">
        <h5><i class="fas fa-ban"></i> Hesabınız Banlandı</h5>
        <div class="alert alert-danger"> 
            <p><strong>Ban Başlangıç Tarihi:  <br></strong> <?php echo htmlspecialchars($ban_bilgisi['ban_baslangic']); ?></p>
            <p><strong>Ban Süresi:</strong> <br><?php echo htmlspecialchars($ban_bilgisi['ban_sure']); ?></p>
            <p><strong>Ban Nedeni:</strong> <?php echo htmlspecialchars($ban_bilgisi['ban_nedeni']); ?></p>
        </div>
        <p>Hesabınız belirtilen nedenlerden dolayı banlanmıştır. Lütfen ban süresinin dolmasını bekleyin veya site yöneticileriyle iletişime geçin.</p>

    </div>

    
<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

    <script src="../../assents/src/kontrol/bsd_form.js"></script>
</body>
</html>
