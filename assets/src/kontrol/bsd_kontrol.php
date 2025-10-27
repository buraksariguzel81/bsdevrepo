<?php
header('Content-Type: application/json');

// Veritabanı bağlantı ayarları
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');

// Kontrol işlemleri
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'checkUsername') {
        $username = $_GET['username'];
        
        // Kullanıcılar tablosunda kontrol
        $stmt = $vt->prepare("SELECT COUNT(*) FROM kullanicilar WHERE kullanici_adi = ?");
        $stmt->execute([$username]);
        $existsActive = $stmt->fetchColumn() > 0;

        if ($existsActive) {
            // Hesap durumu kontrolü
            $stmt = $vt->prepare("SELECT hesap_durumu FROM kullanicilar WHERE kullanici_adi = ?");
            $stmt->execute([$username]);
            $status = $stmt->fetchColumn();
            echo json_encode(['exists' => true, 'status' => $status]);
        } else {
            echo json_encode(['exists' => false]);
        }
    }

    

    if ($action === 'checkEmail') {
        $email = $_GET['email'];

        // Kullanıcılar tablosunda kontrol
        $stmt = $vt->prepare("SELECT COUNT(*) FROM kullanicilar WHERE eposta = ?");
        $stmt->execute([$email]);
        $existsActive = $stmt->fetchColumn() > 0;

        if ($existsActive) {
            // Hesap durumu kontrolü
            $stmt = $vt->prepare("SELECT hesap_durumu FROM kullanicilar WHERE eposta = ?");
            $stmt->execute([$email]);
            $status = $stmt->fetchColumn();
            echo json_encode(['exists' => true, 'status' => $status]);
        } else {
            echo json_encode(['exists' => false]);
        }
    }
}
?>