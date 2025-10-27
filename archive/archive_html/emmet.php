<?php
// Navigasyonu dahil et
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Kod Kopyalama Örneği</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-code me-2"></i> Kod Kopyalama Örneği</h5>
        </div>
        <div class="card-body">
            <?php
            // Kodları ve başlıkları içeren dizi
            $kodlar = [
                [
                    'baslik' => 'Emmet Kodları',
                    'kod' => 'hr>a.bsd-navlink1*2>hr*1',
                    'button_text' => 'Kopyala'
                ],
                [
                    'baslik' => 'Copy Div 1',
                    'kod' => 'div>h5{}+pre>code{}^button.copy-btn.bsd-btn1>i.fas.fa-copy+{Kopyala}',
                    'button_text' => 'Kopyala'
                ],
                [
                    'baslik' => 'PHP Kodları için Div',
                    'kod' => 'div>h5{php_rol_özelliği}+pre>code{&lt;___&gt;}^button.copy-btn.bsd-btn1>i.fas.fa-copy+{_Kopyala}',
                    'button_text' => 'Kopyala'
                ],
                [
                    'baslik' => 'HTML Kodları için Div',
                    'kod' => 'div>h5.code-title{link}+pre>code{&lt;_&lt;_&gt;}^button.copy-btn.bsd-btn1>i.fas.fa-copy+{_Kopyala}',
                    'button_text' => 'Kopyala'
                ]
            ];

            // Diziyi döngü ile kullanarak her bir başlık ve kodu yazdıralım
            foreach ($kodlar as $kod) {
                echo '<div class="card mb-3 shadow-sm">';
                echo '<div class="card-header bg-light"><h5 class="mb-0"><i class="fas fa-terminal me-2"></i>' . $kod['baslik'] . '</h5></div>';
                echo '<div class="card-body">';
                echo '<pre class="mb-2"><code>' . htmlentities($kod['kod']) . '</code></pre>';
                echo '<button class="copy-btn bsd-btn1 btn btn-sm btn-outline-primary"><i class="fas fa-copy"></i> ' . $kod['button_text'] . '</button>';
                echo '</div></div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Notification for Copy Success -->
<div class="notification" id="copyNotification">
    <i class="fas fa-check-circle"></i> Kod panoya kopyalandı!
</div>

<script src="../../assets/src/js/copy.js"></script>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
