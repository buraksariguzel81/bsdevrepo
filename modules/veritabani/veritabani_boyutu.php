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

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function veritabani_boyutu_goster($vt) {
    try {
        $dbName = $vt->query('select database()')->fetchColumn();
        
        // Toplam boyut
        $sql = "SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'boyut_mb',
                    SUM(data_length + index_length) AS 'boyut_bytes'
                FROM information_schema.tables 
                WHERE table_schema = ?
                GROUP BY table_schema";
        $stmt = $vt->prepare($sql);
        $stmt->execute([$dbName]);
        $dbInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Tablo boyutları
        $sqlTables = "SELECT 
                        table_name AS 'tablo',
                        ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'boyut_mb',
                        (data_length + index_length) AS 'boyut_bytes',
                        table_rows AS 'kayit_sayisi'
                    FROM information_schema.tables 
                    WHERE table_schema = ?
                    ORDER BY (data_length + index_length) DESC";
        $stmt = $vt->prepare($sqlTables);
        $stmt->execute([$dbName]);
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Toplam kayıt sayısı
        $totalRecords = array_sum(array_column($tables, 'kayit_sayisi'));
        
        // Çıktı oluştur
        ?>
      <body>

<div class="container py-4">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-hdd-rack text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Veritabanı Boyut Analizi</h5>
                        <p class="text-muted small mb-0">Veritabanı tablolarının boyutlarını ve kayıt sayılarını görüntüleyin</p>
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
            
            <!-- Özet Kartları -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card h-100 border-start border-4 border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Veritabanı Adı</h6>
                                    <h4 class="mb-0"><?= htmlspecialchars($dbName) ?></h4>
                                </div>
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-database text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-start border-4 border-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Toplam Boyut</h6>
                                    <h4 class="mb-0">
                                        <?= htmlspecialchars(number_format($dbInfo['boyut_mb'], 2, ',', '.')) ?> MB
                                    </h4>
                                    <small class="text-muted">
                                        (<?= formatBytes($dbInfo['boyut_bytes']) ?>)
                                    </small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-hdd text-success" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-start border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Toplam Kayıt</h6>
                                    <h4 class="mb-0"><?= number_format($totalRecords, 0, ',', '.') ?></h4>
                                    <small class="text-muted"><?= count($tables) ?> tabloda</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-table text-info" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tablo Detayları -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-table-list text-primary me-2"></i>
                        Tablo Detayları
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tablo Adı</th>
                                    <th class="text-end">Kayıt Sayısı</th>
                                    <th class="text-end">Boyut (MB)</th>
                                    <th class="text-end">Boyut (Boyut)</th>
                                    <th class="text-end">Yüzde</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tables as $table): 
                                    $percentage = ($table['boyut_bytes'] / $dbInfo['boyut_bytes']) * 100;
                                ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-table text-muted me-2"></i>
                                        <?= htmlspecialchars($table['tablo']) ?>
                                    </td>
                                    <td class="text-end"><?= number_format($table['kayit_sayisi'], 0, ',', '.') ?></td>
                                    <td class="text-end"><?= number_format($table['boyut_mb'], 2, ',', '.') ?></td>
                                    <td class="text-end"><?= formatBytes($table['boyut_bytes']) ?></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="me-2"><?= number_format($percentage, 1) ?>%</span>
                                            <div class="progress" style="width: 100px; height: 6px;">
                                                <div class="progress-bar bg-<?= $percentage > 30 ? 'danger' : ($percentage > 10 ? 'warning' : 'success') ?>" 
                                                     role="progressbar" 
                                                     style="width: <?= $percentage ?>%" 
                                                     aria-valuenow="<?= $percentage ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
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
                <div class="card-footer bg-white py-3">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Son Güncelleme: <?= date('d.m.Y H:i:s') ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
    } catch (Exception $e) {
        echo '<div class="container py-5">
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Hata Oluştu</h6>
                            <p class="mb-0">Veritabanı boyut bilgileri alınamadı: ' . htmlspecialchars($e->getMessage()) . '</p>
                        </div>
                    </div>
                </div>
              </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veritabanı Boyut Analizi - BSD Soft</title>
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
        
        .progress {
            height: 6px;
            border-radius: 3px;
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
        
        .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <?php veritabani_boyutu_goster($vt); ?>
    
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
