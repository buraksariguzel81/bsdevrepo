<?php
session_start();
$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';

$message = '';
$messageClass = '';
$createdPath = '';
$currentDir = isset($_GET['dir']) ? trim($_GET['dir']) : '';
$baseDir = $_SERVER['DOCUMENT_ROOT'];

// Rol kontrolü
if (file_exists($rol_kontrol_path)) {
    include($rol_kontrol_path);
    if (function_exists('rol_kontrol')) {
        rol_kontrol(1);
    }
}

// İşlem yapıldığında
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dir = trim($_POST['dir']);
    $type = $_POST['type'];
    $name = trim($_POST['name']);
    $content = $_POST['content'] ?? '';
    
    // Güvenlik kontrolü
    $targetPath = realpath($baseDir . '/' . ltrim($dir, '/')) . '/' . $name;
    $realBase = realpath($baseDir);
    
    if (strpos($targetPath, $realBase) !== 0) {
        $message = "Geçersiz dizin yolu.";
        $messageClass = "danger";
    } else {
        if ($type === "folder") {
            if (!file_exists($targetPath)) {
                if (mkdir($targetPath, 0755, true)) {
                    $message = "Klasör başarıyla oluşturuldu.";
                    $messageClass = "success";
                    $createdPath = str_replace($realBase, '', $targetPath);
                } else {
                    $message = "Klasör oluşturulurken hata oluştu.";
                    $messageClass = "danger";
                }
            } else {
                $message = "Bu klasör zaten mevcut.";
                $messageClass = "warning";
            }
        } elseif ($type === "file") {
            if (!file_exists($targetPath)) {
                if (file_put_contents($targetPath, $content) !== false) {
                    $message = "Dosya başarıyla oluşturuldu.";
                    $messageClass = "success";
                    $createdPath = str_replace($realBase, '', $targetPath);
                    
                    // İzinleri ayarla
                    chmod($targetPath, 0644);
                } else {
                    $message = "Dosya oluşturulurken hata oluştu.";
                    $messageClass = "danger";
                }
            } else {
                $message = "Bu dosya zaten mevcut.";
                $messageClass = "warning";
            }
        } else {
            $message = "Geçersiz işlem tipi.";
            $messageClass = "danger";
        }
    }
    
    // Yönlendirme yapmadan önce mevcut dizini güncelle
    $currentDir = $dir;
}

// Dizin içeriğini listeleme
$directoryContent = [];
$currentPath = realpath($baseDir . '/' . ltrim($currentDir, '/'));

if ($currentPath && is_dir($currentPath) && strpos($currentPath, $baseDir) === 0) {
    $items = scandir($currentPath);
    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..') {
            $itemPath = $currentPath . '/' . $item;
            $relativePath = str_replace($baseDir, '', $itemPath);
            $isDir = is_dir($itemPath);
            $size = $isDir ? '-' : formatSize(filesize($itemPath));
            $modified = date('d.m.Y H:i', filemtime($itemPath));
            
            $directoryContent[] = [
                'name' => $item,
                'path' => $relativePath,
                'is_dir' => $isDir,
                'size' => $size,
                'modified' => $modified
            ];
        }
    }
}

// Boyut formatlama fonksiyonu
function formatSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, 2) . ' ' . $units[$pow];
}

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dosya ve Klasör Yönetimi | Yönetim Paneli</title>
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/src/img/favicon.png">
  
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  
  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --primary-hover: #3a56d4;
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
      background: linear-gradient(135deg, var(--primary) 0%, #3f37c9 100%);
      padding: 1.5rem 0;
      margin-bottom: 2rem;
      color: white;
      border-radius: 0 0 15px 15px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.03);
      margin-bottom: 2rem;
      overflow: hidden;
    }
    
    .card-header {
      background: white;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      padding: 1.25rem 1.5rem;
      font-weight: 600;
      color: var(--dark);
    }
    
    .nav-tabs .nav-link {
      border: none;
      color: var(--secondary);
      font-weight: 500;
      padding: 0.75rem 1.25rem;
      border-bottom: 3px solid transparent;
    }
    
    .nav-tabs .nav-link.active {
      color: var(--primary);
      background: transparent;
      border-color: var(--primary);
    }
    
    .nav-tabs .nav-link:hover {
      border-color: transparent;
      color: var(--primary);
    }
    
    .file-browser {
      background: white;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 1.5rem;
    }
    
    .file-item {
      display: flex;
      align-items: center;
      padding: 0.75rem 1.25rem;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      transition: background 0.2s;
    }
    
    .file-item:last-child {
      border-bottom: none;
    }
    
    .file-item:hover {
      background: rgba(67, 97, 238, 0.05);
    }
    
    .file-icon {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      color: var(--primary);
      background: rgba(67, 97, 238, 0.1);
      border-radius: 8px;
    }
    
    .file-info {
      flex: 1;
    }
    
    .file-name {
      font-weight: 500;
      margin-bottom: 0.25rem;
      word-break: break-all;
    }
    
    .file-meta {
      font-size: 0.8rem;
      color: var(--secondary);
    }
    
    .CodeMirror {
      height: 300px;
      border-radius: 8px;
      font-family: 'Fira Code', 'Consolas', monospace;
      font-size: 14px;
      line-height: 1.5;
    }
    
    .path-breadcrumb {
      background: white;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 0.5rem;
    }
    
    .path-segment {
      display: flex;
      align-items: center;
      color: var(--primary);
    }
    
    .path-segment a {
      color: var(--primary);
      text-decoration: none;
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
    }
    
    .path-segment a:hover {
      background: rgba(67, 97, 238, 0.1);
    }
    
    .path-separator {
      margin: 0 0.5rem;
      color: var(--secondary);
      opacity: 0.6;
    }
    
    .btn-icon {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .toast-container {
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 1100;
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
        padding: 1rem 0;
      }
      
      .file-item {
        padding: 0.75rem 1rem;
      }
      
      .file-icon {
        width: 36px;
        height: 36px;
        margin-right: 0.75rem;
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
                        <i class="bi bi-file-plus text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dosya Oluşturma</h5>
                        <p class="text-muted small mb-0">Sistem dosyalarını oluşturmak için araçlar</p>
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






  <div class="row">
    <!-- Sol Taraf: Dosya Gezgini -->
    <div class="col-lg-4 mb-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span><i class="fas fa-folder-open me-2"></i>Dosya Gezgini</span>
          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newItemModal">
            <i class="fas fa-plus me-1"></i> Yeni
          </button>
        </div>
        <div class="card-body p-0">
          <!-- Path Breadcrumb -->
          <div class="path-breadcrumb">
            <div class="path-segment">
              <a href="?dir="><i class="fas fa-home"></i></a>
            </div>
            <?php 
            $pathParts = [];
            $currentPath = '';
            if (!empty($currentDir)) {
                $parts = explode('/', trim($currentDir, '/'));
                $currentPath = '';
                foreach ($parts as $part) {
                    $currentPath .= '/' . $part;
                    $pathParts[] = [
                        'name' => $part,
                        'path' => $currentPath
                    ];
                }
                
                foreach ($pathParts as $part) {
                    echo '<div class="path-separator"><i class="fas fa-chevron-right"></i></div>';
                    echo '<div class="path-segment"><a href="?dir=' . urlencode(ltrim($part['path'], '/')) . '">' . htmlspecialchars($part['name']) . '</a></div>';
                }
            }
            ?>
          </div>
          
          <!-- Directory Contents -->
          <div class="file-browser">
            <?php if (empty($directoryContent)): ?>
              <div class="text-center py-4 text-muted">
                <i class="fas fa-folder-open fa-2x mb-2"></i>
                <p class="mb-0">Klasör boş</p>
              </div>
            <?php else: ?>
              <?php foreach ($directoryContent as $item): ?>
                <div class="file-item">
                  <div class="file-icon">
                    <i class="fas fa-<?= $item['is_dir'] ? 'folder' : 'file' ?>"></i>
                  </div>
                  <div class="file-info">
                    <div class="file-name">
                      <a href="?dir=<?= urlencode(ltrim($item['path'], '/')) ?>" class="text-decoration-none text-dark">
                        <?= htmlspecialchars($item['name']) ?>
                      </a>
                    </div>
                    <div class="file-meta">
                      <span class="me-2"><?= $item['size'] ?></span>
                      <span><?= $item['modified'] ?></span>
                    </div>
                  </div>
                  <div class="file-actions">
                    <div class="btn-group btn-group-sm">
                      <button class="btn btn-sm btn-outline-secondary" 
                              onclick="setPath('<?= htmlspecialchars($currentDir) ?>', '<?= htmlspecialchars($item['name']) ?>')">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Sağ Taraf: İşlem Formu -->
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button" role="tab">
                <i class="fas fa-plus-circle me-1"></i> Yeni Oluştur
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab">
                <i class="fas fa-upload me-1"></i> Dosya Yükle
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="myTabContent">
            <!-- Yeni Oluşturma Formu -->
            <div class="tab-pane fade show active" id="create" role="tabpanel">
              <?php if ($message): ?>
                <div class="alert alert-<?= $messageClass ?> alert-dismissible fade show" role="alert">
                  <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : ($messageClass === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle') ?> me-2"></i>
                  <?= htmlspecialchars($message) ?>
                  <?php if ($createdPath): ?>
                    <div class="mt-2">
                      <code><?= htmlspecialchars($createdPath) ?></code>
                      <button class="btn btn-sm btn-outline-secondary ms-2 btn-copy" data-clipboard-text="<?= htmlspecialchars($createdPath) ?>">
                        <i class="far fa-copy"></i> Kopyala
                      </button>
                    </div>
                  <?php endif; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>
              
              <form method="post" id="createForm">
                <div class="mb-3">
                  <label for="dir" class="form-label fw-bold">
                    <i class="fas fa-folder me-2"></i> Dizin Yolu
                  </label>
                  <div class="input-group">
                    <span class="input-group-text"><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>/</span>
                    <input type="text" class="form-control" id="dir" name="dir" value="<?= htmlspecialchars($currentDir) ?>" required>
                  </div>
                  <div class="form-text">Oluşturulacak dosya veya klasörün tam dizin yolu</div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold">
                    <i class="fas fa-file-alt me-2"></i> İşlem Tipi
                  </label>
                  <div class="d-flex gap-3">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="type" id="typeFile" value="file" checked>
                      <label class="form-check-label" for="typeFile">
                        <i class="far fa-file me-1"></i> Dosya Oluştur
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="type" id="typeFolder" value="folder">
                      <label class="form-check-label" for="typeFolder">
                        <i class="far fa-folder me-1"></i> Klasör Oluştur
                      </label>
                    </div>
                  </div>
                </div>

                <div class="mb-3" id="nameGroup">
                  <label for="name" class="form-label fw-bold">
                    <i class="fas fa-tag me-2"></i> İsim
                  </label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="ornek_dosya.php" required>
                  <div class="form-text">Dosya adı uzantısını belirtmeyi unutmayın (örn: .php, .txt)</div>
                </div>

                <div class="mb-3" id="contentGroup">
                  <label for="content" class="form-label fw-bold">
                    <i class="fas fa-file-code me-2"></i> İçerik
                  </label>
                  <textarea class="form-control" id="content" name="content" rows="10" placeholder="Dosya içeriğini buraya yazın..."></textarea>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Oluştur
                  </button>
                </div>
              </form>
            </div>
            
            <!-- Dosya Yükleme Formu -->
            <div class="tab-pane fade" id="upload" role="tabpanel">
              <div class="text-center py-5 border rounded bg-light">
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <h5>Dosya Yükle</h5>
                <p class="text-muted mb-4">Sürükle bırak veya tıklayarak dosya seçin</p>
                <button type="button" class="btn btn-primary" id="browseFiles">
                  <i class="fas fa-folder-open me-2"></i> Dosya Seç
                </button>
                <input type="file" id="fileInput" class="d-none" multiple>
                <div class="mt-3 small text-muted">
                  Maksimum dosya boyutu: 10MB
                </div>
              </div>
              
              <div class="mt-4">
                <h6 class="fw-bold mb-3">Yüklenecek Dizin</h6>
                <div class="input-group mb-3">
                  <span class="input-group-text"><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>/</span>
                  <input type="text" class="form-control" id="uploadDir" value="<?= htmlspecialchars($currentDir) ?>">
                </div>
              </div>
              
              <div id="uploadProgress" class="mt-3 d-none">
                <div class="progress mb-2" style="height: 24px;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                </div>
                <div class="text-center small text-muted">
                  <span id="uploadStatus">Dosya yükleniyor...</span>
                  <span id="uploadPercent">0%</span>
                </div>
              </div>
            </div>
          </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>

<script>
// Hide loading screen when page is fully loaded
document.addEventListener('DOMContentLoaded', function() {
  const loading = document.getElementById('loading');
  if (loading) {
    loading.style.opacity = '0';
    setTimeout(() => {
      loading.style.display = 'none';
    }, 300);
  }
  
  // Initialize CodeMirror
  const editor = CodeMirror.fromTextArea(document.getElementById('content'), {
    lineNumbers: true,
    mode: 'application/x-httpd-php',
    theme: 'monokai',
    indentUnit: 4,
    lineWrapping: true,
    autoCloseBrackets: true,
    matchBrackets: true,
    extraKeys: {
      'Ctrl-Space': 'autocomplete'
    }
  });
  
  // Initialize clipboard.js
  new ClipboardJS('.btn-copy');
  
  // Set up file type change handler
  const typeFile = document.getElementById('typeFile');
  const typeFolder = document.getElementById('typeFolder');
  const contentGroup = document.getElementById('contentGroup');
  
  function updateFormFields() {
    if (typeFile.checked) {
      contentGroup.style.display = 'block';
      document.getElementById('name').placeholder = 'ornek_dosya.php';
    } else {
      contentGroup.style.display = 'none';
      document.getElementById('name').placeholder = 'yeni_klasor';
    }
  }
  
  typeFile.addEventListener('change', updateFormFields);
  typeFolder.addEventListener('change', updateFormFields);
  
  // Set initial state
  updateFormFields();
  
  // Handle file upload button click
  document.getElementById('browseFiles').addEventListener('click', function() {
    document.getElementById('fileInput').click();
  });
  
  // Handle file selection
  document.getElementById('fileInput').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length > 0) {
      uploadFiles(files);
    }
  });
  
  // Set path when clicking on folder items
  window.setPath = function(basePath, itemName) {
    const path = basePath ? `${basePath}/${itemName}` : itemName;
    document.getElementById('dir').value = path;
    document.getElementById('uploadDir').value = path;
  };
});

// File upload function
function uploadFiles(files) {
  const uploadProgress = document.getElementById('uploadProgress');
  const progressBar = uploadProgress.querySelector('.progress-bar');
  const uploadStatus = document.getElementById('uploadStatus');
  const uploadPercent = document.getElementById('uploadPercent');
  const uploadDir = document.getElementById('uploadDir').value;
  
  uploadProgress.classList.remove('d-none');
  progressBar.style.width = '0%';
  uploadStatus.textContent = 'Dosya yükleniyor...';
  uploadPercent.textContent = '0%';
  
  const formData = new FormData();
  formData.append('dir', uploadDir);
  
  for (let i = 0; i < files.length; i++) {
    formData.append('files[]', files[i]);
  }
  
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'upload.php', true);
  
  xhr.upload.onprogress = function(e) {
    if (e.lengthComputable) {
      const percentComplete = Math.round((e.loaded / e.total) * 100);
      progressBar.style.width = percentComplete + '%';
      uploadPercent.textContent = percentComplete + '%';
    }
  };
  
  xhr.onload = function() {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.success) {
        uploadStatus.textContent = 'Yükleme başarılı!';
        progressBar.classList.remove('progress-bar-animated');
        progressBar.classList.add('bg-success');
        
        // Reload the page to show the new files
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        uploadStatus.textContent = 'Hata: ' + (response.message || 'Bilinmeyen hata');
        progressBar.classList.remove('progress-bar-animated');
        progressBar.classList.add('bg-danger');
      }
    } else {
      uploadStatus.textContent = 'Sunucu hatası: ' + xhr.statusText;
      progressBar.classList.remove('progress-bar-animated');
      progressBar.classList.add('bg-danger');
    }
  };
  
  xhr.onerror = function() {
    uploadStatus.textContent = 'Bağlantı hatası oluştu';
    progressBar.classList.remove('progress-bar-animated');
    progressBar.classList.add('bg-danger');
  };
  
  xhr.send(formData);
}

// Handle drag and drop
const dropZone = document.querySelector('.file-browser');
if (dropZone) {
  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
  });
  
  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }
  
  ['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
  });
  
  ['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
  });
  
  function highlight() {
    dropZone.classList.add('bg-light');
  }
  
  function unhighlight() {
    dropZone.classList.remove('bg-light');
  }
  
  dropZone.addEventListener('drop', handleDrop, false);
  
  function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
      document.getElementById('fileInput').files = files;
      uploadFiles(files);
    }
  }
}
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>
