<?php
// Navigasyonu dahil et
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>HTML Özel Karakterler</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-font me-2"></i> HTML Özel Karakterler Tablosu</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Entity Kodu</th>
                        <th>Gösterim</th>
                        <th>Açıklama</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Özel karakterler dizisi
                    $karakterler = [
                        ['kod' => '&amp;amp;', 'gosterim' => '&amp;', 'aciklama' => 'Ve işareti (&amp;)'],
                        ['kod' => '&amp;lt;', 'gosterim' => '&lt;', 'aciklama' => 'Küçüktür işareti (&lt;)'],
                        ['kod' => '&amp;gt;', 'gosterim' => '&gt;', 'aciklama' => 'Büyüktür işareti (&gt;)'],
                        ['kod' => '&amp;quot;', 'gosterim' => '&quot;', 'aciklama' => 'Çift tırnak (&quot;)'],
                        ['kod' => '&amp;apos;', 'gosterim' => '&apos;', 'aciklama' => 'Tek tırnak (&apos;)'],
                        ['kod' => '&amp;nbsp;', 'gosterim' => '&nbsp;', 'aciklama' => 'Boşluk (Non-breaking space)'],
                        ['kod' => '&amp;cent;', 'gosterim' => '&cent;', 'aciklama' => 'Cent işareti (&cent;)'],
                        ['kod' => '&amp;pound;', 'gosterim' => '&pound;', 'aciklama' => 'Pound işareti (&pound;)'],
                        ['kod' => '&amp;yen;', 'gosterim' => '&yen;', 'aciklama' => 'Yen işareti (&yen;)'],
                        ['kod' => '&amp;euro;', 'gosterim' => '&euro;', 'aciklama' => 'Euro işareti (&euro;)'],
                        ['kod' => '&amp;copy;', 'gosterim' => '&copy;', 'aciklama' => 'Telif hakkı işareti (&copy;)'],
                        ['kod' => '&amp;reg;', 'gosterim' => '&reg;', 'aciklama' => 'Kayıtlı ticari marka işareti (&reg;)'],
                        ['kod' => '&amp;sect;', 'gosterim' => '&sect;', 'aciklama' => 'Bölüm işareti (&sect;)'],
                        ['kod' => '&amp;deg;', 'gosterim' => '&deg;', 'aciklama' => 'Derece işareti (&deg;)'],
                        ['kod' => '&amp;plusmn;', 'gosterim' => '&plusmn;', 'aciklama' => 'Artı-Eksi işareti (&plusmn;)'],
                        ['kod' => '&amp;sup2;', 'gosterim' => '&sup2;', 'aciklama' => 'Karesi (superscript two) (&sup2;)'],
                        ['kod' => '&amp;sup3;', 'gosterim' => '&sup3;', 'aciklama' => 'Küpü (superscript three) (&sup3;)'],
                        ['kod' => '&amp;frac14;', 'gosterim' => '&frac14;', 'aciklama' => 'Bir bölü dört (&frac14;)'],
                        ['kod' => '&amp;frac12;', 'gosterim' => '&frac12;', 'aciklama' => 'Bir bölü iki (&frac12;)'],
                        ['kod' => '&amp;frac34;', 'gosterim' => '&frac34;', 'aciklama' => 'Üç bölü dört (&frac34;)'],
                        ['kod' => '&amp;times;', 'gosterim' => '&times;', 'aciklama' => 'Çarpı işareti (&times;)'],
                        ['kod' => '&amp;divide;', 'gosterim' => '&divide;', 'aciklama' => 'Bölü işareti (&divide;)']
                    ];

                    // Diziyi döngü ile yazdırma
                    foreach ($karakterler as $karakter) {
                        echo '<tr>';
                        echo '<td>' . $karakter['kod'] . '</td>';
                        echo '<td>' . $karakter['gosterim'] . '</td>';
                        echo '<td>' . $karakter['aciklama'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
