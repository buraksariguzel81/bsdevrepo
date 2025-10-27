<?php
$menu_name = 'auth';
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
$vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$users = [];
try {
    $stmt = $vt->query("SELECT id, kullanici_adi, cevrimici FROM kullanicilar");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Hata: ' . $e->getMessage();
}

$onlineCount = 0;
$offlineCount = 0;
foreach ($users as $user) {
    $user['cevrimici'] ? $onlineCount++ : $offlineCount++;
}
$totalCount = count($users);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Kullanıcı Durumları</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0"><i class="fas fa-users"></i> Kullanıcı Durumları</h4>
        </div>
        <div class="card-body">

            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#onlineUsers" style="cursor: pointer;">
                    <div><i class="fas fa-circle me-2"></i> Çevrimiçi Kullanıcılar</div>
                    <span class="badge bg-light text-success"><?= $onlineCount ?></span>
                </div>
                <div id="onlineUsers" class="collapse">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['cevrimici']): ?>
                                <li class="list-group-item"><i class="fas fa-circle text-success me-2"></i><?= htmlspecialchars($user['kullanici_adi']) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#offlineUsers" style="cursor: pointer;">
                    <div><i class="far fa-circle me-2"></i> Çevrimdışı Kullanıcılar</div>
                    <span class="badge bg-light text-danger"><?= $offlineCount ?></span>
                </div>
                <div id="offlineUsers" class="collapse">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($users as $user): ?>
                            <?php if (!$user['cevrimici']): ?>
                                <li class="list-group-item"><i class="far fa-circle text-muted me-2"></i><?= htmlspecialchars($user['kullanici_adi']) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div><i class="fas fa-chart-bar me-2"></i> Toplam Kullanıcı</div>
                    <span class="badge bg-primary fs-5"><?= $totalCount ?></span>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
