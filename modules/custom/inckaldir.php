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
$searchResults = [];
$showResults = false;
$inputPattern = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $inputPattern = trim($_POST['pattern'] ?? '');
  $action = $_POST['action'] ?? 'search'; // Varsayılan aksiyon arama
  
  if (empty($inputPattern)) {
    $message = "Aranacak include satırı boş olamaz.";
    $messageClass = "danger";
  } else {
    // include satırı filtrelemeli güvenli ifade
    $escapedPattern = preg_quote($inputPattern, '/');
    $pattern = "/$escapedPattern/i";
    $baseDir = $_SERVER['DOCUMENT_ROOT'] . '/';
    $files = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
      RecursiveIteratorIterator::SELF_FIRST
    );

    $foundCount = 0;
    $searchResults = [];
    
    // Dosyaları tara
    foreach ($files as $file) {
      if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
        $filePath = $file->getPathname();
        $content = file_get_contents($filePath);
        
        if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
          $relativePath = str_replace($baseDir, '', $filePath);
          $searchResults[$relativePath] = [
            'full_path' => $filePath,
            'matches' => []
          ];
          
          // Eşleşen satırları bul
          $lines = explode("\n", $content);
          $lineNumber = 0;
          $currentPosition = 0;
          
          foreach ($lines as $lineNumber => $line) {
            $lineNumber++;
            $lineLength = strlen($line) + 1; // +1 for newline
            
            foreach ($matches[0] as $match) {
              if ($match[1] >= $currentPosition && $match[1] < $currentPosition + $lineLength) {
                $searchResults[$relativePath]['matches'][] = [
                  'line' => $lineNumber,
                  'content' => htmlspecialchars(trim($line))
                ];
                $foundCount++;
              }
            }
            $currentPosition += $lineLength;
          }
        }
      }
    }

    if ($action === 'delete' && !empty($searchResults)) {
      // Silme işlemi
      $deletedCount = 0;
      
      foreach ($searchResults as $relativePath => $fileData) {
        $filePath = $fileData['full_path'];
        $content = file_get_contents($filePath);
        $newContent = preg_replace($pattern, '', $content);
        
        if (file_put_contents($filePath, $newContent) !== false) {
          $logOutput .= "✔ $relativePath - " . count($fileData['matches']) . " satır silindi\n";
          $deletedCount += count($fileData['matches']);
        } else {
          $logOutput .= "✖ $relativePath - Hata oluştu\n";
        }
      }
      
      if ($deletedCount > 0) {
        $message = "Toplam <strong>$deletedCount</strong> include satırı başarıyla silindi.";
        $messageClass = "success";
        $searchResults = []; // Sonuçları temizle
      } else {
        $message = "Silme işlemi sırasında bir hata oluştu.";
        $messageClass = "danger";
      }
    } else {
      // Sadece arama yapıldı
      $showResults = true;
      if (empty($searchResults)) {
        $message = "Belirtilen include satırı hiçbir dosyada bulunamadı.";
        $messageClass = "warning";
      } else {
        $message = "Toplam <strong>" . count($searchResults) . "</strong> dosyada <strong>$foundCount</strong> eşleşme bulundu. Aşağıdan inceleyebilir ve silme işlemi yapabilirsiniz.";
        $messageClass = "info";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Include Silici | BSDSoft</title>
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
      background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      border-bottom: none;
      padding: 1.25rem 1.5rem;
    }
    .form-control:focus {
      border-color: #4361ee;
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    .btn-danger {
      background: linear-gradient(135deg, #e63946 0%, #d90429 100%);
      border: none;
      padding: 0.6rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s;
    }
    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
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
      border-left-color: #2ecc71;
    }
    .alert-danger {
      border-left-color: #e74c3c;
    }
    .alert-warning {
      border-left-color: #f39c12;
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
                        <i class="bi bi-trash text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Include Satırı Silici</h5>
                        <p class="text-muted small mb-0">PHP dosyalarından include satırlarını güvenle kaldırma aracı</p>
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
              <input type="hidden" name="action" id="formAction" value="search">
              <div class="mb-4">
                <label for="pattern" class="form-label fw-bold">
                  <i class="fas fa-search me-2 text-primary"></i> Aranacak Include Satırı
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-code text-muted"></i>
                  </span>
                  <input type="text" name="pattern" id="pattern" class="form-control form-control-lg" 
                         placeholder="Örn: include('../bsd_yonetim/src/include/kullanici_adi.php');" 
                         value="<?= htmlspecialchars($inputPattern) ?>" required>
                </div>
                <div class="form-text text-muted small mt-1">
                  <i class="fas fa-info-circle me-1"></i> Aramak istediğiniz tam include satırını giriniz.
                </div>
              </div>

              <div class="d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-primary btn-lg py-2 flex-grow-1" id="searchBtn">
                  <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                  <i class="fas fa-search me-2"></i>Include Satırlarını Ara
                </button>
                <?php if (!empty($searchResults)): ?>
                <button type="button" class="btn btn-outline-danger btn-lg py-2" id="confirmDeleteBtn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                  <i class="fas fa-trash-alt me-2"></i>Bulunanları Sil
                </button>
                <?php endif; ?>
              </div>
            </form>

            <?php if ($message): ?>
              <div class="alert alert-<?= $messageClass ?> alert-dismissible fade show mt-4" role="alert">
                <div class="d-flex align-items-center">
                  <i class="fas <?= $messageClass === 'success' ? 'fa-check-circle' : 
                                 ($messageClass === 'danger' ? 'fa-times-circle' : 
                                 ($messageClass === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle')) 
                                ?> me-2"></i>
                  <div>
                    <strong class="d-block">
                      <?= $messageClass === 'success' ? 'İşlem Başarılı!' : 
                            ($messageClass === 'danger' ? 'Hata!' : 
                            ($messageClass === 'warning' ? 'Uyarı!' : 'Bilgi')) ?>
                    </strong>
                    <?= $message ?>
                  </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
              </div>
            <?php endif; ?>

            <?php if ($showResults && !empty($searchResults)): ?>
              <div class="mt-5">
                <h5 class="border-bottom pb-2 mb-3 d-flex justify-content-between align-items-center">
                  <span>
                    <i class="fas fa-search me-2 text-primary"></i>Arama Sonuçları
                    <span class="badge bg-primary rounded-pill ms-2"><?= count($searchResults) ?> dosya</span>
                  </span>
                  <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleAllResults">
                    <i class="fas fa-chevron-down me-1"></i>Tümünü Aç/Kapat
                  </button>
                </h5>
                
                <div class="accordion" id="searchResultsAccordion">
                  <?php $fileIndex = 0; ?>
                  <?php foreach ($searchResults as $relativePath => $fileData): ?>
                    <?php $fileIndex++; ?>
                    <div class="accordion-item mb-2 border-0 shadow-sm">
                      <h2 class="accordion-header" id="heading<?= $fileIndex ?>">
                        <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse<?= $fileIndex ?>" aria-expanded="false" 
                                aria-controls="collapse<?= $fileIndex ?>">
                          <i class="fas fa-file-code text-primary me-2"></i>
                          <span class="text-truncate me-2" style="max-width: 70%;"><?= htmlspecialchars($relativePath) ?></span>
                          <span class="badge bg-primary rounded-pill ms-auto">
                            <?= count($fileData['matches']) ?> eşleşme
                          </span>
                        </button>
                      </h2>
                      <div id="collapse<?= $fileIndex ?>" class="accordion-collapse collapse" 
                           aria-labelledby="heading<?= $fileIndex ?>" data-bs-parent="#searchResultsAccordion">
                        <div class="accordion-body p-0">
                          <div class="list-group list-group-flush">
                            <?php foreach ($fileData['matches'] as $match): ?>
                              <div class="list-group-item d-flex align-items-start">
                                <div class="text-muted me-3 text-nowrap" style="width: 50px;">
                                  Satır #<?= $match['line'] ?>
                                </div>
                                <code class="bg-light p-1 rounded"><?= $match['content'] ?></code>
                              </div>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              
              <!-- Onay Modalı -->
              <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                      <h5 class="modal-title" id="confirmDeleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Emin misiniz?
                      </h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>
                    <div class="modal-body">
                      <p>
                        <strong><?= count($searchResults) ?> dosyada toplam <?= array_reduce($searchResults, function($carry, $item) {
                          return $carry + count($item['matches']);
                        }, 0) ?> include satırı silinecektir.</strong>
                      </p>
                      <p class="mb-0">Bu işlem geri alınamaz. Devam etmek istediğinize emin misiniz?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> İptal
                      </button>
                      <button type="button" class="btn btn-danger" id="confirmDeleteFinalBtn">
                        <i class="fas fa-trash-alt me-1"></i> Evet, Sil
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            
            <?php if (!empty($logOutput)): ?>
              <div class="mt-5">
                <h5 class="border-bottom pb-2 mb-3">
                  <i class="fas fa-history me-2 text-primary"></i>İşlem Geçmişi
                  <span class="badge bg-primary rounded-pill ms-2"><?= count(explode("\n", trim($logOutput))) ?> işlem</span>
                </h5>
                <div class="card shadow-sm">
                  <div class="card-body p-0">
                    <div class="p-3 file-list">
                      <pre class="mb-0"><?= htmlspecialchars($logOutput) ?></pre>
                    </div>
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const searchForm = document.getElementById('searchForm');
      const searchBtn = document.getElementById('searchBtn');
      const searchSpinner = searchBtn ? searchBtn.querySelector('.spinner-border') : null;
      const patternInput = document.getElementById('pattern');
      const confirmDeleteBtn = document.getElementById('confirmDeleteFinalBtn');
      const toggleAllResultsBtn = document.getElementById('toggleAllResults');
      
      // Input'a otomatik odaklan
      if (patternInput) {
        patternInput.focus();
        
        // Form gönderiminde yükleniyor animasyonu
        if (searchForm) {
          searchForm.addEventListener('submit', function(e) {
            if (searchBtn && searchSpinner) {
              searchBtn.disabled = true;
              searchSpinner.classList.remove('d-none');
            }
          });
        }
      }
      
      // Silme onayı
      if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
          // Modal'ı kapat
          const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
          if (modal) modal.hide();
          
          // Form action'ını değiştir ve gönder
          const form = document.getElementById('searchForm');
          if (form) {
            document.getElementById('formAction').value = 'delete';
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><i class="fas fa-trash-alt me-2"></i>Siliniyor...';
            form.submit();
          }
        });
      }
      
      // Tüm sonuçları aç/kapat
      if (toggleAllResultsBtn) {
        toggleAllResultsBtn.addEventListener('click', function() {
          const accordionItems = document.querySelectorAll('#searchResultsAccordion .accordion-collapse');
          const isAnyOpen = Array.from(accordionItems).some(item => item.classList.contains('show'));
          
          const bsCollapse = new bootstrap.Collapse(accordionItems[0], { toggle: false });
          
          if (isAnyOpen) {
            // Tümünü kapat
            accordionItems.forEach(item => {
              bootstrap.Collapse.getOrCreateInstance(item).hide();
            });
            toggleAllResultsBtn.innerHTML = '<i class="fas fa-chevron-down me-1"></i>Tümünü Aç';
          } else {
            // Tümünü aç
            accordionItems.forEach(item => {
              bootstrap.Collapse.getOrCreateInstance(item).show();
            });
            toggleAllResultsBtn.innerHTML = '<i class="fas fa-chevron-up me-1"></i>Tümünü Kapat';
          }
        });
      }
      
      // Accordion açılıp kapanınca ikonu değiştir
      const accordionButtons = document.querySelectorAll('.accordion-button');
      accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
          if (this.classList.contains('collapsed')) {
            this.querySelector('i').className = 'fas fa-chevron-down text-muted me-2';
          } else {
            this.querySelector('i').className = 'fas fa-chevron-up text-primary me-2';
          }
        });
      });
    });
  </script>
</body>
</html>
