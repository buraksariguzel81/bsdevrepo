
<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<?php
// Hata raporlamayı açalım
error_reporting(E_ALL);
ini_set('display_errors', 1);



// CSRF token oluştur
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Kök dizini tanımlayalım (document root kullanarak)
$baseDirectory = $_SERVER['DOCUMENT_ROOT'];

// Mevcut değerleri bulma fonksiyonu
function findExistingValues($directory) {
    $values = [
        'api_key' => '',
        'from_email' => '',
        'from_name' => '',
        'base_url' => '',
        'openai_api_key' => '',
        'gpt_model' => '',
        'google_api_key' => '',
        'google_custom_search_engine_id' => ''
    ];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST,
        RecursiveIteratorIterator::CATCH_GET_CHILD
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'html', 'sh'])) {
            $content = file_get_contents($file->getPathname());
            
            // PHP ve HTML dosyaları için mevcut regex'ler
            if ($file->getExtension() == 'php' || $file->getExtension() == 'html') {
                if (preg_match('/\$apiKey\s*=\s*[\'"](.+?)[\'"]\s*;/', $content, $matches)) {
                    $values['api_key'] = $matches[1];
                }
                
                if (preg_match('/\'email\'\s*=>\s*[\'"](.+?)[\'"]\s*,/', $content, $matches)) {
                    $values['from_email'] = $matches[1];
                }
                
                if (preg_match('/\'name\'\s*=>\s*[\'"](.+?)[\'"]\s*,/', $content, $matches)) {
                    $values['from_name'] = $matches[1];
                }
            }
            
            // SH dosyaları için yeni regex'ler
            if ($file->getExtension() == 'sh') {
                if (preg_match('/Authorization:\s*Bearer\s*(.+?)[\'"]/i', $content, $matches)) {
                    $values['api_key'] = $matches[1];
                }
                
                if (preg_match('/\"email\":\s*\"(.+?)\"/', $content, $matches)) {
                    $values['from_email'] = $matches[1];
                }
                
                if (preg_match('/\"name\":\s*\"(.+?)\"/', $content, $matches)) {
                    $values['from_name'] = $matches[1];
                }
            }
        }
    }

    return $values;
}

// Değerleri güncelleme fonksiyonu
function updateValues($directory, $oldValues, $newValues) {
    $updatedFiles = 0;
    $updatedFields = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST,
        RecursiveIteratorIterator::CATCH_GET_CHILD
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'html', 'sh'])) {
            $content = file_get_contents($file->getPathname());
            $updatedContent = $content;
            $fileUpdated = false;
            
            if ($file->getExtension() == 'php' || $file->getExtension() == 'html') {
                if (!empty($newValues['api_key'])) {
                    $pattern = '/(\$apiKey\s*=\s*[\'"]).*?([\'"])/';
                    $replacement = '${1}' . $newValues['api_key'] . '${2}';
                    $updatedContent = preg_replace($pattern, $replacement, $updatedContent);
                    if ($updatedContent !== $content) {
                        $fileUpdated = true;
                        $updatedFields['api_key'] = true;
                    }
                }
                
                if (!empty($newValues['from_email'])) {
                    $pattern = '/(\'email\'\s*=>\s*[\'"]).*?([\'"])/';
                    $replacement = '${1}' . $newValues['from_email'] . '${2}';
                    $updatedContent = preg_replace($pattern, $replacement, $updatedContent);
                    if ($updatedContent !== $content) {
                        $fileUpdated = true;
                        $updatedFields['from_email'] = true;
                    }
                }
                
                if (!empty($newValues['from_name'])) {
                    $pattern = '/(\'name\'\s*=>\s*[\'"]).*?([\'"])/';
                    $replacement = '${1}' . $newValues['from_name'] . '${2}';
                    $updatedContent = preg_replace($pattern, $replacement, $updatedContent);
                    if ($updatedContent !== $content) {
                        $fileUpdated = true;
                        $updatedFields['from_name'] = true;
                    }
                }
            }
            
            if ($file->getExtension() == 'sh') {
                if (!empty($newValues['api_key'])) {
                    $pattern = '/(Authorization:\s*Bearer\s*).*?([\'"])/i';
                    $replacement = '${1}' . $newValues['api_key'] . '${2}';
                    $updatedContent = preg_replace($pattern, $replacement, $updatedContent);
                    if ($updatedContent !== $content) {
                        $fileUpdated = true;
                        $updatedFields['api_key'] = true;
                    }
                }
                
                if (!empty($newValues['from_email'])) {
                    $pattern = '/(\"email\":\s*\").*?(\")/';;
                    $replacement = '${1}' . $newValues['from_email'] . '${2}';
                    $updatedContent = preg_replace($pattern, $replacement, $updatedContent);
                    if ($updatedContent !== $content) {
                        $fileUpdated = true;
                        $updatedFields['from_email'] = true;
                    }
                }
                
                if (!empty($newValues['from_name'])) {
                    $pattern = '/(\"name\":\s*\").*?(\")/';;
                    $replacement = '${1}' . $newValues['from_name'] . '${2}';
                    $updatedContent = preg_replace($pattern, $replacement, $updatedContent);
                    if ($updatedContent !== $content) {
                        $fileUpdated = true;
                        $updatedFields['from_name'] = true;
                    }
                }
            }
            
            if ($fileUpdated) {
                file_put_contents($file->getPathname(), $updatedContent);
                $updatedFiles++;
            }
        }
    }

    return ['updatedFiles' => $updatedFiles, 'updatedFields' => $updatedFields];
}

// Mevcut değerleri bul
$existingValues = findExistingValues($baseDirectory);

// Eğer form gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token doğrulama hatası!');
    }

    $new_values = [
        'api_key' => trim($_POST['api_key'] ?? ''),
        'from_email' => trim($_POST['from_email'] ?? ''),
        'from_name' => trim($_POST['from_name'] ?? '')
    ];

    $mesaj = "";
    $hata_mesaji = "";

    try {
        // Basit doğrulama
        if (!empty($new_values['from_email']) && !filter_var($new_values['from_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Geçersiz e-posta adresi formatı!");
        }

        // Değerleri güncelle
        $updateResult = updateValues($baseDirectory, $existingValues, $new_values);

        $mesaj = "Güncelleme tamamlandı. {$updateResult['updatedFiles']} dosya güncellendi.<br>";
        if (!empty($updateResult['updatedFields'])) {
            if (isset($updateResult['updatedFields']['api_key'])) {
                $mesaj .= "API anahtarı güncellendi.<br>";
            }
            if (isset($updateResult['updatedFields']['from_email'])) {
                $mesaj .= "E-posta adresi güncellendi.<br>";
            }
            if (isset($updateResult['updatedFields']['from_name'])) {
                $mesaj .= "Gönderen ismi güncellendi.<br>";
            }
        } else {
            $mesaj .= "Hiçbir değişiklik yapılmadı.";
        }
        $existingValues = findExistingValues($baseDirectory);
    } catch (Exception $e) {
        $hata_mesaji = "Bir hata oluştu: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API ve E-posta Bilgileri Güncelleme</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .mesaj {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .hata {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .bsd-btn1 {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .bsd-btn1:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body class="bg-light">

  <div class="container py-5">

    <div class="card shadow-sm rounded">
      <div class="card-body">

        <!-- Başlık -->
        <h5 class="card-title mb-4 text-primary">
          <i class="fas fa-envelope-open-text me-2"></i> API ve E-posta Bilgileri Güncelleme
        </h5>

        <!-- Mevcut Bilgiler -->
        <?php if(!empty($existingValues['api_key'])): ?>
        <div class="mb-3">
          <p><i class="fas fa-key me-2"></i> Mevcut API Anahtarı: 
            <code><?= htmlspecialchars($existingValues['api_key']) ?></code></p>
          <p><i class="fas fa-at me-2"></i> Mevcut E-posta: 
            <strong><?= htmlspecialchars($existingValues['from_email']) ?></strong></p>
          <p><i class="fas fa-user-tag me-2"></i> Mevcut İsim: 
            <strong><?= htmlspecialchars($existingValues['from_name']) ?></strong></p>
        </div>
        <?php endif; ?>

        <!-- Mesaj Gösterimi -->
        <?php if(isset($mesaj) && $mesaj !== ""): ?>
          <div class="alert alert-success"><?= htmlspecialchars($mesaj) ?></div>
        <?php endif; ?>

        <?php if(isset($hata_mesaji) && $hata_mesaji !== ""): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($hata_mesaji) ?></div>
        <?php endif; ?>

        <!-- Güncelleme Formu -->
        <form method="post">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

          <div class="mb-3">
            <label for="api_key" class="form-label"><i class="fas fa-key me-2"></i> API Anahtarı</label>
            <input type="text" class="form-control" id="api_key" name="api_key">
          </div>

          <div class="mb-3">
            <label for="from_email" class="form-label"><i class="fas fa-at me-2"></i> Gönderen E-posta</label>
            <input type="email" class="form-control" id="from_email" name="from_email">
          </div>

          <div class="mb-3">
            <label for="from_name" class="form-label"><i class="fas fa-user-tag me-2"></i> Gönderen İsim</label>
            <input type="text" class="form-control" id="from_name" name="from_name">
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-save me-1"></i> Güncelle
          </button>
        </form>

      </div>
    </div>

  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>

</html>
