<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Yapılacaklar Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5><i class="fas fa-tasks me-2"></i> Yapılacaklar Listesi</h5>
        </div>
        <div class="card-body">
       

            <nav class="menu-container d-flex flex-column gap-3">
                <a href="task/task1.php" class="btn btn-outline-danger text-start">
                    <i class="fas fa-check-circle me-2"></i> Görev 1
                </a>
                <a href="task/task2.php" class="btn btn-outline-danger text-start">
                    <i class="fas fa-check-circle me-2"></i> Görev 2
                </a>
            </nav>
        </div>
    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
