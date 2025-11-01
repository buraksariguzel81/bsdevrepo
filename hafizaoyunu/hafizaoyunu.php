<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/src/classes/ImageManager.php');

// Image Manager'ı başlat (hafizaoyunu asset type ile)
$imageManager = new ImageManager('.', 'hafizaoyunu');

// JSON dosyasını güncelle
if (isset($_POST['update_json'])) {
    $image_data = $imageManager->generateImageData();
    $imageManager->updateJson($image_data);
    $success_message = "JSON dosyası başarıyla güncellendi!";
}

// Resim dosyalarını al
$image_files = $imageManager->scanImageFiles();

// HTML başlat
echo $imageManager->getHtmlHead("Hafıza Oyunu Management System");

echo "<div class='container'>";
echo "<div class='header' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);'>";
echo "<h1>🧠 Hafıza Oyunu Management System</h1>";
echo "<p>Hafıza oyunu resimlerini otomatik tarar ve JSON dosyasını günceller</p>";
echo "</div>";

if (isset($success_message)) {
    echo "<div class='success'>✅ $success_message</div>";
}

// İstatistikler
$total_size = $imageManager->getTotalSize($image_files);
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
echo "<div class='asset-grid'>";

foreach ($image_files as $image_file) {
    echo $imageManager->renderImageCard($image_file);
}

echo "</div>";
echo "</div>";
echo "</body></html>";
?>