<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
  include($rol_kontrol_path);
  if (function_exists('rol_kontrol')) {
    rol_kontrol(1);
  }
}

$message = '';
$messageClass = '';
$siteURL = $_SERVER['SERVER_NAME'] === 'localhost' ? 'http://localhost:8003' : 'https://buraksariguzeldev.wuaze.com';
$baseDirectory = $_SERVER['DOCUMENT_ROOT'];
if (empty($baseDirectory)) {
  $baseDirectory = '/storage/emulated/0/Buraksariguzeldev/hızlı_klasör';
}

// İçerik değiştirici fonksiyon
function searchInFiles($dir, $search) {
  $results = [];
  $files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
  );

  foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
      $path = $file->getRealPath();
      $content = file_get_contents($path);
      
      if (strpos($content, $search) !== false) {
        $relativePath = str_replace($dir, '', $path);
        $results[] = [
          'path' => $relativePath,
          'full_path' => $path,
          'lines' => []
        ];
      }
    }
  }

  return $results;
}

function replaceInFiles($dir, $search, $replace) {
  $modified = 0;
  $files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
  );

  foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
      $path = $file->getRealPath();
      $content = file_get_contents($path);
      $newContent = str_replace($search, $replace, $content);

      if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        $modified++;
      }
    }
  }

  return $modified;
}

$searchResults = [];
$showResults = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $searchContent = trim($_POST['search_content']);
  $replaceContent = trim($_POST['replace_content']);
  
  // Handle search form submission
  if (isset($_POST['search_btn']) && $searchContent) {
    $searchResults = searchInFiles($baseDirectory, $searchContent);
    $showResults = true;
    
    if (!empty($searchResults)) {
      $message = count($searchResults) . " dosyada eşleşme bulundu.";
      $messageClass = "info";
    } else {
      $message = "Aranan içerik hiçbir dosyada bulunamadı.";
      $messageClass = "warning";
    }
  }
  // Handle replace form submission
  elseif (isset($_POST['replace_btn'])) {
    if (empty($searchContent)) {
      $message = "Lütfen aranacak içeriği giriniz.";
      $messageClass = "danger";
    } else {
      $count = replaceInFiles($baseDirectory, $searchContent, $replaceContent);
      if ($count > 0) {
        $message = "Toplam <strong>$count</strong> dosyada içerik değiştirildi.";
        $messageClass = "success";
      } else {
        $message = "Hiçbir eşleşen içerik bulunamadı.";
        $messageClass = "warning";
      }
    }
  } else {
    $message = "Aranacak ve değiştirilecek içerikler boş olamaz.";
    $messageClass = "danger";
  }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP İçerik Değiştirici</title>
  
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
    
    .path-display {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 1rem;
      font-family: monospace;
      word-break: break-all;
      font-size: 0.9rem;
      margin: 1rem 0;
    }
    
    .file-icon {
      font-size: 1.2em;
      margin-right: 0.5rem;
      color: var(--secondary);
    }
    
    .info-box {
      background: #f8f9fa;
      border-left: 4px solid var(--primary);
      padding: 1rem;
      margin-bottom: 1.5rem;
      border-radius: 0 4px 4px 0;
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
                        <i class="bi bi-code-square text-primary fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">PHP İçerik Değiştirici</h5>
                        <p class="text-muted small mb-0">PHP dosyalarında toplu içerik değiştirme aracı</p>
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
            <i class="fas fa-file-code me-2"></i>
            <span class="d-none d-md-inline">PHP İçerik Değiştirici</span>
          </span>
          <span class="badge bg-primary">Güçlü Araç</span>
        </div>
        
        <div class="card-body">
          <!-- Uyarı Mesajı -->
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
              <strong>Dikkat!</strong> Bu araç tüm PHP dosyalarında değişiklik yapar. Lütfen işlem yapmadan önce yedek alınız.
            </div>
          </div>
          
          <!-- Bilgi Kutusu -->
          <div class="info-box mb-4">
            <i class="fas fa-info-circle text-primary me-2"></i>
            <span class="text-muted">Bu araç sayesinde, sunucunuzdaki tüm <code>.php</code> dosyalarında tam metin araması yapabilir ve içerikleri toplu olarak değiştirebilirsiniz.</span>
          </div>

          <!-- Form -->
          <form method="post" id="contentForm">
            <div class="mb-4">
              <label for="search_content" class="form-label fw-bold">
                <i class="fas fa-search me-2"></i> Aranacak İçerik
              </label>
              <input type="text" 
                     class="form-control form-control-lg" 
                     id="search_content" 
                     name="search_content" 
                     placeholder="Örn: $eski_degisken" 
                     required
                     value="<?= isset($_POST['search_content']) ? htmlspecialchars($_POST['search_content']) : '' ?>">
              <div class="form-text">Değiştirilecek metin veya değişken adı</div>
            </div>

            <div class="mb-4" id="replaceContentContainer">
              <label for="replace_content" class="form-label fw-bold">
                <i class="fas fa-exchange-alt me-2"></i> Yeni İçerik
              </label>
              <input type="text" 
                     class="form-control form-control-lg" 
                     id="replace_content" 
                     name="replace_content" 
                     placeholder="Örn: $yeni_degisken" 
                     value="<?= isset($_POST['replace_content']) ? htmlspecialchars($_POST['replace_content']) : '' ?>">
              <div class="form-text">Yerine yazılacak yeni metin veya değişken adı (sadece değiştirme yapılacaksa doldurun)</div>
            </div>

            <!-- Onay Kutusu -->
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" id="confirmReplace" required>
              <label class="form-check-label" for="confirmReplace">
                Bu işlemin geri alınamayacağını ve yedek aldığımı onaylıyorum
              </label>
            </div>

            <div class="d-grid gap-2">
              <div class="row g-2">
                <div class="col-md-6">
                  <button type="submit" name="search_btn" class="btn btn-info w-100">
                    <i class="fas fa-search me-2"></i> Sadece Ara
                  </button>
                </div>
                <div class="col-md-6">
                  <button type="submit" name="replace_btn" class="btn btn-primary w-100">
                    <i class="fas fa-sync-alt me-2"></i> Bul ve Değiştir
                  </button>
                </div>
              </div>
              <a href="custom.php" class="btn btn-outline-secondary mt-2">
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

          <?php if ($showResults && !empty($searchResults)): ?>
            <div class="mt-4">
              <h5 class="fw-bold mb-3"><i class="fas fa-search me-2"></i> Arama Sonuçları</h5>
              <div class="card">
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>Dosya</th>
                          <th>İşlem</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($searchResults as $result): ?>
                        <tr>
                          <td>
                            <div class="d-flex align-items-center">
                              <i class="fas fa-file-code text-muted me-2"></i>
                              <span class="text-truncate" style="max-width: 500px;" title="<?= htmlspecialchars($result['path']) ?>">
                                <?= htmlspecialchars($result['path']) ?>
                              </span>
                            </div>
                          </td>
                          <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary view-file" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#fileContentModal"
                                    data-filepath="<?= htmlspecialchars($result['full_path']) ?>"
                                    data-searchterm="<?= htmlspecialchars($searchContent) ?>">
                              <i class="fas fa-eye me-1"></i> İncele
                            </button>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <div class="mt-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Sistem Bilgileri</h5>
            <div class="path-display">
              <div><strong>Kök Dizin:</strong> <?= htmlspecialchars($baseDirectory) ?></div>
              <div class="mt-2"><strong>Site URL:</strong> <?= htmlspecialchars($siteURL) ?></div>
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
      <?= date('Y') ?> buraksariguzeldev – Tüm hakları saklıdır
    </div>
  </div>
</footer>

<!-- File Content Modal -->
<div class="modal fade" id="fileContentModal" tabindex="-1" aria-labelledby="fileContentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileContentModalLabel">Dosya İçeriği</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <span class="badge bg-secondary me-2" id="filePathBadge"></span>
              <span class="badge bg-info" id="resultCount"></span>
            </div>
            <div>
              <button class="btn btn-sm btn-outline-secondary" id="prevMatch">
                <i class="fas fa-chevron-up"></i> Önceki
              </button>
              <button class="btn btn-sm btn-outline-secondary" id="nextMatch">
                <i class="fas fa-chevron-down"></i> Sonraki
              </button>
            </div>
          </div>
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Toggle replace content field based on which button was clicked
  const searchBtn = document.querySelector('button[name="search_btn"]');
  const replaceBtn = document.querySelector('button[name="replace_btn"]');
  const replaceContainer = document.getElementById('replaceContentContainer');
  const replaceInput = document.getElementById('replace_content');
  
  if (searchBtn && replaceBtn) {
    // Hide replace container by default
    replaceContainer.style.display = 'none';
    
    searchBtn.addEventListener('click', function(e) {
      // Make replace field not required when searching
      replaceInput.required = false;
      // Hide the replace container when searching
      replaceContainer.style.display = 'none';
      
      // Validate search content
      const searchContent = document.getElementById('search_content').value.trim();
      if (!searchContent) {
        e.preventDefault();
        alert('Lütfen aranacak içeriği giriniz.');
        return false;
      }
      return true;
    });
    
    replaceBtn.addEventListener('click', function(e) {
      // Show and make required when replacing
      replaceContainer.style.display = 'block';
      replaceInput.required = true;
      
      // Validate both fields for replace
      const searchContent = document.getElementById('search_content').value.trim();
      const replaceContent = replaceInput.value.trim();
      
      if (!searchContent) {
        e.preventDefault();
        alert('Lütfen aranacak içeriği giriniz.');
        return false;
      }
      
      if (!replaceContent) {
        e.preventDefault();
        alert('Lütfen yeni içeriği giriniz.');
        return false;
      }
      
      if (!confirm(`Aşağıdaki değişikliği yapmak istediğinize emin misiniz?\n\n"${searchContent}" → "${replaceContent}"\n\nBu işlem tüm eşleşen dosyalarda yapılacaktır.`)) {
        e.preventDefault();
        return false;
      }
      
      return true;
    });
  }
});

// File content modal functionality
const fileContentModal = document.getElementById('fileContentModal');
if (fileContentModal) {
  let currentFile = '';
  let searchTerm = '';
  let matches = [];
  let currentMatchIndex = -1;

  fileContentModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const filePath = button.getAttribute('data-filepath');
    searchTerm = button.getAttribute('data-searchterm');
    
    document.getElementById('filePathBadge').textContent = filePath.split(/[\\/]/).pop();
    document.getElementById('filePathBadge').title = filePath;
    
    // Load file content
    fetch('get_file_content.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `filepath=${encodeURIComponent(filePath)}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        currentFile = filePath;
        let content = data.content;
        
        // Highlight search term
        if (searchTerm) {
          const regex = new RegExp('(' + escapeRegExp(searchTerm) + ')', 'gi');
          content = content.replace(regex, '<mark class="bg-warning">$1</mark>');
          
          // Find all matches
          const matchRegex = new RegExp(escapeRegExp(searchTerm), 'gi');
          matches = [];
          let match;
          while ((match = matchRegex.exec(data.content)) !== null) {
            matches.push(match.index);
          }
          
          document.getElementById('resultCount').textContent = `${matches.length} eşleşme bulundu`;
          
          if (matches.length > 0) {
            currentMatchIndex = 0;
            scrollToMatch(currentMatchIndex);
          }
        }
        
        document.getElementById('fileContent').innerHTML = content;
      } else {
        document.getElementById('fileContent').textContent = 'Dosya içeriği yüklenemedi: ' + (data.message || 'Bilinmeyen hata');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('fileContent').textContent = 'Dosya yüklenirken bir hata oluştu: ' + error.message;
    });
  });
  
  // Navigation between matches
  document.getElementById('nextMatch').addEventListener('click', () => {
    if (matches.length > 0) {
      currentMatchIndex = (currentMatchIndex + 1) % matches.length;
      scrollToMatch(currentMatchIndex);
    }
  });
  
  document.getElementById('prevMatch').addEventListener('click', () => {
    if (matches.length > 0) {
      currentMatchIndex = (currentMatchIndex - 1 + matches.length) % matches.length;
      scrollToMatch(currentMatchIndex);
    }
  });
  
  function scrollToMatch(index) {
    if (matches.length === 0) return;
    
    const contentElement = document.getElementById('fileContent');
    const markElements = contentElement.getElementsByTagName('mark');
    
    if (markElements.length > 0 && index >= 0 && index < markElements.length) {
      markElements[index].scrollIntoView({ behavior: 'smooth', block: 'center' });
      
      // Remove previous highlight
      Array.from(markElements).forEach(el => el.classList.remove('bg-danger', 'text-white'));
      
      // Highlight current match
      markElements[index].classList.add('bg-danger', 'text-white');
      
      // Update navigation buttons state
      document.getElementById('prevMatch').disabled = matches.length <= 1;
      document.getElementById('nextMatch').disabled = matches.length <= 1;
    }
  }
  
  function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }
}
</script>

</body>
</html>
