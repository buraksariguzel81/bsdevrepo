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
  <title>Dosya ve Klasör İstatistikleri - BSD Soft</title>
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
    }
    
    .card-body {
      padding: 2rem;
    }
    
    .table {
      margin-bottom: 0;
      border-collapse: separate;
      border-spacing: 0 0.5rem;
    }
    
    .table thead th {
      background-color: var(--secondary-color);
      border: none;
      padding: 1rem 1.5rem;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
      color: #6e707e;
    }
    
    .table tbody tr {
      background-color: white;
      transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
      transform: translateX(5px);
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    }
    
    .table td {
      padding: 1.25rem 1.5rem;
      vertical-align: middle;
      border: none;
      border-top: 1px solid #e3e6f0;
    }
    
    .table td:first-child {
      border-top-left-radius: 0.5rem;
      border-bottom-left-radius: 0.5rem;
    }
    
    .table td:last-child {
      border-top-right-radius: 0.5rem;
      border-bottom-right-radius: 0.5rem;
    }
    
    .badge {
      padding: 0.5em 0.8em;
      font-weight: 600;
      border-radius: 0.35rem;
    }
    
    .text-primary {
      color: var(--primary-color) !important;
    }
    
    .bg-primary {
      background-color: var(--primary-color) !important;
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
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
      
      .table td, .table th {
        padding: 0.75rem;
      }
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
                        <h5 class="mb-1 fw-bold text-dark">Dosya ve Klasör İstatistikleri</h5>
                        <p class="text-muted small mb-0">Sistemdeki dosya ve klasörlerin sayısal durumunu ve boş olanları buradan görüntüleyebilirsiniz.</p>
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
        <i class="fas fa-info-circle me-2"></i>
        Sistem İstatistikleri
      </h6>
    </div>
    <div class="card-body">
      <div class="alert alert-primary border-left-primary alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Bilgi!</strong> Sistemdeki dosya ve klasörlerin sayısal durumunu ve boş olanları buradan görüntüleyebilirsiniz.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      
      <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card border-left-primary h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Toplam Dosya</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-files">0</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-file fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card border-left-success h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Toplam Klasör</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-folders">0</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-folder fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card border-left-warning h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Boş Klasörler</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800" id="empty-folders">0</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-folder-minus fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="card border-left-danger h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Boş Dosyalar</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800" id="empty-files">0</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-file-excel fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php
      // İstatistik hesaplama fonksiyonu
      function dosyaVeKlasorSayisi($klasor) {
          $dosyaSayisi = 0;
          $klasorSayisi = 0;
          $bosKlasorSayisi = 0;
          $bosKlasorYollari = [];
          $bosDosyaSayisi = 0;
          $bosDosyaYollari = [];

          $iterator = new RecursiveIteratorIterator(
              new RecursiveDirectoryIterator($klasor, RecursiveDirectoryIterator::SKIP_DOTS),
              RecursiveIteratorIterator::SELF_FIRST
          );

          foreach ($iterator as $item) {
              if ($item->isFile()) {
                  $dosyaSayisi++;
                  if ($item->getSize() === 0) {
                      $bosDosyaSayisi++;
                      $bosDosyaYollari[] = str_replace($klasor . DIRECTORY_SEPARATOR, '', $item->getPathname());
                  }
              } elseif ($item->isDir()) {
                  $klasorSayisi++;
                  $count = iterator_count(new FilesystemIterator($item->getPathname(), FilesystemIterator::SKIP_DOTS));
                  if ($count === 0) {
                      $bosKlasorSayisi++;
                      $bosKlasorYollari[] = str_replace($klasor . DIRECTORY_SEPARATOR, '', $item->getPathname());
                  }
              }
          }

          // İstatistik değerlerini JavaScript'e aktar
          echo "<script>
            document.getElementById('total-files').textContent = '$dosyaSayisi';
            document.getElementById('total-folders').textContent = '$klasorSayisi';
            document.getElementById('empty-folders').textContent = '$bosKlasorSayisi';
            document.getElementById('empty-files').textContent = '$bosDosyaSayisi';
          </script>";
          
          // Detaylı istatistik tablosu
          echo '<div class="table-responsive">';
          echo '<table class="table table-hover">';
          echo '<thead class="table-light">';
          echo '  <tr>';
          echo '    <th><i class="fas fa-info-circle me-2"></i>Özellik</th>';
          echo '    <th class="text-end"><i class="fas fa-chart-bar me-2"></i>Değer</th>';
          echo '  </tr>';
          echo '</thead>';
          echo '<tbody>';
          
          // Toplam İstatistikler
          echo '  <tr class="table-primary">';
          echo '    <td><i class="fas fa-chart-pie me-2"></i><strong>Toplam İstatistikler</strong></td>';
          echo '    <td class="text-end"><span class="badge bg-primary">' . ($dosyaSayisi + $klasorSayisi) . ' Toplam Öğe</span></td>';
          echo '  </tr>';
          
          // Dosya İstatistikleri
          echo '  <tr>';
          echo '    <td><i class="fas fa-file me-2 text-primary"></i>Toplam Dosya Sayısı</td>';
          echo '    <td class="text-end"><span class="badge bg-primary text-white">' . number_format($dosyaSayisi, 0, ',', '.') . '</span></td>';
          echo '  </tr>';
          
          // Klasör İstatistikleri
          echo '  <tr>';
          echo '    <td><i class="fas fa-folder me-2 text-success"></i>Toplam Klasör Sayısı</td>';
          echo '    <td class="text-end"><span class="badge bg-success text-white">' . number_format($klasorSayisi, 0, ',', '.') . '</span></td>';
          echo '  </tr>';
          
          // Boş Klasör İstatistikleri
          echo '  <tr class="' . ($bosKlasorSayisi > 0 ? 'table-warning' : '') . '">';
          echo '    <td><i class="fas fa-folder-minus me-2 text-warning"></i>Boş Klasör Sayısı</td>';
          echo '    <td class="text-end">';
          echo '      <span class="badge ' . ($bosKlasorSayisi > 0 ? 'bg-warning text-dark' : 'bg-secondary') . '">' . number_format($bosKlasorSayisi, 0, ',', '.') . '</span>';
          if ($bosKlasorSayisi > 0) {
              echo ' <span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>';
          }
          echo '    </td>';
          echo '  </tr>';
          
          // Boş Dosya İstatistikleri
          echo '  <tr class="' . ($bosDosyaSayisi > 0 ? 'table-danger' : '') . '">';
          echo '    <td><i class="fas fa-file-excel me-2 text-danger"></i>Boş Dosya Sayısı</td>';
          echo '    <td class="text-end">';
          echo '      <span class="badge ' . ($bosDosyaSayisi > 0 ? 'bg-danger text-white' : 'bg-secondary') . '">' . number_format($bosDosyaSayisi, 0, ',', '.') . '</span>';
          if ($bosDosyaSayisi > 0) {
              echo ' <span class="text-danger"><i class="fas fa-exclamation-triangle"></i></span>';
          }
          echo '    </td>';
          echo '  </tr>';
          
          echo '</tbody>';
          echo '</table>';
          echo '</div>';

          // Boş dosya ve klasör listesi
          if ($bosKlasorSayisi > 0 || $bosDosyaSayisi > 0) {
              echo '<div class="mt-4">';
              echo '<div class="d-flex justify-content-between align-items-center mb-3">';
              echo '  <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-warning"></i> Boş Öğeler</h5>';
              echo '  <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#emptyItemsCollapse" aria-expanded="false" aria-controls="emptyItemsCollapse">';
              echo '    <i class="fas fa-eye me-1"></i> Göster/Gizle';
              echo '  </button>';
              echo '</div>';
              
              echo '<div class="collapse show" id="emptyItemsCollapse">';
              echo '  <div class="card shadow-sm">';
              echo '    <div class="card-body p-0">';
              echo '      <div class="table-responsive">';
              echo '        <table class="table table-hover table-sm">';
              echo '          <thead class="table-light">';
              echo '            <tr>';
              echo '              <th style="width: 180px;"><i class="fas fa-tag me-2"></i>Kategori</th>';
              echo '              <th><i class="fas fa-folder-open me-2"></i>Dosya/Klasör Yolu</th>';
              echo '            </tr>';
              echo '          </thead>';
              echo '          <tbody>';
              
              $counter = 1;
              foreach ($bosKlasorYollari as $bosKlasor) {
                  echo '<tr class="' . ($counter % 2 == 0 ? 'table-light' : '') . '">';
                  echo '  <td><span class="badge bg-warning text-dark"><i class="fas fa-folder-minus me-1"></i> Boş Klasör</span></td>';
                  echo '  <td><code>' . htmlspecialchars($bosKlasor) . '</code></td>';
                  echo '</tr>';
                  $counter++;
              }
              
              foreach ($bosDosyaYollari as $bosDosya) {
                  echo '<tr class="' . ($counter % 2 == 0 ? 'table-light' : '') . '">';
                  echo '  <td><span class="badge bg-danger text-white"><i class="fas fa-file-excel me-1"></i> Boş Dosya</span></td>';
                  echo '  <td><code>' . htmlspecialchars($bosDosya) . '</code></td>';
                  echo '</tr>';
                  $counter++;
              }
              
              echo '          </tbody>';
              echo '        </table>';
              echo '      </div>';
              echo '    </div>';
              echo '  </div>';
              echo '</div>'; // End collapse
              echo '</div>'; // End mt-4
          }

          echo '<hr />';
          echo '<h5><i class="fas fa-info-circle me-2"></i> Özet</h5>';
          echo '<p>Toplam Dosya ve Klasör Sayısı: <strong>' . ($dosyaSayisi + $klasorSayisi) . '</strong></p>';
      }

      dosyaVeKlasorSayisi($_SERVER['DOCUMENT_ROOT']);
      ?>

    </div>
  </div>

  <footer class="text-center text-muted py-4 mt-4 border-top">
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
document.addEventListener('DOMContentLoaded', function() {
  // Yükleme ekranını kaldır
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
  
  // Tarih ve saat güncelleme
  updateDateTime();
  setInterval(updateDateTime, 60000); // Her dakika güncelle
  
  // Tooltip'leri aktif et
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
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
  document.getElementById('current-time').textContent = 'Son Güncelleme: ' + now.toLocaleTimeString('tr-TR', timeOptions);
}

// Arama kutusuna odaklan
const searchInput = document.getElementById("searchInput");
if (searchInput) {
  searchInput.focus();
}
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>
