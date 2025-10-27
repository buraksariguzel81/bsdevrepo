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
$message = '';
$messageClass = '';
$changedFiles = [];

// Class içerik bul ve değiştir
function findAndReplaceInFiles($directory, $targetClass, $newContent) {
  $changes = [];
  $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
  foreach ($iterator as $file) {
    if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
      $path = $file->getPathname();
      $content = file_get_contents($path);
      $pattern = "/class\s+" . preg_quote($targetClass, '/') . "\s*{[\s\S]*?}/";
      $replacement = "class $targetClass {\n$newContent\n}";
      $newContentFull = preg_replace($pattern, $replacement, $content);
      if ($newContentFull !== null && $newContentFull !== $content) {
        file_put_contents($path, $newContentFull);
        $changes[] = $path;
      }
    }
  }
  return $changes;
}

// İşlem tetiklenirse
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $targetClass = $_POST['target_class'] ?? '';
  $newContentInput = $_POST['new_content'] ?? '';

  if (!empty($targetClass) && !empty($newContentInput)) {
    $changedFiles = findAndReplaceInFiles($baseDirectory, $targetClass, $newContentInput);
    if ($changedFiles) {
      $message = "Class <strong>$targetClass</strong> içeriği başarıyla güncellendi.";
      $messageClass = "success";
    } else {
      $message = "Hiçbir dosyada <strong>$targetClass</strong> bulunamadı.";
      $messageClass = "warning";
    }
  } else {
    $message = "Class adı ve içerik alanları boş olamaz.";
    $messageClass = "danger";
  }
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Class İçerik Yöneticisi - BSD Soft</title>
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
  </style>
</head>
<body>

<div class="container py-4">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-file-earmark-code text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Class İçerik Yöneticisi</h5>
                        <p class="text-muted small mb-0">PHP sınıf içeriklerini düzenleme ve yönetme aracı</p>
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


      <!-- Form -->
      <form method="post" class="needs-validation" novalidate>
        <div class="row g-4">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="target_class" class="form-label fw-semibold">
                <i class="fas fa-bullseye me-2 text-primary"></i>Hedef Class Adı
              </label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-code"></i></span>
                <input type="text" 
                       name="target_class" 
                       id="target_class" 
                       class="form-control form-control-lg" 
                       required 
                       placeholder="Örn: KullaniciGiris"
                       pattern="[a-zA-Z_][a-zA-Z0-9_]*"
                       title="Geçerli bir PHP sınıf adı girin">
              </div>
              <div class="form-text">Güncellenecek class adını girin (Örn: KullaniciIslemleri)</div>
            </div>
          </div>
          
          <div class="col-12">
            <div class="mb-3">
              <label for="new_content" class="form-label fw-semibold">
                <i class="fas fa-file-code me-2 text-primary"></i>Yeni Class İçeriği
              </label>
              <div class="position-relative">
                <div class="position-absolute top-0 end-0 p-2 d-flex gap-2 bg-light rounded-bottom">
                  <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Temizle" onclick="document.getElementById('new_content').value=''">
                    <i class="fas fa-eraser"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Biçimlendir" onclick="formatCode()">
                    <i class="fas fa-indent"></i>
                  </button>
                </div>
                <textarea 
                  name="new_content" 
                  id="new_content" 
                  rows="12" 
                  class="form-control font-monospace" 
                  required 
                  placeholder="Class içeriğini buraya yazın..."
                  style="font-size: 0.9rem;"></textarea>
              </div>
              <div class="form-text">
                <i class="fas fa-info-circle me-1"></i> Class yapısını koruyarak sadece içeriği değiştirin.
              </div>
            </div>
          </div>
          
          <div class="col-12">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button type="reset" class="btn btn-outline-secondary me-md-2">
                <i class="fas fa-undo me-1"></i> Sıfırla
              </button>
              <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Class İçeriğini Güncelle
              </button>
            </div>
          </div>
        </div>
      </form>

      <!-- Sonuç Bölümü -->
      <?php if (!empty($message)): ?>
      <div class="mt-4">
        <div class="alert alert-<?= $messageClass ?> alert-dismissible fade show" role="alert">
          <div class="d-flex align-items-center">
            <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : ($messageClass === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle') ?> me-2"></i>
            <div>
              <h6 class="alert-heading mb-1">
                <?= $messageClass === 'success' ? 'Başarılı!' : ($messageClass === 'warning' ? 'Uyarı!' : 'Hata!') ?>
              </h6>
              <div class="mb-0"><?= $message ?></div>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
        </div>
      </div>
      <?php endif; ?>

      <!-- Değiştirilen Dosyalar -->
      <?php if (!empty($changedFiles)): ?>
      <div class="mt-4">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="mb-0"><i class="fas fa-file-code me-2"></i>Değişiklik Yapılan Dosyalar</h6>
              <span class="badge bg-primary rounded-pill"><?= count($changedFiles) ?> dosya</span>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="file-list">
              <?php foreach ($changedFiles as $index => $file): 
                $fileInfo = pathinfo($file);
                $fileIcon = getFileIcon($fileInfo['extension'] ?? '');
              ?>
              <div class="file-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <i class="fas <?= $fileIcon ?> me-2 text-muted"></i>
                  <div>
                    <div class="fw-medium"><?= htmlspecialchars(basename($file)) ?></div>
                    <small class="text-muted"><?= htmlspecialchars(dirname($file)) ?></small>
                  </div>
                </div>
                <div class="text-end">
                  <small class="text-muted d-block"><?= date('d.m.Y H:i', filemtime($file)) ?></small>
                  <span class="badge bg-success bg-opacity-10 text-success">Güncellendi</span>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
              <small class="text-muted">Toplam <?= count($changedFiles) ?> dosya güncellendi</small>
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyFileList()">
                <i class="fas fa-copy me-1"></i> Listeyi Kopyala
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

    </div><!-- /.card-body -->
  </div><!-- /.card -->
</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.container -->

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

// Form doğrulama
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()

// Kod biçimlendirme
function formatCode() {
  const textarea = document.getElementById('new_content');
  try {
    // Basit bir kod biçimlendirme (gerçek bir kod formatlayıcı ile değiştirilebilir)
    let code = textarea.value;
    // Girintileri düzenle
    code = code.replace(/\n\s*\n/g, '\n');
    code = code.replace(/\n/g, '\n    ');
    textarea.value = code;
  } catch (e) {
    console.error('Kod biçimlendirilirken hata oluştu:', e);
  }
}

// Dosya listesini kopyalama
function copyFileList() {
  const files = document.querySelectorAll('.file-item');
  let fileList = 'Değiştirilen Dosyalar:\n\n';
  
  files.forEach((file, index) => {
    const fileName = file.querySelector('.fw-medium').textContent;
    const filePath = file.querySelector('small').textContent;
    fileList += `${index + 1}. ${filePath}${filePath.endsWith('/') ? '' : '/'}${fileName}\n`;
  });
  
  navigator.clipboard.writeText(fileList).then(() => {
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3';
    alert.innerHTML = `
      <i class="fas fa-check-circle me-2"></i>
      <span>Dosya listesi panoya kopyalandı!</span>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
    `;
    document.body.appendChild(alert);
    
    // 3 saniye sonra uyarıyı kaldır
    setTimeout(() => {
      alert.classList.remove('show');
      setTimeout(() => alert.remove(), 150);
    }, 3000);
  });
}

// Dosya ikonlarını belirleme fonksiyonu
function getFileIcon(extension) {
  const icons = {
    'php': 'fa-file-code',
    'html': 'fa-file-code',
    'css': 'fa-file-code',
    'js': 'fa-file-code',
    'json': 'fa-file-code',
    'sql': 'fa-database',
    'md': 'fa-file-lines',
    'txt': 'fa-file-lines',
    'pdf': 'fa-file-pdf',
    'jpg': 'fa-file-image',
    'jpeg': 'fa-file-image',
    'png': 'fa-file-image',
    'gif': 'fa-file-image',
    'zip': 'fa-file-zipper',
    'rar': 'fa-file-zipper',
    'doc': 'fa-file-word',
    'docx': 'fa-file-word',
    'xls': 'fa-file-excel',
    'xlsx': 'fa-file-excel',
    'ppt': 'fa-file-powerpoint',
    'pptx': 'fa-file-powerpoint',
    'mp3': 'fa-file-audio',
    'wav': 'fa-file-audio',
    'mp4': 'fa-file-video',
    'avi': 'fa-file-video',
    'mov': 'fa-file-video'
  };
  
  return icons[extension.toLowerCase()] || 'fa-file';
}

// Tooltip'leri etkinleştir
document.addEventListener('DOMContentLoaded', function() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>

<?php 
// Footer'ı dahil et
$footerPath = $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php";
if (file_exists($footerPath)) {
  include $footerPath;
}
?>
</body>
</html>
