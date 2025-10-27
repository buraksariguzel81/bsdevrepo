<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Rol kontrolü
$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
  include($rol_kontrol_path);
  if (function_exists('rol_kontrol')) {
    rol_kontrol(1);
  }
}

// Mesaj değişkeni
$message = '';
$messageClass = '';

// Rekürsif klasör silme fonksiyonu
function deleteDirectory($dir) {
  if (!is_dir($dir)) return unlink($dir);
  $items = scandir($dir);
  foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;
    deleteDirectory($dir . DIRECTORY_SEPARATOR . $item);
  }
  return rmdir($dir);
}

// Silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $baseDirectory = $_SERVER['DOCUMENT_ROOT'];
  $dir = trim($_POST['dir']);
  $name = trim($_POST['name']);

  if (strpos($dir, '..') !== false || strpos($name, '..') !== false) {
    $message = "Erişim reddedildi.";
    $messageClass = 'danger';
  } else {
    $fullPath = rtrim($baseDirectory, '/') . '/' . ltrim($dir, '/');
    if (is_dir($fullPath)) {
      $targetPath = $fullPath . '/' . $name;
      if (file_exists($targetPath)) {
        if (is_dir($targetPath)) {
          if (deleteDirectory($targetPath)) {
            $message = "Klasör başarıyla silindi.";
            $messageClass = 'success';
          } else {
            $message = "Klasör silinirken hata oluştu.";
            $messageClass = 'danger';
          }
        } else {
          if (unlink($targetPath)) {
            $message = "Dosya başarıyla silindi.";
            $messageClass = 'success';
          } else {
            $message = "Dosya silinirken hata oluştu.";
            $messageClass = 'danger';
          }
        }
      } else {
        $message = "Hedef dosya veya klasör bulunamadı.";
        $messageClass = 'warning';
      }
    } else {
      $message = "Dizin bulunamadı.";
      $messageClass = 'warning';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dosya / Klasör Silme Paneli</title>
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/src/img/favicon.png">
  
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  
  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  
  <style>
    :root {
      --primary: #dc3545;
      --primary-hover: #bb2d3b;
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
      background: linear-gradient(135deg, var(--primary) 0%, #b02a37 100%);
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
    
    .btn-danger {
      background-color: var(--primary);
      border-color: var(--primary);
      transition: all 0.2s;
    }
    
    .btn-danger:hover {
      background-color: var(--primary-hover);
      border-color: var(--primary-hover);
      transform: translateY(-1px);
    }
    
    .btn-outline-danger {
      color: var(--primary);
      border-color: var(--primary);
    }
    
    .btn-outline-danger:hover {
      background-color: var(--primary);
      border-color: var(--primary);
    }
    
    .form-control:focus, .form-select:focus {
      border-color: rgba(220, 53, 69, 0.4);
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.1);
    }
    
    .file-preview {
      background: #f8f9fa;
      border: 2px dashed #dee2e6;
      border-radius: 8px;
      padding: 1.5rem;
      text-align: center;
      margin-bottom: 1.5rem;
      transition: all 0.3s;
    }
    
    .file-preview:hover {
      border-color: var(--primary);
      background: #fff8f8;
    }
    
    .file-icon {
      font-size: 3rem;
      color: var(--primary);
      margin-bottom: 1rem;
    }
    
    .file-path {
      background: #fff;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      padding: 0.5rem;
      font-family: monospace;
      word-break: break-all;
      text-align: left;
      margin: 1rem 0;
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
                        <i class="bi bi-trash text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dosya Silme</h5>
                        <p class="text-muted small mb-0">Sistem dosyalarını silmek için araçlar</p>
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
            <i class="fas fa-trash-alt me-2"></i>
            <span class="d-none d-md-inline">Dosya / Klasör Sil</span>
          </span>
          <span class="badge bg-danger">Dikkat!</span>
        </div>
        
        <div class="card-body">
          <!-- Uyarı Mesajı -->
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
              Bu panel üzerinden yapılan silme işlemleri <strong>geri alınamaz</strong>. Lütfen silmek istediğiniz dosya veya klasörü dikkatle seçin.
            </div>
          </div>
          
          <!-- Silinecek Öğe Önizleme -->
          <div class="file-preview" id="filePreview">
            <div class="file-icon">
              <i class="fas fa-question-circle"></i>
            </div>
            <h5>Silinecek Öğe</h5>
            <p class="text-muted">Aşağıdaki bilgileri doldurduktan sonra önizleme burada görünecektir.</p>
            <div class="file-path d-none" id="previewPath"></div>
          </div>

          <!-- Form -->
          <form method="post" id="deleteForm" onsubmit="return confirmDelete();">
            <div class="mb-4">
              <label for="dir" class="form-label fw-bold">
                <i class="fas fa-folder-open me-2"></i> Dizin Yolu
              </label>
              <div class="input-group">
                <span class="input-group-text"><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>/</span>
                <input type="text" 
                      class="form-control" 
                      id="dir" 
                      name="dir" 
                      placeholder="modules/data" 
                      required
                      oninput="updatePreview()">
              </div>
              <div class="form-text">Silinecek dosya veya klasörün bulunduğu dizin</div>
            </div>

            <div class="mb-4">
              <!-- Silme Türü Seçimi -->
              <div class="mb-3">
                <label class="form-label fw-bold d-block">
                  <i class="fas fa-trash-alt me-2"></i> Silme Türü
                </label>
                <div class="btn-group w-100" role="group">
                  <input type="radio" class="btn-check" name="deleteType" id="deleteFile" value="file" autocomplete="off" checked onchange="updatePreview()">
                  <label class="btn btn-outline-danger" for="deleteFile">
                    <i class="fas fa-file me-2"></i>Dosya Sil
                  </label>
                  
                  <input type="radio" class="btn-check" name="deleteType" id="deleteFolder" value="folder" autocomplete="off" onchange="updatePreview()">
                  <label class="btn btn-outline-danger" for="deleteFolder">
                    <i class="fas fa-folder me-2"></i>Klasör Sil
                  </label>
                </div>
              </div>

              <!-- Dosya/Klasör Adı -->
              <div class="mb-4">
                <label for="name" class="form-label fw-bold">
                  <i class="fas fa-file me-2"></i> <span id="nameLabel">Dosya</span> Adı
                </label>
                <div class="input-group">
                  <span class="input-group-text" id="dirPrefix"><?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>/<span id="dirDisplay"></span></span>
                  <input type="text" 
                        class="form-control" 
                        id="name" 
                        name="name" 
                        placeholder="eski_dosya.php" 
                        required
                        oninput="updatePreview()">
                </div>
                <div class="form-text" id="nameHelp">Silinecek dosyanın adını ve uzantısını yazın (örn: eski_dosya.php)</div>
              </div>
            </div>

            <!-- Onay Kutusu -->
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" id="confirmDelete" required>
              <label class="form-check-label" for="confirmDelete">
                Bu işlemin geri alınamayacağını anlıyorum
              </label>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-danger btn-lg">
                <i class="fas fa-trash-alt me-2"></i> Silme İşlemini Onayla
              </button>
              <a href="custom.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> İptal ve Geri Dön
              </a>
            </div>
          </form>

          <!-- Geri Bildirim -->
          <?php if ($message): ?>
            <div class="mt-4 alert alert-<?= $messageClass ?> alert-dismissible fade show" role="alert">
              <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : ($messageClass === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle') ?> me-2"></i>
              <?= htmlspecialchars($message) ?>
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
// Update file preview
function updatePreview() {
  const dir = document.getElementById('dir').value.trim();
  const name = document.getElementById('name').value.trim();
  const deleteType = document.querySelector('input[name="deleteType"]:checked').value;
  const nameLabel = document.getElementById('nameLabel');
  const nameHelp = document.getElementById('nameHelp');
  const dirDisplay = document.getElementById('dirDisplay');
  const previewPath = document.getElementById('previewPath');
  const fileIcon = document.querySelector('#filePreview .file-icon i');
  const fileTitle = document.querySelector('#filePreview h5');
  const fileDescription = document.querySelector('#filePreview p');
  
  // Update UI based on delete type
  if (deleteType === 'file') {
    nameLabel.textContent = 'Dosya';
    nameHelp.textContent = 'Silinecek dosyanın adını ve uzantısını yazın (örn: eski_dosya.php)';
    document.getElementById('name').placeholder = 'eski_dosya.php';
  } else {
    nameLabel.textContent = 'Klasör';
    nameHelp.textContent = 'Silinecek klasörün adını yazın';
    document.getElementById('name').placeholder = 'eski_klasor';
  }
  
  // Update directory display
  dirDisplay.textContent = dir ? dir + '/' : '';
  
  if (dir || name) {
    const fullPath = '<?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>' + 
                    (dir ? '/' + dir : '') + 
                    (dir && name ? '/' : '') + 
                    (name ? name : '');
    
    previewPath.textContent = fullPath;
    previewPath.classList.remove('d-none');
    
    // Update icon and text based on delete type and name
    if (deleteType === 'file' || (name && name.includes('.'))) {
      // It's a file
      const ext = name.split('.').pop().toLowerCase();
      fileIcon.className = 'fas fa-file';
      
      // Set specific icons for common file types
      if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) {
        fileIcon.className = 'fas fa-file-image';
      } else if (['pdf'].includes(ext)) {
        fileIcon.className = 'fas fa-file-pdf';
      } else if (['doc', 'docx'].includes(ext)) {
        fileIcon.className = 'fas fa-file-word';
      } else if (['xls', 'xlsx'].includes(ext)) {
        fileIcon.className = 'fas fa-file-excel';
      } else if (['php', 'html', 'css', 'js', 'json', 'xml'].includes(ext)) {
        fileIcon.className = 'fas fa-file-code';
      }
      
      fileTitle.textContent = 'Dosya Silinecek';
      fileDescription.textContent = 'Aşağıdaki dosya kalıcı olarak silinecektir:';
    } else {
      // It's a folder
      fileIcon.className = 'fas fa-folder';
      fileTitle.textContent = 'Klasör Silinecek';
      fileDescription.textContent = 'Aşağıdaki klasör ve tüm içeriği kalıcı olarak silinecektir:';
    }
    } else {
      fileTitle.textContent = 'Silinecek Öğe';
      fileDescription.textContent = 'Lütfen silmek istediğiniz dosya veya klasörü seçin.';
      previewPath.classList.add('d-none');
    }
  } else {
    fileIcon.className = 'fas fa-question-circle';
    fileTitle.textContent = 'Silinecek Öğe';
    fileDescription.textContent = 'Aşağıdaki bilgileri doldurduktan sonra önizleme burada görünecektir.';
    previewPath.classList.add('d-none');
  }
}

// Confirm delete
function confirmDelete() {
  const dir = document.getElementById('dir').value.trim();
  const name = document.getElementById('name').value.trim();
  const deleteType = document.querySelector('input[name="deleteType"]:checked').value;
  
  if (!dir || !name) {
    return false;
  }
  
  const fullPath = '<?= htmlspecialchars($_SERVER['DOCUMENT_ROOT']) ?>/' + 
                  (dir ? dir + '/' : '') + 
                  name;
  
  const itemType = deleteType === 'file' ? 'dosyayı' : 'klasörü';
  const warning = deleteType === 'file' 
    ? '\n\nBu işlem geri alınamaz!' 
    : '\n\nDİKKAT: Klasör ve tüm içeriği silinecektir!\nBu işlem geri alınamaz!';
  
  return confirm(`Aşağıdaki ${itemType} silmek istediğinize emin misiniz?\n\n${fullPath}${warning}`);
}

</script>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/footer.php'; ?>
</body>
</html>
