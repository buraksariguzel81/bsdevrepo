<?php
// Meyve CSS otomatik oluşturma scripti
$meyve_dir = dirname(__FILE__); // Şu anki dizin (meyveler klasörü)
$css_file = $meyve_dir . '/meyveler.css';

// Desteklenen resim formatları
$supported_formats = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

// Meyve resimlerini tara
$meyve_files = array_filter(scandir($meyve_dir), function($item) use ($meyve_dir, $supported_formats) {
    return is_file($meyve_dir . '/' . $item) && in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), $supported_formats);
});

$css_content = "/* Otomatik meyve stilleri - " . date('Y-m-d H:i:s') . " */\n\n";

foreach ($meyve_files as $meyve_file) {
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
    $css_content .= "    border-radius: 8px;\n";
    $css_content .= "    border: 2px solid #eee;\n";
    $css_content .= "    margin: 5px;\n";
    $css_content .= "}\n\n";

    $css_content .= ".meyve-$meyve_name:hover {\n";
    $css_content .= "    transform: scale(1.1);\n";
    $css_content .= "    transition: transform 0.3s ease;\n";
    $css_content .= "    border-color: #ff6b6b;\n";
    $css_content .= "}\n\n";

    // Büyük versiyon için de CSS sınıfı ekle
    $css_content .= ".meyve-$meyve_name-large {\n";
    $css_content .= "    background-image: url('https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/meyveler/$meyve_file');\n";
    $css_content .= "    background-size: contain;\n";
    $css_content .= "    background-repeat: no-repeat;\n";
    $css_content .= "    background-position: center;\n";
    $css_content .= "    width: 200px;\n";
    $css_content .= "    height: 200px;\n";
    $css_content .= "    display: block;\n";
    $css_content .= "    margin: 10px auto;\n";
    $css_content .= "    border-radius: 12px;\n";
    $css_content .= "    border: 3px solid #ddd;\n";
    $css_content .= "}\n\n";
}

file_put_contents($css_file, $css_content);

echo "✅ Meyve CSS dosyası başarıyla güncellendi!\n";
echo "📊 Toplam " . count($meyve_files) . " meyve resmi işlendi.\n";
echo "📁 Bulunan meyveler:\n";
foreach ($meyve_files as $file) {
    echo "  - " . pathinfo($file, PATHINFO_FILENAME) . "\n";
}
echo "📝 CSS dosyası: $css_file\n";
?>
