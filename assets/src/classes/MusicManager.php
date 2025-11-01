<?php
require_once 'AssetManager.php';

/**
 * Music Asset Manager
 * Müzik dosyalarını yönetir
 */
class MusicManager extends AssetManager
{
    private $supported_formats = ['mp3', 'wav', 'ogg', 'm4a', 'flac'];

    public function __construct($asset_dir = '.')
    {
        parent::__construct($asset_dir, 'music');
    }

    /**
     * Müzik dosyalarını tara
     */
    public function scanMusicFiles()
    {
        return $this->scanFiles($this->supported_formats);
    }

    /**
     * Müzik dosyası bilgilerini al
     */
    public function getMusicInfo($file)
    {
        $base_info = $this->getFileInfo($file);
        $file_size_mb = round($base_info['size'] / 1024 / 1024, 2);

        return [
            'name' => $base_info['name'],
            'filename' => $base_info['filename'],
            'extension' => $base_info['extension'],
            'size' => $base_info['size'],
            'size_mb' => $file_size_mb,
            'url' => $this->getCdnUrl($file),
            'local_path' => $this->getLocalPath($file),
            'updated' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Tüm müzik verilerini JSON formatında hazırla
     */
    public function generateMusicData()
    {
        $music_files = $this->scanMusicFiles();
        $music_data = [];

        foreach ($music_files as $file) {
            $music_data[] = $this->getMusicInfo($file);
        }

        return $music_data;
    }

    /**
     * Müzik kartı HTML'i oluştur
     */
    public function renderMusicCard($file)
    {
        $info = $this->getMusicInfo($file);
        $cdn_url = $info['url'];
        $local_url = $info['local_path'];

        $html = "<div class='asset-card'>";
        $html .= "<div class='asset-name'>🎵 " . $info['name'] . "</div>";
        $html .= "<div class='asset-info'><strong>Dosya:</strong> " . $info['filename'] . "</div>";
        $html .= "<div class='asset-info'><strong>Format:</strong> " . strtoupper($info['extension']) . "</div>";
        $html .= "<div class='asset-info'><strong>Boyut:</strong> " . $info['size_mb'] . " MB</div>";

        $html .= "<div style='margin: 10px 0;'>";
        $html .= "<audio controls style='width: 100%;'>";
        $html .= "<source src='$local_url' type='audio/" . $info['extension'] . "'>";
        $html .= "Tarayıcınız audio elementini desteklemiyor.";
        $html .= "</audio>";
        $html .= "</div>";

        $html .= "<div style='display: flex; gap: 10px; align-items: center; margin-top: 10px;'>";
        $html .= "<input type='text' value='$cdn_url' id='cdn-" . $info['filename'] . "' readonly class='asset-url' style='flex: 1;'>";
        $html .= "<button onclick='copyToClipboard(\"cdn-" . $info['filename'] . "\")' class='btn-small'>📋 CDN URL</button>";
        $html .= "</div>";

        $html .= "<div style='display: flex; gap: 10px; align-items: center; margin-top: 5px;'>";
        $html .= "<input type='text' value='$local_url' id='local-" . $info['filename'] . "' readonly class='asset-url' style='flex: 1;'>";
        $html .= "<button onclick='copyToClipboard(\"local-" . $info['filename'] . "\")' class='btn-small'>🗂️ Local Path</button>";
        $html .= "</div>";

        $html .= "</div>";

        return $html;
    }
}
