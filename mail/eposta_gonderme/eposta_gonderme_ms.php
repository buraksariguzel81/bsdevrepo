<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Veritabanı bağlantısı
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php'); // MySQL bağlantısını sağlayan dosya

// MailerSend API bilgileri
$url = 'https://api.mailersend.com/v1/email';
$apiKey = 'mlsn.5192df543e07292a8dfa540334a9cad60c8d7d73e4e0086219e18b59529e6950';

// E-posta şablonu
function getEmailTemplate($username, $subject, $message) {
    return "
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
            .message {
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Merhaba {$username},</h1>
            <div class='message'>
                <h2>{$subject}</h2>
                <p>{$message}</p>
            </div>
            <p>Saygılarımızla,<br>buraksariguzeldev Ekibi</p>
        </div>
    </body>
    </html>
    ";
}

// E-posta gönderme fonksiyonu
function sendEmail($email, $username, $subject, $message) {
    global $url, $apiKey;

    $emailContent = getEmailTemplate($username, $subject, $message);

    $data = [
        'from' => [
            'email' => 'norelpy@test-yxj6lj9wxjx4do2r.mlsender.net',
            'name' => 'buraksariguzeldev'
        ],
        'to' => [
            [
                'email' => $email,
                'name' => $username
            ]
        ],
        'subject' => $subject,
        'html' => $emailContent,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Kullanıcıları getir
$query = $vt->query("SELECT id, kullanici_adi, eposta FROM kullanicilar WHERE hesap_durumu = 'aktif'");
$users = $query->fetchAll(PDO::FETCH_ASSOC);

$message = '';

// Form gönderildi mi kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $emailMessage = $_POST['message'];
    $selectedUsers = isset($_POST['users']) ? $_POST['users'] : [];

    if ($_POST['send_type'] == 'all') {
        foreach ($users as $user) {
            $response = sendEmail($user['eposta'], $user['kullanici_adi'], $subject, $emailMessage);
            $result = json_decode($response, true);
            if (isset($result['id'])) {
                $message .= "<p><i class='fas fa-check-circle'></i> {$user['kullanici_adi']} adlı kullanıcıya e-posta gönderildi.</p>";
            } else {
                $message .= "<p><i class='fas fa-times-circle'></i> {$user['kullanici_adi']} adlı kullanıcıya e-posta gönderilemedi.</p>";
            }
        }
    } else {
        foreach ($selectedUsers as $userId) {
            $user = array_filter($users, function($u) use ($userId) { return $u['id'] == $userId; });
            $user = reset($user);
            if ($user) {
                $response = sendEmail($user['eposta'], $user['kullanici_adi'], $subject, $emailMessage);
                $result = json_decode($response, true);
                if (isset($result['id'])) {
                    $message .= "<p><i class='fas fa-check-circle'></i> {$user['kullanici_adi']} adlı kullanıcıya e-posta gönderildi.</p>";
                } else {
                    $message .= "<p><i class='fas fa-times-circle'></i> {$user['kullanici_adi']} adlı kullanıcıya e-posta gönderilemedi.</p>";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Toplu E-posta Gönderme</title>

</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="card shadow-sm rounded">
      <div class="card-body">

        <h4 class="card-title text-primary mb-4">
          <i class="fas fa-envelope me-2"></i> Toplu E-posta Gönderme
        </h4>

        <!-- ✅ Sonuç Mesajı -->
        <?php if (!empty($message)): ?>
          <div class="alert alert-info">
            <h5><i class="fas fa-info-circle me-1"></i> Gönderim Sonuçları</h5>
            <?= $message ?>
          </div>
        <?php endif; ?>

        <!-- ✅ E-posta Formu -->
        <form method="post">

          <div class="mb-3">
            <label for="subject" class="form-label">
              <i class="fas fa-heading me-2"></i> Konu
            </label>
            <input type="text" class="form-control" id="subject" name="subject" required>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">
              <i class="fas fa-align-left me-2"></i> Mesaj
            </label>
            <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
          </div>

          <div class="mb-3">
            <label for="send_type" class="form-label">
              <i class="fas fa-users-cog me-2"></i> Gönderim Türü
            </label>
            <select id="send_type" name="send_type" class="form-select">
              <option value="all">Tüm Kullanıcılar</option>
              <option value="selected">Seçili Kullanıcılar</option>
            </select>
          </div>

          <!-- 👥 Seçilebilir Kullanıcılar -->
          <div id="user_selection" class="mb-3" style="display: none;">
            <label class="form-label"><i class="fas fa-user-check me-2"></i> Kullanıcılar</label>
            <div class="border rounded p-3 bg-white">
              <?php foreach ($users as $user): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="users[]" value="<?= $user['id'] ?>" id="user_<?= $user['id'] ?>">
                  <label class="form-check-label" for="user_<?= $user['id'] ?>">
                    <?= htmlspecialchars($user['kullanici_adi']) ?> <small class="text-muted">(<?= htmlspecialchars($user['eposta']) ?>)</small>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-paper-plane me-1"></i> E-posta Gönder
          </button>
        </form>

      </div>
    </div>
  </div>

  <!-- JS: Gönderim Türüne Göre Kullanıcı Seçimi -->
  <script>
    document.getElementById('send_type').addEventListener('change', function () {
      document.getElementById('user_selection').style.display =
        this.value === 'selected' ? 'block' : 'none';
    });
  </script>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>

</html>