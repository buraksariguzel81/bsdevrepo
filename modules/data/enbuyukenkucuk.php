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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>En Büyük ve En Küçük Dosya / Klasör</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .file-card {
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
      border: none;
      border-radius: 0.75rem;
      overflow: hidden;
    }
    .file-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .file-icon {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      opacity: 0.9;
    }
    .file-size {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0.5rem 0;
    }
    .file-path {
      color: #6c757d;
      font-size: 0.9rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .badge-file-type {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 0.7rem;
      padding: 0.25rem 0.5rem;
      border-radius: 50rem;
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
                        <h5 class="mb-1 fw-bold text-dark">En Büyük ve En Küçük Dosya / Klasör</h5>
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






  

      <?php
      $baseDirectory = $_SERVER['DOCUMENT_ROOT'];
      $files = glob($baseDirectory . '/*');

      $largestFile = $smallestFile = $largestFolder = $smallestFolder = null;
      $largestFileSize = $smallestFileSize = $largestFolderSize = $smallestFolderSize = null;
      $totalFileCount = $totalFolderCount = 0;

      function formatSize($size) {
        $units = ['bytes', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
          $size /= 1024;
          $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
      }

      foreach ($files as $file) {
        if (is_file($file)) {
          $totalFileCount++;
          $size = filesize($file);
          if ($largestFileSize === null || $size > $largestFileSize) {
            $largestFile = $file;
            $largestFileSize = $size;
          }
          if ($smallestFileSize === null || $size < $smallestFileSize) {
            $smallestFile = $file;
            $smallestFileSize = $size;
          }
        } elseif (is_dir($file)) {
          $totalFolderCount++;
          $folderSize = 0;
          foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($file, FilesystemIterator::SKIP_DOTS)) as $f) {
            if ($f->isFile()) $folderSize += $f->getSize();
          }
          if ($largestFolderSize === null || $folderSize > $largestFolderSize) {
            $largestFolder = $file;
            $largestFolderSize = $folderSize;
          }
          if ($smallestFolderSize === null || $folderSize < $smallestFolderSize) {
            $smallestFolder = $file;
            $smallestFolderSize = $folderSize;
          }
        }
      }
      ?>

      <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
          <div class="card file-card h-100 border-primary">
            <div class="card-body text-center position-relative">
              <span class="badge bg-primary badge-file-type">Dosya</span>
              <i class="bi bi-file-earmark-arrow-up-fill text-primary file-icon"></i>
              <h5 class="card-title text-primary">En Büyük Dosya</h5>
              <div class="file-size"><?= $largestFile ? formatSize($largestFileSize) : 'Yok' ?></div>
              <p class="file-path" title="<?= $largestFile ? htmlspecialchars($largestFile) : '' ?>">
                <?= $largestFile ? htmlspecialchars(basename($largestFile)) : 'Dosya bulunamadı' ?>
              </p>
              <?php if ($largestFile): ?>
              <button class="btn btn-sm btn-outline-primary mt-2" onclick="showFileInfo('<?= htmlspecialchars(addslashes($largestFile)) ?>')">
                <i class="bi bi-info-circle"></i> Detaylar
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
          <div class="card file-card h-100 border-success">
            <div class="card-body text-center position-relative">
              <span class="badge bg-success badge-file-type">Dosya</span>
              <i class="bi bi-file-earmark-arrow-down-fill text-success file-icon"></i>
              <h5 class="card-title text-success">En Küçük Dosya</h5>
              <div class="file-size"><?= $smallestFile ? formatSize($smallestFileSize) : 'Yok' ?></div>
              <p class="file-path" title="<?= $smallestFile ? htmlspecialchars($smallestFile) : '' ?>">
                <?= $smallestFile ? htmlspecialchars(basename($smallestFile)) : 'Dosya bulunamadı' ?>
              </p>
              <?php if ($smallestFile): ?>
              <button class="btn btn-sm btn-outline-success mt-2" onclick="showFileInfo('<?= htmlspecialchars(addslashes($smallestFile)) ?>')">
                <i class="bi bi-info-circle"></i> Detaylar
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card file-card h-100 border-warning">
            <div class="card-body text-center position-relative">
              <span class="badge bg-warning text-dark badge-file-type">Klasör</span>
              <i class="bi bi-folder-plus-fill text-warning file-icon"></i>
              <h5 class="card-title text-warning">En Büyük Klasör</h5>
              <div class="file-size"><?= $largestFolder ? formatSize($largestFolderSize) : 'Yok' ?></div>
              <p class="file-path" title="<?= $largestFolder ? htmlspecialchars($largestFolder) : '' ?>">
                <?= $largestFolder ? htmlspecialchars(basename($largestFolder)) : 'Klasör bulunamadı' ?>
              </p>
              <?php if ($largestFolder): ?>
              <button class="btn btn-sm btn-outline-warning mt-2" onclick="showFolderInfo('<?= htmlspecialchars(addslashes($largestFolder)) ?>')">
                <i class="bi bi-folder-symlink"></i> İncele
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
          <div class="card file-card h-100 border-info">
            <div class="card-body text-center position-relative">
              <span class="badge bg-info text-dark badge-file-type">Klasör</span>
              <i class="bi bi-folder-minus-fill text-info file-icon"></i>
              <h5 class="card-title text-info">En Küçük Klasör</h5>
              <div class="file-size"><?= $smallestFolder ? formatSize($smallestFolderSize) : 'Yok' ?></div>
              <p class="file-path" title="<?= $smallestFolder ? htmlspecialchars($smallestFolder) : '' ?>">
                <?= $smallestFolder ? htmlspecialchars(basename($smallestFolder)) : 'Klasör bulunamadı' ?>
              </p>
              <?php if ($smallestFolder): ?>
              <button class="btn btn-sm btn-outline-info mt-2" onclick="showFolderInfo('<?= htmlspecialchars(addslashes($smallestFolder)) ?>')">
                <i class="bi bi-folder-symlink"></i> İncele
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-4">
        <p><i class="fas fa-file me-1"></i> Toplam Dosya Sayısı: <strong><?= $totalFileCount ?></strong></p>
        <p><i class="fas fa-folder me-1"></i> Toplam Klasör Sayısı: <strong><?= $totalFolderCount ?></strong></p>
      </div>
    </div>
  </div>

  <footer class="text-center text-muted mt-5 small">
    <hr>
    © <?= date('Y') ?> buraksariguzeldev – Dosya boyut analizi modülü
  </footer>
</div>

<!-- File Info Modal -->
<div class="modal fade" id="fileInfoModal" tabindex="-1" aria-labelledby="fileInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="fileInfoModalLabel">Dosya Bilgileri</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <div id="fileInfoContent" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Yükleniyor...</span>
          </div>
          <p class="mt-2">Dosya bilgileri yükleniyor...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-primary" id="openFileBtn" style="display: none;">
          <i class="bi bi-folder2-open"></i> Klasörde Göster
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Folder Info Modal -->
<div class="modal fade" id="folderInfoModal" tabindex="-1" aria-labelledby="folderInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="folderInfoModalLabel">Klasör İçeriği</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <div id="folderInfoContent" class="text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Yükleniyor...</span>
          </div>
          <p class="mt-2">Klasör içeriği yükleniyor...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        <button type="button" class="btn btn-primary" id="openFolderBtn" style="display: none;">
          <i class="bi bi-folder2-open"></i> Klasörü Aç
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
  // Enable tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
  
  // Initialize modals
  window.fileInfoModal = new bootstrap.Modal(document.getElementById('fileInfoModal'));
  window.folderInfoModal = new bootstrap.Modal(document.getElementById('folderInfoModal'));
});

// Show file information
function showFileInfo(filePath) {
  const modal = document.getElementById('fileInfoModal');
  const modalTitle = modal.querySelector('.modal-title');
  const contentDiv = document.getElementById('fileInfoContent');
  const openBtn = document.getElementById('openFileBtn');
  
  // Show loading state
  contentDiv.innerHTML = `
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Yükleniyor...</span>
    </div>
    <p class="mt-2">Dosya bilgileri yükleniyor...</p>
  `;
  
  // Set modal title
  modalTitle.textContent = 'Dosya Bilgileri: ' + filePath.split('/').pop();
  
  // In a real application, you would fetch this data from the server
  setTimeout(() => {
    const fileName = filePath.split('/').pop();
    const fileExt = fileName.includes('.') ? fileName.split('.').pop().toUpperCase() : 'Dosya';
    const fileSize = Math.floor(Math.random() * 1024 * 1024 * 10); // Random size for demo
    const createdDate = new Date();
    const modifiedDate = new Date();
    
    contentDiv.innerHTML = `
      <div class="text-center mb-4">
        <i class="bi bi-file-earmark-text display-1 text-primary"></i>
        <h4 class="mt-3">${fileName}</h4>
        <span class="badge bg-primary">${fileExt} Dosyası</span>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
            <th class="text-nowrap"><i class="bi bi-hdd"></i> Konum</th>
            <td>${filePath}</td>
          </tr>
          <tr>
            <th class="text-nowrap"><i class="bi bi-file-earmark-text"></i> Boyut</th>
            <td>${formatBytes(fileSize)}</td>
          </tr>
          <tr>
            <th class="text-nowrap"><i class="bi bi-calendar-plus"></i> Oluşturulma</th>
            <td>${createdDate.toLocaleString()}</td>
          </tr>
          <tr>
            <th class="text-nowrap"><i class="bi bi-pencil"></i> Değiştirilme</th>
            <td>${modifiedDate.toLocaleString()}</td>
          </tr>
          <tr>
            <th class="text-nowrap"><i class="bi bi-shield-lock"></i> İzinler</th>
            <td>-rw-r--r--</td>
          </tr>
        </table>
      </div>
    `;
    
    // Show open button and set its click handler
    openBtn.style.display = 'inline-block';
    openBtn.onclick = function() {
      // In a real app, this would open the file in the system file explorer
      alert('Dosya konumu açılıyor: ' + filePath);
    };
    
    // Show the modal
    window.fileInfoModal.show();
  }, 800);
}

// Show folder information
function showFolderInfo(folderPath) {
  const modal = document.getElementById('folderInfoModal');
  const modalTitle = modal.querySelector('.modal-title');
  const contentDiv = document.getElementById('folderInfoContent');
  const openBtn = document.getElementById('openFolderBtn');
  
  // Show loading state
  contentDiv.innerHTML = `
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Yükleniyor...</span>
    </div>
    <p class="mt-2">Klasör içeriği yükleniyor...</p>
  `;
  
  // Set modal title
  modalTitle.textContent = 'Klasör İçeriği: ' + folderPath.split('/').pop();
  
  // In a real application, you would fetch this data from the server
  setTimeout(() => {
    // Generate some sample files and folders for demo
    const items = [];
    const fileTypes = ['jpg', 'png', 'pdf', 'docx', 'xlsx', 'mp4', 'mp3', 'zip'];
    const fileNames = ['rapor', 'fatura', 'fotoğraf', 'döküman', 'sunum', 'tablo', 'müzik', 'video'];
    
    // Add some folders
    for (let i = 1; i <= 3; i++) {
      items.push({
        name: `Klasör ${i}`,
        type: 'folder',
        size: Math.floor(Math.random() * 1024 * 1024 * 5),
        items: Math.floor(Math.random() * 50),
        modified: new Date(Date.now() - Math.floor(Math.random() * 30) * 24 * 60 * 60 * 1000)
      });
    }
    
    // Add some files
    for (let i = 1; i <= 7; i++) {
      const type = fileTypes[Math.floor(Math.random() * fileTypes.length)];
      const name = fileNames[Math.floor(Math.random() * fileNames.length)] + '_' + 
                  (Math.floor(Math.random() * 10) + 1) + '.' + type;
      
      items.push({
        name: name,
        type: type,
        size: Math.floor(Math.random() * 1024 * 1024 * 10),
        modified: new Date(Date.now() - Math.floor(Math.random() * 30) * 24 * 60 * 60 * 1000)
      });
    }
    
    // Sort items (folders first, then files)
    items.sort((a, b) => {
      if (a.type === 'folder' && b.type !== 'folder') return -1;
      if (a.type !== 'folder' && b.type === 'folder') return 1;
      return a.name.localeCompare(b.name);
    });
    
    // Generate table rows
    let tableRows = '';
    items.forEach(item => {
      const icon = item.type === 'folder' ? 'bi-folder-fill text-warning' : 
                 `bi-file-earmark-${getFileIcon(item.type)}`;
      
      tableRows += `
        <tr>
          <td class="text-start">
            <i class="bi ${icon} me-2"></i>
            ${item.name}
          </td>
          <td class="text-nowrap">${item.type === 'folder' ? 'Klasör' : item.type.toUpperCase() + ' Dosyası'}</td>
          <td class="text-nowrap text-end">${item.type === 'folder' ? item.items + ' öğe' : formatBytes(item.size)}</td>
          <td class="text-nowrap text-end">${item.modified.toLocaleDateString()}</td>
        </tr>
      `;
    });
    
    contentDiv.innerHTML = `
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th class="text-start">İsim</th>
              <th>Tür</th>
              <th class="text-end">Boyut</th>
              <th class="text-end">Değiştirilme</th>
            </tr>
          </thead>
          <tbody>
            ${tableRows}
          </tbody>
        </table>
      </div>
      <div class="text-muted small mt-2">
        Toplam ${items.length} öğe (${items.filter(i => i.type === 'folder').length} klasör, ${items.filter(i => i.type !== 'folder').length} dosya)
      </div>
    `;
    
    // Show open button and set its click handler
    openBtn.style.display = 'inline-block';
    openBtn.onclick = function() {
      // In a real app, this would open the folder in the system file explorer
      alert('Klasör açılıyor: ' + folderPath);
    };
    
    // Show the modal
    window.folderInfoModal.show();
  }, 1000);
}

// Helper function to get appropriate file icon
function getFileIcon(ext) {
  const icons = {
    'jpg': 'image text-info',
    'jpeg': 'image text-info',
    'png': 'image text-info',
    'gif': 'image text-info',
    'pdf': 'file-pdf text-danger',
    'doc': 'file-word text-primary',
    'docx': 'file-word text-primary',
    'xls': 'file-excel text-success',
    'xlsx': 'file-excel text-success',
    'ppt': 'file-ppt text-warning',
    'pptx': 'file-ppt text-warning',
    'zip': 'file-zip text-muted',
    'rar': 'file-zip text-muted',
    'mp3': 'file-music text-primary',
    'mp4': 'file-play text-danger',
    'txt': 'file-text text-secondary',
    'html': 'file-code text-info',
    'css': 'file-code text-info',
    'js': 'file-code text-warning',
    'php': 'file-code text-primary',
    'sql': 'file-code text-dark'
  };
  
  const icon = icons[ext.toLowerCase()];
  return icon || 'file-earmark text-secondary';
}

// Format bytes to human-readable format
function formatBytes(bytes, decimals = 2) {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
</script>
</body>
</html>
