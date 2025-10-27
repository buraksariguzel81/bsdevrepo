<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$tasks = [
    "Fontlar -> tek dosya",
    "admin bsd -> css html js",
    "kaynakmenudiv -> bsd_navbar",
    "kaynakmenualt -> bsd_footerbar",
    "lib menu sistemi",
    "md dosyası geliş",
    "resource -> hızlandır",
    "lib -> js_dersleri",
    "1.scss -> 1.scssgiris.scss",
    "1.php -> 1.phpgiris.php",
    "3 -> 4 E-B_js_dersleri",
    "3 -> 3-js_dersleri",
    "müzik çalar projesi",
    "google console işlemleri",
    "sayfa aktarma",
    "python projesi",
    "Veritabanı;"
];

?>

<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <title>Yapılacaklar 1</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

    <div class="container py-4">

        <!-- Başlık Kartı -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-clipboard-list fa-lg me-3 text-primary"></i>
                <h5 class="card-title mb-0">Yapılacaklar Listesi 1</h5>
            </div>
        </div>

        <!-- Yapılacaklar Listesi -->
        <ol class="list-group list-group-numbered" id="checkboxes">
            <?php foreach ($tasks as $index => $task): ?>
                <li class="list-group-item d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkbox<?= $index + 1 ?>" />
                        <label class="form-check-label ms-2" for="checkbox<?= $index + 1 ?>"><?= htmlspecialchars($task) ?></label>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>

        <hr class="my-4" />

    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../tasks.js" type="text/javascript" charset="utf-8"></script>

    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>
