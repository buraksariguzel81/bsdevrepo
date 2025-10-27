<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Menü öğelerini bir dizi olarak tanımlıyoruz
$menu_items = [
    [
        'link' => 'panel/panel.php',
        'icon' => 'fas fa-user-shield',
        'label' => 'Panel'
    ],
    [
        'link' => 'tasks/tasks.php',
        'icon' => 'fa fa-tasks',
        'label' => 'Tasks'
    ],
    [
        'link' => '#',
        'icon' => 'fas fa-book',
        'label' => 'Lib'
    ],
    [
        'link' => 'project/project.php',
        'icon' => 'fas fa-hammer',
        'label' => 'Project'
    ],
    [
        'link' => 'web/web.php',
        'icon' => 'fas fa-globe',
        'label' => 'Web'
    ],
    [
        'link' => '#',
        'icon' => 'fas fa-mobile-alt',
        'label' => 'App'
    ],
    [
        'link' => 'font/font.php',
        'icon' => 'fas fa-font',
        'label' => 'Fonts'
    ],
    [
        'link' => 'icon/icon.php',
        'icon' => 'fas fa-icons',
        'label' => 'Icon'
    ],

    [

     'link' => 'meyveler/meyveler.php',
        'icon' => 'fas fa-icons',
        'label' => 'Icon'

    ]
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Karşılama Ekranı</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #fff8f0;
        }
        .card-header {
            background-color: #a71930; /* Galatasaray Kırmızısı */
            color: #ffb81c; /* Galatasaray Sarısı */
            font-weight: 700;
        }
        .menu-container {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .menu-item {
            flex: 1 1 150px;
            padding: 10px 15px;
            background: #ffb81c; /* Sarı zemin */
            color: #a71930; /* Kırmızı yazı */
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .menu-item i {
            margin-right: 8px;
        }
        .menu-item:hover {
            background-color: #a71930;
            color: #ffb81c;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5><i class="fas fa-sitemap me-2"></i> Site Haritamız</h5>
        </div>
        <div class="card-body">
            <nav class="menu-container">
                <?php foreach ($menu_items as $item): ?>
                    <a href="<?= htmlspecialchars($item['link']) ?>" class="menu-item">
                        <i class="<?= htmlspecialchars($item['icon']) ?>"></i> <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>
