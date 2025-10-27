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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dosya Yöneticisi | BSDSoft</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --success-color: #4bb543;
      --danger-color: #dc3545;
      --warning-color: #ffc107;
      --light-bg: #f8f9fa;
      --dark-bg: #343a40;
      --border-radius: 0.5rem;
      --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
      --transition: all 0.3s ease;
    }
    
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f5f7fb;
      color: #333;
    }
    
    .card {
      border: none;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      transition: var(--transition);
      margin-bottom: 1.5rem;
    }
    
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08);
    }
    
    .card-header {
      background-color: #fff;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      font-weight: 600;
      padding: 1.25rem 1.5rem;
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }
    
    .file-item {
      transition: var(--transition);
      border-radius: var(--border-radius);
      border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .file-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
      border-color: rgba(67, 97, 238, 0.2);
    }
    
    .file-icon {
      font-size: 2.5rem;
      margin-bottom: 0.75rem;
      color: var(--primary-color);
    }
    
    .breadcrumb {
      background-color: transparent;
      padding: 0.5rem 0;
      margin-bottom: 0;
    }
    
    .breadcrumb-item a {
      text-decoration: none;
      color: var(--primary-color);
    }
    
    .path-display {
      background-color: #f1f3f5;
      border-radius: var(--border-radius);
      padding: 0.5rem 1rem;
      font-family: 'Roboto Mono', monospace;
      font-size: 0.85rem;
      word-break: break-all;
    }
    
    .table th {
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      color: #6c757d;
      border-top: none;
    }
    
    .badge {
      font-weight: 500;
      padding: 0.35em 0.65em;
    }
    
    .modal-content {
      border: none;
      border-radius: var(--border-radius);
      box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .btn-icon {
      width: 36px;
      height: 36px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }
    
    .btn-icon:hover {
      background-color: rgba(67, 97, 238, 0.1);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
    
    /* Animation for file operations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
      animation: fadeIn 0.3s ease-out forwards;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .card-header {
        padding: 1rem;
      }
      
      .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
      }
    }
  </style>
</head>
<body class="bg-light">

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Istanbul');

$baseDirectory = $_SERVER['DOCUMENT_ROOT'];
$adi = $_GET['adi'] ?? '';
$yol = $baseDirectory . '/' . $adi;
$dosyaModu = 'varsayilan';

if (file_exists($yol)) {
  if (is_dir($yol)) {
    $dosyalar = scandir($yol);
    $dosyaModu = 'klasor';
  } else {
    $dosyaModu = 'dosya';
  }
} elseif ($adi) {
  $dosyaModu = 'hata';
}

function formatBytes($bytes, $precision = 2) {
  $units = ['B', 'KB', 'MB', 'GB', 'TB'];
  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);
  $bytes /= (1 << (10 * $pow));
  return number_format($bytes, $precision, ',', '.') . ' ' . $units[$pow];
}

$renk = match($dosyaModu) {
  'klasor' => 'bg-warning',
  'dosya'  => 'bg-success',
  'hata'   => 'bg-danger',
  default  => 'bg-primary'
};

$icon = match($dosyaModu) {
  'klasor' => 'bi-folder2-open',
  'dosya'  => 'bi-file-earmark-text',
  'hata'   => 'bi-exclamation-triangle',
  default  => 'bi-hdd-rack'
};

$baslik = match($dosyaModu) {
  'klasor' => 'Klasör İçeriği',
  'dosya'  => 'Dosya Bilgileri',
  'hata'   => 'Hata',
  default  => 'Dosya Yöneticisi'
};

// Get parent directory
$parentDir = '';
if ($adi) {
  $pathParts = explode('/', rtrim($adi, '/'));
  array_pop($pathParts);
  $parentDir = implode('/', $pathParts);
}

?>

<div class="container py-5 " style="border: 1px solidrgb(166, 122, 50);">




      <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-grid text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dosya Bilgileri</h5>
                        <p class="text-muted small mb-0">Dosya bilgilerini görüntüleyin</p>
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
    
    <div class="card-body">
      <form action="liste.php" method="get" class="row g-3 mb-4">
        <div class="col-md-10">
          <input type="text" name="adi" id="dosya_adi"
            value="<?= isset($_GET['adi']) ? htmlspecialchars($_GET['adi']) : '' ?>"
            class="form-control" placeholder="Örn: data/veys/font" required>
        </div>
        <div class="col-md-2 d-grid">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-search me-1"></i> Ara
          </button>
        </div>
      </form>
      <hr>

      <?php if (isset($dosyaModu) && $dosyaModu === 'dosya'): ?>
        <?php $dosyaIcerik = @file_get_contents($yol); ?>
        <?php if ($dosyaIcerik !== false): ?>
          <div class='bg-white p-3 rounded shadow-sm mb-4'>
            <h6 class='mb-3'><i class='fas fa-file-alt me-2'></i> Dosya Detayı</h6>
            <p><strong>Yol:</strong> <?= htmlspecialchars($adi) ?></p>
            <p><strong>Boyut:</strong> <?= formatBytes(filesize($yol)) ?></p>
            <p><strong>Oluşturma:</strong> <?= date("d/m/Y H:i", fileatime($yol)) ?></p>
            <p><strong>Değişiklik:</strong> <?= date("d/m/Y H:i", filemtime($yol)) ?></p>
            <hr>
            <pre class='bg-dark text-white p-3 rounded'><?= htmlspecialchars($dosyaIcerik) ?></pre>
          </div>
        <?php else: ?>
          <div class='alert alert-danger'>Dosya okunamadı: <?= htmlspecialchars($adi) ?></div>
        <?php endif; ?>
      <?php elseif ($dosyaModu === 'hata'): ?>
        <div class='alert alert-warning'>
          <i class='fas fa-exclamation-triangle me-2'></i>
          Yol bulunamadı veya erişim izni yok: <?= htmlspecialchars($adi) ?>
        </div>
      <?php endif; ?>

      <?php if (isset($dosyalar) && is_array($dosyalar)): ?>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
          <i class="bi bi-files me-2"></i>Toplam <?= count($dosyalar) - 2 ?> öğe
        </h5>
        <div class="btn-group btn-group-sm" role="group">
          <button type="button" class="btn btn-outline-secondary active" id="gridViewBtn">
            <i class="bi bi-grid"></i> Izgara
          </button>
          <button type="button" class="btn btn-outline-secondary" id="listViewBtn">
            <i class="bi bi-list-ul"></i> Liste
          </button>
        </div>
      </div>
      
      <!-- Grid View -->
      <div id="gridView" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        <?php 
        $counter = 0;
        foreach ($dosyalar as $dosya): 
          if ($dosya === '.' || $dosya === '..') continue;
          $tamYol = $yol . '/' . $dosya;
          $isDir = is_dir($tamYol);
          $fileExt = !$isDir ? strtolower(pathinfo($dosya, PATHINFO_EXTENSION)) : '';
          $fileSize = !$isDir ? @filesize($tamYol) : 0;
          $fileMtime = @filemtime($tamYol);
          $fileAtime = @fileatime($tamYol);
          $filePerms = substr(sprintf('%o', @fileperms($tamYol)), -4);
          $fileType = mime_content_type($tamYol);
          
          // Get file icon based on type
          $fileIcon = 'bi-file-earmark';
          if ($isDir) {
            $fileIcon = 'bi-folder';
          } elseif (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            $fileIcon = 'bi-file-image';
          } elseif (in_array($fileExt, ['pdf'])) {
            $fileIcon = 'bi-file-pdf';
          } elseif (in_array($fileExt, ['doc', 'docx'])) {
            $fileIcon = 'bi-file-word';
          } elseif (in_array($fileExt, ['xls', 'xlsx', 'csv'])) {
            $fileIcon = 'bi-file-excel';
          } elseif (in_array($fileExt, ['zip', 'rar', '7z', 'tar', 'gz'])) {
            $fileIcon = 'bi-file-zip';
          }
          
          // Truncate long filenames
          $displayName = $dosya;
          if (mb_strlen($displayName) > 25) {
            $displayName = mb_substr($displayName, 0, 22) . '...';
          }
          
          // Get relative path for the link
          $relativePath = $adi ? $adi . '/' . $dosya : $dosya;
        ?>
        <div class="col">
          <div class="card h-100 file-item border-0 shadow-sm">
            <div class="card-body p-3">
              <div class="d-flex align-items-center mb-2">
                <div class="bg-soft-<?= $isDir ? 'warning' : 'primary' ?> rounded p-2 me-3">
                  <i class="bi <?= $fileIcon ?> text-<?= $isDir ? 'warning' : 'primary' ?> fs-3"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                  <h6 class="mb-0 text-truncate" title="<?= htmlspecialchars($dosya) ?>">
                    <?= htmlspecialchars($displayName) ?>
                  </h6>
                  <small class="text-muted">
                    <?= $isDir ? 'Klasör' : strtoupper($fileExt) . ' Dosyası' ?>
                  </small>
                </div>
              </div>
              
              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                  <i class="bi bi-clock-history me-1"></i>
                  <?= date('d.m.Y H:i', $fileMtime) ?>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Dosya İşlemleri">
                  <a href="liste.php?adi=<?= rawurlencode($relativePath) ?>" 
                    class="btn btn-outline-primary" title="Aç">
                    <i class="bi bi-folder2-open"></i>
                  </a>
                  <?php if (!$isDir): ?>
                  <button type="button" class="btn btn-outline-primary" 
                    onclick="downloadFile('<?= rawurlencode($relativePath) ?>')" title="İndir">
                    <i class="bi bi-download"></i>
                  </button>
                  <?php endif; ?>
                  <button type="button" class="btn btn-outline-primary" 
                    data-bs-toggle="modal" data-bs-target="#fileInfoModal" 
                    data-name="<?= htmlspecialchars($dosya) ?>"
                    data-path="<?= htmlspecialchars($relativePath) ?>"
                    data-size="<?= $fileSize ?>"
                    data-type="<?= $fileType ?>"
                    data-perms="<?= $filePerms ?>"
                    data-created="<?= date('d.m.Y H:i', $fileAtime) ?>"
                    data-modified="<?= date('d.m.Y H:i', $fileMtime) ?>"
                    title="Bilgiler">
                    <i class="bi bi-info-circle"></i>
                  </button>
                  <?php if (!$isDir && in_array($fileExt, ['php', 'js', 'css', 'html', 'json', 'xml', 'txt'])): ?>
                  <button type="button" class="btn btn-outline-primary" 
                    data-bs-toggle="modal" data-bs-target="#codeViewerModal" 
                    data-filepath="<?= rawurlencode($relativePath) ?>"
                    title="Kodu Görüntüle">
                    <i class="bi bi-code-square"></i>
                  </button>
                  <?php endif; ?>
                  <button type="button" class="btn btn-outline-danger" 
                    onclick="return confirmDelete('<?= rawurlencode($relativePath) ?>')"
                    title="Sil">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      
      <!-- List View (initially hidden) -->
      
      <?php endif; ?>
    </div>
  </div>

  <footer class="text-muted text-center mt-5 small">
    <hr>
    © <?= date('Y') ?> buraksariguzeldev – Dizin görüntüleyici
  </footer>
</div>

<!-- File Info Modal -->
<div class="modal fade" id="fileInfoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Dosya Bilgileri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <h6 class="text-muted mb-1">Dosya Adı</h6>
          <p id="infoFileName" class="mb-0"></p>
        </div>
        <div class="mb-3">
          <h6 class="text-muted mb-1">Yol</h6>
          <p id="infoFilePath" class="mb-0 text-truncate"></p>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <h6 class="text-muted mb-1">Boyut</h6>
            <p id="infoFileSize" class="mb-0"></p>
          </div>
          <div class="col-md-6 mb-3">
            <h6 class="text-muted mb-1">Tür</h6>
            <p id="infoFileType" class="mb-0"></p>
          </div>
          <div class="col-md-6 mb-3">
            <h6 class="text-muted mb-1">İzinler</h6>
            <p id="infoFilePerms" class="mb-0"></p>
          </div>
          <div class="col-md-6 mb-3">
            <h6 class="text-muted mb-1">Oluşturulma </h6>
            <p id="infoFileCreated" class="mb-0"></p>
          </div>
          <div class="col-12 mb-3">
            <h6 class="text-muted mb-1">Değiştirilme</h6>
            <p id="infoFileModified" class="mb-0"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <a href="#" id="downloadFileBtn" class="btn btn-primary">
          <i class="bi bi-download me-1"></i>İndir
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Code Viewer Modal -->
<div class="modal fade" id="codeViewerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-code-square me-2"></i>Kod Görüntüleyici</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body p-0">
        <div class="d-flex align-items-center px-3 py-2 border-bottom">
          <small class="text-muted" id="codeViewerFileName"></small>
          <div class="ms-auto">
            <button class="btn btn-sm btn-outline-secondary" id="copyCodeBtn" title="Kopyala">
              <i class="bi bi-clipboard"></i>
            </button>
          </div>
        </div>
        <pre class="m-0"><code id="codeViewerContent" class="language-php"></code></pre>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-upload me-2"></i>Dosya Yükle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="mb-3">
            <label for="fileToUpload" class="form-label">Dosya Seçin</label>
            <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" required>
            <input type="hidden" name="currentDir" value="<?= htmlspecialchars($adi) ?>">
          </div>
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="overwriteFile" name="overwrite">
            <label class="form-check-label" for="overwriteFile">Var olan dosyanın üzerine yaz</label>
          </div>
          <div class="progress d-none" id="uploadProgress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
          <button type="submit" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" id="uploadSpinner" role="status" aria-hidden="true"></span>
            <span id="uploadBtnText">Yükle</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- New Folder Modal -->
<div class="modal fade" id="newFolderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-folder-plus me-2"></i>Yeni Klasör</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <form id="newFolderForm" action="create_folder.php" method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label for="folderName" class="form-label">Klasör Adı</label>
            <input type="text" class="form-control" id="folderName" name="folderName" required>
            <input type="hidden" name="currentDir" value="<?= htmlspecialchars($adi) ?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
          <button type="submit" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" id="folderSpinner" role="status" aria-hidden="true"></span>
            Oluştur
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-xml-doc.min.js"></script>

<script>
// View Toggle
const gridViewBtn = document.getElementById('gridViewBtn');
const listViewBtn = document.getElementById('listViewBtn');
const gridView = document.getElementById('gridView');
const listView = document.getElementById('listView');

if (gridViewBtn && listViewBtn && gridView && listView) {
  gridViewBtn.addEventListener('click', () => {
    gridView.classList.remove('d-none');
    listView.classList.add('d-none');
    gridViewBtn.classList.add('active');
    listViewBtn.classList.remove('active');
    localStorage.setItem('fileManagerView', 'grid');
  });
  
  listViewBtn.addEventListener('click', () => {
    listView.classList.remove('d-none');
    gridView.classList.add('d-none');
    listViewBtn.classList.add('active');
    gridViewBtn.classList.remove('active');
    localStorage.setItem('fileManagerView', 'list');
  });
  
  // Load saved view preference
  const savedView = localStorage.getItem('fileManagerView') || 'grid';
  if (savedView === 'list') {
    listViewBtn.click();
  }
}

// File Info Modal
const fileInfoModal = document.getElementById('fileInfoModal');
if (fileInfoModal) {
  fileInfoModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const modal = this;
    
    modal.querySelector('#infoFileName').textContent = button.getAttribute('data-name');
    modal.querySelector('#infoFilePath').textContent = button.getAttribute('data-path');
    modal.querySelector('#infoFileSize').textContent = formatFileSize(button.getAttribute('data-size'));
    modal.querySelector('#infoFileType').textContent = button.getAttribute('data-type');
    modal.querySelector('#infoFilePerms').textContent = button.getAttribute('data-perms');
    modal.querySelector('#infoFileCreated').textContent = button.getAttribute('data-created');
    modal.querySelector('#infoFileModified').textContent = button.getAttribute('data-modified');
    
    // Set download link
    const downloadBtn = modal.querySelector('#downloadFileBtn');
    downloadBtn.href = 'download.php?file=' + encodeURIComponent(button.getAttribute('data-path'));
  });
}

// Code Viewer Modal
const codeViewerModal = document.getElementById('codeViewerModal');
if (codeViewerModal) {
  codeViewerModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const filePath = button.getAttribute('data-filepath');
    const modal = this;
    const fileName = filePath.split('/').pop();
    const fileExt = fileName.split('.').pop().toLowerCase();
    
    modal.querySelector('#codeViewerFileName').textContent = filePath;
    
    // Show loading state
    const codeElement = modal.querySelector('#codeViewerContent');
    codeElement.textContent = 'Yükleniyor...';
    
    // Fetch file content
    fetch('get_file_content.php?file=' + encodeURIComponent(filePath))
      .then(response => response.text())
      .then(content => {
        codeElement.textContent = content;
        codeElement.className = ''; // Reset class
        codeElement.classList.add('language-' + (fileExt === 'php' ? 'php' : fileExt));
        Prism.highlightElement(codeElement);
      })
      .catch(error => {
        console.error('Error loading file:', error);
        codeElement.textContent = 'Dosya yüklenirken bir hata oluştu.';
      });
  });
  
  // Initialize clipboard.js for copy button
  new ClipboardJS('#copyCodeBtn', {
    target: function() {
      return document.querySelector('#codeViewerContent');
    }
  });
  
  // Show tooltip when copied
  const copyBtn = document.getElementById('copyCodeBtn');
  if (copyBtn) {
    copyBtn.addEventListener('click', function() {
      const tooltip = new bootstrap.Tooltip(copyBtn, {
        title: 'Kopyalandı!',
        trigger: 'manual',
        placement: 'bottom'
      });
      
      tooltip.show();
      setTimeout(() => tooltip.hide(), 1000);
    });
  }
}

// File upload form
const uploadForm = document.getElementById('uploadForm');
if (uploadForm) {
  uploadForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const progressBar = document.querySelector('#uploadProgress .progress-bar');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadSpinner = document.getElementById('uploadSpinner');
    const uploadBtnText = document.getElementById('uploadBtnText');
    
    // Show loading state
    uploadSpinner.classList.remove('d-none');
    uploadBtnText.textContent = 'Yükleniyor...';
    uploadProgress.classList.remove('d-none');
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);
    
    xhr.upload.onprogress = function(e) {
      if (e.lengthComputable) {
        const percentComplete = Math.round((e.loaded / e.total) * 100);
        progressBar.style.width = percentComplete + '%';
        progressBar.setAttribute('aria-valuenow', percentComplete);
      }
    };
    
    xhr.onload = function() {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Show success message and reload
          showAlert('Dosya başarıyla yüklendi!', 'success');
          setTimeout(() => location.reload(), 1000);
        } else {
          showAlert(response.message || 'Dosya yüklenirken bir hata oluştu.', 'danger');
        }
      } else {
        showAlert('Dosya yüklenirken bir hata oluştu.', 'danger');
      }
      
      // Reset form and loading state
      uploadForm.reset();
      uploadSpinner.classList.add('d-none');
      uploadBtnText.textContent = 'Yükle';
      uploadProgress.classList.add('d-none');
      progressBar.style.width = '0%';
      progressBar.setAttribute('aria-valuenow', 0);
    };
    
    xhr.send(formData);
  });
}

// New folder form
const newFolderForm = document.getElementById('newFolderForm');
if (newFolderForm) {
  newFolderForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const folderSpinner = document.getElementById('folderSpinner');
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Show loading state
    folderSpinner.classList.remove('d-none');
    submitBtn.disabled = true;
    
    fetch('create_folder.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showAlert('Klasör başarıyla oluşturuldu!', 'success');
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert(data.message || 'Klasör oluşturulurken bir hata oluştu.', 'danger');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showAlert('Bir hata oluştu. Lütfen tekrar deneyin.', 'danger');
    })
    .finally(() => {
      folderSpinner.classList.add('d-none');
      submitBtn.disabled = false;
    });
  });
}

// Helper functions
function formatFileSize(bytes) {
  if (bytes === 0) return '0 B';
  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function downloadFile(filePath) {
  window.location.href = 'download.php?file=' + encodeURIComponent(filePath);
  return false;
}

function confirmDelete(filePath) {
  if (confirm('Bu öğeyi silmek istediğinizden emin misiniz?\n\n' + filePath)) {
    window.location.href = 'delete.php?file=' + encodeURIComponent(filePath);
  }
  return false;
}

function showAlert(message, type = 'info') {
  const alertDiv = document.createElement('div');
  alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-3 end-3`;
  alertDiv.role = 'alert';
  alertDiv.style.zIndex = '9999';
  alertDiv.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  `;
  
  document.body.appendChild(alertDiv);
  
  // Auto-remove after 5 seconds
  setTimeout(() => {
    const bsAlert = new bootstrap.Alert(alertDiv);
    bsAlert.close();
  }, 5000);
}

// Initialize tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Initialize popovers
const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl);
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/footer.php'; ?>
</body>
</html>
