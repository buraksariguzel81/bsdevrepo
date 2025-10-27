<?php
session_start();

// ROL KONTROL
$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
    include($rol_kontrol_path);
    if (function_exists('rol_kontrol')) {
        rol_kontrol(1);
    }
}

// Navigasyon
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html lang="tr" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dosya Uzantıları | BSD Soft</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --bs-primary: #4e73df;
      --bs-secondary: #858796;
      --bs-success: #1cc88a;
      --bs-info: #36b9cc;
      --bs-warning: #f6c23e;
      --bs-danger: #e74a3b;
    }
    
    body {
      background-color: #f8f9fc;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    
    .card {
      border: none;
      border-radius: 0.5rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      transition: transform 0.2s;
    }
    
    .card:hover {
      transform: translateY(-2px);
    }
    
    .card-header {
      background: linear-gradient(135deg, var(--bs-primary) 0%, #224abe 100%);
      color: white;
      border-bottom: none;
      border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
    .table th {
      background-color: #f8f9fc;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      color: #4e73df;
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(78, 115, 223, 0.05);
    }
    
    .badge {
      font-weight: 500;
      padding: 0.35em 0.65em;
    }
    
    .file-icon {
      width: 36px;
      height: 36px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      background-color: #e3e6f0;
      color: var(--bs-primary);
      margin-right: 10px;
    }
    
    .file-path {
      font-family: 'Consolas', 'Monaco', monospace;
      font-size: 0.85rem;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #b8bfd6;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #9fa7c5;
    }
    
    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
      animation: fadeIn 0.3s ease-out forwards;
    }
  </style>
</head>
<body>



<div class="container py-4 fade-in">



    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="fas fa-file-alt fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dosya Uzantıları</h5>
                        <p class="text-muted small mb-0">Sistemdeki tüm dosyalar uzantılarına göre listelenmiştir</p>
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









    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
          <h5 class="text-primary mb-2"><i class="fas fa-info-circle me-2"></i>Bilgilendirme</h5>
          <p class="text-muted mb-0">
            Bu sayfada sistemde bulunan tüm dosyalar, uzantılarına göre otomatik olarak gruplandırılmıştır. 
            Aşağıdaki arama kutusunu kullanarak istediğiniz dosya uzantısını kolayca bulabilirsiniz.
          </p>
        </div>
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="extensionSearch" class="form-control" 
                   placeholder="Dosya uzantısı ara... (örn: php, jpg, pdf)" 
                   onkeyup="searchExtensions()" autofocus>
            <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Dosya Uzantıları Tablosu -->
  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="extensionTable" class="table table-hover align-middle mb-0">
          <thead class="table-light sticky-top">
            <tr>
              <th class="ps-4">UZANTI</th>
              <th class="text-center">DOSYA SAYISI</th>
              <th class="text-center">TOPLAM BOYUT</th>
              <th class="pe-4">DETAYLAR</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $baseDirectory = $_SERVER['DOCUMENT_ROOT'];

            function formatSize($size) {
              $units = ['B', 'KB', 'MB', 'GB', 'TB'];
              $unitIndex = 0;
              while ($size >= 1024 && $unitIndex < count($units) - 1) {
                $size /= 1024;
                $unitIndex++;
              }
              return round($size, 2) . ' ' . $units[$unitIndex];
            }

            function scanDirectory($dir) {
              global $extensionList;
              $files = scandir($dir);
              foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                $relativePath = str_replace($GLOBALS['baseDirectory'] . DIRECTORY_SEPARATOR, '', $filePath);
                if (is_file($filePath)) {
                  $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                  $size = filesize($filePath);
                  if ($extension) {
                    $extensionList[$extension]['files'][] = ['path' => $relativePath, 'size' => $size];
                    $extensionList[$extension]['total_size'] = ($extensionList[$extension]['total_size'] ?? 0) + $size;
                  }
                }
                if (is_dir($filePath)) scanDirectory($filePath);
              }
            }

            $extensionList = [];
            scanDirectory($baseDirectory);
            ksort($extensionList);

            foreach ($extensionList as $ext => $data):
            ?>
              <tr class="extension-row">
                <td class="ps-4">
                  <div class="d-flex align-items-center">
                    <div class="file-icon">
                      <i class="fas fa-file-code"></i>
                    </div>
                    <div>
                      <span class="fw-bold">.<?= htmlspecialchars($ext) ?: 'uzantısız' ?></span>
                      <div class="small text-muted">
                        <i class="fas fa-file me-1"></i> <?= count($data['files']) ?> dosya
                      </div>
                    </div>
                  </div>
                </td>
                <td class="text-center fw-bold">
                  <?= number_format(count($data['files'])) ?>
                </td>
                <td class="text-center">
                  <span class="badge bg-light text-dark">
                    <i class="fas fa-hdd me-1"></i> <?= formatSize($data['total_size']) ?>
                  </span>
                </td>
                <td class="pe-4">
                  <div class="accordion" id="accordion-<?= md5($ext) ?>">
                    <div class="accordion-item border-0">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-light p-2" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapse-<?= md5($ext) ?>" 
                                aria-expanded="false" aria-controls="collapse-<?= md5($ext) ?>">
                          <small class="text-muted toggle-text">Dosyaları Göster</small>
                          <i class="fas fa-chevron-down ms-2 toggle-icon" style="font-size: 0.8rem; transition: transform 0.2s;"></i>
                        </button>
                      </h2>
                      <div id="collapse-<?= md5($ext) ?>" class="accordion-collapse collapse" 
                           data-bs-parent="#accordion-<?= md5($ext) ?>">
                        <div class="accordion-body p-2 bg-white">
                          <ul class="list-unstyled mb-0">
                            <?php foreach ($data['files'] as $file): ?>
                              <li class="py-1 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                  <span class="file-path text-truncate me-2" style="max-width: 70%;">
                                    <i class="fas fa-file text-muted me-1"></i>
                                    <?= htmlspecialchars($file['path']) ?>
                                  </span>
                                  <span class="badge bg-light text-dark file-size-badge">
                                    <?= formatSize($file['size']) ?>
                                  </span>
                                </div>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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
          <i class="fas fa-info-circle me-1"></i> Dosya Uzantı Yöneticisi v1.0
        </div>
      </div>
    </div>
  </footer>

</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Sayfa yüklendiğinde yükleme ekranını kaldır
  document.addEventListener('DOMContentLoaded', function() {
    // Sayfa yüklendikten sonra loading ekranını kaldır
    const loadingElement = document.getElementById('loading');
    if (loadingElement) {
      loadingElement.style.opacity = '0';
      setTimeout(() => {
        loadingElement.style.display = 'none';
      }, 300);
    }
    
    // Fade-in efektini tetikle
    document.querySelectorAll('.fade-in').forEach((el) => {
      el.style.opacity = '1';
    });
    
    // Accordion butonlarına tıklama olayını ekle
    document.querySelectorAll('.accordion-button').forEach(button => {
      button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-bs-target');
        const target = document.querySelector(targetId);
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        
        // Tüm accordion'ları kapat
        document.querySelectorAll('.accordion-collapse').forEach(collapse => {
          if (collapse.id !== targetId.replace('#', '')) {
            collapse.classList.remove('show');
            const btn = collapse.previousElementSibling?.querySelector('.accordion-button');
            if (btn) {
              btn.setAttribute('aria-expanded', 'false');
              btn.querySelector('.toggle-text').textContent = 'Dosyaları Göster';
              btn.querySelector('.toggle-icon').style.transform = 'rotate(0deg)';
            }
          }
        });
        
        // Tıklanan accordion'u aç/kapat
        if (!isExpanded) {
          target.classList.add('show');
          this.setAttribute('aria-expanded', 'true');
          this.querySelector('.toggle-text').textContent = 'Dosyaları Gizle';
          this.querySelector('.toggle-icon').style.transform = 'rotate(180deg)';
        } else {
          target.classList.remove('show');
          this.setAttribute('aria-expanded', 'false');
          this.querySelector('.toggle-text').textContent = 'Dosyaları Göster';
          this.querySelector('.toggle-icon').style.transform = 'rotate(0deg)';
        }
      });
    });
  });

  // Uzantı arama fonksiyonu
  function searchExtensions() {
    const input = document.getElementById("extensionSearch").value.toLowerCase().trim();
    const rows = document.getElementsByClassName("extension-row");
    
    if (input === '') {
      // Eğer arama kutusu boşsa tüm satırları göster
      for (let row of rows) {
        row.style.display = '';
      }
      return;
    }
    
    // Arama yap
    for (let row of rows) {
      const ext = row.getElementsByTagName("td")[0]?.textContent.toLowerCase().replace('.', '').trim() || "";
      row.style.display = ext.includes(input) ? '' : 'none';
    }
  }
  
  // Arama kutusunu temizle
  function clearSearch() {
    const searchInput = document.getElementById("extensionSearch");
    searchInput.value = '';
    searchExtensions();
    searchInput.focus();
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
  
  // Mevcut tarihi güncelle ve arama kutusuna odaklan
  updateCurrentDate();
  const searchInput = document.getElementById("extensionSearch");
  if (searchInput) {
    searchInput.focus();
  }
</script>

</body>
</html>
