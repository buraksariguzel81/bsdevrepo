<?php

/**
 * Asset Management Base Class
 * Tüm asset türleri için ortak işlevsellik sağlar
 */
class AssetManager
{
    protected $asset_dir;
    protected $json_file;
    protected $asset_type;
    protected $cdn_base_url = "https://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main";

    public function __construct($asset_dir = '.', $asset_type = 'assets')
    {
        $this->asset_dir = $asset_dir;
        $this->asset_type = $asset_type;
        $this->json_file = $asset_type . '_list.json';
    }

    /**
     * Dosyaları tara ve filtrele
     */
    protected function scanFiles($extensions)
    {
        return array_filter(scandir($this->asset_dir), function ($item) use ($extensions) {
            $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
            return is_file($this->asset_dir . '/' . $item) && in_array($extension, $extensions);
        });
    }

    /**
     * Dosya bilgilerini al
     */
    protected function getFileInfo($file)
    {
        $file_path = $this->asset_dir . '/' . $file;
        $file_info = pathinfo($file);
        $file_size = filesize($file_path);

        return [
            'name' => $file_info['filename'],
            'filename' => $file,
            'extension' => $file_info['extension'],
            'size' => $file_size,
            'path' => $file_path,
            'info' => $file_info
        ];
    }

    /**
     * CDN URL oluştur
     */
    protected function getCdnUrl($file)
    {
        return $this->cdn_base_url . '/' . $this->asset_type . '/' . $file;
    }

    /**
     * Local path oluştur
     */
    protected function getLocalPath($file)
    {
        return './' . $this->asset_type . '/' . $file;
    }

    /**
     * JSON dosyasını güncelle
     */
    public function updateJson($data)
    {
        $json_data = json_encode([$this->asset_type => $data], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents($this->json_file, $json_data);
    }

    /**
     * Toplam dosya boyutunu hesapla
     */
    public function getTotalSize($files)
    {
        $total_size = 0;
        foreach ($files as $file) {
            $total_size += filesize($this->asset_dir . '/' . $file);
        }
        return $total_size;
    }

    /**
     * Ortak HTML head
     */
    public function getHtmlHead($title)
    {
        return "<!DOCTYPE html><html><head><meta charset='utf-8'><title>$title</title>" . $this->getCommonStyles() . "</head><body>";
    }

    /**
     * Ortak CSS stilleri
     */
    public function getCommonStyles()
    {
        return '<style>
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #333; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .asset-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; }
        .asset-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; background: #f9f9f9; }
        .asset-name { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #333; }
        .asset-info { font-size: 14px; color: #666; margin: 5px 0; }
        .asset-url { font-size: 12px; color: #555; background: #e9ecef; padding: 5px; border-radius: 3px; word-break: break-all; margin: 5px 0; }
        .btn { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #005a87; }
        .btn-small { background: #28a745; color: white; padding: 5px 10px; font-size: 12px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-small:hover { background: #218838; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .stats { background: #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        </style>
        <script>
        function copyToClipboard(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(copyText.value).then(function() {
                    alert("Kopyalandı!");
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
        </script>';
    }
}
