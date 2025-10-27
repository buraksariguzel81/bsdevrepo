<?php
session_start();
$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';

if (file_exists($rol_kontrol_path)) {
    include($rol_kontrol_path);
    if (function_exists('rol_kontrol')) {
        rol_kontrol(1);
    }
}

function directoryToArray($directory) {
    $result = [];
    if (!is_dir($directory)) return $result;

    $files = scandir($directory);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $directory . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            $result[$file] = directoryToArray($path);
        } else {
            $result[] = $file;
        }
    }
    return $result;
}

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Generate JSON data
$baseDirectory = $_SERVER['DOCUMENT_ROOT'];
$tree = directoryToArray($baseDirectory);
$jsonPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/json/agacgorunumu.json';

// Create directory if it doesn't exist
$dir = dirname($jsonPath);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Save JSON to file
file_put_contents($jsonPath, json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dizin Yapısı - JSON Ağaç Görünümü | BSD Soft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/atom-one-dark.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #6c757d;
            --success: #1cc88a;
            --dark: #5a5c69;
            --light: #f8f9fc;
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
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            transform: translateY(-3px);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
        }
        
        .card-header i {
            margin-right: 0.5rem;
            color: var(--primary);
        }
        
        #jsonDisplay {
            background: #282c34;
            border-radius: 0.5rem;
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.6;
            color: #abb2bf;
        }
        
        .action-buttons {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            z-index: 1000;
        }
        
        .action-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: all 0.3s;
            border: none;
            color: white;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.2);
        }
        
        .btn-copy { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
        .btn-download { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
        .btn-refresh { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        
        /* Stats cards */
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }
        
        .stat-icon {
            font-size: 2rem;
            opacity: 0.8;
        }
        
        /* JSON syntax highlighting */
        .hljs {
            background: #282c34 !important;
            border-radius: 0.5rem;
            padding: 1.5rem !important;
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
                        <h5 class="mb-1 fw-bold text-dark">Dizin Ağacı</h5>
                        <p class="text-muted small mb-0">Sistemdeki dosya uzantılarının sayısal durumunu ve boş olanları buradan görüntüleyebilirsiniz.</p>
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

    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stat-card border-left-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-primary">
                                <i class="fas fa-folder stat-icon"></i>
                            </div>
                            <div>
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Dizin Yolu</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800 text-truncate">
                                    <?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card border-left-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-success">
                                <i class="fas fa-file-alt stat-icon"></i>
                            </div>
                            <div>
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Toplam Öğe</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800" id="itemCount">Hesaplanıyor...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card border-left-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-info">
                                <i class="fas fa-database stat-icon"></i>
                            </div>
                            <div>
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Boyut</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800" id="totalSize">Hesaplanıyor...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JSON Viewer -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-code me-2"></i>JSON Dizin Ağacı
                </h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-1"></i> İşlemler
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#" id="copyBtn"><i class="far fa-copy me-2"></i>Panoya Kopyala</a></li>
                        <li><a class="dropdown-item" href="#" id="downloadBtn"><i class="fas fa-download me-2"></i>JSON'ı İndir</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="refreshBtn"><i class="fas fa-sync-alt me-2"></i>Sayfayı Yenile</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-0">
                <pre id="jsonDisplay"><code class="language-json"><?php 
                    $baseDirectory = $_SERVER['DOCUMENT_ROOT'];
                    $tree = directoryToArray($baseDirectory);
                    $jsonPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/json/agacgorunumu.json';
                    file_put_contents($jsonPath, json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    echo htmlspecialchars(file_get_contents($jsonPath));
                ?></code></pre>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Floating) -->
    <div class="action-buttons">
        <button class="action-btn btn-copy" title="Panoya Kopyala" id="copyBtnFloat">
            <i class="far fa-copy"></i>
        </button>
        <button class="action-btn btn-download" title="JSON'ı İndir" id="downloadBtnFloat">
            <i class="fas fa-download"></i>
        </button>
        <button class="action-btn btn-refresh" title="Yenile" id="refreshBtnFloat">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>

    <!-- Footer -->
    <footer class="text-center text-muted py-4 mt-5 bg-white border-top">
        <div class="container">
            <p class="mb-0">
                © <?= date('Y') ?> <strong>BSD Soft</strong> - Tüm Hakları Saklıdır
                <span class="mx-2">•</span>
                <span id="current-time"></span>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Highlight.js for JSON syntax highlighting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Initialize Highlight.js
        hljs.highlightAll();
        
        // Elements
        const copyBtns = [document.getElementById("copyBtn"), document.getElementById("copyBtnFloat")];
        const downloadBtns = [document.getElementById("downloadBtn"), document.getElementById("downloadBtnFloat")];
        const refreshBtns = [document.getElementById("refreshBtn"), document.getElementById("refreshBtnFloat")];
        const jsonDisplay = document.querySelector("#jsonDisplay code");
        const itemCountEl = document.getElementById("itemCount");
        const totalSizeEl = document.getElementById("totalSize");
        
        // Calculate and display item count and total size
        function updateStats() {
            try {
                const data = JSON.parse(jsonDisplay.textContent);
                
                // Calculate total items
                function countItems(obj) {
                    if (Array.isArray(obj)) return obj.length;
                    if (typeof obj === 'object' && obj !== null) {
                        return Object.values(obj).reduce((sum, val) => sum + countItems(val), 0);
                    }
                    return 0;
                }
                
                // Calculate total size
                function calculateSize(obj) {
                    if (typeof obj === 'string') return obj.length;
                    if (Array.isArray(obj)) {
                        return obj.reduce((sum, item) => sum + calculateSize(item), 0);
                    }
                    if (typeof obj === 'object' && obj !== null) {
                        return Object.values(obj).reduce((sum, val) => sum + calculateSize(val), 0);
                    }
                    return 0;
                }
                
                const totalItems = countItems(data);
                const totalSize = calculateSize(JSON.stringify(data));
                
                // Format size
                function formatSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }
                
                itemCountEl.textContent = totalItems.toLocaleString();
                totalSizeEl.textContent = formatSize(totalSize);
            } catch (e) {
                console.error("Error calculating stats:", e);
            }
        }
        
        // Copy to clipboard
        function handleCopy() {
            const jsonText = jsonDisplay.textContent;
            navigator.clipboard.writeText(jsonText)
                .then(() => {
                    copyBtns.forEach(btn => {
                        if (!btn) return;
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                        }, 2000);
                    });
                })
                .catch(err => console.error("Kopyalama hatası:", err));
        }
        
        // Download JSON
        function handleDownload() {
            const jsonText = jsonDisplay.textContent;
            const blob = new Blob([jsonText], { type: "application/json" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `dizin_yapisi_${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
        
        // Refresh page
        function handleRefresh() {
            window.location.reload();
        }
        
        // Update current time
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('tr-TR', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('tr-TR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('current-time').textContent = `${dateString} ${timeString}`;
        }
        
        // Event Listeners
        copyBtns.forEach(btn => btn && btn.addEventListener("click", handleCopy));
        downloadBtns.forEach(btn => btn && btn.addEventListener("click", handleDownload));
        refreshBtns.forEach(btn => btn && btn.addEventListener("click", handleRefresh));
        
        // Initialize
        updateStats();
        updateCurrentTime();
        setInterval(updateCurrentTime, 1000);
        
        // Add tooltips to floating buttons
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover',
                placement: 'left'
            });
        });
    });
    </script>
</body>
</html>
