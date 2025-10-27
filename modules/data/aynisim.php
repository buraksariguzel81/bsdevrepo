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

// Initialize variables
$duplicateFolders = 0;
$duplicateFiles = 0;

// Scan for duplicates
$baseDirectory = $_SERVER['DOCUMENT_ROOT'];
$fileList = [];

function scanDirectory($dir) {
    global $fileList, $baseDirectory;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
        $relativePath = str_replace($baseDirectory . DIRECTORY_SEPARATOR, '', $filePath);
        if (is_file($filePath) || is_dir($filePath)) {
            $fileList[$file][] = $relativePath;
        }
        if (is_dir($filePath)) {
            scanDirectory($filePath);
        }
    }
}

// Perform the scan
scanDirectory($baseDirectory);

// Filter duplicates and count them
$duplicates = array_filter($fileList, fn($paths) => count($paths) > 1);

// Count duplicate files and folders
foreach ($duplicates as $name => $paths) {
    $isDir = is_dir($baseDirectory . DIRECTORY_SEPARATOR . $paths[0]);
    if ($isDir) {
        $duplicateFolders++;
    } else {
        $duplicateFiles++;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aynı İsimli Dosya ve Klasörler - BSD Soft</title>
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
    
    .list-group-item {
      border-left: none;
      border-right: none;
      padding: 1rem 1.5rem;
      transition: background-color 0.2s;
    }
    
    .list-group-item:hover {
      background-color: rgba(0, 0, 0, 0.03);
    }
    
    .alert {
      border: none;
      border-left: 0.25rem solid;
      border-radius: 0.35rem;
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
<body>

<div class="container py-5 ">

<div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-files text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Aynı İsimli Dosya ve Klasörler</h5>
                        <p class="text-muted small mb-0">
                            <span class="me-3"><i class="bi bi-folder me-1"></i> Aynı İsimli Klasör: <strong><?= $duplicateFolders ?></strong></span>
                            <span><i class="bi bi-file-earmark me-1"></i> Aynı İsimli Dosya: <strong><?= $duplicateFiles ?></strong></span>
                        </p>
                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="printBtn">
                        <i class="bi bi-printer me-1"></i> Yazdır
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="exportBtn">
                        <i class="bi bi-download me-1"></i> Dışa Aktar
                    </button>
                </div>
            </div>
        </div>
    </div>




<!-- Loading Screen -->
<div id="loading" class="position-fixed w-100 h-100 bg-white d-flex justify-content-center align-items-center" style="z-index: 9999;">
  <div class="text-center">
    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
      <span class="visually-hidden">Yükleniyor...</span>
    </div>
    <h5 class="text-primary">Dosya ve Klasörler Taranıyor...</h5>
  </div>
</div>


  
  <div class="card shadow-sm mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-copy me-2"></i>
        Aynı İsimlere Sahip Öğeler
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
        <strong>Bilgi!</strong> Sistemde aynı isimle birden fazla dosya veya klasör varsa aşağıda listelenir.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
      </div>

      <?php
      // Using the already scanned duplicates from the top of the file

      if ($duplicates):
      ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th width="40"></th>
              <th>Dosya/Klasör Adı</th>
              <th>Konumlar</th>
              <th class="text-end">Sayı</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($duplicates as $name => $paths): ?>
              <tr class="border-top">
                <td class="text-center">
                  <i class="<?= is_dir($baseDirectory . DIRECTORY_SEPARATOR . $paths[0]) ? 'fas fa-folder text-warning' : 'fas fa-file text-primary' ?> fa-lg"></i>
                </td>
                <td>
                  <strong class="d-block"><?= htmlspecialchars($name) ?></strong>
                  <small class="text-muted">
                    <?= is_dir($baseDirectory . DIRECTORY_SEPARATOR . $paths[0]) ? 'Klasör' : 'Dosya' ?>
                    • <?= count($paths) ?> farklı konum
                  </small>
                </td>
                <td>
                  <div class="list-group list-group-flush">
                    <?php foreach ($paths as $path): ?>
                      <div class="list-group-item bg-transparent p-1 border-0">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        <span class="text-muted small"><?= htmlspecialchars($path) ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </td>
                <td class="text-end">
                  <span class="badge bg-primary rounded-pill"><?= count($paths) ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php else: ?>
        <div class="alert alert-success mt-4">
          <i class="fas fa-check-circle me-2"></i> Her şey yolunda! Sistemde aynı isimli dosya veya klasör bulunamadı.
        </div>
      <?php endif; ?>

    </div>
  </div>

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
  var alert = document.querySelector('.alert');
  if (alert) {
    var bsAlert = new bootstrap.Alert(alert);
    setTimeout(function() {
      bsAlert.close();
    }, 5000);
  }
}, 5000);
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/footer.php'; ?>

</body>
</html>
