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
  <title>Buraksariguzeldev | Dosya Değişiklik Takip</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4cc9f0;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }
    
    body {
      background-color: #f5f7ff;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    
    .gradient-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 2.5rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 20px 20px;
      box-shadow: 0 4px 20px rgba(67, 97, 238, 0.2);
    }
    
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      transition: var(--transition);
      margin-bottom: 1.5rem;
      overflow: hidden;
    }
    
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      font-weight: 600;
      border: none;
      padding: 1rem 1.5rem;
    }
    
    .btn-primary {
      background: var(--primary-color);
      border: none;
      padding: 0.5rem 1.25rem;
      border-radius: 8px;
      font-weight: 500;
      transition: var(--transition);
    }
    
    .btn-primary:hover {
      background: var(--secondary-color);
      transform: translateY(-1px);
    }
    
    .file-item {
      transition: var(--transition);
      border-left: 3px solid transparent;
    }
    
    .file-item:hover {
      background-color: rgba(67, 97, 238, 0.05);
      border-left-color: var(--primary-color);
    }
    
    .file-icon {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      margin-right: 1rem;
      color: white;
      flex-shrink: 0;
    }
    
    .folder-icon {
      background: linear-gradient(135deg, #4cc9f0, #4895ef);
    }
    
    .file-type-icon {
      background: linear-gradient(135deg, #7209b7, #b5179e);
    }
    
    .stats-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      text-align: center;
      transition: var(--transition);
    }
    
    .stats-card:hover {
      transform: translateY(-5px);
    }
    
    .stats-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary-color);
      margin: 0.5rem 0;
    }
    
    .stats-label {
      color: #6c757d;
      font-size: 0.9rem;
    }
    
    .last-updated {
      font-size: 0.8rem;
      color: #6c757d;
    }
    
    .loading {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.8);
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }
    
    .spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 1rem;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .badge-date {
      background: rgba(67, 97, 238, 0.1);
      color: var(--primary-color);
      padding: 0.35rem 0.65rem;
      border-radius: 50px;
      font-weight: 500;
    }
    
    .file-path {
      font-size: 0.8rem;
      color: #6c757d;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 300px;
    }
    
    @media (max-width: 768px) {
      .gradient-header {
        padding: 1.5rem 0;
        border-radius: 0;
      }
      
      .card {
        border-radius: 0;
      }
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
                        <h5 class="mb-1 fw-bold text-dark">Dosya Değişiklik Takip</h5>
                        <p class="text-muted small mb-0">Dosya ve klasörlerinizdeki son değişiklikleri takip edin</p>
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
  <!-- Tarih Filtreleme Formu Kartı -->
  <div class="card mb-4 animate__animated animate__fadeIn">
    <div class="card-body p-4">
      <h5 class="card-title mb-4"><i class="fas fa-calendar-alt me-2"></i> Tarih Filtreleme</h5>
      <form method="get" class="row g-3">
        <div class="col-md-4">
          <label for="search_date" class="form-label">Tarih Seçin</label>
          <div class="input-group">
            <span class="input-group-text"><i class="far fa-calendar"></i></span>
            <input type="date" id="search_date" name="search_date" class="form-control form-control-lg"
              value="<?= htmlspecialchars($_GET['search_date'] ?? date('Y-m-d')) ?>">
          </div>
        </div>
        <div class="col-md-8 d-flex align-items-end">
          <div class="btn-group w-100" role="group">
            <button type="submit" class="btn btn-primary px-4">
              <i class="fas fa-filter me-2"></i> Filtrele
            </button>
            <button type="submit" name="today" onclick="setToday()" class="btn btn-outline-primary">
              <i class="fas fa-calendar-day me-2"></i> Bugün
            </button>
            <button type="submit" name="clear" onclick="clearDate()" class="btn btn-outline-secondary">
              <i class="fas fa-calendar-times me-2"></i> Tüm Zamanlar
            </button>
          </div>
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
          $filePath = $dir . '/' . $file;
          $mtime = filemtime($filePath);
          $isDir = is_dir($filePath);
          if (!$searchDate || date('Y-m-d', $mtime) === $searchDate) {
            $fileList[] = ['name'=>$file,'path'=>$filePath,'mtime'=>$mtime,'is_dir'=>$isDir];
            $isDir ? $folderCount++ : $fileCount++;
          }
          if ($isDir) scanDirectory($filePath, $fileList, $fileCount, $folderCount, $searchDate);
        }
      }
    }

    scanDirectory($baseDirectory, $fileList, $fileCount, $folderCount, $searchDate);
    usort($fileList, fn($a,$b) => $b['mtime'] - $a['mtime']);
  ?>

  <!-- İstatistikler Kartı -->
  <div class="row mb-4 animate__animated animate__fadeIn">
    <div class="col-md-12 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i> İstatistikler</h5>
        <span class="badge bg-primary bg-opacity-10 text-primary p-2">
          <?php if ($searchDate): ?>
            <i class="fas fa-calendar-check me-1"></i> <?= date('d.m.Y', strtotime($searchDate)) ?>
          <?php else: ?>
            <i class="fas fa-infinity me-1"></i> Tüm Zamanlar
          <?php endif; ?>
        </span>
      </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="stats-card h-100">
        <div class="stats-icon mb-2">
          <i class="fas fa-file-alt fa-2x text-primary"></i>
        </div>
        <div class="stats-number"><?= $fileCount ?></div>
        <div class="stats-label">Toplam Dosya</div>
      </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="stats-card h-100">
        <div class="stats-icon mb-2">
          <i class="fas fa-folder fa-2x text-warning"></i>
        </div>
        <div class="stats-number"><?= $folderCount ?></div>
        <div class="stats-label">Toplam Klasör</div>
      </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="stats-card h-100">
        <div class="stats-icon mb-2">
          <i class="fas fa-database fa-2x text-info"></i>
        </div>
        <div class="stats-number"><?= $fileCount + $folderCount ?></div>
        <div class="stats-label">Toplam Öğe</div>
      </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="stats-card h-100">
        <div class="stats-icon mb-2">
          <i class="fas fa-clock fa-2x text-success"></i>
        </div>
        <div class="stats-number"><?= date('H:i') ?></div>
        <div class="stats-label">Son Güncelleme</div>
      </div>
    </div>
  </div>

  <!-- Listeleme Kartı -->
  <div class="card animate__animated animate__fadeIn">
    <div class="card-body p-0">
      <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i> Detaylı Liste</h5>
        <span class="badge bg-primary bg-opacity-10 text-primary">
          <?= count($fileList) ?> öğe
        </span>
      </div>
      
      <?php if (empty($fileList)): ?>
        <div class="text-center py-5">
          <div class="mb-3">
            <i class="fas fa-inbox fa-4x text-muted opacity-25"></i>
          </div>
          <h5>Sonuç bulunamadı</h5>
          <p class="text-muted">Seçili tarihe ait değişiklik bulunamadı.</p>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th>İsim</th>
                <th>Konum</th>
                <th>Değişiklik Tarihi</th>
                <th class="text-end">Önce</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($fileList as $file): 
                $icon = $file['is_dir'] ? "fa-folder text-warning" : "fa-file text-primary";
                $date = date("d.m.Y", $file['mtime']);
                $hour = date("H:i", $file['mtime']);
                $path = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($file['path']));
                $path = $path ?: '/';
              ?>
                <tr class="file-item">
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="file-icon <?= $file['is_dir'] ? 'folder-icon' : 'file-type-icon' ?>">
                        <i class="fas <?= $file['is_dir'] ? 'fa-folder' : 'fa-file' ?>"></i>
                      </div>
                      <div>
                        <div class="fw-medium"><?= htmlspecialchars($file['name']) ?></div>
                        <div class="file-path" title="<?= htmlspecialchars($path) ?>">
                          <i class="fas fa-folder-open text-muted me-1"></i>
                          <?= htmlspecialchars($path) ?>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-light text-dark" data-bs-toggle="tooltip" title="<?= htmlspecialchars($file['path']) ?>">
                      <i class="fas fa-map-marker-alt me-1"></i>
                      <?= $file['is_dir'] ? 'Klasör' : 'Dosya' ?>
                    </span>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <span class="text-nowrap"><?= $date ?></span>
                      <small class="text-muted"><?= $hour ?></small>
                    </div>
                  </td>
                  <td class="text-end">
                    <span class="days-passed badge bg-light text-dark" data-time="<?= $file['mtime'] ?>">
                      <i class="fas fa-spinner fa-spin me-1"></i>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center mt-5 pt-4 pb-3 border-top">
    <p class="mb-0 text-muted">
      <i class="fas fa-code me-1"></i> 
      <span class="d-inline-block">
        &copy; <?= date('Y') ?> buraksariguzeldev 
        <span class="d-none d-md-inline">-</span> 
        <span class="d-block d-md-inline">Tarih bazlı dosya izleme paneli</span>
      </span>
    </p>
  </footer>

</div>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Sayfa yüklendiğinde çalışacak fonksiyonlar
  document.addEventListener('DOMContentLoaded', function() {
    // Tooltip'leri etkinleştir
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Yükleme ekranını gizle
    setTimeout(() => {
      document.getElementById('loading').style.display = 'none';
    }, 500);
    
    // Dinamik zaman güncellemelerini başlat
    updateTimes();
    setInterval(updateTimes, 1000);
    
    // Form gönderimini yönet
    const filterForm = document.querySelector('form');
    if (filterForm) {
      filterForm.addEventListener('submit', function() {
        document.getElementById('loading').style.display = 'flex';
      });
    }
  });
  
  // Dinamik zaman hesaplama
  function updateTimes() {
    const now = Date.now();
    document.querySelectorAll('.days-passed').forEach(el => {
      if (!el.dataset.time) return;
      
      const mtime = parseInt(el.dataset.time) * 1000;
      const diff = now - mtime;
      const d = Math.floor(diff / 86400000);
      const h = Math.floor((diff % 86400000) / 3600000);
      const m = Math.floor((diff % 3600000) / 60000);
      
      let timeAgo = '';
      if (d > 0) {
        timeAgo = `${d} gün ${h} saat önce`;
      } else if (h > 0) {
        timeAgo = `${h} saat ${m} dakika önce`;
      } else {
        timeAgo = `${m} dakika önce`;
      }
      
      el.innerHTML = `<i class="far fa-clock me-1"></i> ${timeAgo}`;
      el.classList.add('badge-date');
    });
  }
  
  // Tarih temizleme
  function clearDate() {
    const dateInput = document.getElementById('search_date');
    if (dateInput) {
      dateInput.value = '';
    }
  }
  
  // Bugünün tarihini ayarla
  function setToday() {
    const dateInput = document.getElementById('search_date');
    if (dateInput) {
      dateInput.value = new Date().toISOString().slice(0, 10);
    }
  }
  
  // Sayfa yüklendiğinde bugünün tarihini ayarla
  document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('search_date');
    if (dateInput && !dateInput.value) {
      dateInput.value = new Date().toISOString().slice(0, 10);
    }
  });
</script>

</body>
</html>
