<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

$blocks = [
    'green1' => '#4CAF50',
    'green2' => '#4CAF50',
    'yellow' => '#FFD700',
    'red'    => '#FF0000',
    'orange' => '#FFA500'
];

if (!isset($_SESSION['top_blocks'])) {
    $_SESSION['top_blocks'] = array_keys($blocks);
}

$topBlocks = $_SESSION['top_blocks'];
$bottomBlocks = $topBlocks; // Alt sıraya aynı bloklar atanıyor
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lego Oyunu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .lego {
            width: 60px;
            height: 40px;
            cursor: pointer;
            border-radius: 0.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            user-select: none;
            transition: border 0.2s;
        }
        .lego.selected {
            border: 3px solid #000;
        }
        #newGame {
            display: none;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">

    <!-- Başlık Kartı -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <i class="fas fa-brick-wall fa-lg me-3 text-primary"></i>
            <h5 class="card-title mb-0">Lego Oyunu</h5>
        </div>
    </div>

    <!-- Üst Sıra Kartı -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body text-center">
            <h5 class="card-title mb-3">Üst Sıra (Kendi Seçimini Yap)</h5>
            <div class="d-flex justify-content-center gap-3 flex-wrap" id="topRow">
                <?php foreach ($topBlocks as $block): ?>
                    <div
                        class="lego"
                        id="<?= $block ?>"
                        style="background-color: <?= $blocks[$block] ?>"
                        onclick="selectBlock(this, 'top')"
                        title="<?= strtoupper($block) ?>"
                    >
                        <?= strtoupper(substr($block, 0, 1)) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-success mt-3" id="confirmOrder" onclick="lockTopRow()">Tamam</button>
        </div>
    </div>

    <!-- Alt Sıra Kartı -->
    <div class="card mb-4 shadow-sm" id="bottomCard" style="display:none;">
        <div class="card-body text-center">
            <h5 class="card-title mb-3">Alt Sıra (Eşleştir)</h5>
            <div class="d-flex justify-content-center gap-3 flex-wrap" id="bottomRow">
                <?php foreach ($bottomBlocks as $block): ?>
                    <div
                        class="lego"
                        id="b_<?= $block ?>"
                        style="background-color: <?= $blocks[$block] ?>"
                        onclick="selectBlock(this, 'bottom')"
                        title="<?= strtoupper($block) ?>"
                    >
                        <?= strtoupper(substr($block, 0, 1)) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="text-center">
        <div id="score" class="fs-5 fw-bold">0 Doğru</div>
        <div id="message" class="mt-2 fw-semibold text-success"></div>
        <button class="btn btn-primary mt-3" id="newGame" onclick="restartGame()">Yeni Oyun</button>
    </div>

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    let selectedBlock = null;
    let gameLocked = false;

    function selectBlock(block, row) {
        if (row === 'top' && gameLocked) return;

        if (selectedBlock === null) {
            selectedBlock = block;
            block.classList.add('selected');
        } else if (selectedBlock === block) {
            // Aynı bloğa tekrar tıklama kaldırma
            block.classList.remove('selected');
            selectedBlock = null;
        } else {
            // Renk ve yazı değiş tokuşu
            let tempBg = selectedBlock.style.backgroundColor;
            let tempText = selectedBlock.textContent;

            selectedBlock.style.backgroundColor = block.style.backgroundColor;
            selectedBlock.textContent = block.textContent;

            block.style.backgroundColor = tempBg;
            block.textContent = tempText;

            selectedBlock.classList.remove('selected');
            selectedBlock = null;

            if (gameLocked && row === 'bottom') checkWin();
        }
    }

    function lockTopRow() {
        gameLocked = true;
        document.querySelectorAll("#topRow .lego").forEach(el => el.onclick = null);
        document.getElementById("confirmOrder").style.display = "none";
        document.getElementById("bottomCard").style.display = "block";
    }

    function checkWin() {
        let topRow = Array.from(document.getElementById("topRow").children).map(el => el.textContent);
        let bottomRow = Array.from(document.getElementById("bottomRow").children).map(el => el.textContent);

        let correct = topRow.filter((val, idx) => val === bottomRow[idx]).length;
        document.getElementById("score").textContent = correct + " Doğru";

        if (correct === topRow.length) {
            document.getElementById("message").textContent = "Tebrikler! Doğru sıraladınız!";
            document.getElementById("newGame").style.display = "inline-block";
        }
    }

    function restartGame() {
        fetch("reset.php").then(() => location.reload());
    }
</script>

</body>
</html>
