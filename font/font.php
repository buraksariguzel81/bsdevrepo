<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
// Font yönetim sistemi

$font_dir = '.';
$css_file = 'font.css';

// Font klasörlerini tara
$font_folders = array_filter(scandir($font_dir), function($item) use ($font_dir) {
    return is_dir($font_dir . '/' . $item) && !in_array($item, ['.', '..']);
});

// CSS dosyasını güncelle
if (isset($_POST['update_css'])) {
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
                $src_parts[] = "url('http://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/font/$font_name/$woff2_file') format('woff2')";
            }
            foreach ($woff_files as $woff_file) {
                $src_parts[] = "url('http://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/font/$font_name/$woff_file') format('woff')";
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

    // JSON dosyasını güncelle - detaylı bilgi
    $json_fonts = [];
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

            $url_parts = [];
            foreach ($woff2_files as $woff2_file) {
                $url_parts[] = "http://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/font/$font_name/$woff2_file";
            }
            foreach ($woff_files as $woff_file) {
                $url_parts[] = "http://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/font/$font_name/$woff_file";
            }

            $json_fonts[] = [
                'name' => $font_name,
                'class' => ".$font_name",
                'urls' => $url_parts,
                'primary_url' => $url_parts[0] ?? '',
                'updated' => date('Y-m-d H:i:s')
            ];
        }
    }
    $json_data = json_encode(['fonts' => $json_fonts], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents('font_list.json', $json_data);

    $success_message = "CSS dosyası başarıyla güncellendi!";
}

// CSS dosyasını oku ve font isimlerini çıkar
$css_content = file_get_contents($css_file);
preg_match_all('/\.([A-Za-z_]+)\s*{/', $css_content, $matches);
$fonts = $matches[1];

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Font Management System</title>";
echo '<link rel="stylesheet" href="font.css">';
echo '<style>';
echo '.container { max-width: 1200px; margin: 0 auto; }';
echo '.header { background: #333; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }';
echo '.font-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }';
echo '.font-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9; }';
echo '.font-name { font-size: 18px; font-weight: bold; margin-bottom: 10px; }';
echo '.font-preview { font-size: 24px; margin: 10px 0; padding: 10px; background: white; border-radius: 4px; }';
echo '.font-code { background: #f0f0f0; padding: 5px; border-radius: 3px; font-family: monospace; font-size: 12px; }';
echo '.font-url { font-size: 12px; color: #555; background: #e9ecef; padding: 5px; border-radius: 3px; word-break: break-all; }';
echo '.btn { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }';
echo '.btn:hover { background: #005a87; }';
echo '.btn-small { background: #28a745; padding: 5px 10px; font-size: 12px; border: none; border-radius: 4px; cursor: pointer; }';
echo '.btn-small:hover { background: #218838; }';
echo '.success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }';
echo '.stats { background: #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 20px; }';
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

echo "<div class='container  mt-5'>";
echo "<div class='header'>";
echo "<h1>🖋️ Font Management System</h1>";
echo "<p>Font klasörünü otomatik tarar ve CSS dosyasını günceller</p>";
echo "</div>";

if (isset($success_message)) {
    echo "<div class='success'>✅ $success_message</div>";
}

echo "<div class='stats'>";
echo "<h3>📊 İstatistikler</h3>";
echo "<p><strong>Toplam Font Klasörü:</strong> " . count($font_folders) . "</p>";
echo "<p><strong>Aktif Font:</strong> " . count($fonts) . "</p>";
echo "<p><strong>Son Güncelleme:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "</div>";

echo "<form method='post'>";
echo "<button type='submit' name='update_css' class='btn'>🔄 CSS Dosyasını Güncelle</button>";
echo "</form>";

echo "<h2>🎨 Font Preview</h2>";
echo "<div class='font-grid'>";

foreach ($fonts as $font) {
    $url = "http://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/font/$font/$font.woff2";
    echo "<div class='font-card'>";
    echo "<div class='font-name'>$font</div>";
    echo "<div class='font-preview' style='font-family: \"$font\", sans-serif;'>bu yazi fontu boyle</div>";
    echo "<div style='display: flex; gap: 10px; align-items: center;'>";
    echo "<input type='text' value='.$font' id='class-$font' readonly class='font-url' style='flex: 1;'>";
    echo "<button onclick='copyToClipboard(\"class-$font\")' class='btn-small'>📋 Class</button>";
    echo "</div>";
    echo "<div style='margin-top: 10px;'>";
    echo "<input type='text' value='$url' id='url-$font' readonly class='font-url'>";
    echo "<button onclick='copyToClipboard(\"url-$font\")' class='btn-small'>🗒️ URL</button>";
    echo "</div>";
    echo "</div>";
}

echo "</div>";
echo "</div>";
echo "</body></html>";
?>
