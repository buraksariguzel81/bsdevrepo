<?php
session_start();
$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
  include($rol_kontrol_path);
  if (function_exists('rol_kontrol')) {
    rol_kontrol(1);
  }
}
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$menu_items = [
  ['href' => 'iciceclass.php', 'icon' => 'fas fa-layer-group', 'text' => 'İç İçe Class'],
  ['href' => 'icondegisim.php', 'icon' => 'fas fa-exchange-alt', 'text' => 'Icon Değiştirme']
];
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Class Modülü | BSDSoft</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --bs-body-bg: #f8f9fa;
    }
    .card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .card-header {
      background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
      border-bottom: none;
      padding: 1.25rem 1.5rem;
    }
    .module-card {
      border: 1px solid rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      border-radius: 8px;
      overflow: hidden;
    }
    .module-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 20px rgba(13, 110, 253, 0.1);
    }
    .module-icon {
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      background: rgba(13, 110, 253, 0.1);
      border-radius: 50%;
      color: #0d6efd;
      font-size: 1.5rem;
    }
    .footer {
      color: #6c757d;
      font-size: 0.875rem;
      margin-top: 3rem;
      padding: 1.5rem 0;
      border-top: 1px solid #e9ecef;
    }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-layers text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Sınıf Yönetim Merkezi</h5>
                        <p class="text-muted small mb-0">Sınıf ve modül yapılandırmalarını yönetin</p>
                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Yazdır
                    </button>
                </div>
            </div>
        </div>
    </div>


            <div class="row g-4">
              <?php foreach ($menu_items as $item): ?>
              <div class="col-12 col-md-6 col-lg-4">
                <a href="<?= htmlspecialchars($item['href']) ?>" class="text-decoration-none">
                  <div class="card h-100 module-card">
                    <div class="card-body text-center p-4">
                      <div class="module-icon">
                        <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
                      </div>
                      <h5 class="h6 mb-2"><?= htmlspecialchars($item['text']) ?></h5>
                      <span class="text-muted small">Modüle gitmek için tıklayın</span>
                    </div>
                  </div>
                </a>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class="text-center text-muted small footer">
          © <?= date('Y') ?> BSDSoft - Tüm hakları saklıdır
        </div>
      </div>
    </div>
  </div>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/assets/src/include/footer.php"; ?>
</body>
</html>
