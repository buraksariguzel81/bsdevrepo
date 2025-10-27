<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
  include($rol_kontrol_path);
  if (function_exists('rol_kontrol')) rol_kontrol(1);
}

$message = '';
$messageClass = '';
$changedFiles = [];

$baseDirectory = $_SERVER['DOCUMENT_ROOT'];

function changeHtmlLang($directory, $newLang) {
  $changedFiles = [];
  $allowedExtensions = ['html', 'php'];
  $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
  foreach ($iterator as $file) {
    if ($file->isFile() && in_array(strtolower($file->getExtension()), $allowedExtensions)) {
      $filePath = $file->getPathname();
      $content = file_get_contents($filePath);
      if (preg_match('/<html\s+[^>]*lang=["\']([^"\']*)["\']/i', $content)) {
        $newContent = preg_replace('/(<html\s+[^>]*lang=["\'])([^"\']*)(["\'])/i', "$1$newLang$3", $content);
      } else {
        $newContent = preg_replace('/<html([^>]*)>/i', "<html$1 lang=\"$newLang\">", $content);
      }
      if ($content !== $newContent) {
        file_put_contents($filePath, $newContent);
        $changedFiles[] = $filePath;
      }
    }
  }
  return $changedFiles;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_lang'])) {
  $newLang = htmlspecialchars(trim($_POST['new_lang']));
  $changedFiles = changeHtmlLang($baseDirectory, $newLang);
  if ($changedFiles) {
    $message = "Lang özelliği başarıyla <strong>$newLang</strong> olarak güncellendi.";
    $messageClass = "success";
  } else {
    $message = "Hiçbir dosyada değişiklik yapılmadı.";
    $messageClass = "warning";
  }
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dil Özelliği Yöneticisi | BSDSoft</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --bs-body-bg: #f8f9fa;
    }
    .card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.2s;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .card:hover {
      transform: translateY(-2px);
    }
    .card-header {
      background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
      border-bottom: none;
      padding: 1.25rem 1.5rem;
    }
    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .btn-primary {
      background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
      border: none;
      padding: 0.6rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    .file-list {
      max-height: 400px;
      overflow-y: auto;
      scrollbar-width: thin;
    }
    .file-list::-webkit-scrollbar {
      width: 6px;
    }
    .file-list::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    .file-list::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }
    .file-list::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
    .alert {
      border: none;
      border-left: 4px solid;
    }
    .alert-success {
      border-left-color: #198754;
    }
    .alert-warning {
      border-left-color: #ffc107;
    }
    .alert-info {
      border-left-color: #0dcaf0;
    }
    .language-flag {
      width: 24px;
      height: 16px;
      object-fit: cover;
      border-radius: 2px;
      margin-right: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .language-option {
      display: flex;
      align-items: center;
      padding: 8px 12px;
      border-radius: 4px;
      transition: background-color 0.2s;
    }
    .language-option:hover {
      background-color: rgba(13, 110, 253, 0.1);
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
                        <i class="bi bi-translate fs-4 text-primary"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Dil Özelliği Yöneticisi</h5>
                        <p class="text-muted small mb-0">Web sitenizin HTML dil etiketlerini yönetin</p>
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


            <form method="post" id="langForm" novalidate>
              <div class="mb-4">
                <label for="new_lang" class="form-label fw-bold">
                  <i class="fas fa-flag me-2 text-primary"></i> Hedef Dil Kodu
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-language text-muted"></i>
                  </span>
                  <input type="text" 
                         name="new_lang" 
                         id="new_lang" 
                         class="form-control form-control-lg" 
                         placeholder="Örn: tr, en, de, fr" 
                         value="<?= htmlspecialchars($_POST['new_lang'] ?? 'tr') ?>" 
                         required
                         pattern="[a-z]{2}(-[A-Z]{2})?"
                         title="Geçerli bir dil kodu girin (örn: tr, en-US)">
                </div>
                <div class="form-text text-muted small mt-1">
                  <i class="fas fa-info-circle me-1"></i> ISO 639-1 standartına uygun dil kodunu giriniz (örn: tr, en, de, fr).
                </div>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg py-2" id="updateBtn">
                  <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                  <i class="fas fa-sync-alt me-2"></i>Dil Etiketlerini Güncelle
                </button>
              </div>
            </form>

            <?php if ($message): ?>
              <div class="alert alert-<?= $messageClass ?> alert-dismissible fade show mt-4" role="alert">
                <div class="d-flex align-items-center">
                  <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : 
                                 ($messageClass === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle') 
                                ?> me-2"></i>
                  <div>
                    <strong class="d-block">
                      <?= $messageClass === 'success' ? 'Başarılı!' : 
                            ($messageClass === 'warning' ? 'Uyarı!' : 'Bilgi') ?>
                    </strong>
                    <?= $message ?>
                  </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
              </div>
            <?php endif; ?>

            <?php if (!empty($changedFiles)): ?>
              <div class="mt-5">
                <h5 class="border-bottom pb-2 mb-3">
                  <i class="fas fa-file-alt me-2 text-primary"></i>Değiştirilen Dosyalar
                  <span class="badge bg-primary rounded-pill ms-2"><?= count($changedFiles) ?> dosya</span>
                </h5>
                <div class="card shadow-sm">
                  <div class="card-body p-0">
                    <div class="p-3 file-list">
                      <div class="list-group list-group-flush">
                        <?php foreach ($changedFiles as $file): ?>
                          <div class="list-group-item d-flex justify-content-between align-items-center">
                            <code class="text-truncate" style="max-width: 80%;"><?= htmlspecialchars($file) ?></code>
                            <span class="badge bg-success rounded-pill">Güncellendi</span>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <div class="mt-5">
              <h5 class="border-bottom pb-2 mb-3">
                <i class="fas fa-lightbulb me-2 text-warning"></i>Hızlı Dil Kısayolları
              </h5>
              <div class="row g-3">
                <div class="col-6 col-md-4 col-lg-3">
                  <button type="button" class="btn btn-outline-primary w-100 language-preset" data-lang="tr">
                    <img src="https://flagcdn.com/16x12/tr.png" class="language-flag" alt="Türkçe">
                    Türkçe (TR)
                  </button>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                  <button type="button" class="btn btn-outline-primary w-100 language-preset" data-lang="en">
                    <img src="https://flagcdn.com/16x12/gb.png" class="language-flag" alt="English">
                    English (EN)
                  </button>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                  <button type="button" class="btn btn-outline-primary w-100 language-preset" data-lang="de">
                    <img src="https://flagcdn.com/16x12/de.png" class="language-flag" alt="Deutsch">
                    Deutsch (DE)
                  </button>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                  <button type="button" class="btn btn-outline-primary w-100 language-preset" data-lang="fr">
                    <img src="https://flagcdn.com/16x12/fr.png" class="language-flag" alt="Français">
                    Français (FR)
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Form gönderiminde yükleniyor animasyonu
      const form = document.getElementById('langForm');
      const updateBtn = document.getElementById('updateBtn');
      const spinner = updateBtn ? updateBtn.querySelector('.spinner-border') : null;
      
      if (form && updateBtn && spinner) {
        form.addEventListener('submit', function() {
          updateBtn.disabled = true;
          spinner.classList.remove('d-none');
        });
      }
      
      // Hızlı dil seçim butonları
      const presetButtons = document.querySelectorAll('.language-preset');
      const langInput = document.getElementById('new_lang');
      
      presetButtons.forEach(button => {
        button.addEventListener('click', function() {
          if (langInput) {
            langInput.value = this.getAttribute('data-lang');
            langInput.focus();
          }
        });
      });
      
      // Sayfa yüklendiğinde input'a odaklan
      if (langInput) {
        langInput.focus();
      }
    });
  </script>
</body>
</html>
