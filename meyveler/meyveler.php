<?php
// Meyve yönetim sistemi
$meyve_dir = '.';
$css_file = 'meyveler.css';

// Meyve klasörlerindeki resimleri tara
$meyve_folders = array_filter(scandir($meyve_dir), function($item) use ($meyve_dir) {
    return is_file($meyve_dir . '/' . $item) && in_array(pathinfo($item, PATHINFO_EXTENSION), ['png', 'jpg', 'jpeg', 'gif', 'webp']);
});

// CSS dosyasını güncelle
if (isset($_POST['update_css'])) {
    $css_content = "/* Otomatik meyve stilleri - " . date('Y-m-d H:i:s') . " */\n\n";

    foreach ($meyve_folders as $meyve_file) {
        $meyve_name = pathinfo($meyve_file, PATHINFO_FILENAME);
        $meyve_ext = pathinfo($meyve_file, PATHINFO_EXTENSION);

        $css_content .= "/* $meyve_name */\n";
        $css_content .= ".meyve-$meyve_name {\n";
        $css_content .= "    background-image: url('https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/meyveler/$meyve_file');\n";
        $css_content .= "    background-size: contain;\n";
        $css_content .= "    background-repeat: no-repeat;\n";
        $css_content .= "    background-position: center;\n";
        $css_content .= "    width: 100px;\n";
        $css_content .= "    height: 100px;\n";
        $css_content .= "    display: inline-block;\n";
        $css_content .= "}\n\n";

        $css_content .= ".meyve-$meyve_name:hover {\n";
        $css_content .= "    transform: scale(1.1);\n";
        $css_content .= "    transition: transform 0.3s ease;\n";
        $css_content .= "}\n\n";
    }

    file_put_contents($css_file, $css_content);
    $success_message = "CSS dosyası başarıyla güncellendi!";
}

// CSS dosyasını oku ve meyve isimlerini çıkar
$css_content = file_get_contents($css_file);
preg_match_all('/\.meyve-([A-Za-z_]+)\s*{/', $css_content, $matches);
$meyveler = $matches[1];

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Meyve Management System</title>";
echo '<link rel="stylesheet" href="meyveler.css">';
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .container { max-width: 1200px; margin: 0 auto; }
    .header { background: linear-gradient(45deg, #ff6b6b, #4ecdc4); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    .meyve-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
    .meyve-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9; text-align: center; }
    .meyve-name { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #333; }
    .meyve-preview { width: 120px; height: 120px; margin: 10px auto; border-radius: 8px; background: white; border: 2px solid #eee; }
    .meyve-code { background: #f0f0f0; padding: 5px; border-radius: 3px; font-family: monospace; font-size: 12px; }
    .btn { background: #ff6b6b; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    .btn:hover { background: #ff5252; }
    .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }
    .stats { background: #e8f5e8; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #4ecdc4; }
</style>";
echo "</head><body>";

echo "<div class='container'>";
echo "<div class='header'>";
echo "<h1>🍎 Meyve Management System</h1>";
echo "<p>Meyve resimlerini otomatik tarar ve CSS sınıfları oluşturur</p>";
echo "</div>";

if (isset($success_message)) {
    echo "<div class='success'>✅ $success_message</div>";
}

echo "<div class='stats'>";
echo "<h3>📊 Meyve İstatistikleri</h3>";
echo "<p><strong>Toplam Meyve Resmi:</strong> " . count($meyve_folders) . "</p>";
echo "<p><strong>Aktif CSS Sınıfı:</strong> " . count($meyveler) . "</p>";
echo "<p><strong>Son Güncelleme:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "</div>";

echo "<form method='post'>";
echo "<button type='submit' name='update_css' class='btn'>🔄 CSS Dosyasını Güncelle</button>";
echo "</form>";

echo "<h2>🍓 Meyve Galerisi</h2>";
echo "<div class='meyve-grid'>";

foreach ($meyveler as $meyve) {
    echo "<div class='meyve-card'>";
    echo "<div class='meyve-name'>" . ucfirst($meyve) . "</div>";
    echo "<div class='meyve-preview meyve-$meyve'></div>";
    echo "<div class='meyve-code'>.meyve-$meyve</div>";
    echo "</div>";
}

echo "</div>";
echo "</div>";
echo "</body></html>";
?>
