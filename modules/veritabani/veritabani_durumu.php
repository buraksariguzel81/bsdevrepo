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

function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}

function veritabani_durumu_goster($vt) {
    try {
        // Temel veritabanı bilgileri
        $dbName = $vt->query('select database()')->fetchColumn();
        $tableCount = $vt->query('SHOW TABLES')->rowCount();
        
        // Tablo istatistikleri
        $tables = $vt->query('SHOW TABLE STATUS')->fetchAll(PDO::FETCH_ASSOC);
        $totalRows = 0;
        $totalSize = 0;
        $tableStats = [];
        
        foreach ($tables as $table) {
            $tableName = $table['Name'];
            $rowCount = $vt->query("SELECT COUNT(*) FROM `$tableName`")->fetchColumn();
            $totalRows += $rowCount;
            $size = $table['Data_length'] + $table['Index_length'];
            $totalSize += $size;
            
            $tableStats[] = [
                'name' => $tableName,
                'rows' => $rowCount,
                'size' => $size,
                'engine' => $table['Engine'] ?? 'N/A',
                'collation' => $table['Collation'] ?? 'N/A',
                'create_time' => $table['Create_time'] ?? 'N/A',
                'update_time' => $table['Update_time'] ?? 'N/A'
            ];
        }
        
        // En büyük tabloları sırala
        usort($tableStats, function($a, $b) {
            return $b['size'] - $a['size'];
        });
        
        // Veritabanı bağlantı bilgileri
        $connectionInfo = [
            'Sunucu' => $vt->getAttribute(PDO::ATTR_CONNECTION_STATUS),
            'Sürücü' => $vt->getAttribute(PDO::ATTR_DRIVER_NAME),
            'Sunucu Sürümü' => $vt->getAttribute(PDO::ATTR_SERVER_VERSION),
            'İstemci Sürümü' => $vt->getAttribute(PDO::ATTR_CLIENT_VERSION)
        ];
        
        // Çıktı oluştur
        ?>
     <body>

<div class="container py-4">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-speedometer2 text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Veritabanı Durumu</h5>
                        <p class="text-muted small mb-0">Veritabanı performans metrikleri ve sistem durumu istatistikleri</p>
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
                <!-- Veritabanı Bilgileri -->
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
                
                <!-- Tablo İstatistikleri -->
                <div class="col-md-4">
                    <div class="card h-100 border-start border-4 border-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Toplam Tablo</h6>
                                    <h4 class="mb-0"><?= formatNumber($tableCount) ?></h4>
                                    <small class="text-muted"><?= count($tables) ?> aktif tablo</small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-table text-success" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kayıt İstatistikleri -->
                <div class="col-md-4">
                    <div class="card h-100 border-start border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Toplam Kayıt</h6>
                                    <h4 class="mb-0"><?= formatNumber($totalRows) ?></h4>
                                    <small class="text-muted">Tüm tabolardaki kayıtlar</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-list-ol text-info" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tablo İstatistikleri -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-table-list text-primary me-2"></i>
                                Tablo İstatistikleri
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tablo Adı</th>
                                            <th class="text-end">Kayıt Sayısı</th>
                                            <th class="text-end">Boyut</th>
                                            <th>Motor</th>
                                            <th>Karşılaştırma</th>
                                            <th class="text-end">Son Güncelleme</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($tableStats, 0, 15) as $table): ?>
                                        <tr>
                                            <td>
                                                <i class="fas fa-table text-muted me-2"></i>
                                                <?= htmlspecialchars($table['name']) ?>
                                            </td>
                                            <td class="text-end"><?= formatNumber($table['rows']) ?></td>
                                            <td class="text-end"><?= formatBytes($table['size']) ?></td>
                                            <td><?= htmlspecialchars($table['engine']) ?></td>
                                            <td><?= htmlspecialchars($table['collation']) ?></td>
                                            <td class="text-end"><?= $table['update_time'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white py-3">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Toplam <?= count($tables) ?> tablodan ilk 15 tablo gösteriliyor. Son Güncelleme: <?= date('d.m.Y H:i:s') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bağlantı Bilgileri -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-plug text-primary me-2"></i>
                                Veritabanı Bağlantı Bilgileri
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($connectionInfo as $key => $value): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark me-2"><?= $key ?></span>
                                        <span class="text-muted"><?= htmlspecialchars($value) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
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
                            <p class="mb-0">Veritabanı bilgileri alınamadı: ' . htmlspecialchars($e->getMessage()) . '</p>
                        </div>
                    </div>
                </div>
              </div>';
    }
}

// Yardımcı fonksiyon: Bayt boyutunu okunabilir formata çevirir
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veritabanı Durumu - BSD Soft</title>
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
        
        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <?php veritabani_durumu_goster($vt); ?>
    
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
