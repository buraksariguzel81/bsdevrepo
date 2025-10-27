<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$fonts = [
    'bsd-UnicornPop',
    'bsd-Nabla',
    'bsd-Skranji',
    'bsd-PottaOne',
    'bsd-Langdon',
    'bsd-SquimFont',
    'bsd-Moonstar',
    'bsd-Righteous',
    'bsd-BlackOpsOne',
    'bsd-Ethnocentric',
    'bsd-Brolink',
    'bsd-LeviBrush',
    'bsd-Abla',
    'bsd-Turkish_Participants',
    'bsd-Argor_Biw_Scaqh'
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Fonts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
    <style>
        .font-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .font-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            transition: background 0.2s;
        }
        .font-item:hover {
            background: #f8f9fa;
        }
        .font-name {
            font-size: 1.2rem;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-font me-2"></i> Fonts</h5>
        </div>
        <div class="card-body">
            <ol class="font-list">
                <?php foreach ($fonts as $font): ?>
                    <li class="font-item <?= $font ?>">
                        <span class="font-name"><?= $font ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
