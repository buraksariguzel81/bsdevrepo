<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Rol kontrolü (admin yetkisi)
$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
  include($rol_kontrol_path);
  if (function_exists('rol_kontrol')) {
    rol_kontrol(1);
  }
}

$message = '';
$messageClass = '';

// Taşıma işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $baseDirectory = $_SERVER['DOCUMENT_ROOT'];
  $sourceDir = trim($_POST['sourceDir']);
  $targetDir = trim($_POST['targetDir']);
  $itemName = trim($_POST['itemName']);

  $sourcePath = rtrim($baseDirectory, '/') . '/' . ltrim($sourceDir, '/') . '/' . $itemName;
  $targetPath = rtrim($baseDirectory, '/') . '/' . ltrim($targetDir, '/') . '/' . $itemName;
  $targetDirPath = dirname($targetPath);

  // Güvenlik kontrolü
  if (strpos($sourceDir, '..') !== false || strpos($targetDir, '..') !== false) {
    $message = "Erişim reddedildi.";
    $messageClass = "danger";
  } elseif (!file_exists($sourcePath)) {
    $message = "Kaynak dosya veya klasör bulunamadı.";
    $messageClass = "warning";
  } else {
    if (!is_dir($targetDirPath)) {
      if (!mkdir($targetDirPath, 0755, true)) {
        $message = "Hedef dizin oluşturulurken hata oluştu.";
        $messageClass = "danger";
      }
    }
    if (rename($sourcePath, $targetPath)) {
      $message = "Dosya veya klasör başarıyla taşındı.";
      $messageClass = "success";
    } else {
      $message = "Taşıma sırasında hata oluştu: " . htmlspecialchars(print_r(error_get_last(), true));
      $messageClass = "danger";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dosya Yöneticisi | BSDSoft</title>
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
      transition: transform 0.2s;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .card-header {
      background: linear-gradient(135deg, #ffc107 0%, #ffab00 100%);
      border-bottom: none;
      padding: 1.25rem 1.5rem;
    }
    .form-control:focus {
      border-color: #ffc107;
      box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
    }
    .btn-warning {
      background: linear-gradient(135deg, #ffc107 0%, #ffab00 100%);
      border: none;
      color: #212529;
      font-weight: 500;
      padding: 0.65rem 1.5rem;
      transition: all 0.3s;
    }
    .btn-warning:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
    }
    .input-group-text {
      background-color: #fff8e1;
      border-color: #ffe082;
    }
    .alert {
      border: none;
      border-left: 4px solid;
      border-radius: 0.375rem;
    }
    .alert-success {
      border-left-color: #198754;
    }
    .alert-warning {
      border-left-color: #ffc107;
    }
    .alert-danger {
      border-left-color: #dc3545;
    }
    .info-box {
      background-color: #fff8e1;
      border-left: 4px solid #ffc107;
      padding: 1rem;
      border-radius: 0.375rem;
      margin-bottom: 1.5rem;
    }
    .info-box i {
      color: #ffab00;
    }
    .footer {
      color: #6c757d;
      font-size: 0.875rem;
      margin-top: 3rem;
      padding: 1.5rem 0;
      border-top: 1px solid #e9ecef;
    }
    .form-label {
      color: #495057;
      font-weight: 500;
    }
    .form-control, .form-select {
      padding: 0.65rem 0.85rem;
      border-radius: 0.375rem;
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
                        <i class="bi bi-arrows-move text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dosya Taşıma</h5>
                        <p class="text-muted small mb-0">Sistem dosyalarını taşımak için araçlar</p>
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


            <!-- Form -->
            <form method="post" id="moveForm" class="needs-validation" novalidate>
              <div class="mb-4">
                <label for="sourceDir" class="form-label">
                  <i class="bi bi-folder-open me-2 text-warning"></i> Kaynak Dizin
                </label>
                <div class="input-group mb-2">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-folder text-muted"></i>
                  </span>
                  <input type="text" 
                         name="sourceDir" 
                         id="sourceDir" 
                         class="form-control form-control-lg" 
                         placeholder="Örn: modules/data" 
                         required
                         value="<?= htmlspecialchars($_POST['sourceDir'] ?? '') ?>">
                </div>
                <small class="text-muted">
                  <i class="fas fa-info-circle me-1"></i> Taşınacak öğenin bulunduğu klasör yolu
                </small>
              </div>

              <div class="mb-4">
                <label for="itemName" class="form-label">
                  <i class="fas fa-file me-2 text-warning"></i> Taşınacak Öğe
                </label>
                <div class="input-group mb-2">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-file-alt text-muted"></i>
                  </span>
                  <input type="text" 
                         name="itemName" 
                         id="itemName" 
                         class="form-control form-control-lg" 
                         placeholder="Örn: index.php veya klasor_adi" 
                         required
                         value="<?= htmlspecialchars($_POST['itemName'] ?? '') ?>">
                </div>
                <small class="text-muted">
                  <i class="fas fa-info-circle me-1"></i> Taşınacak dosya veya klasör adı
                </small>
              </div>

              <div class="mb-4">
                <label for="targetDir" class="form-label">
                  <i class="fas fa-folder-plus me-2 text-warning"></i> Hedef Dizin
                </label>
                <div class="input-group mb-2">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-map-marker-alt text-muted"></i>
                  </span>
                  <input type="text" 
                         name="targetDir" 
                         id="targetDir" 
                         class="form-control form-control-lg" 
                         placeholder="Örn: backup/2023/aralik" 
                         required
                         value="<?= htmlspecialchars($_POST['targetDir'] ?? '') ?>">
                </div>
                <small class="text-muted">
                  <i class="fas fa-info-circle me-1"></i> Taşınacak yeni konum (dizin yoksa oluşturulacaktır)
                </small>
              </div>

              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-warning btn-lg py-2" id="moveButton">
                  <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                  <i class="fas fa-truck-fast me-2"></i>Taşıma İşlemini Başlat
                </button>
              </div>
            </form>

            <!-- Geri Bildirim -->
            <?php if ($message): ?>
              <div class="alert alert-<?= $messageClass ?> alert-dismissible fade show mt-4" role="alert">
                <div class="d-flex align-items-center">
                  <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : 
                                 ($messageClass === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle') 
                                ?> me-2"></i>
                  <div>
                    <strong class="d-block">
                      <?= $messageClass === 'success' ? 'Başarılı!' : 
                            ($messageClass === 'warning' ? 'Uyarı!' : 'Hata!') ?>
                    </strong>
                    <?= $message ?>
                  </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="text-center text-muted small footer">
          © <?= date('Y') ?> BSDSoft - Tüm hakları saklıdır
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Form gönderiminde yükleniyor animasyonu
      const form = document.getElementById('moveForm');
      const moveButton = document.getElementById('moveButton');
      const spinner = moveButton ? moveButton.querySelector('.spinner-border') : null;
      
      // Form doğrulama
      if (form) {
        form.addEventListener('submit', function(event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          } else if (!confirm('Bu öğeyi taşımak istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
            event.preventDefault();
            return false;
          } else if (moveButton && spinner) {
            moveButton.disabled = true;
            spinner.classList.remove('d-none');
          }
          
          form.classList.add('was-validated');
        });
      }
      
      // Sayfa yüklendiğinde ilk input'a odaklan
      const firstInput = document.querySelector('input[required]');
      if (firstInput) {
        firstInput.focus();
      }
    });
  </script>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/footer.php'; ?>
</body>
</html>
