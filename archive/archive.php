<?php
// navisyon
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Kategoriler ve içerikleri
$categories = [
    [
        'title' => 'Php Kodları',
        'icon' => 'fas fa-laptop-code',
        'items' => [
            [
                'href' => 'archive_php/session.php',
                'text' => 'session'
            ],

        ]
    ],
    [
        'title' => 'HTML Kodları',
        'icon' => 'fas fa-html5',
        'items' => [
            [
                'href' => 'archive_html/emmet.php',
                'text' => 'emmet'
            ],
            [
                'href' => 'archive_html/ozelkrakter.php',
                'text' => 'özel karakter'
            ],

        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Arşiv Sayfası</title>
</head>
<body>

<h5>Arşiv Sayfası</h5>

<nav class="menu-container">
    <?php foreach ($categories as $category): ?>
        <ul>
            <li>
                <i class="<?php echo $category['icon']; ?>"></i> <?php echo $category['title']; ?>
                <?php foreach ($category['items'] as $item): ?>
                    <a href="<?php echo $item['href']; ?>" class="menu-item bsd-navlink1"><?php echo $item['text']; ?></a>
                <?php endforeach; ?>
            </li>
        </ul>
    <?php endforeach; ?>
</nav>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>