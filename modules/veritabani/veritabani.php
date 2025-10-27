
<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php';

// Rol kontrolü
$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
  include($rol_kontrol_path);
  if (function_exists('rol_kontrol')) rol_kontrol(1);
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veritabanı Yönetimi - BSD Soft</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
        }
        
        [data-bs-theme="dark"] {
            --dark-color: #f8fafc;
            --light-color: #1e293b;
            --card-bg: #1e293b;
            --border-color: #334155;
        }
        
        body {
            background-color: var(--light-color);
            color: var(--dark-color);
            transition: background-color 0.3s, color 0.3s;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: none;
        }
        
        .nav-tabs .nav-link {
            color: var(--dark-color);
            border: 1px solid transparent;
            border-radius: 0.5rem 0.5rem 0 0;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link.active {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            border-bottom-color: transparent;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .nav-tabs .nav-link:hover:not(.active) {
            border-color: transparent;
            color: var(--primary-color);
        }
        
        .module-card {
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }
        
        .module-card:hover {
            transform: translateX(5px);
        }
        
        .module-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-right: 1rem;
        }
        
        .theme-toggle {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            background-color: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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
                        <i class="bi bi-database-gear text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Veritabanı Yönetim Merkezi</h5>
                        <p class="text-muted small mb-0">Veritabanı işlemlerinizi güvenli bir şekilde yönetebilir, yedek alabilir ve performans analizi yapabilirsiniz.</p>
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
                    
                    <div class="row g-4">
                        <!-- Veritabanı Durumu -->
                        <div class="col-md-6">
                            <a href="veritabani_durumu.php" class="text-decoration-none">
                                <div class="card module-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="module-icon">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Veritabanı Durumu</h6>
                                                <p class="text-muted small mb-0">Veritabanı istatistiklerini ve genel durumunu görüntüleyin</p>
                                            </div>
                                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Veritabanı Boyutu -->
                        <div class="col-md-6">
                            <a href="veritabani_boyutu.php" class="text-decoration-none">
                                <div class="card module-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="module-icon">
                                                <i class="fas fa-database"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Veritabanı Boyutu</h6>
                                                <p class="text-muted small mb-0">Veritabanı boyutunu ve kullanımını görüntüleyin</p>
                                            </div>
                                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Yedekleme (Örnek) -->
                        <div class="col-md-6">
                            <a href="#" class="text-decoration-none">
                                <div class="card module-card h-100 opacity-50">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="module-icon">
                                                <i class="fas fa-save"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Veritabanı Yedekleme</h6>
                                                <p class="text-muted small mb-0">Veritabanınızın yedeğini alın veya geri yükleyin</p>
                                            </div>
                                            <span class="badge bg-warning text-dark ms-auto">Yakında</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Sorgu Çalıştır (Örnek) -->
                        <div class="col-md-6">
                            <a href="#" class="text-decoration-none">
                                <div class="card module-card h-100 opacity-50">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="module-icon">
                                                <i class="fas fa-terminal"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">SQL Sorgu Çalıştır</h6>
                                                <p class="text-muted small mb-0">Özel SQL sorguları çalıştırın</p>
                                            </div>
                                            <span class="badge bg-warning text-dark ms-auto">Yakında</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning border-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Önemli Uyarı</h6>
                        <p class="mb-0 small">Veritabanı işlemleri geri alınamaz değişikliklere neden olabilir. Lütfen işlem yapmadan önce mutlaka yedek alınız.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Theme Toggle Button -->
<button type="button" class="theme-toggle" id="themeToggle" title="Tema Değiştir">
    <i class="fas fa-moon"></i>
</button>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom Scripts -->
<script>
// Tema değiştirme
const themeToggle = document.getElementById('themeToggle');
const html = document.documentElement;
const icon = themeToggle.querySelector('i');

// Kullanıcının tercihini kontrol et
const currentTheme = localStorage.getItem('theme') || 'light';
if (currentTheme === 'dark') {
    html.setAttribute('data-bs-theme', 'dark');
    icon.className = 'fas fa-sun';
} else {
    html.setAttribute('data-bs-theme', 'light');
    icon.className = 'fas fa-moon';
}

// Tema değiştirme işlevi
themeToggle.addEventListener('click', () => {
    if (html.getAttribute('data-bs-theme') === 'dark') {
        html.setAttribute('data-bs-theme', 'light');
        icon.className = 'fas fa-moon';
        localStorage.setItem('theme', 'light');
    } else {
        html.setAttribute('data-bs-theme', 'dark');
        icon.className = 'fas fa-sun';
        localStorage.setItem('theme', 'dark');
    }
});
</script>
</body>
</html>
