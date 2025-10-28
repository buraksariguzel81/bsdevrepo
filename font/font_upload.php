<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Font Upload - ZIP Yükleme</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .upload-form {
            margin-bottom: 30px;
        }
        .file-input {
            width: 100%;
            padding: 10px;
            border: 2px dashed #007cba;
            border-radius: 5px;
            margin-bottom: 15px;
            background: #f9f9fa;
        }
        .btn {
            background: #007cba;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #005a87;
        }
        .btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
        .progress {
            display: none;
            margin-top: 20px;
        }
        .progress-bar {
            width: 0%;
            height: 20px;
            background: #28a745;
            border-radius: 10px;
            transition: width 0.3s;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .file-list {
            margin-top: 20px;
        }
        .file-item {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>

<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Upload işlemleri
$message = [];
$uploaded_files = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['font_zip'])) {
        $file = $_FILES['font_zip'];
        $upload_dir = './'; // font/ içinde

        $zip_name = basename($file['name']);
        $folder_name = pathinfo($zip_name, PATHINFO_FILENAME); // ZIP adından klasör adı
        $upload_path = $upload_dir . $zip_name;

        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_ext !== 'zip') {
            $message = ['type' => 'error', 'text' => 'Sadece ZIP dosyaları yüklenebilir!'];
        } elseif (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            $message = ['type' => 'error', 'text' => 'Dosya yüklenirken hata oluştu!'];
        } else {
            // ZIP'i klasör olarak çöz
            $zip = new ZipArchive();
            if ($zip->open($upload_path) === true) {
                // Font isimlerini içinden çıkar (woff/woff2 files'lardan)
                $font_names = [];
                $files_in_zip = $zip->numFiles;
                for ($i = 0; $i < $files_in_zip; $i++) {
                    $filename = $zip->getNameIndex($i);
                    if (!empty($filename) && preg_match('/(.+)\.(woff2?|ttf)$/i', $filename, $matches)) {
                        $font_names[] = $matches[1];
                    }
                }

                // İlk font adını klasör adı olarak kullan
                $dynamic_folder_name = !empty($font_names) ? $font_names[0] : $folder_name;

                $extract_path = $upload_dir . $dynamic_folder_name . '/'; // Dinamik klasör adı

                if ($zip->extractTo($extract_path)) {
                    // Çıkartılan dosyaları listele
                    $extracted_files = [];
                    for ($i = 0; $i < $files_in_zip; $i++) {
                        $filename = $zip->getNameIndex($i);
                        if (!empty($filename)) {
                            $extracted_files[] = $dynamic_folder_name . '/' . $filename;
                        }
                    }

                    $message = ['type' => 'success', 'text' => "Font '{$dynamic_folder_name}' klasörü olarak başarıyla yüklendi ve çıkarıldı!"];
                    $uploaded_files = $extracted_files;

                    // Clean up
                    $zip->close();
                    unlink($upload_path); // ZIP dosyasını sil
                } else {
                    $message = ['type' => 'error', 'text' => 'ZIP çıkarma hatası!'];
                    $zip->close();
                }
            } else {
                $message = ['type' => 'error', 'text' => 'ZIP açma hatası!'];
            }
        }
    }
}
?>

<div class="container">
    <div class="header">
        <h1>📦 Font ZIP Yükleme</h1>
        <p>Font dosyalarınızı ZIP halinde yükleyin ve klasör olarak çıkarılacaktır.</p>
    </div>

    <?php if ($message): ?>
        <div class="result <?php echo $message['type']; ?>">
            <strong><?php echo htmlspecialchars($message['text'], ENT_QUOTES); ?></strong>
            <?php if (!empty($uploaded_files)): ?>
                <br><br>
                <strong>Yüklenen Dosyalar:</strong><br>
                <div class="file-list">
                    <?php foreach ($uploaded_files as $ufile): ?>
                        <div class="file-item"><?php echo htmlspecialchars($ufile, ENT_QUOTES); ?></div>
                    <?php endforeach; ?>
                </div>
                <br>
                <a href="font.php" class="btn">🔄 Font Yönetimine Git ve CSS'yi Güncelle</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="upload-form">
        <label for="font_zip">ZIP Dosyası Seçin (Klasekül olarak çıkarılacak):</label>
        <input type="file" name="font_zip" id="font_zip" accept=".zip" required class="file-input">

        <button type="submit" class="btn">📤 Yükle ve Klasöre Çıkar</button>
    </form>

    <div class="progress">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <script>
        // Progress bar gösterimi (basit)
        document.querySelector('form').addEventListener('submit', function(e) {
            document.querySelector('.progress').style.display = 'block';
            document.querySelector('#progressBar').style.width = '50%'; // Loading...
        });
    </script>
</div>

</body>
</html>
