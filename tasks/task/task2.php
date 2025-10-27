<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$tasks = [
    ["icon" => "fas fa-code", "text" => "strong'dan span etiketine geçiş yap"],
    ["icon" => "fas fa-shoe-prints", "text" => "Footer kısmını düzenle"],
    ["icon" => "fab fa-python", "text" => "Python a href değiştirmeyi öğren"],
    ["icon" => "fas fa-language", "text" => "Lang'ları tr yap"]
];

?>

<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <title>Yapılacaklar Listesi 2</title>
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
            <h5 class="card-title mb-0">Yapılacaklar Listesi 2</h5>
        </div>
    </div>

    <!-- Yapılacaklar Listesi -->
    <ol class="list-group list-group-numbered" id="checkboxes">
        <?php foreach ($tasks as $index => $task): ?>
            <li class="list-group-item d-flex align-items-center">
                <div class="form-check d-flex align-items-center gap-2">
                    <input class="form-check-input" type="checkbox" id="checkbox<?= $index + 1 ?>" />
                    <label class="form-check-label d-flex align-items-center gap-2 mb-0" for="checkbox<?= $index + 1 ?>">
                        <i class="<?= htmlspecialchars($task["icon"]) ?> text-secondary"></i>
                        <?= htmlspecialchars($task["text"]) ?>
                    </label>
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
