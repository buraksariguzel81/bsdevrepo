<?php
session_start(); 

// Kullanıcı giriş yapmış mı kontrol et
if (isset($_SESSION['kullanici_adi'])) {
    // Kullanıcı giriş yapmışsa hoş geldiniz mesajı göster
    $kullanici_adi = htmlspecialchars($_SESSION['kullanici_adi'], ENT_QUOTES, 'UTF-8');
    echo "<p>Merhaba, $kullanici_adi! Hoş geldiniz.</p>";
} else {
    // Kullanıcı giriş yapmamışsa giriş yapma bağlantıları göster
    echo '<p>Giriş yapmadınız. <a href="auth/giris/giris.php">Giriş yap</a> veya <a href="auth/kayit/kayit.php">kayıt ol</a>.</p>';
}

?>