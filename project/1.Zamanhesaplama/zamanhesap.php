<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Geçmiş kayıtlarını oku
$history = file_exists('history.txt') ? file('history.txt', FILE_IGNORE_NEW_LINES) : [];

$result = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['datetime1'], $_POST['datetime2'])) {
    $tz = new DateTimeZone("Europe/Istanbul");
    $start = new DateTime($_POST['datetime1'], $tz);
    $end = new DateTime($_POST['datetime2'], $tz);
    $interval = $start->diff($end);

    $result = "Zaman farkı: " . $interval->format('%d gün, %h saat, %i dakika, %s saniye') .
              "<br>Başlangıç: " . $start->format('d-m-Y H:i:s') .
              "<br>Bitiş: " . $end->format('d-m-Y H:i:s');

    if (!empty(trim($_POST['reason']))) {
        $history[] = "Başlangıç: {$_POST['datetime1']}, Bitiş: {$_POST['datetime2']}, Neden: " . trim($_POST['reason']) . ", Sonuç: $result";
        file_put_contents('history.txt', implode("\n", $history) . "\n");
    }
}

if (isset($_POST['clear_history'])) {
    file_put_contents('history.txt', "");
    $history = [];
}

if (isset($_GET['delete']) && is_numeric($_GET['delete']) && isset($history[$_GET['delete']])) {
    unset($history[$_GET['delete']]);
    file_put_contents('history.txt', implode("\n", $history) . "\n");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Zaman Farkı Hesaplayıcı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-4">

    <!-- Hesaplama Kartı -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Zaman Farkı Hesaplayıcı</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="mb-3">
                <div class="mb-3">
                    <label for="datetime1" class="form-label"><i class="fas fa-hourglass-start"></i> Başlangıç:</label>
                    <input type="datetime-local" class="form-control" id="datetime1" name="datetime1" value="<?= $_POST['datetime1'] ?? '' ?>" required>
                </div>

                <div class="mb-3">
                    <label for="datetime2" class="form-label"><i class="fas fa-hourglass-end"></i> Bitiş:</label>
                    <input type="datetime-local" class="form-control" id="datetime2" name="datetime2" value="<?= $_POST['datetime2'] ?? '' ?>" required>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label"><i class="fas fa-question-circle"></i> Neden?</label>
                    <textarea class="form-control" id="reason" name="reason" rows="2"><?= $_POST['reason'] ?? '' ?></textarea>
                </div>

                <button type="submit" class="btn btn-success"><i class="fas fa-calculator me-1"></i> Hesapla</button>
            </form>

            <?php if ($result): ?>
                <div class="alert alert-info mt-4" id="result">
                    <?= $result ?>
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById("result").remove();
                    }, 120000);
                </script>
            <?php endif; ?>
        </div>
    </div>

    <!-- Geçmiş Kartı -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i> Geçmiş Kayıtlar</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="mb-3 d-flex gap-2">
                <button type="submit" name="clear_history" class="btn btn-danger"><i class="fas fa-trash-alt me-1"></i> Geçmişi Temizle</button>
            </form>

            <ul class="list-group">
                <?php if (empty($history)): ?>
                    <li class="list-group-item text-muted">Henüz kayıt yok.</li>
                <?php else: ?>
                    <?php foreach ($history as $i => $kayit): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= htmlspecialchars($kayit) ?></span>
                            <a href="?delete=<?= $i ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Silinsin mi?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
