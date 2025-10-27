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

// NAVİGASYON
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gelişmiş Disk Kullanımı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .card-hover {
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .progress {
      height: 1.5rem;
      border-radius: 1rem;
      overflow: visible;
    }
    .progress-bar {
      position: relative;
      border-radius: 1rem;
      font-weight: 600;
      overflow: visible;
      transition: width 1s ease-in-out;
    }
    .progress-bar::after {
      content: attr(aria-valuenow) "%";
      position: absolute;
      right: 10px;
      color: #fff;
      font-size: 0.85rem;
      text-shadow: 0 0 3px rgba(0,0,0,0.5);
    }
    .stat-card {
      border-radius: 0.75rem;
      border: none;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      transition: all 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .stat-card .card-body {
      padding: 1.5rem;
    }
    .stat-icon {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      opacity: 0.9;
    }
    .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      margin: 0.5rem 0;
    }
    .stat-label {
      color: #6c757d;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
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
                        <i class="fas fa-file-alt fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Disk Kullanımı</h5>
                        <p class="text-muted small mb-0">Sunucunuzdaki disk alanı kullanımını gerçek zamanlı olarak takip edin. Aşağıdaki grafikler ve ölçümler mevcut disk kullanımınızı detaylı bir şekilde göstermektedir.</p>
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






  
  
      <?php
      $baseDirectory = $_SERVER['DOCUMENT_ROOT'];

      // Klasörün toplam boyutunu hesaplayan fonksiyon (recursive)
      function klasorBoyutunuHesapla($klasor) {
          $toplam = 0;
          $iterator = new RecursiveIteratorIterator(
              new RecursiveDirectoryIterator($klasor, RecursiveDirectoryIterator::SKIP_DOTS),
              RecursiveIteratorIterator::SELF_FIRST
          );
          foreach ($iterator as $item) {
              if ($item->isFile()) $toplam += $item->getSize();
          }
          return $toplam;
      }

      // Bayt cinsinden değeri okunabilir hale getir
      function formatBoyut($bayt) {
          $birimler = ['B','KB','MB','GB','TB'];
          $i = 0;
          while ($bayt >= 1024 && $i < count($birimler)-1) {
              $bayt /= 1024;
              $i++;
          }
          return round($bayt, 2).' '.$birimler[$i];
      }

      $toplamBoyut = klasorBoyutunuHesapla($baseDirectory);
      $toplamBoyutGB = round($toplamBoyut / (1024**3), 2);
      $toplamBoyutMB = round($toplamBoyut / (1024**2), 2);

      // Toplam disk alanı 5 GB olarak sabitlenmiş (geleneksel ama yeterli)
      $toplamAlan = 5 * 1024**3; 
      $kalanAlan = $toplamAlan - $toplamBoyut;
      $kullanımYüzdesi = max(0, min(100, round($toplamBoyut / $toplamAlan * 100, 2)));
      ?>

      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <div class="card stat-card h-100">
            <div class="card-body text-center">
              <i class="bi bi-hdd-rack text-primary stat-icon"></i>
              <div class="stat-value">5 GB</div>
              <div class="stat-label">Toplam Alan</div>
              <div class="text-muted small mt-2">5120 MB</div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card stat-card h-100">
            <div class="card-body text-center">
              <i class="bi bi-hdd text-success stat-icon"></i>
              <div class="stat-value"><?= $toplamBoyutGB ?> GB</div>
              <div class="stat-label">Kullanılan Alan</div>
              <div class="text-muted small mt-2"><?= $toplamBoyutMB ?> MB (<?= $kullanımYüzdesi ?>%)</div>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
          <h5 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Disk Kullanım Oranı</h5>
        </div>
        <div class="card-body">
          <div class="progress mb-3">
            <div class="progress-bar progress-bar-striped progress-bar-animated
              <?php 
                if ($kullanımYüzdesi > 85) echo 'bg-danger';
                else if ($kullanımYüzdesi > 60) echo 'bg-warning';
                else echo 'bg-success';
              ?>" 
              role="progressbar" 
              style="width: <?= $kullanımYüzdesi ?>%" 
              aria-valuenow="<?= $kullanımYüzdesi ?>" 
              aria-valuemin="0" 
              aria-valuemax="100">
              <?= $kullanımYüzdesi ?>%
            </div>
          </div>
          
          <div class="row text-center">
            <div class="col-4">
              <div class="text-muted small">Boş Alan</div>
              <div class="fw-bold"><?= number_format(5 - $toplamBoyutGB, 2) ?> GB</div>
            </div>
            <div class="col-4">
              <div class="text-muted small">Kullanılan</div>
              <div class="fw-bold"><?= $toplamBoyutGB ?> GB</div>
            </div>
            <div class="col-4">
              <div class="text-muted small">Toplam</div>
              <div class="fw-bold">5 GB</div>
            </div>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th><i class="bi bi-info-circle me-1"></i> Özellik</th>
              <th class="text-end">Değer</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><i class="bi bi-hdd me-2 text-primary"></i> Toplam Alan</td>
              <td class="text-end">5 GB (5120 MB)</td>
            </tr>
            <tr>
              <td><i class="bi bi-archive me-2 text-success"></i> Kullanılan Alan</td>
              <td class="text-end"><?= $toplamBoyutGB ?> GB (<?= $toplamBoyutMB ?> MB)</td>
            </tr>
            <tr>
              <td><i class="bi bi-hdd me-2 text-info"></i> Kalan Alan</td>
              <td class="text-end"><?= number_format(5 - $toplamBoyutGB, 2) ?> GB (<?= number_format(5120 - $toplamBoyutMB, 2) ?> MB)</td>
            </tr>
            <tr>
              <td><i class="bi bi-pie-chart me-2 text-warning"></i> Kullanım Oranı</td>
              <td class="text-end">
                <div class="d-flex align-items-center justify-content-end">
                  <div class="me-2"><?= $kullanımYüzdesi ?>%</div>
                  <div class="progress flex-grow-1" style="max-width: 100px; height: 8px;">
                    <div class="progress-bar 
                      <?php 
                        if ($kullanımYüzdesi > 85) echo 'bg-danger';
                        else if ($kullanımYüzdesi > 60) echo 'bg-warning';
                        else echo 'bg-success';
                      ?>" 
                      role="progressbar" 
                      style="width: <?= $kullanımYüzdesi ?>%" 
                      aria-valuenow="<?= $kullanımYüzdesi ?>" 
                      aria-valuemin="0" 
                      aria-valuemax="100">
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
          <h5 class="mb-0"><i class="bi bi-folder2-open me-2"></i>Klasör Analizi</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th><i class="bi bi-folder me-1"></i> Klasör</th>
                  <th class="text-end">Boyut</th>
                  <th class="text-end">Yüzde</th>
                  <th class="text-center">İşlemler</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $iterator = new DirectoryIterator($baseDirectory);
                $toplamKlasorBoyutu = 0;
                $klasorler = [];

                foreach ($iterator as $file) {
                  if ($file->isDir() && !$file->isDot()) {
                    $klasorAdi = $file->getFilename();
                    $klasorYolu = $file->getPathname();
                    $boyut = klasorBoyutunuHesapla($klasorYolu);
                    $toplamKlasorBoyutu += $boyut;
                    $klasorler[] = [
                      'ad' => $klasorAdi,
                      'boyut' => $boyut,
                      'yuzde' => $toplamBoyut > 0 ? ($boyut / $toplamBoyut) * 100 : 0
                    ];
                  }
                }

                // En büyükten en küçüğe sırala
                usort($klasorler, function($a, $b) {
                  return $b['boyut'] - $a['boyut'];
                });

                // Diğer klasörlerin toplam boyutunu hesapla
                $digerKlasorlerToplam = $toplamBoyut - $toplamKlasorBoyutu;
                if ($digerKlasorlerToplam > 0) {
                  $klasorler[] = [
                    'ad' => 'Diğer Klasörler',
                    'boyut' => $digerKlasorlerToplam,
                    'yuzde' => $toplamBoyut > 0 ? ($digerKlasorlerToplam / $toplamBoyut) * 100 : 0
                  ];
                }

                foreach ($klasorler as $klasor) {
                  $boyut = formatBoyut($klasor['boyut']);
                  $yuzde = round($klasor['yuzde'], 2);
                  $progressBarClass = $yuzde > 50 ? 'bg-danger' : ($yuzde > 20 ? 'bg-warning' : 'bg-success');
                  
                  echo "<tr class='table-hover'>";
                  echo "<td><i class='bi bi-folder-fill text-warning me-2'></i>" . htmlspecialchars($klasor['ad']) . "</td>";
                  echo "<td class='text-end fw-medium'>" . $boyut . "</td>";
                  echo "<td class='align-middle'>";
                  echo "<div class='d-flex align-items-center'>";
                  echo "<div class='me-2 small'>" . $yuzde . "%</div>";
                  echo "<div class='progress flex-grow-1' style='height: 6px;'>";
                  echo "<div class='progress-bar $progressBarClass' role='progressbar' style='width: $yuzde%' aria-valuenow='$yuzde' aria-valuemin='0' aria-valuemax='100'></div>";
                  echo "</div>";
                  echo "</div>";
                  echo "</td>";
                  echo "<td class='text-center'>";
                  echo "<div class='btn-group btn-group-sm' role='group'>";
                  echo "<button type='button' class='btn btn-outline-primary' onclick='detayGoster(\"" . urlencode($klasor['ad']) . "\")' data-bs-toggle='tooltip' title='Detayları Görüntüle'>";
                  echo "<i class='bi bi-search'></i>";
                  echo "</button>";
                  echo "<button type='button' class='btn btn-outline-danger' data-bs-toggle='tooltip' title='Temizle'>";
                  echo "<i class='bi bi-trash'></i>";
                  echo "</button>";
                  echo "</div>";
                  echo "</td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light">
              <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Disk Kullanım Dağılımı</h5>
            </div>
            <div class="card-body">
              <canvas id="diskKullanimGrafik" height="300"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mt-4 mt-lg-0">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-light">
              <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Disk İstatistikleri</h5>
            </div>
            <div class="card-body">
              <div class="d-flex flex-column h-100">
                <div class="mb-4">
                  <h6 class="text-uppercase small text-muted mb-3">Genel Durum</h6>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Toplam Alan:</span>
                    <span class="fw-medium">5 GB</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Kullanılan:</span>
                    <span class="fw-medium"><?= $toplamBoyutGB ?> GB</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Boş Alan:</span>
                    <span class="fw-medium"><?= number_format(5 - $toplamBoyutGB, 2) ?> GB</span>
                  </div>
                </div>
                <div class="mt-auto">
                  <div class="alert alert-info mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    <small>Disk alanınızın %80'inden fazlası doluysa, eski dosyaları temizlemeyi düşünebilirsiniz.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center text-muted mt-5 small">
    <hr />
    <?= date('Y') ?> buraksariguzeldev – Disk Yönetim Paneli
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Tooltip'leri etkinleştir
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Disk kullanım grafiği
  const ctx = document.getElementById('diskKullanimGrafik').getContext('2d');
  const usedPercentage = <?= $kullanımYüzdesi ?>;
  
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Kullanılan Alan', 'Boş Alan'],
      datasets: [{
        data: [usedPercentage, 100 - usedPercentage],
        backgroundColor: ['#4e73df', '#e74a3b'],
        hoverBackgroundColor: ['#2e59d9', '#e02d1b'],
        hoverBorderColor: 'rgba(234, 236, 244, 1)',
        borderWidth: 2,
      }],
    },
    options: {
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 20,
            usePointStyle: true,
            pointStyle: 'circle',
            font: {
              family: 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
              size: 13
            }
          }
        },
        tooltip: {
          backgroundColor: 'rgb(255,255,255)',
          bodyColor: '#6e707e',
          titleColor: '#4e73df',
          titleFont: {
            weight: 'bold',
            size: 14
          },
          bodyFont: {
            size: 14
          },
          borderColor: '#dddfeb',
          borderWidth: 1,
          padding: 15,
          displayColors: false,
          caretPadding: 10,
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              return `${label}: ${value}%`;
            }
          }
        },
      },
      cutout: '70%',
      animation: {
        animateScale: true,
        animateRotate: true
      },
      layout: {
        padding: {
          top: 20,
          bottom: 20
        }
      },
      elements: {
        arc: {
          borderWidth: 0
        }
      }
    },
  });
});

function detayGoster(klasorAdi) {
  // Örnek bir modal açma işlemi
  const modal = new bootstrap.Modal(document.getElementById('klasorDetayModal'));
  const modalTitle = document.querySelector('#klasorDetayModal .modal-title');
  modalTitle.textContent = 'Klasör Detayı: ' + decodeURIComponent(klasorAdi);
  
  // Burada AJAX ile klasör detaylarını yükleyebilirsiniz
  // Örnek:
  /*
  fetch(`/api/klasor-detay?path=${encodeURIComponent(klasorAdi)}`)
    .then(response => response.json())
    .then(data => {
      // Modal içeriğini güncelle
      document.getElementById('klasorIcerik').innerHTML = JSON.stringify(data, null, 2);
      modal.show();
    });
  */
  
  modal.show();
}

function formatBytes(bytes, decimals = 2) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
</script>

<!-- Klasör Detay Modal -->
<div class="modal fade" id="klasorDetayModal" tabindex="-1" aria-labelledby="klasorDetayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="klasorDetayModalLabel">Klasör Detayı</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <div class="text-center py-4" id="yukleniyor">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Yükleniyor...</span>
          </div>
          <p class="mt-2">Klasör bilgileri yükleniyor...</p>
        </div>
        <div id="klasorIcerik" class="d-none">
          <!-- AJAX ile yüklenecek içerik -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-primary">Dışa Aktar</button>
      </div>
    </div>
  </div>
</div>
</html>
