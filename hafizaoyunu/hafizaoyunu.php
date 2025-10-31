<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
// Hafıza Oyunu yönetim sistemi

$image_dir = '.';
$json_file = 'hafizaoyunu_list.json';

// Resim dosyalarını tara
$image_files = array_filter(scandir($image_dir), function ($item) use ($image_dir) {
    $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
    return is_file($image_dir . '/' . $item) && in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
});

// JSON dosyasını güncelle
if (isset($_POST['update_json'])) {
    $json_images = [];

    foreach ($image_files as $image_file) {
        $file_path = $image_dir . '/' . $image_file;
        $file_info = pathinfo($image_file);
        $file_size = filesize($file_path);
        $file_size_kb = round($file_size / 1024, 2);

        // Resim boyutlarını al
        $image_info = getimagesize($file_path);
        $width = $image_info[0] ?? 0;
        $height = $image_info[1] ?? 0;

        $json_images[] = [
            'name' => $file_info['filename'],
            'filename' => $image_file,
            'extension' => $file_info['extension'],
            'size' => $file_size,
            'size_kb' => $file_size_kb,
            'width' => $width,
            'height' => $height,
            'dimensions' => $width . 'x' . $height,
            'url' => "https://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/hafizaoyunu/$image_file",
            'local_path' => "./hafizaoyunu/$image_file",
            'updated' => date('Y-m-d H:i:s')
        ];
    }

    $json_data = json_encode(['images' => $json_images], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($json_file, $json_data);

    $success_message = "JSON dosyası başarıyla güncellendi!";
}

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Hafıza Oyunu Management System</title>";
echo '<style>';
echo '.container { max-width: 1200px; margin: 0 auto; }';
echo '.header { background: #333; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }';
echo '.image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }';
echo '.image-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9; }';
echo '.image-name { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #333; }';
echo '.image-info { font-size: 14px; color: #666; margin: 5px 0; }';
echo '.image-preview { text-align: center; margin: 10px 0; }';
echo '.image-preview img { max-width: 150px; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }';
echo '.image-url { font-size: 12px; color: #555; background: #e9ecef; padding: 5px; border-radius: 3px; word-break: break-all; margin: 5px 0; }';
echo '.btn { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }';
echo '.btn:hover { background: #005a87; }';
echo '.btn-small { background: #28a745; color: white; padding: 5px 10px; font-size: 12px; border: none; border-radius: 4px; cursor: pointer; }';
echo '.btn-small:hover { background: #218838; }';
echo '.success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }';
echo '.stats { background: #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 20px; }';
echo '.game-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }';
echo '</style>';
echo '<script>';
echo 'function copyToClipboard(elementId) {';
echo '    var copyText = document.getElementById(elementId);';
echo '    copyText.select();';
echo '    copyText.setSelectionRange(0, 99999);';
echo '    if (navigator.clipboard && window.isSecureContext) {';
echo '        navigator.clipboard.writeText(copyText.value).then(function() {';
echo '            alert("Kopyalandı!");';
echo '        }, function(err) {';
echo '            alert("Kopyalama başarısız: " + err);';
echo '        });';
echo '    } else {';
echo '        var textArea = document.createElement("textarea");';
echo '        textArea.value = copyText.value;';
echo '        document.body.appendChild(textArea);';
echo '        textArea.focus();';
echo '        textArea.select();';
echo '        try {';
echo '            document.execCommand("copy");';
echo '            alert("Kopyalandı!");';
echo '        } catch (err) {';
echo '            alert("Kopyalama başarısız!");';
echo '        }';
echo '        document.body.removeChild(textArea);';
echo '    }';
echo '}';
echo '</script>';
echo "</head><body>";

echo "<div class='container mt-5'>";
echo "<div class='header game-card'>";
echo "<h1>🧠 Hafıza Oyunu Management System</h1>";
echo "<p>Hafıza oyunu resimlerini otomatik tarar ve JSON dosyasını günceller</p>";
echo "</div>";

if (isset($success_message)) {
    echo "<div class='success'>✅ $success_message</div>";
}

// Toplam dosya boyutunu hesapla
$total_size = 0;
foreach ($image_files as $image_file) {
    $total_size += filesize($image_dir . '/' . $image_file);
}
$total_size_mb = round($total_size / 1024 / 1024, 2);

echo "<div class='stats'>";
echo "<h3>📊 İstatistikler</h3>";
echo "<p><strong>Toplam Resim Dosyası:</strong> " . count($image_files) . "</p>";
echo "<p><strong>Toplam Boyut:</strong> " . $total_size_mb . " MB</p>";
echo "<p><strong>Son Güncelleme:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "</div>";

echo "<form method='post'>";
echo "<button type='submit' name='update_json' class='btn'>🔄 JSON Dosyasını Güncelle</button>";
echo "</form>";

echo "<h2>🖼️ Image Preview</h2>";
echo "<div class='image-grid'>";

foreach ($image_files as $image_file) {
    $file_info = pathinfo($image_file);
    $file_size = filesize($image_dir . '/' . $image_file);
    $file_size_kb = round($file_size / 1024, 2);
    $cdn_url = "https://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/hafizaoyunu/$image_file";
    $local_url = "./$image_file";

    // Resim boyutlarını al
    $image_info = getimagesize($image_dir . '/' . $image_file);
    $width = $image_info[0] ?? 0;
    $height = $image_info[1] ?? 0;

    echo "<div class='image-card'>";
    echo "<div class='image-name'>🖼️ " . $file_info['filename'] . "</div>";
    echo "<div class='image-info'><strong>Dosya:</strong> $image_file</div>";
    echo "<div class='image-info'><strong>Format:</strong> " . strtoupper($file_info['extension']) . "</div>";
    echo "<div class='image-info'><strong>Boyut:</strong> $file_size_kb KB</div>";
    echo "<div class='image-info'><strong>Çözünürlük:</strong> {$width}x{$height}px</div>";

    echo "<div class='image-preview'>";
    echo "<img src='$local_url' alt='" . $file_info['filename'] . "' loading='lazy'>";
    echo "</div>";

    echo "<div style='display: flex; gap: 10px; align-items: center; margin-top: 10px;'>";
    echo "<input type='text' value='$cdn_url' id='cdn-$image_file' readonly class='image-url' style='flex: 1;'>";
    echo "<button onclick='copyToClipboard(\"cdn-$image_file\")' class='btn-small'>📋 CDN URL</button>";
    echo "</div>";

    echo "<div style='display: flex; gap: 10px; align-items: center; margin-top: 5px;'>";
    echo "<input type='text' value='$local_url' id='local-$image_file' readonly class='image-url' style='flex: 1;'>";
    echo "<button onclick='copyToClipboard(\"local-$image_file\")' class='btn-small'>🗂️ Local Path</button>";
    echo "</div>";

    echo "</div>";
}

echo "</div>";
echo "</div>";
echo "</body></html>";
