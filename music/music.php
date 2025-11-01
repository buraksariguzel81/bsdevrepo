<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/src/classes/MusicManager.php');

// Music Manager'ı başlat
$musicManager = new MusicManager('.');

// JSON dosyasını güncelle
if (isset($_POST['update_json'])) {
    $music_data = $musicManager->generateMusicData();
    $musicManager->updateJson($music_data);
    $success_message = "JSON dosyası başarıyla güncellendi!";
}

// Müzik dosyalarını al
$music_files = $musicManager->scanMusicFiles();

// HTML başlat
echo $musicManager->getHtmlHead("Music Management System");

echo "<div class='container'>";
echo "<div class='header'>";
echo "<h1>🎵 Music Management System</h1>";
echo "<p>Müzik klasörünü otomatik tarar ve JSON dosyasını günceller</p>";
echo "</div>";

if (isset($success_message)) {
    echo "<div class='success'>✅ $success_message</div>";
}

// İstatistikler
$total_size = $musicManager->getTotalSize($music_files);
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
echo "<div class='asset-grid'>";

foreach ($music_files as $music_file) {
    echo $musicManager->renderMusicCard($music_file);
}

echo "</div>";
echo "</div>";
echo "</body></html>";
?>