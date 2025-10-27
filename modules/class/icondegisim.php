<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Rol kontrolü
$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
  include($rol_kontrol_path);
  if (function_exists('rol_kontrol')) rol_kontrol(1);
}

$baseDirectory = $_SERVER['DOCUMENT_ROOT'];
$targetClass = '';
$newIconClass = '';
$changedFiles = [];
$message = '';
$messageClass = '';

// Icon class'ı değiştir
function findAndReplaceIconClass($directory, $targetClass, $newIconClass) {
  $changed = [];
  $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
  foreach ($iterator as $file) {
    if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
      $path = $file->getPathname();
      $content = file_get_contents($path);

      // class adını bul
      if (preg_match("/class\s+" . preg_quote($targetClass, '/') . "\s*{/", $content)) {
        $pattern = "/(\\\$icon_class\s*=\s*['\"])([^'\"]+)(['\"]\s*;)/";
        $replaced = preg_replace($pattern, "$1$newIconClass$3", $content);
        if ($replaced !== null && $replaced !== $content) {
          file_put_contents($path, $replaced);
          $changed[] = $path;
        }
      }
    }
  }
  return $changed;
}

// İşlem tetiklenirse
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $targetClass = $_POST['target_class'] ?? '';
  $newIconClass = $_POST['new_icon_class'] ?? '';

  if (!empty($targetClass) && !empty($newIconClass)) {
    $changedFiles = findAndReplaceIconClass($baseDirectory, $targetClass, $newIconClass);
    if ($changedFiles) {
      $message = "Icon class başarıyla güncellendi.";
      $messageClass = "success";
    } else {
      $message = "Hiçbir dosyada hedef class veya icon tanımı bulunamadı.";
      $messageClass = "warning";
    }
  } else {
    $message = "Hedef class ve yeni icon class boş olamaz.";
    $messageClass = "danger";
  }
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Icon Class Yöneticisi - BSD Soft</title>
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
    
    .form-control, .form-select {
      border: 1px solid var(--border-color);
      background-color: var(--card-bg);
      color: var(--dark-color);
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
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
    
    .file-list {
      max-height: 300px;
      overflow-y: auto;
    }
    
    .file-item {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid var(--border-color);
      transition: background-color 0.2s;
    }
    
    .file-item:last-child {
      border-bottom: none;
    }
    
    .file-item:hover {
      background-color: rgba(67, 97, 238, 0.05);
    }
    
    .alert {
      border: none;
    }
    
    .alert-success {
      background-color: rgba(16, 185, 129, 0.1);
      color: #0d8a5f;
    }
    
    .alert-warning {
      background-color: rgba(245, 158, 11, 0.1);
      color: #c07d08;
    }
    
    .alert-danger {
      background-color: rgba(239, 68, 68, 0.1);
      color: #d63031;
    }
    
    .icon-preview {
      font-size: 1.5rem;
      width: 3rem;
      height: 3rem;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: rgba(67, 97, 238, 0.1);
      border-radius: 0.5rem;
      color: var(--primary-color);
      margin-right: 1rem;
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
                        <i class="bi bi-tags text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Icon Class Yöneticisi</h5>
                        <p class="text-muted small mb-0">Icon sınıflarını toplu olarak güncelleme aracı</p>
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


      <!-- 🔧 Form -->
      <form method="post">
        <div class="mb-3">
          <label for="target_class" class="form-label fw-bold"><i class="fas fa-code me-2"></i> Hedef Class</label>
          <input type="text" name="target_class" id="target_class" class="form-control" required placeholder="örn: KullaniciPanel">
        </div>

        <div class="mb-3">
          <label for="new_icon_class" class="form-label fw-bold"><i class="fas fa-pencil-alt me-2"></i> Yeni Icon Class</label>
          <input type="text" name="new_icon_class" id="new_icon_class" class="form-control" required placeholder="örn: fa-user-check">
        </div>

        <button type="submit" class="btn btn-success w-100">
          <i class="fas fa-sync-alt me-1"></i> Güncelle
        </button>
      </form>

      <!-- ✉️ Mesajlar -->
      <?php if ($message): ?>
      <div class="alert alert-<?= $messageClass ?> mt-4">
        <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : ($messageClass === 'warning' ? 'fa-exclamation-circle' : 'fa-times-circle') ?> me-1"></i>
        <?= $message ?>
      </div>
      <?php endif; ?>

      <!-- 📝 Değiştirilen Dosyalar -->
      <?php if (!empty($changedFiles)): ?>
      <div class="mt-3">
        <label class="form-label fw-bold">Güncellenen Dosyalar</label>
        <ul class="list-group">
          <?php foreach ($changedFiles as $file): ?>
          <li class="list-group-item"><i class="fas fa-check text-success me-2"></i><?= htmlspecialchars($file) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>
