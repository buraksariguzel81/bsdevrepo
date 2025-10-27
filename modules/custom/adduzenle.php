<?php
session_start();

$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
    include($rol_kontrol_path);
    if (function_exists('rol_kontrol')) {
        rol_kontrol(1);
    }
}

$message = '';
$messageClass = '';
$error_code = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dirPath = trim($_POST['dirPath']);
    $currentName = trim($_POST['currentName']);
    $newName = trim($_POST['newName']);

    if (strpos($dirPath, '..') !== false || strpos($currentName, '..') !== false || strpos($newName, '..') !== false) {
        $message = "Geçersiz karakter kullanımı tespit edildi.";
        $messageClass = "danger";
        $error_code = 1;
    } else {
        $baseDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
        $fullDir = $baseDir . '/' . ltrim($dirPath, '/');

        $oldPath = $fullDir . '/' . $currentName;
        $newPath = $fullDir . '/' . $newName;

        if (!file_exists($oldPath)) {
            $message = "Eski dosya veya klasör bulunamadı.";
            $messageClass = "warning";
            $error_code = 2;
        } elseif (file_exists($newPath)) {
            $message = "Yeni isim zaten mevcut.";
            $messageClass = "warning";
            $error_code = 3;
        } else {
            if (@rename($oldPath, $newPath)) {
                $message = "Başarıyla yeniden adlandırıldı: <strong>" . htmlspecialchars($currentName) . "</strong> → <strong>" . htmlspecialchars($newName) . "</strong>";
                $messageClass = "success";
                $error_code = 0;
                // Clear form on success
                $currentName = '';
                $newName = '';
            } else {
                $message = "Yeniden adlandırma sırasında hata oluştu.";
                $messageClass = "danger";
                $error_code = 4;
            }
        }
    }
}

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dosya / Klasör Yeniden Adlandırma</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/src/img/favicon.png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <style>
    :root {
        --primary: #0d6efd;
        --primary-hover: #0b5ed7;
        --secondary: #6c757d;
        --light: #f8f9fa;
        --dark: #212529;
        --success: #198754;
        --danger: #dc3545;
        --warning: #ffc107;
    }
    
    body {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background-color: #f5f7fb;
        color: #333;
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, #0a58ca 100%);
        padding: 1.5rem 0;
        margin-bottom: 2rem;
        color: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.06);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background: white;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: var(--dark);
    }
    
    .btn-primary {
        background-color: var(--primary);
        border-color: var(--primary);
        transition: all 0.2s;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: rgba(13, 110, 253, 0.4);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    
    .path-preview {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }
    
    .path-preview:hover {
        border-color: var(--primary);
        background: #f0f7ff;
    }
    
    .path-icon {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    
    .path-display {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 0.5rem;
        font-family: monospace;
        word-break: break-all;
        text-align: left;
        margin: 1rem 0;
        font-size: 0.9rem;
    }
    
    .file-icon {
        font-size: 1.2em;
        margin-right: 0.5rem;
        color: var(--secondary);
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 1rem 0;
        }
        
        .card-body {
            padding: 1.25rem;
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
                        <i class="bi bi-pencil-square text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dosya / Klasör Yeniden Adlandırma</h5>
                        <p class="text-muted small mb-0">Dosya ve klasör isimlerini güvenle değiştirin</p>
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


    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-edit me-2"></i>
                        <span class="d-none d-md-inline">Dosya / Klasör Yeniden Adlandır</span>
                    </span>
                    <span class="badge bg-primary">Yönetim</span>
                </div>
                
                <div class="card-body">
                    <!-- Uyarı Mesajı -->
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            Bu araç sayesinde dosya veya klasörlerin yolunu ve isimlerini girerek kolayca yeniden adlandırabilirsiniz.
                        </div>
                    </div>
                    
                    <!-- Yol Önizleme -->
                    <div class="path-preview" id="pathPreview">
                        <div class="path-icon">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <h5>Yol Önizleme</h5>
                        <p class="text-muted">Aşağıdaki bilgileri doldurduktan sonra tam yol burada görünecektir.</p>
                        <div class="path-display d-none" id="previewPath"></div>
                    </div>

                    <!-- Form -->
                    <form method="post" id="renameForm">
                        <div class="mb-4">
                            <label for="dirPath" class="form-label fw-bold">
                                <i class="fas fa-folder-open me-2"></i> Dizin Yolu
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>/</span>
                                <input type="text" 
                                       class="form-control" 
                                       id="dirPath" 
                                       name="dirPath" 
                                       placeholder="modules/data" 
                                       required
                                       value="<?= isset($_POST['dirPath']) ? htmlspecialchars($_POST['dirPath']) : '' ?>"
                                       oninput="updatePreview()">
                            </div>
                            <div class="form-text">Dosya veya klasörün bulunduğu dizin</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="currentName" class="form-label fw-bold">
                                    <i class="fas fa-file-export me-2"></i> Mevcut Ad
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="currentName" 
                                       name="currentName" 
                                       placeholder="eski_dosya.php" 
                                       required
                                       value="<?= isset($currentName) ? htmlspecialchars($currentName) : '' ?>"
                                       oninput="updatePreview()">
                                <div class="form-text">Mevcut dosya veya klasör adı</div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="newName" class="form-label fw-bold">
                                    <i class="fas fa-file-import me-2"></i> Yeni Ad
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="newName" 
                                       name="newName" 
                                       placeholder="yeni_dosya.php" 
                                       required
                                       value="<?= isset($newName) ? htmlspecialchars($newName) : '' ?>"
                                       oninput="updatePreview()">
                                <div class="form-text">Yeni dosya veya klasör adı</div>
                            </div>
                        </div>

                        <!-- Onay Kutusu -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="confirmRename" required>
                            <label class="form-check-label" for="confirmRename">
                                Bu işlemin geri alınamayacağını anlıyorum
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Yeniden Adlandır
                            </button>
                            <a href="custom.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> İptal ve Geri Dön
                            </a>
                        </div>
                    </form>

                    <?php if ($message): ?>
                        <div class="mt-4 alert alert-<?= $messageClass ?> alert-dismissible fade show" role="alert">
                            <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : ($messageClass === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle') ?> me-2"></i>
                            <?= $message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
// Update path preview
function updatePreview() {
    const dir = document.getElementById('dirPath').value.trim();
    const currentName = document.getElementById('currentName').value.trim();
    const newName = document.getElementById('newName').value.trim();
    const previewPath = document.getElementById('previewPath');
    const pathIcon = document.querySelector('#pathPreview .path-icon i');
    const pathTitle = document.querySelector('#pathPreview h5');
    const pathDescription = document.querySelector('#pathPreview p');
    
    if (dir || currentName || newName) {
        const fullPath = '<?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>' + 
                        (dir ? '/' + dir : '') + 
                        (currentName ? '/' + currentName : '') +
                        (newName ? ' → ' + newName : '');
        
        previewPath.textContent = fullPath;
        previewPath.classList.remove('d-none');
        
        pathTitle.textContent = 'Yeniden Adlandırma Önizleme';
        pathDescription.textContent = 'Tam yol aşağıdaki gibi görünecektir:';
        
        if (currentName || newName) {
            pathIcon.className = 'fas fa-exchange-alt';
        } else {
            pathIcon.className = 'fas fa-folder-open';
        }
    } else {
        pathIcon.className = 'fas fa-file-import';
        pathTitle.textContent = 'Yol Önizleme';
        pathDescription.textContent = 'Aşağıdaki bilgileri doldurduktan sonra tam yol burada görünecektir.';
        previewPath.classList.add('d-none');
    }
}

// Confirm rename
function confirmRename() {
    const dir = document.getElementById('dirPath').value.trim();
    const currentName = document.getElementById('currentName').value.trim();
    const newName = document.getElementById('newName').value.trim();
    
    if (!dir || !currentName || !newName) {
        return false;
    }
    
    const oldPath = '<?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>/' + 
                   (dir ? dir + '/' : '') + currentName;
    const newPath = '<?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>/' + 
                   (dir ? dir + '/' : '') + newName;
    
    return confirm(`Aşağıdaki dosya/klasör yeniden adlandırılacak:\n\nEski: ${oldPath}\nYeni: ${newPath}\n\nBu işlem geri alınamaz!`);
}

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>

</body>
</html>
