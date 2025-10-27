<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$icon_categories = [
    "Temel ve Arayüz" => [
        "category_icon" => "fas fa-desktop",
        "link" => "/temel-arayuz",
        "link_text" => "Temel ve Arayüz detaylarına git",
        "icons" => [
            ["icon" => "fa fa-search", "text" => "Search"],
            ["icon" => "fa fa-home", "text" => "Home"],
            ["icon" => "fa fa-code", "text" => "Code"],
            ["icon" => "fa fa-file", "text" => "File"],
            ["icon" => "fa-solid fa-gear", "text" => "Gear"],
            ["icon" => "fas fa-user", "text" => "User"],
            ["icon" => "fas fa-envelope", "text" => "Envelope"],
            ["icon" => "fas fa-bell", "text" => "Bell"],
            ["icon" => "fas fa-calendar", "text" => "Calendar"],
            ["icon" => "fas fa-camera", "text" => "Camera"],
            ["icon" => "fas fa-chart-bar", "text" => "Chart Bar"],
            ["icon" => "fas fa-cloud", "text" => "Cloud"],
            ["icon" => "fas fa-cog", "text" => "Cog"],
            ["icon" => "fas fa-database", "text" => "Database"],
            ["icon" => "fas fa-book", "text" => "Book"],
            ["icon" => "fas fa-pencil-alt", "text" => "Pencil"],
            ["icon" => "fas fa-trash", "text" => "Trash"],
            ["icon" => "fas fa-heart", "text" => "Heart"],
            ["icon" => "fas fa-star", "text" => "Star"],
            ["icon" => "fas fa-music", "text" => "Music"]
        ]
    ],
    "Ulaşım" => [
        "category_icon" => "fas fa-car",
        "link" => "/ulasim",
        "link_text" => "Ulaşım ikonlarına göz at",
        "icons" => [
            ["icon" => "fa-solid fa-bus", "text" => "Bus"],
            ["icon" => "fas fa-truck", "text" => "Truck"],
            ["icon" => "fas fa-bicycle", "text" => "Bicycle"],
            ["icon" => "fas fa-car", "text" => "Car"],
            ["icon" => "fas fa-plane", "text" => "Plane"],
            ["icon" => "fas fa-rocket", "text" => "Rocket"],
            ["icon" => "fas fa-ship", "text" => "Ship"],
            ["icon" => "fas fa-train", "text" => "Train"],
            ["icon" => "fas fa-subway", "text" => "Subway"],
            ["icon" => "fas fa-taxi", "text" => "Taxi"]
        ]
    ],
    "Yiyecek ve İçecek" => [
        "category_icon" => "fas fa-utensils",
        "link" => "/yiyecek-icecek",
        "link_text" => "Lezzetli ikonlara bak",
        "icons" => [
            ["icon" => "fas fa-coffee", "text" => "Coffee"],
            ["icon" => "fas fa-utensils", "text" => "Utensils"],
            ["icon" => "fas fa-pizza-slice", "text" => "Pizza Slice"],
            ["icon" => "fas fa-hamburger", "text" => "Hamburger"],
            ["icon" => "fas fa-ice-cream", "text" => "Ice Cream"],
            ["icon" => "fas fa-cookie", "text" => "Cookie"]
        ]
    ],
    "İş ve Finans" => [
        "category_icon" => "fas fa-briefcase",
        "link" => "/is-finans",
        "link_text" => "Finans ikonlarına göz at",
        "icons" => [
            ["icon" => "fas fa-briefcase", "text" => "Briefcase"],
            ["icon" => "fas fa-chart-line", "text" => "Chart Line"],
            ["icon" => "fas fa-piggy-bank", "text" => "Piggy Bank"],
            ["icon" => "fas fa-money-bill-wave", "text" => "Money Bill"],
            ["icon" => "fas fa-coins", "text" => "Coins"],
            ["icon" => "fas fa-credit-card", "text" => "Credit Card"]
        ]
    ],
    "Spor ve Sağlık" => [
        "category_icon" => "fas fa-dumbbell",
        "link" => "/spor-saglik",
        "link_text" => "Sağlık ve spor ikonları",
        "icons" => [
            ["icon" => "fas fa-dumbbell", "text" => "Dumbbell"],
            ["icon" => "fas fa-running", "text" => "Running"],
            ["icon" => "fas fa-heartbeat", "text" => "Heartbeat"],
            ["icon" => "fas fa-medkit", "text" => "Medkit"],
            ["icon" => "fas fa-stethoscope", "text" => "Stethoscope"],
            ["icon" => "fas fa-hospital", "text" => "Hospital"]
        ]
    ]
];

?>


<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <title>Kapsamlı Font Awesome İkon Galerisi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        .category-card {
            margin-top: 2rem;
            box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.075);
        }
        .category-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #0d6efd;
        }
        .icon-list {
            list-style: none;
            padding-left: 0;
            margin-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .icon-item {
            flex: 0 0 120px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #212529;
            user-select: none;
        }
        .icon-item i {
            font-size: 1.5rem;
            color: #0d6efd;
            min-width: 24px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <i class="fas fa-icons fa-lg me-3 text-primary"></i>
            <h5 class="card-title mb-0">Kapsamlı Font Awesome İkon Galerisi</h5>
        </div>
    </div>

    <?php foreach ($icon_categories as $category => $data): ?>
        <div class="card category-card">
            <div class="card-body">
                <div class="category-header">
                    <i class="<?= htmlspecialchars($data['category_icon']) ?>"></i>
                    <span><?= htmlspecialchars($category) ?></span>
                </div>

                <ul class="icon-list">
                    <?php foreach ($data["icons"] as $icon): ?>
                        <li class="icon-item">
                            <i class="<?= htmlspecialchars($icon['icon']) ?>"></i>
                            <?= htmlspecialchars($icon['text']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>
