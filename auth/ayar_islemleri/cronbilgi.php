<?php

// navisyon
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>
<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Cron İşlemi Hakkında Bilgi</title>

</head>
<body>

  
    <h1><i class="fas fa-info-circle"></i> Cron İşlemi Hakkında Bilgi</h1>

    <h2><i class="fas fa-cog"></i> Sunucu Tarafında Cron Kurulumu</h2>
    <p>
        Cron işlemini sunucu tarafında otomatik olarak çalıştırmak için aşağıdaki adımları izleyin:
    </p>
    <ol>
        <li><i class="fas fa-terminal"></i> SSH ile sunucunuza bağlanın.</li>
        <li>Crontab'ı düzenlemek için şu komutu kullanın: 
            <code>crontab -e</code>
        </li>
        <li>Açılan editöre aşağıdaki satırı ekleyin (her gün saat 00:00'da çalıştırmak için):
            <code>0 0 * * * /usr/bin/php /path/to/your/script.php</code>
        </li>
        <li>Değişiklikleri kaydedin ve editörden çıkın.</li>
    </ol>

    <h3><i class="fas fa-info-circle"></i> Otomatik Çalışma Hakkında</h3>
    <p>
        Yukarıdaki adımları tamamladıktan sonra, cron işlemi otomatik olarak sunucu tarafında çalışacaktır. Kullanıcının siteye girmesine veya Termux'u açık tutmasına gerek yoktur. Cron, belirlenen zamanda (yukarıdaki örnekte her gün gece yarısı) PHP dosyasını çalıştıracak ve gerekli işlemleri gerçekleştirecektir.
    </p>
    <p>
        Bu işlem, 30 günden eski silinmiş hesapları pasif_kullanicilar tablosundan ve bu kullanıcılara ait kayıtları rollerplus tablosundan otomatik olarak temizleyecektir.
    </p>
    <p>
        Cron işleminin doğru çalıştığından emin olmak için, sunucu loglarını kontrol edebilirsiniz.
    </p>

    <h3><i class="fas fa-exclamation-triangle"></i> Dikkat Edilmesi Gerekenler</h3>
    <ul>
        <li>Cron işlemi için belirtilen yolun doğru olduğundan emin olun.</li>
        <li>PHP dosyasının çalıştırılabilir olduğundan emin olun.</li>
        <li>Sunucunuzun zaman diliminin doğru ayarlandığından emin olun.</li>
        <li>Cron işleminin çalışması için sunucunun sürekli açık olması gerektiğini unutmayın.</li>
    </ul>

    <h3><i class="fas fa-code"></i> Cron İşlemi Kodu</h3>
    <p>
        Cron işleminin gerçekleştirdiği temel işlemler şunlardır:
    </p>
    <pre><code>
// 30 günden eski pasif hesapları sil
$otuz_gun_once = date('Y-m-d H:i:s', strtotime('-30 days'));
$stmt = $vt->prepare("DELETE FROM pasif_kullanicilar WHERE hesap_durumu = 'Silinmiş' AND hesap_silinme_tarihi <= :otuz_gun_once");
$stmt->execute(['otuz_gun_once' => $otuz_gun_once]);

// Silinen kullanıcıların rollerplus tablosundaki kayıtlarını sil
$stmt = $vt->prepare("DELETE FROM rollerplus WHERE kullanici_id IN (SELECT id FROM pasif_kullanicilar WHERE hesap_durumu = 'Silinmiş' AND hesap_silinme_tarihi <= :otuz_gun_once)");
$stmt->execute(['otuz_gun_once' => $otuz_gun_once]);
    </code></pre>

    <h3><i class="fas fa-question-circle"></i> Sık Sorulan Sorular</h3>
    <dl>
        <dt>S: Cron işlemi neden önemlidir?</dt>
        <dd>C: Cron işlemi, veritabanınızı otomatik olarak temiz tutmanıza ve gereksiz verileri düzenli aralıklarla silmenize olanak tanır. Bu, sistem performansını artırır ve veri yönetimini kolaylaştırır.</dd>

        <dt>S: Cron işlemi ne sıklıkla çalışmalıdır?</dt>
        <dd>C: Bu, sisteminizin ihtiyaçlarına bağlıdır. Genellikle günlük çalıştırmak yeterlidir, ancak daha yoğun kullanılan sistemlerde daha sık çalıştırılabilir.</dd>

        <dt>S: Cron işlemi çalışmazsa ne yapmalıyım?</dt>
        <dd>C: İlk olarak sunucu loglarını kontrol edin. Hata mesajları varsa bunları inceleyip gerekli düzeltmeleri yapın. Ayrıca, cron ayarlarınızı ve PHP dosyanızın yolunu tekrar kontrol edin.</dd>
    </dl>


<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>
</html>
