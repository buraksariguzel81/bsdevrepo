<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php');

// Rol kontrolü yapın (örneğin, rol ID'si 3 için)
rol_kontrol(3);

// Sayfa içeriği buradan devam eder...
?>

<!DOCTYPE html>
<html lang="tr">
<head>

    <title>php session kodları</title>

</head>
<body>

    <hr>

    <h5>PHP Session Kodları</h5>

    <br>

    <?php
    // PHP kodlarını bir dizi içinde tutalım
    $kodlar = [
        [
            'baslik' => 'PHP Rol Özelliği',
            'kod' => '<?php
session_start();
include($_SERVER[\'DOCUMENT_ROOT\'] . \'/assets/src/include/rol_kontrol.php\');

// Rol kontrolü yapın (örneğin, rol ID\'si 3 için)
rol_kontrol(3);

// Sayfa içeriği buradan devam eder...
?>',
            'button_text' => 'Kopyala',
            'meta_tag' => '&lt;meta name=&quot;gerekli-rol-id&quot; content=&quot;3&quot;&gt;',
        ],
        [
            'baslik' => 'PHP Menü Sistemi',
            'kod' => '<?php
include($_SERVER[\'DOCUMENT_ROOT\'] . \'/assets/src/include/navigasyon.php\');
?>',
            'button_text' => 'Kopyala',
            'meta_tag' => '',
        ]
    ];

    // Diziyi döngü ile kullanarak her bir başlık ve kodu yazdıralım
    foreach ($kodlar as $kod) {
        echo '<div>';
        echo '<h5>' . $kod['baslik'] . '</h5>';
        echo '<pre><code>' . htmlentities($kod['kod']) . '</code></pre>';
        echo '<button class="copy-btn bsd-btn1" data-code="' . htmlentities($kod['meta_tag']) . '">';
        echo '<i class="fas fa-copy"></i> ' . $kod['button_text'] . '</button>';
        echo '</div><hr>';
    }
    ?>

    <!-- Notification for Copy Success -->
    <div class="notification" id="copyNotification">
        <i class="fas fa-check-circle"></i> Kod panoya kopyalandı!
    </div>

    <script src="../../assets/src/js/copy.js"></script>

    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>