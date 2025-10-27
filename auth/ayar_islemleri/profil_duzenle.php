<?php
session_start();

$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$is_localhost = (strpos($current_url, 'localhost:8002') !== false);
$is_production = (strpos($current_url, 'buraksariguzeldev.wuaze.com') !== false);

if ($is_localhost) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

$hata = $mesaj = "";
$current_email = $_SESSION["eposta"] ?? null;

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');

if (!$vt) {
    die("Veritabanı bağlantısı başarısız: " . $vt->errorInfo());
}

$vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Doğrulama kodu kontrolü
if (isset($_GET['dogrulama_kodu']) && isset($_SESSION["dogrulama_kodu"])) {
    $gelen_kodu = $_GET['dogrulama_kodu'];
    $dogrulama_kodu = $_SESSION["dogrulama_kodu"];

    if ($gelen_kodu == $dogrulama_kodu) {
        $stmt = $vt->prepare("UPDATE kullanicilar SET eposta = :eposta WHERE kullanici_adi = :kullanici_adi");
        $stmt->bindValue(':eposta', $_SESSION["yeni_eposta"], PDO::PARAM_STR);
        $stmt->bindValue(':kullanici_adi', $_SESSION["kullanici_adi"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['eposta_guncellendi'] = true;
            header("Location: ../../../index.php");
            exit();
        } else {
            $hata = "E-posta kaydedilemedi.";
        }
    } else {
        $hata = "Geçersiz doğrulama kodu.";
    }
}

// POST ile e-posta gönderme ve doğrulama kodu üretme
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["yeni_eposta"])) {
    $yeni_eposta = trim($_POST["yeni_eposta"]);

    if (filter_var($yeni_eposta, FILTER_VALIDATE_EMAIL)) {
        $stmt = $vt->prepare("SELECT COUNT(*) FROM kullanicilar WHERE eposta = :eposta");
        $stmt->bindValue(':eposta', $yeni_eposta, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['COUNT(*)'] > 0) {
                $hata = "Bu e-posta adresi zaten sistemde kayıtlı.";
            } else {
                $_SESSION["yeni_eposta"] = $yeni_eposta;
                $dogrulama_kodu = rand(100000, 999999);
                $_SESSION["dogrulama_kodu"] = $dogrulama_kodu;

                $token = md5($dogrulama_kodu . time());

                // Token kaydet
                $stmtToken = $vt->prepare("INSERT INTO eposta_token (kullanici_adi, token, eposta, islem_turu, olusturma_zamani) VALUES (:kullanici_adi, :token, :eposta, :islem_turu, NOW())");
                $stmtToken->execute([
                    ':kullanici_adi' => $_SESSION['kullanici_adi'],
                    ':token' => $token,
                    ':eposta' => $yeni_eposta,
                    ':islem_turu' => 'Eposta_Degistirme'
                ]);

                if ($is_localhost) {
                    $verificationLink = "http://localhost:8003/mail/e-posta_islemleri/doğrulamalink.php?token=" . $token;
                } elseif ($is_production) {
                    $verificationLink = "https://buraksariguzeldev.wuaze.com/mail/e-posta_islemleri/doğrulamalink.php?token=" . $token;
                } else {
                    $verificationLink = "http://$_SERVER[HTTP_HOST]/mail/e-posta_islemleri/doğrulamalink.php?token=" . $token;
                }

                $emailSent = sendVerificationEmail($yeni_eposta, $dogrulama_kodu, $verificationLink);

                if ($emailSent === true) {
                    $mesaj = "Doğrulama e-postası başarıyla gönderildi.";
                } else {
                    $hata = "E-posta gönderme hatası: $emailSent";
                }
            }
        } else {
            $hata = "Veritabanı sorgusu başarısız oldu.";
        }
    } else {
        $hata = "Geçersiz e-posta adresi.";
    }
}

function sendVerificationEmail($email, $verificationCode, $verificationLink) {
    $url = 'https://api.mailersend.com/v1/email';
    $apiKey = 'mlsn.5192df543e07292a8dfa540334a9cad60c8d7d73e4e0086219e18b59529e6950';

    $emailContent = "
    <html>
    <head>
        <style>
            body {font-family: Arial, sans-serif; line-height: 1.6; color: #333;}
            .container {max-width: 600px; margin: 0 auto; padding: 20px; background: #f9f9f9; border-radius: 5px;}
            h1 {color: #0056b3;}
            .verification-code {font-size: 24px; font-weight: bold; color: #0056b3; margin: 20px 0;}
            .btn {display: inline-block; padding: 10px 20px; background-color: #0056b3; color: #fff; text-decoration: none; border-radius: 5px;}
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Merhaba {$_SESSION["kullanici_adi"]},</h1>
            <p>E-posta doğrulama kodunuz:</p>
            <p class='verification-code'>$verificationCode</p>
            <p>Ya da aşağıdaki butona tıklayarak hesabınızı doğrulayabilirsiniz:</p>
            <p><a href='$verificationLink' class='btn'>Hesabımı Doğrula</a></p>
            <p>Bu link 3 saat içinde geçerlidir.</p>
            <p>Saygılarımızla,<br>buraksariguzeldev Ekibi</p>
        </div>
    </body>
    </html>";

    $data = [
        'from' => [
            'email' => 'norelpy@test-yxj6lj9wxjx4do2r.mlsender.net',
            'name' => 'buraksariguzeldev'
        ],
        'to' => [
            [
                'email' => $email,
                'name' => $_SESSION["kullanici_adi"] ?? 'Kullanıcı'
            ]
        ],
        'subject' => 'E-posta Doğrulama',
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
    curl_close($ch);

    return ($httpCode == 202) ? true : "E-posta gönderme hatası: HTTP kodu $httpCode, Yanıt: $response";
}

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>E-posta Doğrulama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 700px;">
    <div class="card shadow-sm rounded">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fas fa-envelope me-2"></i>
            <h5 class="mb-0">E-posta Doğrulama</h5>
        </div>
        <div class="card-body">

            <?php if ($hata): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle me-2"></i><?= $hata ?>
                </div>
            <?php endif; ?>

            <?php if ($mesaj): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?= $mesaj ?>
                </div>
            <?php endif; ?>

            <form method="post" class="mb-4">
                <div class="mb-3">
                    <label for="yeni_eposta" class="form-label fw-bold">Yeni E-posta Adresi</label>
                    <input type="email" name="yeni_eposta" id="yeni_eposta" class="form-control" required placeholder="example@mail.com" />
                </div>
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-paper-plane me-2"></i> Doğrulama E-postasını Gönder
                </button>
            </form>

            <hr />

            <h5>Doğrulama Kodunu Girin</h5>
            <form method="get">
                <div class="mb-3">
                    <label for="dogrulama_kodu" class="form-label fw-bold">Doğrulama Kodu</label>
                    <input type="text" name="dogrulama_kodu" id="dogrulama_kodu" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-check me-2"></i> Doğrulama Kodunu Gönder
                </button>
            </form>

        </div>
    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
