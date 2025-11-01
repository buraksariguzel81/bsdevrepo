<?php
require_once 'AssetManager.php';

/**
 * Image Asset Manager
 * Resim dosyalarını yönetir
 */
class ImageManager extends AssetManager
{
    private $supported_formats = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    public function __construct($asset_dir = '.', $asset_type = 'images')
    {
        parent::__construct($asset_dir, $asset_type);
    }

    /**
     * Resim dosyalarını tara
     */
    public function scanImageFiles()
    {
        return $this->scanFiles($this->supported_formats);
    }

    /**
     * Resim dosyası bilgilerini al
     */
    public function getImageInfo($file)
    {
        $base_info = $this->getFileInfo($file);
        $file_size_kb = round($base_info['size'] / 1024, 2);

        // Resim boyutlarını al
        $image_info = getimagesize($base_info['path']);
        $width = $image_info[0] ?? 0;
        $height = $image_info[1] ?? 0;

        return [
            'name' => $base_info['name'],
            'filename' => $base_info['filename'],
            'extension' => $base_info['extension'],
            'size' => $base_info['size'],
            'size_kb' => $file_size_kb,
            'width' => $width,
            'height' => $height,
            'dimensions' => $width . 'x' . $height,
            'url' => $this->getCdnUrl($file),
            'local_path' => $this->getLocalPath($file),
            'updated' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Tüm resim verilerini JSON formatında hazırla
     */
    public function generateImageData()
    {
        $image_files = $this->scanImageFiles();
        $image_data = [];

        foreach ($image_files as $file) {
            $image_data[] = $this->getImageInfo($file);
        }

        return $image_data;
    }

    /**
     * Resim kartı HTML'i oluştur
     */
    public function renderImageCard($file)
    {
        $info = $this->getImageInfo($file);
        $cdn_url = $info['url'];
        $local_url = $info['local_path'];

        $html = "<div class='asset-card'>";
        $html .= "<div class='asset-name'>🖼️ " . $info['name'] . "</div>";
        $html .= "<div class='asset-info'><strong>Dosya:</strong> " . $info['filename'] . "</div>";
        $html .= "<div class='asset-info'><strong>Format:</strong> " . strtoupper($info['extension']) . "</div>";
        $html .= "<div class='asset-info'><strong>Boyut:</strong> " . $info['size_kb'] . " KB</div>";
        $html .= "<div class='asset-info'><strong>Çözünürlük:</strong> " . $info['dimensions'] . "px</div>";

        $html .= "<div style='text-align: center; margin: 10px 0;'>";
        $html .= "<img src='$local_url' alt='" . $info['name'] . "' loading='lazy' style='max-width: 150px; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);'>";
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
