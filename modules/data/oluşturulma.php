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
  <title>Buraksariguzeldev - Oluşturulma Zamanı Takibi</title>
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
    .file-item {
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
    }
    .file-item:hover {
      background-color: #f8f9fc;
      border-left-color: var(--primary-color);
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
    .folder-icon {
      color: #f6c23e;
    }
    .badge-date {
      background-color: #eaecf4;
      color: #5a5c69;
      font-weight: 500;
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
  </style>
</head>
<body>


<div class="container py-3">

<div class="container py-4">
<div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="fas fa-file-alt fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Oluşturulma Zamanı Takibi</h5>
                        <p class="text-muted small mb-0">Dosya ve klasörlerin oluşturulma tarihlerini takip edin</p>
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

  <!-- Filtreleme Kartı -->
  <div class="card shadow-sm border-0 mb-4 fade-in">
    <div class="card-header bg-white border-0 py-3">
      <h5 class="mb-0"><i class="fas fa-sliders-h me-2 text-primary"></i>Filtreleme Seçenekleri</h5>
    </div>
    <div class="card-body">
      <form method="get" class="row g-3 align-items-center">
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-text bg-light border-0"><i class="far fa-calendar-alt text-primary"></i></span>
            <input type="date" id="search_date" name="search_date" class="form-control border-start-0"
              value="<?= htmlspecialchars($_GET['search_date'] ?? date('Y-m-d')) ?>">
          </div>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-filter me-1"></i> Filtrele
          </button>
        </div>
        <div class="col-auto">
          <button type="submit" name="clear" onclick="clearDate()" class="btn btn-outline-secondary">
            <i class="fas fa-calendar-times me-1"></i> Tüm Zamanlar
          </button>
        </div>
        <div class="col-auto">
          <button type="submit" name="today" onclick="setToday()" class="btn btn-outline-primary">
            <i class="fas fa-calendar-day me-1"></i> Bugün
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- PHP Veri Hazırlığı -->
  <?php
    date_default_timezone_set('Europe/Istanbul');
    $baseDirectory = $_SERVER['DOCUMENT_ROOT'];
    $searchDate = $_GET['search_date'] ?? null;
    if (isset($_GET['today'])) $searchDate = date('Y-m-d');

    $fileList = [];
    $fileCount = 0;
    $folderCount = 0;

    function scanDirectory($dir, &$fileList, &$fileCount, &$folderCount, $searchDate) {
      $files = scandir($dir);
      foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
          $path = $dir . '/' . $file;
          $ctime = filectime($path);
          $isDir = is_dir($path);
          if (!$searchDate || date('Y-m-d', $ctime) === $searchDate) {
            $fileList[] = ['name'=>$file, 'path'=>$path, 'ctime'=>$ctime, 'is_dir'=>$isDir];
            $isDir ? $folderCount++ : $fileCount++;
          }
          if ($isDir) scanDirectory($path, $fileList, $fileCount, $folderCount, $searchDate);
        }
      }
    }

    scanDirectory($baseDirectory, $fileList, $fileCount, $folderCount, $searchDate);
    usort($fileList, fn($a,$b) => $b['ctime'] - $a['ctime']);
  ?>

  <!-- İstatistik Paneli -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card h-100 stat-card fade-in" style="animation-delay: 0.1s">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-uppercase text-muted mb-2">Toplam Dosya</h6>
              <h3 class="mb-0"><?= $fileCount ?></h3>
            </div>
            <div class="file-icon">
              <i class="fas fa-file-alt fa-2x"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100 stat-card fade-in" style="animation-delay: 0.2s">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-uppercase text-muted mb-2">Toplam Klasör</h6>
              <h3 class="mb-0"><?= $folderCount ?></h3>
            </div>
            <div class="file-icon">
              <i class="fas fa-folder fa-2x text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100 stat-card fade-in" style="animation-delay: 0.3s">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-uppercase text-muted mb-2">Tarih Aralığı</h6>
              <h6 class="mb-0">
                <?php if ($searchDate): ?>
                  <i class="fas fa-calendar-check me-2"></i><?= date('d.m.Y', strtotime($searchDate)) ?>
                <?php else: ?>
                  <i class="fas fa-infinity me-2"></i>Tüm Zamanlar
                <?php endif; ?>
              </h6>
            </div>
            <div class="file-icon">
              <i class="far fa-calendar-alt fa-2x text-info"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Dosya Listesi -->
  <div class="card shadow-sm border-0 fade-in" style="animation-delay: 0.2s">
    <div class="card-header bg-white border-0 py-3">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list-ul me-2 text-primary"></i>Oluşturulma Listesi</h5>
        <span class="badge bg-primary"><?= count($fileList) ?> öğe</span>
      </div>
    </div>
    <div class="card-body p-0">
      <?php if (empty($fileList)): ?>
        <div class="text-center py-5">
          <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
          <p class="text-muted mb-0">Gösterilecek dosya veya klasör bulunamadı</p>
        </div>
      <?php else: ?>
        <div class="list-group list-group-flush">
          <?php foreach ($fileList as $index => $file):
            $icon = $file['is_dir'] ? "fa-folder" : "fa-file";
            $date = date("d.m.Y", $file['ctime']);
            $hour = date("H:i:s", $file['ctime']);
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $iconClass = $file['is_dir'] ? 'folder-icon' : 'text-primary';
          ?>
            <div class="list-group-item list-group-item-action file-item py-3" style="animation-delay: <?= ($index % 10) * 0.1 + 0.2 ?>s">
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <i class="fas <?= $icon ?> fa-2x <?= $iconClass ?>"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <h6 class="mb-1 text-truncate" style="max-width: 70%"><?= htmlspecialchars($file['name']) ?></h6>
                    <small class="text-muted ms-2">
                      <i class="far fa-calendar-plus me-1"></i><?= $date ?>
                      <span class="mx-2">•</span>
                      <i class="far fa-clock me-1"></i><?= $hour ?>
                    </small>
                  </div>
                  <div class="d-flex flex-wrap align-items-center mt-1">
                    <small class="badge bg-light text-dark me-2 mb-1">
                      <i class="fas <?= $file['is_dir'] ? 'fa-folder' : 'fa-file' ?> me-1"></i>
                      <?= $file['is_dir'] ? 'Klasör' : (strtoupper($fileExt) ?: 'Dosya') ?>
                    </small>
                    <small class="text-muted me-2 mb-1">
                      <i class="fas fa-map-marker-alt me-1"></i>
                      <?= htmlspecialchars(str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($file['path']))) ?>
                    </small>
                  </div>
                </div>
                <div class="text-end ms-3">
                  <small class="badge badge-date">
                    <i class="fas fa-history me-1"></i>
                    <span class="days-passed" data-time="<?= $file['ctime'] ?>">...</span>
                  </small>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
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
          <i class="fas fa-info-circle me-1"></i> Oluşturulma Tarihi Takip Modülü v1.0
        </div>
      </div>
    </div>
  </footer>
</div>

<!-- JS: Dinamik Zaman Hesabı -->
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

  // Tarih formatlama fonksiyonu
  function formatDate(date) {
    return new Date(date).toLocaleDateString('tr-TR', {
      day: '2-digit',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  }

  // Dinamik zaman güncelleme
  function updateTimes() {
    const now = Date.now();
    document.querySelectorAll('.days-passed').forEach(el => {
      const ctime = parseInt(el.dataset.time) * 1000;
      const diff = now - ctime;
      
      const d = Math.floor(diff / 86400000);
      const h = Math.floor((diff % 86400000) / 3600000);
      const m = Math.floor((diff % 3600000) / 60000);
      const s = Math.floor((diff % 60000) / 1000);
      
      let timeString = '';
      if (d > 7) {
        timeString = `${d} gün önce`;
      } else if (d > 0) {
        timeString = `${d}g ${h}sa önce`;
      } else if (h > 0) {
        timeString = `${h}sa ${m}d önce`;
      } else {
        timeString = `${m}d ${s}sn önce`;
      }
      
      el.textContent = timeString;
      el.title = formatDate(ctime);
    });
  }

  // Tarih temizleme
  function clearDate() {
    document.getElementById('search_date').value = '';
  }

  // Bugünün tarihini ayarla
  function setToday() {
    document.getElementById('search_date').value = new Date().toISOString().slice(0, 10);
  }

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
    updateTimes();
    setInterval(updateTimes, 1000);
    
    // Tooltip'leri etkinleştir
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>

</body>
</html>
