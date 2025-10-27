<?php
ob_start(); // Çıktı tamponlamasını başlat
session_start();
date_default_timezone_set('Europe/Istanbul');

// Tüm include işlemlerini buraya taşıyın


include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');




$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$is_localhost = (strpos($current_url, 'localhost') !== false);
$is_production = (strpos($current_url, 'buraksariguzeldev.wuaze.com') !== false);

if ($is_localhost) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}


// Veritabanı bağlantı ayarları
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');


// Eğer sifre sütunu yoksa ekle
$result = $vt->query("
    SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'eposta_token' AND TABLE_SCHEMA = 'veritabani_adi'
");

$sifre_sutunu_var = false;
$cinsiyet_sutunu_var = false;

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    if ($row['COLUMN_NAME'] === 'sifre') {
        $sifre_sutunu_var = true;
    }
    if ($row['COLUMN_NAME'] === 'cinsiyet') {
        $cinsiyet_sutunu_var = true;
    }
}


function generateResetLink($email) {
    global $is_localhost, $is_production;
    $token = bin2hex(random_bytes(16));
    $_SESSION['reset_token'] = $token;
    
    if ($is_localhost) {
        return "http://localhost:8002/mail/e-posta_islemleri/doğrulamalink.php?token=$token&email=" . urlencode($email);
    } elseif ($is_production) {
        
        "https://buraksariguzeldev.wuaze.com/mail/e-posta_islemleri/doğrulamalink.php?token=$token&email="
        . urlencode($email);
    } else {
        return
        "http://$_SERVER[HTTP_HOST]/mail/e-posta_islemleri/doğrulamalink.php?token=$token&email="
        . urlencode($email);
    }
}

function sendResetEmail($email, $resetCode, $resetLink) {
    $url = 'https://api.mailersend.com/v1/email';
    $apiKey =
    'mlsn.efc120a49b4bac601e536bf80458f542f07422fdde65f3ce698f00e7b7ac5ad2';

    $emailContent = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f9f9f9;
                border-radius: 5px;
            }
            h1 {
                color: #0056b3;
            }
            .reset-code {
                font-size: 24px;
                font-weight: bold;
                color: #0056b3;
                margin: 20px 0;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #0056b3;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Merhaba,</h1>
            <p>Şifre sıfırlama kodunuz:</p>
            <p class='reset-code'>$resetCode</p>
            <p>Veya aşağıdaki butona tıklayarak şifrenizi sıfırlayabilirsiniz:</p>
            <p><a href='$resetLink' class='btn'>Şifremi Sıfırla</a></p>
            <p>Bu link 3 saat içinde geçerliliğini yitirecektir.</p>
            <p>Saygılarımızla,<br>buraksariguzeldev Ekibi</p>
        </div>
    </body>
    </html>
    ";

    $data = [
        'from' => [
            'email' => 'norelpy@test-yxj6lj9wxjx4do2r.mlsender.net',
            'name' => 'buraksariguzeldev'
        ],
        'to' => [
            [
                'email' => $email,
                'name' => 'Kullanıcı'
            ]
        ],
        'subject' => 'Şifre Sıfırlama',
        'html' => $emailContent,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return "cURL Hatası: $error";
    }
    curl_close($ch);

    if ($httpCode == 202) {
        return true;
    } else {
        return "E-posta gönderme hatası: HTTP kodu $httpCode, Yanıt: $response";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sifre_sifirla"])) {
    $eposta = trim($_POST["eposta"]);
    
    // Kullanıcının varlığını kontrol et
  $stmt = $vt->prepare("SELECT * FROM kullanicilar WHERE eposta = :eposta");
$stmt->bindParam(':eposta', $eposta, PDO::PARAM_STR);
$stmt->execute();
$kullanici_adi = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kullanici_adi) {
        $resetCode = rand(100000, 999999);
        $_SESSION["reset_code"] = $resetCode;
        $_SESSION["reset_email"] = $eposta;  // E-posta adresini session'a ekle
        $resetLink = generateResetLink($eposta);

        // Eski token'ı sil
    $stmt = $vt->prepare("DELETE FROM eposta_token WHERE eposta = :eposta AND islem_turu = 'Sifre_Sifirlama'");
    $stmt->bindValue(':eposta', $eposta, PDO::PARAM_STR);
    $stmt->execute();

    // Yeni token'ı ekle
    $stmt = $vt->prepare("INSERT INTO eposta_token (kullanici_adi, eposta, token, olusturma_zamani, islem_turu) VALUES (:kullanici_adi, :eposta, :token, :olusturma_zamani, 'Sifre_Sifirlama')");
    $stmt->bindValue(':kullanici_adi', $kullanici_adi['kullanici_adi'], PDO::PARAM_STR);
    $stmt->bindValue(':eposta', $eposta, PDO::PARAM_STR);
    $stmt->bindValue(':token', $_SESSION['reset_token'], PDO::PARAM_STR);
    $stmt->bindValue(':olusturma_zamani', date("d-m-Y H:i:s"), PDO::PARAM_STR);
        $stmt->execute();

        $result = sendResetEmail($eposta, $resetCode, $resetLink);
        if ($result !== true) {
            $hata = "E-posta gönderilirken bir hata oluştu: " . $result;
            error_log("E-posta gönderme hatası: " . $result);
        } else {
            $mesaj = "Şifre sıfırlama e-postası gönderildi. Lütfen gelen kutunuzu kontrol edin.";
        }
    } else {
        $hata = "Bu e-posta adresiyle kayıtlı bir kullanıcı bulunamadı.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["dogrula"])) {
    $girilen_kod = trim($_POST["reset_code"]);

    if ($girilen_kod == $_SESSION["reset_code"]) {
        // Şifre sıfırlama formunu göster
        $show_reset_form = true;
    } else {
        $hata = "Geçersiz sıfırlama kodu. Lütfen tekrar deneyin.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["yeni_sifre_kaydet"])) {
    $yeni_sifre = trim($_POST["yeni_sifre"]);

    if (isset($_SESSION["reset_email"])) {
        // Şifreyi güncelle
    $stmt = $vt->prepare("UPDATE kullanicilar SET sifre = :sifre WHERE eposta = :eposta");
    $stmt->bindValue(':sifre', password_hash($yeni_sifre, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $stmt->bindValue(':eposta', $_SESSION["reset_email"], PDO::PARAM_STR);
    $stmt->execute();

    // Token'ı sil
    $stmt = $vt->prepare("DELETE FROM eposta_token WHERE eposta = :eposta AND islem_turu = 'Sifre_Sifirlama'");
    $stmt->bindValue(':eposta', $_SESSION["reset_email"], PDO::PARAM_STR);
    $stmt->execute();

        $mesaj = "Şifreniz başarıyla güncellendi. Şimdi giriş yapabilirsiniz.";
        // Oturum değişkenlerini temizle
        unset($_SESSION["reset_code"]);
        unset($_SESSION["reset_email"]);
        unset($_SESSION["reset_token"]);
        $show_reset_form = false;
    } else {
        $hata = "Oturum bilgisi bulunamadı. Lütfen şifre sıfırlama işlemini baştan başlatın.";
    }
}

if (isset($_GET['token']) && isset($_GET['email'])) {
    $stmt = $vt->prepare("SELECT * FROM eposta_token WHERE eposta = :eposta AND islem_turu = 'Sifre_Sifirlama'");
    $stmt->bindValue(':eposta', $_GET['email'], PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $_GET['token'] === $row['token']) {
        $olusturma_zamani = new DateTime($row['olusturma_zamani']);
        $simdi = new DateTime();
        $fark = $simdi->diff($olusturma_zamani);

        if ($fark->h < 3) {
            // Şifre sıfırlama formunu göster
            $show_reset_form = true;
            $_SESSION["reset_email"] = $_GET['email'];
        } else {
            $hata = "Şifre sıfırlama linkinin süresi dolmuş. Lütfen yeni bir şifre sıfırlama e-postası isteyin.";
        }
    } else {
        $hata = "Geçersiz şifre sıfırlama linki.";
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Şifre Sıfırlama</title>


</head>
<body>
    <div class="container">
        <div class="form-container">
            <h5><i class="fas fa-key"></i> Şifre Sıfırlama</h5>
            <?php if(isset($hata)): ?>
                <div class="alert alert-danger fade-out" role="alert" id="hataMessage"><?php echo $hata; ?></div>
            <?php endif; ?>
            <?php if(isset($mesaj)): ?>
                <div class="alert alert-success fade-out" role="alert" id="basariMessage"><?php echo $mesaj; ?></div>
            <?php endif; ?>

            <?php if(!isset($show_reset_form)): ?>
                <?php if(!isset($_SESSION["reset_code"])): ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="eposta" class="form-label"><i class="fas fa-envelope"></i> E-posta Adresi</label>
                            <input type="email" class="form-control" id="eposta" name="eposta" required>
                        </div>
                        <button type="submit" class="bsd-btn1 bsd-navlink1" name="sifre_sifirla"><i class="fas fa-paper-plane"></i> Şifre Sıfırlama E-postası Gönder</button>
                    </form>
                <?php else: ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="reset_code" class="form-label"><i class="fas fa-unlock"></i> Sıfırlama Kodu</label>
                            <input type="text" class="form-control" id="reset_code" name="reset_code" required maxlength="6">
                        </div>
                        <button type="submit" class="bsd-btn1 bsd-navlink1" name="dogrula"><i class="fas fa-check"></i> Doğrula</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="yeni_sifre" class="form-label"><i class="fas fa-lock"></i> Yeni Şifre</label>
                        <input type="password" class="form-control" id="yeni_sifre" name="yeni_sifre" required>
                    </div>
                    <button type="submit" class="bsd-btn1 bsd-navlink1" name="yeni_sifre_kaydet"><i class="fas fa-save"></i> Yeni Şifreyi Kaydet</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <hr>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
    <hr>


</body>
</html>
<?php
ob_end_flush(); // Çıktı tamponlamasını bitir ve içeriği gönder
?>
