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
        'href' => 'olustur.php', 
        'icon' => 'fas fa-plus-circle', 
        'text' => 'Dosya Oluştur',
        'description' => 'Yeni dosya veya klasör oluşturma işlemleri'
    ],
    [ 
        'href' => 'silme.php', 
        'icon' => 'fas fa-trash-alt', 
        'text' => 'Silme İşlemleri',
        'description' => 'Dosya veya klasör silme işlemleri'
    ],
    [ 
        'href' => 'tasima.php', 
        'icon' => 'fas fa-exchange-alt', 
        'text' => 'Taşıma İşlemleri',
        'description' => 'Dosya veya klasör taşıma işlemleri'
    ],
    [ 
        'href' => 'inckaldir.php', 
        'icon' => 'fas fa-unlink', 
        'text' => 'Include Silme',
        'description' => 'PHP include ifadelerini kaldırma'
    ],
    [ 
        'href' => 'incdegis.php', 
        'icon' => 'fas fa-sync-alt', 
        'text' => 'Include Değiştirme',
        'description' => 'Include yollarını güncelleme'
    ],
    [ 
        'href' => 'adduzenle.php', 
        'icon' => 'fas fa-edit', 
        'text' => 'İsim Düzenleme',
        'description' => 'Dosya veya klasör isimlerini düzenleme'
    ],
    [ 
        'href' => 'kodduzenleyici.php', 
        'icon' => 'fas fa-code', 
        'text' => 'Kod Düzenleyici',
        'description' => 'Kod dosyalarını düzenleme aracı'
    ],
    [ 
        'href' => 'lang.php', 
        'icon' => 'fas fa-language', 
        'text' => 'Dil Dosyaları',
        'description' => 'Çoklu dil desteği düzenlemeleri'
    ],
    [ 
        'href' => 'değişken_duzenle.php', 
        'icon' => 'fa-brands fa-php', 
        'text' => 'PHP Değişkenleri',
        'description' => 'PHP değişkenlerini düzenleme aracı'
    ]
];
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Custom İşlemler | Yönetim Paneli</title>
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/src/img/favicon.png">
  
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  
  <style>
    :root {
      --primary: #4361ee;
      --primary-hover: #3a56d4;
      --secondary: #6c757d;
      --light: #f8f9fa;
      --dark: #212529;
      --card-bg: #ffffff;
      --card-hover: #f8f9ff;
    }
    
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background-color: #f5f7fb;
      color: #333;
    }
    
    .page-header {
      background: linear-gradient(135deg, var(--primary) 0%, #3f37c9 100%);
      padding: 2rem 0;
      margin-bottom: 2rem;
      color: white;
      border-radius: 0 0 15px 15px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .card-tool {
      border: none;
      border-radius: 12px;
      transition: all 0.3s ease;
      background: var(--card-bg);
      overflow: hidden;
      height: 100%;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .card-tool:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(67, 97, 238, 0.15) !important;
      background: var(--card-hover);
    }
    
    .card-tool .card-body {
      padding: 1.75rem;
      text-align: center;
    }
    
    .card-tool i {
      font-size: 2.25rem;
      margin-bottom: 1rem;
      color: var(--primary);
      background: rgba(67, 97, 238, 0.1);
      width: 70px;
      height: 70px;
      line-height: 70px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }
    
    .card-tool:hover i {
      transform: scale(1.1);
      background: rgba(67, 97, 238, 0.2);
    }
    
    .card-tool .card-title {
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
    }
    
    .card-tool .card-text {
      color: var(--secondary);
      font-size: 0.9rem;
      margin-bottom: 0;
    }
    
    .tool-link {
      text-decoration: none;
      display: block;
      height: 100%;
    }
    
    .tool-link:hover .card-tool {
      border-color: var(--primary-hover);
    }
    
    .page-title {
      font-weight: 700;
      margin-bottom: 0.5rem;
      font-size: 1.8rem;
    }
    
    .page-description {
      opacity: 0.9;
      font-weight: 400;
      margin-bottom: 0;
    }
    
    .breadcrumb {
      background: transparent;
      padding: 0.5rem 0;
      margin-bottom: 1.5rem;
    }
    
    .breadcrumb-item a {
      color: var(--primary);
      text-decoration: none;
    }
    
    .breadcrumb-item.active {
      color: var(--secondary);
    }
    
    .card {
      border: none;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.03);
      margin-bottom: 2rem;
    }
    
    .card-header {
      background: white;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      padding: 1.25rem 1.5rem;
    }
    
    .card-header h5 {
      font-weight: 600;
      margin: 0;
      color: var(--dark);
    }
    
    .loading {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.3s ease;
    }
    
    .spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid var(--primary);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
      .page-header {
        padding: 1.5rem 0;
      }
      
      .page-title {
        font-size: 1.5rem;
      }
      
      .card-tool .card-body {
        padding: 1.5rem 1rem;
      }
    }
  </style>
</head>
<body>
<!-- Loading Screen -->
<div class="loading" id="loading">
  <div class="spinner"></div>
</div>

<div class="container py-4">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-grid text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Custom İşlemler</h5>
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

  <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
    <?php foreach ($menu_items as $item): ?>
      <div class="col">
        <a href="<?= htmlspecialchars($item['href']) ?>" class="tool-link" data-loading-text="Yükleniyor...">
          <div class="card card-tool h-100">
            <div class="card-body">
              <i class="<?= htmlspecialchars($item['icon']) ?>"></i>
              <h5 class="card-title"><?= htmlspecialchars($item['text']) ?></h5>
              <p class="card-text"><?= $item['description'] ?? 'Detaylı işlem yapmak için tıklayın' ?></p>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<!-- Footer -->
<footer class="bg-white py-4 mt-5">
  <div class="container">
    <div class="text-center text-muted small">
      © <?= date('Y') ?> buraksariguzeldev – Tüm hakları saklıdır
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Hide loading screen when page is fully loaded
  window.addEventListener('load', function() {
    const loading = document.getElementById('loading');
    if (loading) {
      loading.style.opacity = '0';
      setTimeout(() => {
        loading.style.display = 'none';
      }, 300);
    }
  });
  
  // Add loading state to all tool links
  document.querySelectorAll('.tool-link').forEach(link => {
    link.addEventListener('click', function(e) {
      const loadingText = this.getAttribute('data-loading-text') || 'Yükleniyor...';
      const originalHtml = this.innerHTML;
      this.innerHTML = `
        <div class="card card-tool h-100">
          <div class="card-body d-flex flex-column justify-content-center">
            <div class="spinner-border text-primary mb-2 mx-auto" role="status">
              <span class="visually-hidden">${loadingText}</span>
            </div>
            <p class="mb-0 text-muted">${loadingText}</p>
          </div>
        </div>
      `;
    });
  });
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>
