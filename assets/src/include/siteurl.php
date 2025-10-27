<?php
if (!function_exists('site_url')) {
    function site_url($path = '') {
        $server_name = $_SERVER['SERVER_NAME'];
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

        // Local veya geliştirme ortamı hostları
        $local_hosts = ['localhost', '127.0.0.1', 'buraksariguzeldev.local'];

        if (in_array($server_name, $local_hosts)) {
            // Tam URL döndür, ör: http://localhost/musteriler/satislar/urun_raporlari.php
            $base_url = $protocol . $server_name . '/';
        } else {
            // Canlı ortamda sadece site kökü (veya dilersen domain)
            // Buraya istersen tam domaini de yazabilirsin:
            $base_url = '/';
        }

        // Path başındaki / kaldırılır, sonra eklenir
        $clean_path = ltrim($path, '/');

        return $base_url . $clean_path;
    }
}
?>
