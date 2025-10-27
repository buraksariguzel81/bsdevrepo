<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Dosya Listesi</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body { background: #f8f9fa; }
        .folder {
            cursor: pointer;
            font-weight: 600;
            color: #A71930; /* Galatasaray Kırmızısı */
            margin: 5px 0;
            user-select: none;
        }
        .folder:before {
            content: '▶ ';
            display: inline-block;
            width: 20px;
            color: #FF7F00; /* Galatasaray Turuncusu */
            transition: transform 0.3s ease;
        }
        .folder.open:before {
            content: '▼ ';
            color: #FF7F00;
        }
        .folder-content {
            display: none;
            margin-left: 25px;
        }
        a.menu-item {
            display: block;
            color: #34495e;
            text-decoration: none;
            padding: 2px 0;
        }
        a.menu-item:hover {
            text-decoration: underline;
            color: #A71930;
        }
        i.fa-file-code {
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="card shadow-sm">

        <!-- Bu kısım istediğin gibi oldu -->
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-lock me-2"></i> Private Sayfası - Dosya Listesi</h5>
        </div>

        <div class="card-body">
            <nav class="folder-list">
                <?php
                $anaKlasor = 'code/';

                function listDirectory($path, $level = 0) {
                    $dosyalar = scandir($path);
                    foreach ($dosyalar as $dosya) {
                        if ($dosya !== '.' && $dosya !== '..') {
                            $fullPath = $path . '/' . $dosya;
                            $extension = strtolower(pathinfo($dosya, PATHINFO_EXTENSION));
                            $allowed = ['php', 'html', 'htm', 'py', 'js', 'css', 'txt', 'json', 'xml'];

                            if (is_dir($fullPath)) {
                                echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . '<div class="folder">' . htmlspecialchars($dosya) . '</div>';
                                echo '<div class="folder-content">';
                                listDirectory($fullPath, $level + 1);
                                echo '</div>';
                            } elseif (in_array($extension, $allowed)) {
                                echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . '<a class="bsd-navlink1 menu-item" href="' . htmlspecialchars($fullPath) . '" target="_blank">'
                                    . '<i class="fas fa-file-code me-1"></i> ARF - ' . htmlspecialchars($dosya) . '</a>';
                            }
                        }
                    }
                }

                listDirectory($anaKlasor);
                ?>
            </nav>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('.folder').click(function() {
        $(this).toggleClass('open');
        $(this).next('.folder-content').slideToggle(200);
    });
});
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>
