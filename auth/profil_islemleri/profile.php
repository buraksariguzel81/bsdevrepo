<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');

$kullanici_adi = $_SESSION['kullanici_adi'] ?? '';

if (!$kullanici_adi) {
    die("Giriş yapılmamış.");
}

$stmt = $vt->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = :kullanici_adi");
$stmt->bindParam(':kullanici_adi', $kullanici_adi, PDO::PARAM_STR);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Kullanıcı bilgileri bulunamadı.");
}

$kullanici_id = $row['id'];
$eposta = $row['eposta'] ?? '';
$kayit_tarihi = $row['kayit_tarihi'] ?? '';
$cinsiyet = $row['cinsiyet'] ?? '';
$dogum_tarihi = $row['dogum_tarihi'] ?? '';

$roller = [];
$sql_roles = "SELECT r.rol_adi FROM rollerplus rp JOIN roller r ON rp.rol_id = r.id WHERE rp.kullanici_id = :kullanici_id";
$stmt_roles = $vt->prepare($sql_roles);
$stmt_roles->bindParam(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
$stmt_roles->execute();

while ($rol = $stmt_roles->fetch(PDO::FETCH_ASSOC)) {
    $roller[] = $rol['rol_adi'];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Sayfası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
      body {
        background-color: #f8f9fa;
      }
      .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgb(163 46 56 / 0.3);
      }
      .card-title {
        font-weight: 700;
        color: #a32638; /* Galatasaray kırmızısı */
      }
      .list-group-item strong {
        color: #444;
      }
      .list-group-item i {
        color: #a32638;
      }
    </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm rounded">
          <div class="card-body">
            <h5 class="card-title text-center mb-4">
              <i class="fas fa-user-circle me-2"></i> Kullanıcı Profili
            </h5>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <i class="fas fa-users-cog me-2"></i>
                <strong>Roller:</strong> <?= htmlspecialchars(implode(', ', $roller)) ?: 'Yok' ?>
              </li>
              <li class="list-group-item">
                <i class="fas fa-envelope me-2"></i>
                <strong>E-posta:</strong> <?= htmlspecialchars($eposta) ?: 'Belirtilmemiş' ?>
              </li>
              <li class="list-group-item">
                <i class="fas fa-calendar-alt me-2"></i>
                <strong>Kayıt Tarihi:</strong> <?= $kayit_tarihi ? date('d.m.Y H:i:s', strtotime($kayit_tarihi)) : 'Belirtilmemiş' ?>
              </li>
              <li class="list-group-item">
                <i class="fas fa-venus-mars me-2"></i>
                <strong>Cinsiyet:</strong> <?= htmlspecialchars($cinsiyet) ?: 'Belirtilmemiş' ?>
              </li>
              <?php if (!empty($dogum_tarihi)): ?>
              <li class="list-group-item">
                <i class="fas fa-birthday-cake me-2"></i>
                <strong>Doğum Tarihi:</strong> <?= date('d.m.Y', strtotime($dogum_tarihi)) ?>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
