<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$rol_kontrol_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php';
if (file_exists($rol_kontrol_path)) {
  include($rol_kontrol_path);
  if (function_exists('rol_kontrol')) rol_kontrol(1);
}

$baseDirectory = $_SERVER['DOCUMENT_ROOT'];

function findTag($directory, $tag) {
  $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
  $matches = [];
  foreach ($iterator as $file) {
    if ($file->isFile() && in_array(pathinfo($file, PATHINFO_EXTENSION), ['php', 'html'])) {
      $lines = explode("\n", file_get_contents($file->getPathname()));
      foreach ($lines as $i => $line) {
        if (strpos($line, $tag) !== false) {
          $matches[$file->getPathname()][] = [
            'line' => $i + 1,
            'content' => trim($line)
          ];
        }
      }
    }
  }
  return $matches;
}

function replaceTag($directory, $oldTag, $newTag) {
  $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
  $changes = [];
  foreach ($iterator as $file) {
    if ($file->isFile() && in_array(pathinfo($file, PATHINFO_EXTENSION), ['php', 'html'])) {
      $content = file_get_contents($file->getPathname());
      $newContent = str_replace($oldTag, $newTag, $content, $count);
      if ($count > 0) {
        file_put_contents($file, $newContent);
        $changes[$file->getPathname()] = $count;
      }
    }
  }
  return $changes;
}
?>
<!DOCTYPE html>
<html lang="tr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HTML Etiket Düzenleyici | BSDSoft</title>
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
      background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
      border-bottom: none;
      padding: 1.25rem 1.5rem;
    }
    .form-control:focus {
      border-color: #2e7d32;
      box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
    }
    .btn-success {
      background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
      border: none;
      padding: 0.6rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s;
    }
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    }
    .btn-warning {
      background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
      border: none;
      color: #fff;
    }
    .btn-warning:hover {
      background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
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
      border-left-color: #2e7d32;
    }
    .alert-danger {
      border-left-color: #c62828;
    }
    .alert-warning {
      border-left-color: #f57c00;
    }
    .alert-info {
      border-left-color: #0288d1;
    }
    .code-snippet {
      background-color: #f8f9fa;
      border-radius: 4px;
      padding: 2px 6px;
      font-family: 'Courier New', Courier, monospace;
      font-size: 0.9em;
      color: #d63384;
    }
    .line-number {
      color: #6c757d;
      user-select: none;
      min-width: 40px;
      text-align: right;
      padding-right: 10px;
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
                        <h5 class="mb-1 fw-bold text-dark">HTML Etiket Düzenleyici</h5>
                        <p class="text-muted small mb-0">Projedeki HTML etiketlerini kolayca bulun ve değiştirin</p>
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
              <div class="mb-4">
                <label for="old_tag" class="form-label fw-bold">
                  <i class="fas fa-search me-2 text-success"></i> Aranacak Etiket
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-tag text-muted"></i>
                  </span>
                  <input type="text" name="old_tag" id="old_tag" class="form-control form-control-lg" 
                         placeholder="örn: &lt;h1&gt;Başlık&lt;/h1&gt;" 
                         value="<?= htmlspecialchars($_POST['old_tag'] ?? '') ?>" required>
                </div>
                <div class="form-text text-muted small mt-1">
                  <i class="fas fa-info-circle me-1"></i> Aramak istediğiniz tam HTML etiketini giriniz.
                </div>
              </div>

              <div class="d-grid">
                <button type="submit" name="action" value="search" class="btn btn-success btn-lg py-2" id="searchBtn">
                  <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                  <i class="fas fa-search me-2"></i>Etiketi Ara
                </button>
              </div>
            </form>

      <?php
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $oldTag = $_POST['old_tag'] ?? '';

        if (!empty($oldTag)) {
          if ($_POST['action'] === 'search') {
            $results = findTag($baseDirectory, $oldTag);
            echo "<div class='mb-4'>";
            if ($results) {
              echo "<div class='alert alert-info'><i class='fas fa-check-circle me-1'></i> Etiket bulundu! Değiştirmek istersen aşağıdan yeni etiketi girebilirsin.</div>";
              echo "<ul class='list-group mb-3'>";
              foreach ($results as $file => $lines) {
                echo "<li class='list-group-item'>";
                echo "<strong>" . htmlspecialchars($file) . "</strong><ul class='small text-muted'>";
                foreach ($lines as $line) {
                  echo "<li>Satır {$line['line']}: <code>" . htmlspecialchars($line['content']) . "</code></li>";
                }
                echo "</ul></li>";
              }
              echo "</ul>";
              echo "<form method='post'>";
              echo "<input type='hidden' name='old_tag' value='" . htmlspecialchars($oldTag) . "'>";
              echo "<div class='mb-3'>";
              echo "<label for='new_tag' class='form-label fw-bold'>Yeni Etiket</label>";
              echo "<textarea name='new_tag' id='new_tag' rows='2' class='form-control' required placeholder='örn: &lt;h2&gt;Başlık&lt;/h2&gt;'></textarea>";
              echo "</div>";
              echo "<button type='submit' name='action' value='replace' class='btn btn-warning w-100'><i class='fas fa-sync-alt me-1'></i> Etiketi Değiştir</button>";
              echo "</form>";
            } else {
              echo "<div class='alert alert-warning'><i class='fas fa-exclamation-circle me-1'></i> Etiket bulunamadı.</div>";
            }
            echo "</div>";
          } elseif ($_POST['action'] === 'replace') {
            $newTag = $_POST['new_tag'] ?? '';
            if (!empty($newTag)) {
              $changes = replaceTag($baseDirectory, $oldTag, $newTag);
              echo "<div class='mb-4'>";
              if ($changes) {
                echo "<div class='alert alert-success'><i class='fas fa-check-circle me-1'></i> Etiket başarıyla değiştirildi!</div>";
                echo "<ul class='list-group'>";
                foreach ($changes as $file => $count) {
                  echo "<li class='list-group-item'>" . htmlspecialchars($file) . " – $count değişiklik</li>";
                }
                echo "</ul>";
              } else {
                echo "<div class='alert alert-warning'><i class='fas fa-exclamation-circle me-1'></i> Hiçbir dosyada değişiklik yapılmadı.</div>";
              }
              echo "</div>";
            } else {
              echo "<div class='alert alert-danger'><i class='fas fa-times-circle me-1'></i> Yeni etiket boş olamaz.</div>";
            }
          }
        } else {
          echo "<div class='alert alert-danger'><i class='fas fa-exclamation-triangle me-1'></i> Aranacak etiket girilmelidir.</div>";
        }
      }
      ?>

    </div>
  </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>
