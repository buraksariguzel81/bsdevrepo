<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>
<?php

// Meyve yönetim sistemi - CDN uyumlu
$meyve_dir = __DIR__;
$css_file = $meyve_dir . '/meyveler.css';
$cdn_base = 'https://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main/meyveler/';

$supported_formats = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

$meyve_files = array_filter(scandir($meyve_dir), function ($item) use ($meyve_dir, $supported_formats) {
    return is_file($meyve_dir . '/' . $item) && in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), $supported_formats);
});

if (isset($_POST['update_css'])) {
    $css_content = "/* Otomatik meyve stilleri - " . date('Y-m-d H:i:s') . " */\n\n";
    foreach ($meyve_files as $file) {
        $name = pathinfo($file, PATHINFO_FILENAME);

        $css_content .= "/* $name */\n";
        $css_content .= ".meyve-$name {\n";
        $css_content .= "    background-image: url('" . $cdn_base . $file . "');\n";
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

        $css_content .= ".meyve-$name:hover {\n";
        $css_content .= "    transform: scale(1.1);\n";
        $css_content .= "    transition: transform 0.3s ease;\n";
        $css_content .= "    border-color: #ff6b6b;\n";
        $css_content .= "}\n\n";
    }
    file_put_contents($css_file, $css_content);
    $success_message = "CSS dosyası başarıyla güncellendi!";
}

$meyveler = array_map(function($file) {
    return pathinfo($file, PATHINFO_FILENAME);
}, $meyve_files);

?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Meyve Önizleme</title>
    <link rel="stylesheet" href="meyveler.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f8f9fa;
        }

        h1 {
            color: #ff6b6b;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .btn {
            background: #ff6b6b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background: #ff5252;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .card img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border: 2px solid #eee;
            border-radius: 8px;
            margin: 5px 0;
        }

        .code {
            background: #f0f0f0;
            padding: 5px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 12px;
            display: block;
            margin: 5px 0;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>🍎 Meyve Önizleme</h1>

        <?php if (isset($success_message)) echo "<div class='success'>✅ $success_message</div>"; ?>

        <form method="post">
            <button type="submit" name="update_css" class="btn">🔄 CSS Güncelle</button>
        </form>

        <h2>CSS Arka Planlı Önizleme</h2>
        <div class="grid">
            <?php foreach ($meyveler as $meyve): ?>
                <div class="card">
                    <h3><?= ucfirst($meyve) ?></h3>
                    <div class="meyve-<?= $meyve ?>"></div>
                    <span class="code">.meyve-<?= $meyve ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Doğrudan IMG Önizleme</h2>
        <div class="grid">
            <?php foreach ($meyve_files as $file):
                $name = pathinfo($file, PATHINFO_FILENAME);
                $url = $cdn_base . $file;
            ?>
                <div class="card">
                    <h3><?= ucfirst($name) ?></h3>
                    <img src="<?= $url ?>" alt="<?= $name ?>">
                    <span class="code"><?= $url ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
