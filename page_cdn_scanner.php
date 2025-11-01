<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>CDN Link Tarayıcısı</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #333 0%, #555 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn {
            background: #007cba;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background: #005a87;
        }

        .btn-small {
            background: #28a745;
            padding: 5px 10px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-small:hover {
            background: #218838;
        }

        .cdn-section {
            margin: 20px 0;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cdn-header {
            cursor: pointer;
            background: #007cba;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .cdn-content {
            display: none;
            padding: 10px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, padding 0.5s ease;
        }

        .cdncontent.show {
            max-height: 5000px;
            padding: 10px;
        }

        .cdn-list {
            display: grid;
            gap: 10px;
        }

        .cdn-item {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            background: #fafafa;
        }

        .cdn-url {
            font-size: 12px;
            color: #555;
            word-break: break-all;
        }

        .url-display {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .stats {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
    <script>
        var scanComplete = false;

        function toggleSection(id) {
            var content = document.getElementById(id);
            if (content.style.display === "block") {
                content.style.display = "none";
                content.style.maxHeight = "0";
            } else {
                content.style.display = "block";
                content.style.maxHeight = "5000px";
            }
        }

        function copyToClipboard(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(copyText.value).then(function() {
                    alert("URL kopyalandı!");
                }, function(err) {
                    alert("Kopyalama başarısız: " + err);
                });
            } else {
                var textArea = document.createElement("textarea");
                textArea.value = copyText.value;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand("copy");
                    alert("Kopyalandı!");
                } catch (err) {
                    alert("Kopyalama başarısız!");
                }
                document.body.removeChild(textArea);
            }
        }

        function searchFiles() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toLowerCase();
            var sections = document.querySelectorAll(".cdn-section");
            for (var i = 0; i < sections.length; i++) {
                var items = sections[i].querySelectorAll(".cdn-item");
                var sectionVisible = false;
                for (var j = 0; j < items.length; j++) {
                    var fileName = items[j].querySelector("strong").textContent.toLowerCase();
                    if (fileName.indexOf(filter) > -1) {
                        items[j].style.display = "";
                        sectionVisible = true;
                    } else {
                        items[j].style.display = "none";
                    }
                }
                sections[i].style.display = sectionVisible ? "" : "none";
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("searchInput").addEventListener("keyup", searchFiles);
        });
    </script>
</head>

<body>

    <?php


    // Kendi proje dosyalarını tara ve CDN linkleri oluştur
    function scanProjectFiles($base_path, $relative_path = '')
    {
        $files = [];
        $full_path = $base_path . ($relative_path ? '/' . $relative_path : '');

        if (!is_dir($full_path)) {
            return [];
        }

        $items = scandir($full_path);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $item_path = $relative_path ? $relative_path . '/' . $item : $item;
            $full_item_path = $full_path . '/' . $item;

            if (is_dir($full_item_path)) {
                // Alt klasörleri tarama
                $sub_files = scanProjectFiles($base_path, $item_path);
                $files = array_merge($files, $sub_files);
            } else {
                // Dosya için CDN URL oluştur
                $cdn_url = 'http://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/' . $item_path;
                $files[] = [
                    'name' => $item,
                    'path' => $item_path,
                    'url' => $cdn_url,
                    'type' => pathinfo($item, PATHINFO_EXTENSION)
                ];
            }
        }

        return $files;
    }

    // Kök dizinden tarama başla
    $base_path = $_SERVER['DOCUMENT_ROOT'];
    $project_files = scanProjectFiles($base_path);

    // Dosyaları filtrele - php hariç belirli uzantılar
    $allowed_extensions = ['html', 'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'pdf', 'json', 'xml'];
    $filtered_files = array_filter($project_files, function ($file) use ($allowed_extensions) {
        return in_array(strtolower($file['type']), $allowed_extensions);
    });

    // ğ İstatistikler
    $files_scanned = count($project_files);

    // Dosyaları klasörlere göre grupla (basitleştirilmiş)
    function groupFilesByFolder($files)
    {
        $groups = [];
        foreach ($files as $file) {
            $parts = explode('/', $file['path']);
            $first_folder = $parts[0] ?? 'root';
            if (!isset($groups[$first_folder])) {
                $groups[$first_folder] = [];
            }
            $groups[$first_folder][] = $file;
        }
        return $groups;
    }

    $grouped_files = groupFilesByFolder($filtered_files);

    // Html render fonksiyonu
    function renderFolderTree($folder_data, $parent_id = '', $level = 0)
    {
        $margin = $level * 20;
        $html = "";

        foreach ($folder_data as $folder_name => $files) {
            $folder_id = $parent_id ? $parent_id . '-' . $folder_name : $folder_name;
            $files_count = count($files);

            $html .= "<div class='cdn-section' style='margin-left: {$margin}px;'>";
            $html .= "<div class='cdn-header' onclick=\"toggleSection('" . htmlspecialchars($folder_id, ENT_QUOTES) . "')\">📁 " . htmlspecialchars($folder_name, ENT_QUOTES) . " ({$files_count} dosya)</div>";
            $html .= "<div id='" . htmlspecialchars($folder_id, ENT_QUOTES) . "' class='cdn-content'>";
            $html .= "<div class='cdn-list'>";

            foreach ($files as $file) {
                $id_suffix = htmlspecialchars($folder_id . '-' . $file['name'], ENT_QUOTES);
                $html .= "<div class='cdn-item'>";
                $html .= "<strong>" . htmlspecialchars($file['name'], ENT_QUOTES) . "</strong>";
                $html .= "<div class='url-display'>";
                $html .= "<input type='text' value='" . htmlspecialchars($file['url'], ENT_QUOTES) . "' id='cdn-" . $id_suffix . "' readonly class='cdn-url' style='flex: 1;'>";
                $html .= "<button onclick='copyToClipboard(\"cdn-" . $id_suffix . "\")' class='btn-small'>📋 Kopyala</button>";
                $html .= "<button onclick=\"window.open('" . htmlspecialchars($file['url'], ENT_QUOTES) . "', '_blank')\" class='btn-small' style='background: #24292e; color: white;'>🔗 CDN Aç</button>";
                $html .= "</div>";
                $html .= "</div>";
            }

            $html .= "</div></div></div>";
        }

        return $html;
    }
    ?>

    <div class="container">
        <div class="header">
            <h1>🔍 CDN Link Tarayıcısı</h1>
            <p>Projedeki tüm dosyaları tarar ve harici CDN bağlantılarını gösterir</p>
            <input type="text" id="searchInput" placeholder="Dosya ara..." style="padding: 10px; width: 300px; margin: 10px 0; border: 2px solid #007cba; border-radius: 4px; font-size: 16px;">
        </div>

        <?php
        $filtered_count = count($filtered_files);
        ?>
        <div class="stats">
            <h3>📊 Tarama İstatistikleri</h3>
            <p><strong>Taranan Dosya:</strong> <?php echo $files_scanned; ?></p>
            <p><strong>Filtreden Geçen Dosya:</strong> <?php echo $filtered_count; ?></p>
        </div>

        <?php
        if (empty($grouped_files)) {
            echo "<p>CDN bulunamadı. Dosyalar tarandı ama harici link tespit edilmedi.</p>";
        } else {
            echo renderFolderTree($grouped_files);
        }
        ?>

    </div>

    </div> <!-- bsd-content kapatma -->

</body>

</html>