<?php
// Font CSS otomatik oluşturma scripti
$font_dir = '.';
$css_file = 'font.css';

// Font klasörlerini tara
$font_folders = array_filter(scandir($font_dir), function($item) use ($font_dir) {
    return is_dir($font_dir . '/' . $item) && !in_array($item, ['.', '..']);
});

$css_content = "/* Otomatik @font-face ve class tanımları - " . date('Y-m-d H:i:s') . " */\n\n";

foreach ($font_folders as $font_name) {
    $font_path = $font_dir . '/' . $font_name;
    $files = array_filter(scandir($font_path), function($file) {
        return pathinfo($file, PATHINFO_EXTENSION) === 'woff2' || pathinfo($file, PATHINFO_EXTENSION) === 'woff';
    });

    if (!empty($files)) {
        $woff2_files = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'woff2';
        });
        $woff_files = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'woff';
        });

        $css_content .= "@font-face {\n";
        $css_content .= "    font-family: '$font_name';\n";
        $css_content .= "    src: ";

        $src_parts = [];
        foreach ($woff2_files as $woff2_file) {
            $src_parts[] = "url('https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/font/$font_name/$woff2_file') format('woff2')";
        }
        foreach ($woff_files as $woff_file) {
            $src_parts[] = "url('https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/font/$font_name/$woff_file') format('woff')";
        }

        $css_content .= implode(",\n         ", $src_parts) . ";\n";
        $css_content .= "    font-weight: normal;\n";
        $css_content .= "    font-style: normal;\n";
        $css_content .= "}\n\n";

        $css_content .= ".$font_name {\n";
        $css_content .= "    font-family: '$font_name', sans-serif;\n";
        $css_content .= "    font-weight: normal;\n";
        $css_content .= "    font-style: normal;\n";
        $css_content .= "}\n\n";
    }
}

file_put_contents($css_file, $css_content);
echo "✅ CSS dosyası başarıyla güncellendi!\n";
echo "📊 Toplam " . count($font_folders) . " font klasörü işlendi.\n";
echo "📝 CSS dosyası: $css_file\n";
?>
