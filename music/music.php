<?php
// Müzik yönetim sistemi - CDN uyumlu
$music_dir = __DIR__; // Şu anki dosyanın bulunduğu dizin
$css_file = $music_dir . '/music.css';
$json_file = $music_dir . '/musicList.json';
$cdn_base = 'https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/';

// Desteklenen müzik formatları
$supported_formats = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];

// Müzik dosyalarını tara
$music_files = array_filter(scandir($music_dir), function ($item) use ($music_dir, $supported_formats) {
    return is_file($music_dir . '/' . $item) && in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), $supported_formats);
});

// CSS ve JSON dosyalarını güncelle
if (isset($_POST['update_files'])) {
    // CSS içeriği oluştur
    $css_content = "/* Otomatik müzik stilleri - " . date('Y-m-d H:i:s') . " */\n\n";

foreach ($music_files as $file) {
    $name = str_replace(' ', '_', pathinfo($file, PATHINFO_FILENAME));
    $ext = pathinfo($file, PATHINFO_EXTENSION);

        $css_content .= "/* $name */\n";
        $css_content .= ".music-$name {\n";
        $css_content .= "    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjZmY2YjZiIi8+CjxwYXRoIGQ9Ik01MCAyMEw3MCA0MEg1MEw0MCA0MFoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik01MCA0MEw3MCA2MEg1MEw0MCA2MFoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik01MCA2MEw3MCA4MEg1MEw0MCA4MFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=');\n";
        $css_content .= "    background-size: contain;\n";
        $css_content .= "    background-repeat: no-repeat;\n";
        $css_content .= "    background-position: center;\n";
        $css_content .= "    width: 100px;\n";
        $css_content .= "    height: 100px;\n";
        $css_content .= "    display: inline-block;\n";
        $css_content .= "    border-radius: 8px;\n";
        $css_content .= "    border: 2px solid #eee;\n";
        $css_content .= "    margin: 5px;\n";
        $css_content .= "    cursor: pointer;\n";
        $css_content .= "    position: relative;\n";
        $css_content .= "}\n\n";

        $css_content .= ".music-$name:hover {\n";
        $css_content .= "    transform: scale(1.1);\n";
        $css_content .= "    transition: transform 0.3s ease;\n";
        $css_content .= "    border-color: #ff6b6b;\n";
        $css_content .= "}\n\n";

        $css_content .= ".music-$name::after {\n";
        $css_content .= "    content: '▶';\n";
        $css_content .= "    position: absolute;\n";
        $css_content .= "    top: 50%;\n";
        $css_content .= "    left: 50%;\n";
        $css_content .= "    transform: translate(-50%, -50%);\n";
        $css_content .= "    background: rgba(0,0,0,0.7);\n";
        $css_content .= "    color: white;\n";
        $css_content .= "    width: 30px;\n";
        $css_content .= "    height: 30px;\n";
        $css_content .= "    border-radius: 50%;\n";
        $css_content .= "    display: flex;\n";
        $css_content .= "    align-items: center;\n";
        $css_content .= "    justify-content: center;\n";
        $css_content .= "    font-size: 12px;\n";
        $css_content .= "}\n\n";

        // Audio element için CSS
        $css_content .= ".audio-$name {\n";
        $css_content .= "    display: none;\n";
        $css_content .= "}\n\n";
    }

    file_put_contents($css_file, $css_content);

    // JSON içeriği oluştur
    $json_data = [
        'generated' => date('Y-m-d H:i:s'),
        'total' => count($music_files),
        'music' => []
    ];

    foreach ($music_files as $file) {
        $name = str_replace(' ', '_', pathinfo($file, PATHINFO_FILENAME));
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $clean_name = ucwords(str_replace(['_', '-'], ' ', $name));

        $json_data['music'][] = [
            'id' => $name,
            'name' => $clean_name,
            'filename' => $file,
            'url' => $cdn_base . $file,
            'cdn_url' => $cdn_base . $file,
            'css_class' => 'music-' . $name,
            'audio_class' => 'audio-' . $name,
            'type' => 'audio/' . $ext,
            'size' => rand(1, 10) . '.' . rand(1, 9) . ' MB', // Geçici dosya boyutu
            'duration' => rand(2, 6) . ':' . rand(10, 59) // Geçici süre
        ];
    }

    file_put_contents($json_file, json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $success_message = "CSS ve JSON dosyaları başarıyla güncellendi!";
}

// JSON dosyasını oku
$json_content = '';
if (file_exists($json_file)) {
    $json_data = json_decode(file_get_contents($json_file), true);
    $json_content = file_get_contents($json_file);
}

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Müzik Management System</title>";
echo '<link rel="stylesheet" href="music.css">';
echo "</head><body>";

echo "<h1>🎵 Müzik Management System</h1>";
echo "<p>Müzik dosyalarını otomatik tarar ve CSS/JSON oluşturur</p>";

// Sadece CSS güncelleme formu göster
if (isset($_POST['update_files'])) {
    echo "<div style='background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0;'>✅ $success_message</div>";
}

echo "<form method='post' style='margin: 20px 0;'>";
echo "<button type='submit' name='update_files' style='background: #ff6b6b; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;'>🔄 CSS ve JSON Güncelle</button>";
echo "</form>";

echo "<h2>📊 Müzik İstatistikleri</h2>";
echo "<p><strong>Toplam Müzik Dosyası:</strong> " . count($music_files) . "</p>";
echo "<p><strong>Son Güncelleme:</strong> " . date('Y-m-d H:i:s') . "</p>";

echo "<h2>🎵 Müzik Listesi</h2>";
echo "<div style='display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;'>";

foreach ($music_files as $file) {
    $name = str_replace(' ', '_', pathinfo($file, PATHINFO_FILENAME));
    echo "<div style='border: 1px solid #ddd; border-radius: 8px; padding: 15px; text-align: center; background: #f9f9f9;'>";
    echo "<h3>" . ucwords(str_replace(['_', '-'], ' ', $name)) . "</h3>";
    echo "<div class='music-$name' style='margin: 10px auto;'></div>";
    echo "<code style='background: #f0f0f0; padding: 5px; border-radius: 3px; font-family: monospace; font-size: 12px;'>CSS: .music-$name</code>";
    echo "<br><small style='color: #666;'>$file</small>";
    echo "</div>";
}

echo "</div>";

echo "<h2>🔗 CDN Bağlantıları</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 8px;'>";
foreach ($music_files as $file) {
    $name = str_replace(' ', '_', pathinfo($file, PATHINFO_FILENAME));
    echo "<div style='margin: 5px 0;'>";
    echo "<strong>" . ucwords(str_replace(['_', '-'], ' ', $name)) . ":</strong> ";
    echo "<a href='$cdn_base$file' target='_blank'>$cdn_base$file</a>";
    echo "</div>";
}
echo "</div>";

if (!empty($json_content)) {
    echo "<h2>📄 JSON İçeriği</h2>";
    echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 8px; overflow-x: auto;'>" . htmlspecialchars($json_content) . "</pre>";
}

echo "</body></html>";
?>
