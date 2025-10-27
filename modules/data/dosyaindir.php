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
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dosya İndir - BSD Soft</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #f8f9fc;
      --accent-color: #2e59d9;
      --text-color: #5a5c69;
    }
    
    body {
      font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      color: var(--text-color);
      background-color: #f8f9fc;
      overflow-x: hidden;
    }
    
    .card {
      border: none;
      border-radius: 0.5rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
      transform: translateY(-5px);
    }
    
    .card-header {
      background-color: var(--primary-color);
      border-bottom: none;
      border-radius: 0.5rem 0.5rem 0 0 !important;
      padding: 1.25rem 1.5rem;
    }
    
    .card-header h5 {
      font-weight: 700;
      font-size: 1.25rem;
      margin: 0;
    }
    
    .card-body {
      padding: 2rem;
    }
    
    .form-control, .form-select, .form-control:focus, .form-select:focus {
      border-color: #d1d3e2;
      padding: 0.75rem 1rem;
      height: calc(2.5rem + 2px);
      border-radius: 0.35rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #bac8f3;
      box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .btn {
      padding: 0.5rem 1.5rem;
      border-radius: 0.35rem;
      font-weight: 600;
      transition: all 0.2s ease-in-out;
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
      transform: translateY(-1px);
    }
    
    .form-label {
      font-weight: 600;
      color: #5a5c69;
      margin-bottom: 0.5rem;
    }
    
    .alert {
      border: none;
      border-left: 0.25rem solid;
      border-radius: 0.35rem;
    }
    
    .alert-danger {
      border-left-color: #e74a3b;
    }
    
    .alert-success {
      border-left-color: #1cc88a;
    }
    
    .text-primary {
      color: var(--primary-color) !important;
    }
    
    .bg-primary {
      background-color: var(--primary-color) !important;
    }
    
    /* Loading Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
      opacity: 0;
      animation: fadeIn 0.5s ease-out forwards;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .card-body {
        padding: 1.5rem;
      }
    }
  </style>
</head>


<div class="container py-4 fade-in">

  <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="fas fa-file-download fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dosya İndirme Aracı</h5>
                        <p class="text-muted small mb-0">Dosya indirme aracını kullanarak dosyalarınızı indirebilirsiniz.</p>
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







  
  <div class="card shadow-sm mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-file-download me-2"></i>
        Dosya İndir
      </h6>
      <div class="dropdown">
        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-cog me-1"></i> İşlemler
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="#" onclick="window.location.reload()"><i class="fas fa-sync-alt me-2"></i>Yenile</a></li>
          <li><a class="dropdown-item" href="klasorsayisi.php"><i class="fas fa-folder me-2"></i>Klasör İstatistikleri</a></li>
          <li><a class="dropdown-item" href="uzantılar.php"><i class="fas fa-file-alt me-2"></i>Dosya Uzantıları</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <div class="alert alert-primary border-left-primary alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Bilgi!</strong> Bu sayfadan dosya indirme işlemlerinizi gerçekleştirebilirsiniz. Klasör indirme özelliği şu an için devre dışıdır.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-light py-3">
              <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-upload me-2"></i>Dosya İndirme Formu</h6>
            </div>
            <div class="card-body">
              <form method="post" class="needs-validation" novalidate>
                <div class="mb-4">
                  <label for="dirPath" class="form-label"><i class="fas fa-folder me-1 text-primary"></i> Dizin Yolu</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-folder-open"></i></span>
                    <input type="text" name="dirPath" id="dirPath" class="form-control" placeholder="Örn: /uploads veya /images/2023" required />
                  </div>
                  <div class="form-text">İndirilecek dosyanın bulunduğu klasörün yolunu giriniz.</div>
                </div>
                
                <div class="mb-4">
                  <label for="itemName" class="form-label"><i class="fas fa-file me-1 text-primary"></i> Dosya Adı</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                    <input type="text" name="itemName" id="itemName" class="form-control" placeholder="Örn: rapor.pdf veya resim.jpg" required />
                  </div>
                  <div class="form-text">İndirilecek dosyanın tam adını ve uzantısını giriniz.</div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                  <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-download me-2"></i> Dosyayı İndir
                  </button>
                </div>
              </form>
            </div>
          </div>
          
          <div class="alert alert-info">
            <h6><i class="fas fa-lightbulb me-2"></i>İpucu</h6>
            <ul class="mb-0">
              <li>Dosya yolu olarak kök dizininden itibaren başlayan yolu giriniz.</li>
              <li>Dosya adını yazarken büyük/küçük harf duyarlılığına dikkat ediniz.</li>
              <li>İzin verilen dosya boyutu sunucu ayarlarına bağlıdır.</li>
            </ul>
          </div>
        </div>
      </div>

      <?php
      $baseDirectory = $_SERVER['DOCUMENT_ROOT'];
      $message = '';
      $error_code = 0;

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dirPath = trim($_POST['dirPath']);
        $itemName = trim($_POST['itemName']);

        // Gerçek tam yol oluşturuluyor
        $fullPath = realpath(rtrim($baseDirectory, '/') . '/' . ltrim($dirPath, '/') . '/' . $itemName);

        // Güvenlik kontrolü: Kök dizinin dışına çıkılamaz
        if ($fullPath && strpos($fullPath, $baseDirectory) === 0) {
          if (file_exists($fullPath) && is_file($fullPath)) {
            // Dosya indirme başlıkları
            $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $mime);
            header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fullPath));
            readfile($fullPath);
            exit;
          } elseif (is_dir($fullPath)) {
            $message = "Bu bir klasördür, dosya indirilemez.";
            $error_code = 1;
          } else {
            $error_code = 404;
            $message = "Dosya bulunamadı.";
          }
        } else {
          $error_code = 403;
          $message = "Geçersiz yol veya erişim izni yok.";
        }
      }
      ?>

      <?php if ($message): ?>
        <div id="message" class="alert <?= $error_code ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show mt-4" role="alert">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <i class="fas <?= $error_code ? 'fa-exclamation-triangle' : 'fa-check-circle' ?> fa-2x me-3"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="alert-heading"><?= $error_code ? 'Hata Oluştu!' : 'İşlem Başarılı!' ?></h5>
              <p class="mb-0"><?= htmlspecialchars($message) ?></p>
              <?php if ($error_code): ?>
                <div class="mt-2 small">
                  <strong>Hata Kodu:</strong> <?= htmlspecialchars($error_code) ?>
                  <?php if ($error_code == 404): ?>
                    <div class="mt-1">
                      <i class="fas fa-lightbulb me-1"></i>
                      <small>Dosya bulunamadı. Lütfen dosya adı ve yolun doğru olduğundan emin olun.</small>
                    </div>
                  <?php elseif ($error_code == 403): ?>
                    <div class="mt-1">
                      <i class="fas fa-lightbulb me-1"></i>
                      <small>Bu işlem için yetkiniz bulunmuyor. Lütfen yöneticinizle iletişime geçin.</small>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <footer class="text-muted text-center mt-5 small">

  <footer class="text-muted text-center py-4 mt-4 border-top">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <p class="mb-0">
            <i class="far fa-copyright me-1"></i> <?= date('Y') ?> <strong>BSD Soft</strong> - Tüm Hakları Saklıdır
            <span class="mx-2">•</span>
            <span id="current-time"></span>
          </p>
          <p class="small text-muted mt-2 mb-0">
            <i class="fas fa-code me-1"></i> Geliştirici: <a href="https://buraksariguzel.com.tr" target="_blank" class="text-decoration-none">Burak Sarıgüzel</a>
          </p>
        </div>
      </div>
    </div>
  </footer>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Sayfa yüklendiğinde yükleme ekranını kaldır
window.addEventListener('load', function() {
  const loadingElement = document.getElementById('loading');
  if (loadingElement) {
    loadingElement.style.opacity = '0';
    setTimeout(() => {
      loadingElement.style.display = 'none';
      // Fade-in efektini tetikle
      document.querySelectorAll('.fade-in').forEach((el) => {
        el.style.opacity = '1';
      });
    }, 300);
  }
  
  // Tarih ve saat güncelleme
  updateDateTime();
  setInterval(updateDateTime, 60000); // Her dakika güncelle
});

// Tarih ve saati güncelle
function updateDateTime() {
  const now = new Date();
  
  // Tarihi güncelle
  const dateOptions = { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  };
  document.getElementById('current-date').textContent = now.toLocaleDateString('tr-TR', dateOptions);
  
  // Saati güncelle
  const timeOptions = { 
    hour: '2-digit', 
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  };
  const timeElement = document.getElementById('current-time');
  if (timeElement) {
    timeElement.textContent = 'Son Güncelleme: ' + now.toLocaleTimeString('tr-TR', timeOptions);
  }
}

// Otomatik olarak uyarıları kapat
setTimeout(function() {
  var alert = document.getElementById('message');
  if (alert) {
    var bsAlert = new bootstrap.Alert(alert);
    setTimeout(function() {
      bsAlert.close();
    }, 5000);
  }
}, 5000);

// Form validation
(function() {
  'use strict';
  var forms = document.querySelectorAll('.needs-validation');
  Array.prototype.slice.call(forms).forEach(function(form) {
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>
