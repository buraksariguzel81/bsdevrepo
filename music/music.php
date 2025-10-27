<?php
// Müzik yönetim sistemi - CDN uyumlu ve boşlukları "_" ile değiştirme
$music_dir = __DIR__;
$css_file = $music_dir . '/music.css';
$cdn_base = 'https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/';

// Desteklenen müzik formatları
$supported_formats = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];

// Müzik dosyalarını tara
$music_files = array_filter(scandir($music_dir), function ($item) use ($music_dir, $supported_formats) {
    return is_file($music_dir . '/' . $item) && in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), $supported_formats);
});

// CSS dosyasını güncelle
if (isset($_POST['update_css'])) {
    $css_content = "/* Otomatik müzik stilleri - " . date('Y-m-d H:i:s') . " */\n\n";

    foreach ($music_files as $file) {
        $name = pathinfo($file, PATHINFO_FILENAME);
        $safe_name = str_replace(' ', '_', $name); // boşlukları "_" ile değiştir

        $css_content .= "/* $safe_name */\n";
        $css_content .= ".music-$safe_name {\n";
        $css_content .= "    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNmZjZiNmIiLz48L3N2Zz4=');\n";
        $css_content .= "    background-size: contain;\n";
        $css_content .= "    background-repeat: no-repeat;\n";
        $css_content .= "    background-position: center;\n";
        $css_content .= "    width: 120px;\n";
        $css_content .= "    height: 120px;\n";
        $css_content .= "    display: inline-block;\n";
        $css_content .= "    border-radius: 8px;\n";
        $css_content .= "    border: 2px solid #eee;\n";
        $css_content .= "    margin: 5px;\n";
        $css_content .= "    cursor: pointer;\n";
        $css_content .= "    position: relative;\n";
        $css_content .= "}\n\n";
    }

    file_put_contents($css_file, $css_content);
    $success_message = "CSS dosyası başarıyla güncellendi!";
}

// CSS sınıflarını al
$css_content = file_get_contents($css_file);
preg_match_all('/\.music-([A-Za-z0-9_çğıöşü\-\.\(\)]+)\s*{/', $css_content, $matches);
$muzikler = $matches[1];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Müzik CSS Sınıfları</title>
<link rel="stylesheet" href="music.css">
<style>
    body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
    .header { background: linear-gradient(45deg, #ff6b6b, #4ecdc4); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }
    .btn { background: #ff6b6b; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
    .btn:hover { background: #ff5252; }
    .music-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin: 20px 0; }
    .music-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9; text-align: center; transition: transform 0.3s ease; }
    .music-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .music-name { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #333; }
    .music-preview { width: 120px; height: 120px; margin: 10px auto; border-radius: 8px; background: white; border: 2px solid #eee; cursor: pointer; }
    .music-code { background: #f0f0f0; padding: 5px; border-radius: 3px; font-family: monospace; font-size: 12px; color: #666; word-break: break-all; }
</style>
</head>
<body>
<div class="header">
    <h1>🎵 Müzik Yönetim Sistemi</h1>
    <p>Müzik dosyalarını otomatik tarar, CSS sınıfları oluşturur ve URL gösterir</p>
</div>

<?php if(isset($success_message)): ?>
<div class="success">✅ <?php echo $success_message; ?></div>
<?php endif; ?>

<form method="post">
    <button type="submit" name="update_css" class="btn">🔄 CSS Dosyasını Güncelle</button>
</form>

<h2>🎵 Müzik Galerisi</h2>
<div class="music-grid">
<?php foreach($muzikler as $muzik): 
    $file_url = $cdn_base . $muzik . '.mp3'; // varsayılan mp3
?>
    <div class="music-card">
        <div class="music-name"><?php echo ucfirst(str_replace('_', ' ', $muzik)); ?></div>
        <div class="music-preview music-<?php echo $muzik; ?>" onclick="playMusic('<?php echo $muzik; ?>')"></div>
        <div class="music-code">CSS: .music-<?php echo $muzik; ?></div>
        <div class="music-code">URL: <?php echo $file_url; ?></div>
    </div>
<?php endforeach; ?>
</div>

<h2>🎧 Direkt AUDIO Kullanımı</h2>
<div style="display:flex;flex-wrap:wrap;gap:15px;padding:20px;background:#f8f9fa;border-radius:8px;">
<?php foreach($music_files as $file): 
    $name = pathinfo($file, PATHINFO_FILENAME);
    $safe_name = str_replace(' ', '_', $name);
    $ext = pathinfo($file, PATHINFO_EXTENSION);
?>
    <div style="text-align:center;">
        <audio controls style="width:200px;margin-bottom:5px;">
            <source src="<?php echo $cdn_base . $file; ?>" type="audio/<?php echo $ext; ?>">
        </audio>
        <div style="font-size:12px;color:#666;"><?php echo $name; ?></div>
        <div style="font-size:10px;color:#999;"><?php echo $cdn_base . $file; ?></div>
    </div>
<?php endforeach; ?>
</div>

<!-- Hidden audio elements for JS playback -->
<div style="display:none;">
<?php foreach($music_files as $file): 
    $name = pathinfo($file, PATHINFO_FILENAME);
    $safe_name = str_replace(' ', '_', $name);
?>
    <audio class="audio-<?php echo $safe_name; ?>" src="<?php echo $cdn_base . $file; ?>" preload="none"></audio>
<?php endforeach; ?>
</div>

<script>
function playMusic(musicName) {
    const audios = document.querySelectorAll('audio');
    audios.forEach(audio => { audio.pause(); audio.currentTime=0; });
    const selectedAudio = document.querySelector('.audio-' + musicName.replace(/[^a-zA-Z0-9_çğıöşü\-\.\(\)]/g,''));
    if(selectedAudio){ selectedAudio.play(); }
}
</script>
</body>
</html>
