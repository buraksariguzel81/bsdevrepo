<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
// Music yönetim sistemi

$music_dir = '.';
$json_file = 'music_list.json';

// Müzik dosyalarını tara
$music_files = array_filter(scandir($music_dir), function ($item) use ($music_dir) {
    $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
    return is_file($music_dir . '/' . $item) && in_array($extension, ['mp3', 'wav', 'ogg', 'm4a', 'flac']);
});

// JSON dosyasını güncelle
if (isset($_POST['update_json'])) {
    $json_music = [];

    foreach ($music_files as $music_file) {
        $file_path = $music_dir . '/' . $music_file;
        $file_info = pathinfo($music_file);
        $file_size = filesize($file_path);
        $file_size_mb = round($file_size / 1024 / 1024, 2);

        $json_music[] = [
            'name' => $file_info['filename'],
            'filename' => $music_file,
            'extension' => $file_info['extension'],
            'size' => $file_size,
            'size_mb' => $file_size_mb,
            'url' => "https://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/music/$music_file",
            'local_path' => "./music/$music_file",
            'updated' => date('Y-m-d H:i:s')
        ];
    }

    $json_data = json_encode(['music' => $json_music], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($json_file, $json_data);

    $success_message = "JSON dosyası başarıyla güncellendi!";
}

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Music Management System</title>";
echo '<style>';
echo '.container { max-width: 1200px; margin: 0 auto; }';
echo '.header { background: #333; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }';
echo '.music-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px; }';
echo '.music-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9; }';
echo '.music-name { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #333; }';
echo '.music-info { font-size: 14px; color: #666; margin: 5px 0; }';
echo '.music-player { margin: 10px 0; }';
echo '.music-url { font-size: 12px; color: #555; background: #e9ecef; padding: 5px; border-radius: 3px; word-break: break-all; margin: 5px 0; }';
echo '.btn { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }';
echo '.btn:hover { background: #005a87; }';
echo '.btn-small { background: #28a745; color: white; padding: 5px 10px; font-size: 12px; border: none; border-radius: 4px; cursor: pointer; }';
echo '.btn-small:hover { background: #218838; }';
echo '.success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }';
echo '.stats { background: #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 20px; }';
echo 'audio { width: 100%; margin: 10px 0; }';
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
echo "<div class='header'>";
echo "<h1>🎵 Music Management System</h1>";
echo "<p>Müzik klasörünü otomatik tarar ve JSON dosyasını günceller</p>";
echo "</div>";

if (isset($success_message)) {
    echo "<div class='success'>✅ $success_message</div>";
}

// Toplam dosya boyutunu hesapla
$total_size = 0;
foreach ($music_files as $music_file) {
    $total_size += filesize($music_dir . '/' . $music_file);
}
$total_size_mb = round($total_size / 1024 / 1024, 2);

echo "<div class='stats'>";
echo "<h3>📊 İstatistikler</h3>";
echo "<p><strong>Toplam Müzik Dosyası:</strong> " . count($music_files) . "</p>";
echo "<p><strong>Toplam Boyut:</strong> " . $total_size_mb . " MB</p>";
echo "<p><strong>Son Güncelleme:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "</div>";

echo "<form method='post'>";
echo "<button type='submit' name='update_json' class='btn'>🔄 JSON Dosyasını Güncelle</button>";
echo "</form>";

echo "<h2>🎶 Music Preview</h2>";
echo "<div class='music-grid'>";

foreach ($music_files as $music_file) {
    $file_info = pathinfo($music_file);
    $file_size = filesize($music_dir . '/' . $music_file);
    $file_size_mb = round($file_size / 1024 / 1024, 2);
    $cdn_url = "https://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/music/$music_file";
    $local_url = "./$music_file";

    echo "<div class='music-card'>";
    echo "<div class='music-name'>🎵 " . $file_info['filename'] . "</div>";
    echo "<div class='music-info'><strong>Dosya:</strong> $music_file</div>";
    echo "<div class='music-info'><strong>Format:</strong> " . strtoupper($file_info['extension']) . "</div>";
    echo "<div class='music-info'><strong>Boyut:</strong> $file_size_mb MB</div>";

    echo "<div class='music-player'>";
    echo "<audio controls>";
    echo "<source src='$local_url' type='audio/" . $file_info['extension'] . "'>";
    echo "Tarayıcınız audio elementini desteklemiyor.";
    echo "</audio>";
    echo "</div>";

    echo "<div style='display: flex; gap: 10px; align-items: center; margin-top: 10px;'>";
    echo "<input type='text' value='$cdn_url' id='cdn-$music_file' readonly class='music-url' style='flex: 1;'>";
    echo "<button onclick='copyToClipboard(\"cdn-$music_file\")' class='btn-small'>📋 CDN URL</button>";
    echo "</div>";

    echo "<div style='display: flex; gap: 10px; align-items: center; margin-top: 5px;'>";
    echo "<input type='text' value='$local_url' id='local-$music_file' readonly class='music-url' style='flex: 1;'>";
    echo "<button onclick='copyToClipboard(\"local-$music_file\")' class='btn-small'>🗂️ Local Path</button>";
    echo "</div>";

    echo "</div>";
}

echo "</div>";
echo "</div>";
echo "</body></html>";
