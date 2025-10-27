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

// Process file extensions before any output
$baseDirectory = $_SERVER['DOCUMENT_ROOT'];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDirectory));

$extensions = [];
$totalSize = 0;

foreach ($files as $file) {
    if ($file->isFile()) {
        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        $size = $file->getSize();
        $totalSize += $size;

        $extensions[$ext]['count'] = ($extensions[$ext]['count'] ?? 0) + 1;
        $extensions[$ext]['size']  = ($extensions[$ext]['size']  ?? 0) + $size;
    }
}

function formatSize($size) {
    $units = ['B','KB','MB','GB','TB'];
    $i = 0;
    while ($size >= 1024 && $i < count($units) - 1) {
        $size /= 1024;
        $i++;
    }
    return round($size, 2) . ' ' . $units[$i];
}

function getFileIcon($ext) {
    $iconMap = [
        'html'=>'fab fa-html5', 'css'=>'fab fa-css3', 'js'=>'fab fa-js',
        'php'=>'fab fa-php', 'jpg'=>'far fa-file-image', 'png'=>'far fa-file-image',
        'json'=>'far fa-file-code', 'pdf'=>'far fa-file-pdf', 'zip'=>'far fa-file-archive',
        'mp3'=>'fas fa-file-audio', 'doc'=>'far fa-file-word', 'xls'=>'far fa-file-excel'
    ];
    return $iconMap[$ext] ?? 'far fa-file';
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Uzantı Analizi - BSD Soft</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #1cc88a;
      --dark-color: #5a5c69;
    }
    body {
      background-color: #f8f9fc;
      font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    .header-gradient {
      background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
      color: white;
      border-radius: 0 0 15px 15px;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .card {
      border: none;
      border-radius: 0.65rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 1.5rem;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }
    .stat-card {
      border-left: 4px solid var(--primary-color);
    }
    .file-icon {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      background-color: #e3e6f0;
      color: var(--primary-color);
    }
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    .spinner {
      width: 3rem;
      height: 3rem;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: fadeIn 0.5s ease-out forwards;
    }
    .table th {
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
  </style>
</head>
<body>

<div class="container py-4">
<div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="fas fa-file-alt fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Uzantı İstatistikleri</h5>
                        <p class="text-muted small mb-0">Sistemdeki dosya uzantılarının sayısal durumunu ve boş olanları buradan görüntüleyebilirsiniz.</p>
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



<div class="container py-3">
  <div class="card shadow-sm border-0 fade-in">
    <div class="card-header bg-white border-0 py-3">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Uzantı İstatistikleri</h5>
        <div class="small text-muted">
          <i class="fas fa-info-circle me-1"></i> Toplam <?= count($extensions) ?> farklı uzantı tespit edildi
        </div>
      </div>
    </div>

      <?php
      // Functions and variables are now defined at the top of the file
      ?>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">Uzantı</th>
                <th class="text-end pe-4">Dosya Sayısı</th>
                <th class="text-end pe-4">Toplam Boyut</th>
                <th class="text-end pe-4">Oran</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $totalFiles = array_sum(array_column($extensions, 'count'));
              foreach ($extensions as $ext => $data): 
                $icon = getFileIcon($ext);
                $percentage = ($data['count'] / $totalFiles) * 100;
              ?>
              <tr class="file-item">
                <td class="ps-4">
                  <div class="d-flex align-items-center">
                    <div class="file-icon me-3">
                      <i class="<?= $icon ?> fa-lg"></i>
                    </div>
                    <div>
                      <div class="fw-bold">.<?= htmlspecialchars($ext) ?: 'uzantısız' ?></div>
                      <small class="text-muted"><?= $data['count'] > 1 ? $data['count'].' dosya' : '1 dosya' ?></small>
                    </div>
                  </div>
                </td>
                <td class="text-end pe-4 fw-bold"><?= number_format($data['count']) ?></td>
                <td class="text-end pe-4"><?= formatSize($data['size']) ?></td>
                <td class="pe-4">
                  <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percentage ?>%" 
                         aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <small class="text-muted">%<?= number_format($percentage, 1) ?></small>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-light">
              <tr>
                <th class="ps-4"><i class="fas fa-calculator me-2"></i>Toplam</th>
                <th class="text-end pe-4"><?= number_format($totalFiles) ?></th>
                <th class="text-end pe-4"><?= formatSize($totalSize) ?></th>
                <th class="pe-4">%100</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center text-muted py-4 mt-5 small">
    <div class="container">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="mb-2 mb-md-0">
          <span class="d-inline-block me-2">
            <i class="fas fa-code me-1"></i> Burak Sarıgüzel
          </span>
          <span class="d-none d-md-inline">•</span>
          <span class="d-block d-md-inline-block ms-md-2">
            <i class="far fa-calendar-alt me-1"></i> <?= date('Y') ?>
          </span>
        </div>
        <div>
          <i class="fas fa-info-circle me-1"></i> Uzantı Analiz Paneli v1.0
        </div>
      </div>
    </div>
  </footer>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Sayfa yüklendiğinde yükleme ekranını kaldır
  window.addEventListener('load', function() {
    setTimeout(function() {
      document.getElementById('loading').style.display = 'none';
      // Fade-in efektini tetikle
      document.querySelectorAll('.fade-in').forEach((el, index) => {
        el.style.opacity = '1';
      });
    }, 500);
  });

  // Mevcut tarihi güncelle
  function updateCurrentDate() {
    const now = new Date();
    const options = { 
      weekday: 'long', 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    };
    document.getElementById('current-date').textContent = now.toLocaleDateString('tr-TR', options);
  }

  // Sayfa yüklendiğinde çalıştır
  document.addEventListener('DOMContentLoaded', function() {
    updateCurrentDate();
  });
</script>

</body>
</html>
