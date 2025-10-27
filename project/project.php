<?php
// Navigasyon
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">

        <!-- Başlık -->
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-project-diagram me-2"></i> Project</h5>
        </div>

        <!-- Menü -->
        <div class="list-group list-group-flush">
            <a href="1.Zamanhesaplama/zamanhesap.php" class="list-group-item list-group-item-action">
                <i class="far fa-clock me-2"></i> 1. Zaman Hesaplama
            </a>
        </div>

    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
