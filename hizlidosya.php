<?php
include_once('assets/src/include/navigasyon.php');

// Kök dizini belirle
$serverRoot = $_SERVER['DOCUMENT_ROOT'];
$currentPath = isset($_GET['path']) ? $_GET['path'] : $serverRoot;

function listDirectories($dir)
{
    $items = scandir($dir);
    
    echo "<ul style='list-style: none; padding-left: 15px;'>";
    foreach ($items as $item) {
        if ($item === "." || $item === "..") {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $item; 

        if (is_dir($path)) {
            // 📁 Klasörler için aç/kapat yapılabilir div ekliyoruz
            echo "<li class='folder'>
                    <span class='toggle ibm-plex-mono-regular' onclick='toggleFolder(this)'>📁 " . htmlspecialchars($item) . "</span>
                    <ul class='nested' style='display: none;'>";
            listDirectories($path);
            echo "</ul>
                  </li>";
        } else {
            echo "<li class='file'><a class='ibm-plex-mono-regular' href='" . str_replace($_SERVER['DOCUMENT_ROOT'], '', $path) . "'>📄 " . htmlspecialchars($item) . "</a></li>";
        }
    }
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Site Haritası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-diagram-3 me-2"></i> Site Haritası
            </h5>
        </div>

        <div class="card-body">
            <div class="bg-secondary text-white p-2 mb-3 rounded">
                <i class="bi bi-search"></i> ARA...
                <input class="ibm-plex-mono-regular form-control mt-2" type="text" id="searchBox" placeholder="<?= htmlspecialchars($currentPath); ?>" onkeyup="filterList()">
            </div>

            <hr>

            <?php listDirectories($currentPath); ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleFolder(element) {
    let nestedList = element.nextElementSibling;
    nestedList.style.display = nestedList.style.display === "none" ? "block" : "none";
}

function filterList() {
    let input = document.getElementById("searchBox").value.toLowerCase();
    let items = document.querySelectorAll(".file, .folder .toggle");

    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        let parentLi = item.closest("li");

        if (text.includes(input)) {
            parentLi.style.display = "";
        } else {
            parentLi.style.display = "none";
        }
    });
}
</script>

</body>
</html>
