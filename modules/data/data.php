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

// Menü öğeleri dizisi
$menu_items = [
    [
        'href' => 'liste.php',
        'icon' => 'bi-folder2-open',
        'text' => 'Dosyaları Listele',
        'desc' => 'Tüm dosya ve klasörleri listeleyin',
        'color' => 'primary'
    ],
    [
        'href' => 'dosyaindir.php',
        'icon' => 'bi-download',
        'text' => 'Dosya İndir',
        'desc' => 'Dosyaları bilgisayarınıza indirin',
        'color' => 'success'
    ],
    [
        'href' => 'aynisim.php',
        'icon' => 'bi-files',
        'text' => 'Aynı İsim',
        'desc' => 'Aynı isme sahip dosyaları bulun',
        'color' => 'info'
    ],
    [
        'href' => 'uzantılar.php',
        'icon' => 'bi-file-earmark-code',
        'text' => 'Uzantılar',
        'desc' => 'Dosya uzantılarını yönetin',
        'color' => 'warning'
    ],
    [
        'href' => 'klasorsayisi.php',
        'icon' => 'bi-folder',
        'text' => 'Klasör Sayısı',
        'desc' => 'Klasör istatistiklerini görüntüleyin',
        'color' => 'danger'
    ],
    [
        'href' => 'disk_kullanimi.php',
        'icon' => 'bi-hdd',
        'text' => 'Disk Kullanımı',
        'desc' => 'Disk kullanım analizleri',
        'color' => 'secondary'
    ],
    [
        'href' => 'uzantisayisi.php',
        'icon' => 'bi-file-earmark-text',
        'text' => 'Uzantı Sayısı',
        'desc' => 'Dosya uzantılarını sayın',
        'color' => 'primary'
    ],
    [
        'href' => 'enbuyukenkucuk.php',
        'icon' => 'bi-arrow-down-up',
        'text' => 'Boyuta Göre Sırala',
        'desc' => 'En büyük ve en küçük dosyalar',
        'color' => 'success'
    ],
    [
        'href' => 'sondeğişiklik.php',
        'icon' => 'bi-clock-history',
        'text' => 'Son Değişiklik',
        'desc' => 'Son değiştirilen dosyalar',
        'color' => 'info'
    ],
    [
        'href' => 'oluşturulma.php',
        'icon' => 'bi-calendar-plus',
        'text' => 'Oluşturulma Tarihi',
        'desc' => 'Dosya oluşturma tarihleri',
        'color' => 'warning'
    ],
    [
        'href' => 'agacgorunumu.php',
        'icon' => 'bi-diagram-3',
        'text' => 'Ağaç Görünümü',
        'desc' => 'Klasör yapısını görüntüleyin',
        'color' => 'danger'
    ],
    [
        'href' => 'fontyukleyici.php',
        'icon' => 'bi-fonts',
        'text' => 'Font Yöneticisi',
        'desc' => 'Sistem yazı tiplerini yönetin',
        'color' => 'secondary'
    ]
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dosya Yöneticisi - BSD Soft</title>
  <!-- Bootstrap 5.3.2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --bs-body-bg: #f8f9fa;
      --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
      --transition: all 0.3s ease;
    }
    
    body {
      background-color: var(--bs-body-bg);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    
    .module-card {
      border: none;
      border-radius: 0.75rem;
      transition: var(--transition);
      overflow: hidden;
      height: 100%;
      box-shadow: var(--card-shadow);
      border-left: 4px solid var(--bs-primary);
    }
    
    .module-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    }
    
    .module-icon {
      font-size: 2rem;
      margin-bottom: 1rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: rgba(var(--bs-primary-rgb), 0.1);
    }
    
    .module-card .card-body {
      padding: 1.75rem 1.25rem;
    }
    
    .module-card .card-title {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--bs-dark);
      font-size: 1.1rem;
    }
    
    .module-card .card-text {
      color: var(--bs-gray-600);
      font-size: 0.85rem;
      margin-bottom: 1.25rem;
      min-height: 40px;
    }
    
    .btn-module {
      border-radius: 50px;
      padding: 0.4rem 1rem;
      font-weight: 500;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
    }
    
    .page-header {
      background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
      color: #2c3e50;
      padding: 2.5rem 0;
      margin-bottom: 2.5rem;
      border-radius: 0.5rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    .page-title {
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: #2c3e50;
    }
    
    .page-subtitle {
      opacity: 0.9;
      font-weight: 400;
      color: #495057;
    }
    
    .breadcrumb {
      background-color: rgba(255, 255, 255, 0.2);
      padding: 0.5rem 1rem;
      border-radius: 50px;
      display: inline-flex;
    }
    
    .breadcrumb-item a {
      color: #2c3e50;
      text-decoration: none;
      font-weight: 500;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .module-card {
        margin-bottom: 1.5rem;
      }
      
      .page-header {
        margin-left: -1rem;
        margin-right: -1rem;
        border-radius: 0;
      }
    }
  </style>
</head>
<body>
  <div class="container-fluid py-4">


    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-grid text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dosya Yöneticisi</h5>
                        <p class="text-muted small mb-0">Sistem dosyalarınızı yönetmek için araçlar</p>
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







  
      <!-- Tools Grid -->
      <div class="row g-4">
        <?php foreach ($menu_items as $item): 
          $colorClass = 'text-' . $item['color'];
          $bgClass = 'bg-' . $item['color'] . '-subtle';
          $iconBgClass = 'bg-' . $item['color'] . '-subtle';
          $iconColorClass = 'text-' . $item['color'];
        ?>
        <div class="col-12 col-sm-6 col-xl-4">
          <a href="<?= htmlspecialchars($item['href']) ?>" class="text-decoration-none">
            <div class="card module-card h-100">
              <div class="card-body text-center">
                <div class="module-icon mx-auto <?= $iconBgClass ?> <?= $iconColorClass ?>">
                  <i class="bi <?= htmlspecialchars($item['icon']) ?>"></i>
                </div>
                <h5 class="card-title"><?= htmlspecialchars($item['text']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($item['desc']) ?></p>
                <span class="btn btn-sm btn-outline-<?= $item['color'] ?> btn-module">
                  Aç <i class="bi bi-arrow-right ms-1"></i>
                </span>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      
      <!-- Footer -->
      <footer class="text-center text-muted mt-5 pt-4 border-top">
        <div class="d-flex justify-content-center gap-3 mb-2">
          <a href="#" class="text-decoration-none text-muted">Yardım</a>
          <span class="text-muted">•</span>
          <a href="#" class="text-decoration-none text-muted">Gizlilik</a>
          <span class="text-muted">•</span>
          <a href="#" class="text-decoration-none text-muted">Şartlar</a>
        </div>
        <p class="small">
          &copy; <?= date('Y') ?> BSD Soft - Tüm hakları saklıdır.
        </p>
      </footer>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom JavaScript -->
  <script>
    // Add animation on page load
    document.addEventListener('DOMContentLoaded', function() {
      // Animate cards on load
      const cards = document.querySelectorAll('.module-card');
      cards.forEach((card, index) => {
        setTimeout(() => {
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, 100 * index);
      });
      
      // Add tooltips
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  </script>
  
  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>
