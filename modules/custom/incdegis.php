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

$message = '';
$messageClass = '';
$logOutput = '';

$searchPattern = $_POST['search_pattern'] ?? '';
$replaceText = $_POST['replace_text'] ?? '';
$baseDir = $_SERVER['DOCUMENT_ROOT'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $searchPattern = trim($searchPattern);
  $searchResults = [];
  
  // Check if this is a search request
  if (isset($_POST['search_btn'])) {
    if (empty($searchPattern)) {
      $message = "Lütfen arama yapmak için bir metin giriniz.";
      $messageClass = "danger";
    } else {
      $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS)
      );
      
      foreach ($iterator as $file) {
        if ($file->isFile() && in_array(strtolower($file->getExtension()), ['php', 'html', 'js', 'css'])) {
          $filePath = $file->getPathname();
          $content = @file_get_contents($filePath);
          
          if ($content !== false && strpos($content, $searchPattern) !== false) {
            $lines = explode("\n", $content);
            $matches = [];
            
            foreach ($lines as $lineNumber => $line) {
              if (strpos($line, $searchPattern) !== false) {
                $matches[] = [
                  'line' => $lineNumber + 1,
                  'content' => htmlspecialchars($line)
                ];
              }
            }
            
            if (!empty($matches)) {
              $relativePath = str_replace($baseDir, '', $filePath);
              $searchResults[$relativePath] = $matches;
            }
          }
        }
      }
      
      if (empty($searchResults)) {
        $message = "Aranan metin hiçbir dosyada bulunamadı.";
        $messageClass = "warning";
      }
    }
  } 
  // Handle replace operation
  elseif (isset($_POST['replace_btn'])) {
    $replaceText = trim($replaceText);
    
    if (empty($searchPattern) || empty($replaceText)) {
      $message = "Arama veya değiştirme alanı boş olamaz.";
      $messageClass = "danger";
    } else {
      $modifiedFiles = [];
      $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS)
      );

      foreach ($iterator as $file) {
        if ($file->isFile() && in_array(strtolower($file->getExtension()), ['php', 'html', 'js', 'css'])) {
          $filePath = $file->getPathname();
          $content = file_get_contents($filePath);

          if (strpos($content, $searchPattern) !== false) {
            $newContent = str_replace($searchPattern, $replaceText, $content);
            if (file_put_contents($filePath, $newContent) !== false) {
              $modifiedFiles[] = $filePath;
              $logOutput .= "✔ $filePath\n";
            }
          }
        }
      }

      if (!empty($modifiedFiles)) {
        $message = "Toplam <strong>" . count($modifiedFiles) . "</strong> dosyada değişiklik yapıldı.";
        $messageClass = "success";
      } else {
        $message = "Hiçbir dosyada eşleşen metin bulunamadı.";
        $messageClass = "warning";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Metin Değiştirici | BSDSoft</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
      background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      border-bottom: none;
      padding: 1.25rem 1.5rem;
    }
    .form-control:focus {
      border-color: #4361ee;
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    .btn-primary {
      background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      border: none;
      padding: 0.6rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }
    .file-path {
      font-family: 'Courier New', monospace;
      font-size: 0.85rem;
    }
    .alert {
      border: none;
      border-left: 4px solid;
    }
    .alert-success {
      border-left-color: #2ecc71;
    }
    .alert-danger {
      border-left-color: #e74c3c;
    }
    .alert-warning {
      border-left-color: #f39c12;
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
    .search-results {
      max-height: 300px;
      overflow-y: auto;
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
                        <i class="bi bi-code text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">PHP Metin Değiştirici</h5>
                        <p class="text-muted small mb-0">Dosya içi metin arama ve değiştirme aracı</p>
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


            <form method="post" id="searchForm" novalidate>
              <div class="row g-3">
                <div class="col-md-12">
                  <label for="search_pattern" class="form-label fw-bold">
                    <i class="fas fa-search me-1 text-primary"></i> Aranacak Metin
                  </label>
                  <div class="input-group mb-3">
                    <span class="input-group-text bg-light">
                      <i class="fas fa-font text-muted"></i>
                    </span>
                    <textarea name="search_pattern" id="search_pattern" rows="3" 
                      class="form-control form-control-lg" 
                      placeholder="Aranacak metin veya kod parçasını giriniz..." 
                      required><?= htmlspecialchars($searchPattern) ?></textarea>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-grid gap-2">
                    <button type="submit" name="search_btn" class="btn btn-primary btn-lg" id="searchBtn">
                      <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                      <i class="fas fa-search me-2"></i>Metni Ara
                    </button>
                  </div>
                </div>
              </div>
            </form>

            <?php if (isset($searchResults) && !empty($searchResults)): ?>
            <div id="searchResultsSection" class="mt-5">
              <h5 class="border-bottom pb-2 mb-3">
                <i class="fas fa-search me-2 text-primary"></i>Arama Sonuçları
                <span class="badge bg-primary rounded-pill ms-2"><?= count($searchResults) ?> eşleşme</span>
              </h5>
              
              <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Toplam <strong><?= count($searchResults) ?></strong> dosyada eşleşme bulundu. 
                Aşağıdaki dosyalarda değişiklik yapmak için yeni içeriği giriniz.
              </div>

              <div class="search-results mb-4">
                <ul class="list-group">
                  <?php foreach ($searchResults as $file => $matches): ?>
                    <li class="list-group-item">
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="font-monospace small"><?= htmlspecialchars($file) ?></span>
                        <span class="badge bg-primary rounded-pill"><?= count($matches) ?> eşleşme</span>
                      </div>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>

              <form method="post" id="replaceForm" class="mt-4">
                <input type="hidden" name="search_pattern" value="<?= htmlspecialchars($searchPattern) ?>">
                
                <div class="mb-3">
                  <label for="replace_text" class="form-label fw-bold">
                    <i class="fas fa-exchange-alt me-1 text-success"></i> Yeni İçerik
                  </label>
                  <div class="input-group mb-3">
                    <span class="input-group-text bg-light">
                      <i class="fas fa-edit text-muted"></i>
                    </span>
                    <textarea name="replace_text" id="replace_text" rows="3" 
                      class="form-control form-control-lg" 
                      placeholder="Yerine gelecek yeni içeriği giriniz..." 
                      required></textarea>
                  </div>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" name="replace_btn" class="btn btn-warning btn-lg" id="replaceBtn">
                    <i class="fas fa-sync-alt me-2"></i>Değişiklikleri Uygula
                  </button>
                </div>
              </form>
            </div>
            <?php endif; ?>

            <?php if ($message): ?>
              <div class="alert alert-<?= $messageClass ?> alert-dismissible fade show mt-4" role="alert">
                <div class="d-flex align-items-center">
                  <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : 
                                 ($messageClass === 'danger' ? 'fa-times-circle' : 'fa-exclamation-triangle') 
                                ?> me-2"></i>
                  <div>
                    <strong class="d-block">
                      <?= $messageClass === 'success' ? 'Başarılı!' : 
                        ($messageClass === 'danger' ? 'Hata!' : 'Uyarı!') ?>
                      </strong>
                    <?= $message ?>
                  </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
              </div>
            <?php endif; ?>

            <?php if (!empty($logOutput)): ?>
              <div class="card shadow-sm mt-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2 text-primary"></i>İşlem Geçmişi
                  </h5>
                  <span class="badge bg-primary rounded-pill"><?= count(explode("\n", trim($logOutput))) ?> dosya</span>
                </div>
                <div class="card-body p-0">
                  <div class="p-3 file-list">
                    <pre class="mb-0 file-path"><?= htmlspecialchars($logOutput) ?></pre>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Textarea otomatik yükseklik ayarı
    document.querySelectorAll('textarea').forEach(textarea => {
      textarea.style.height = 'auto';
      textarea.style.height = (textarea.scrollHeight) + 'px';
      
      textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
      });
    });

    // Arama formu AJAX ile gönderiliyor
    $('#searchForm').on('submit', function(e) {
      e.preventDefault();
      
      const form = $(this);
      const submitBtn = form.find('button[type="submit"]');
      const spinner = submitBtn.find('.spinner-border');
      
      // Butonu devre dışı bırak ve spinner'ı göster
      submitBtn.prop('disabled', true);
      spinner.removeClass('d-none');
      
      // AJAX isteği gönder
      $.ajax({
        url: window.location.href,
        type: 'POST',
        data: form.serialize() + '&search_btn=1',
        success: function(response) {
          // Sunucudan gelen yanıtı işle
          const tempDiv = $('<div>').html(response);
          const searchResults = tempDiv.find('#searchResultsSection');
          
          // Eğer sonuçlar varsa göster, yoksa hata mesajı göster
          if (searchResults.length > 0) {
            // Eski sonuçları temizle
            $('#searchResultsSection').remove();
            // Yeni sonuçları ekle
            form.after(searchResults);
          } else {
            // Hata mesajını göster
            const errorMessage = tempDiv.find('.alert-warning, .alert-danger');
            if (errorMessage.length > 0) {
              // Eski hata mesajlarını temizle
              $('.alert').not('.alert-info').remove();
              // Yeni hata mesajını ekle
              form.after(errorMessage);
            }
          }
          
          // Butonu tekrar aktif et ve spinner'ı gizle
          submitBtn.prop('disabled', false);
          spinner.addClass('d-none');
        },
        error: function() {
          alert('Bir hata oluştu. Lütfen tekrar deneyin.');
          submitBtn.prop('disabled', false);
          spinner.addClass('d-none');
        }
      });
    });
    
    // Değiştirme formu AJAX ile gönderiliyor
    $(document).on('submit', '#replaceForm', function(e) {
      e.preventDefault();
      
      if (!confirm('Bu işlem geri alınamaz. Devam etmek istediğinize emin misiniz?')) {
        return false;
      }
      
      const form = $(this);
      const submitBtn = form.find('button[type="submit"]');
      
      // Butonu devre dışı bırak
      submitBtn.prop('disabled', true);
      
      // AJAX isteği gönder
      $.ajax({
        url: window.location.href,
        type: 'POST',
        data: form.serialize() + '&replace_btn=1',
        success: function(response) {
          // Sayfayı yenile
          window.location.reload();
        },
        error: function() {
          alert('Bir hata oluştu. Lütfen tekrar deneyin.');
          submitBtn.prop('disabled', false);
        }
      });
    });
  </script>
</body>
</html>
