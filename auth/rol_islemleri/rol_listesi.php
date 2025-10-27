<?php
session_start();

$menu_name = "auth";
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
    $vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}

$mesaj = '';
$roller = [];

try {
    $stmt = $vt->query("SELECT id, rol_adi FROM roller ORDER BY id ASC");
    $roller = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mesaj = "Hata: " . $e->getMessage();
}

if (isset($_POST['rol_adi']) && !empty(trim($_POST['rol_adi']))) {
    $rol_adi = trim($_POST['rol_adi']);
    $stmt = $vt->prepare("SELECT COUNT(*) FROM roller WHERE rol_adi = :rol_adi");
    $stmt->bindValue(':rol_adi', $rol_adi, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $stmt = $vt->prepare("INSERT INTO roller (rol_adi) VALUES (:rol_adi)");
        $stmt->bindValue(':rol_adi', $rol_adi, PDO::PARAM_STR);
        $stmt->execute();
        $mesaj = "Rol başarıyla eklendi.";
        header("Refresh:2");
    } else {
        $mesaj = "Bu rol zaten mevcut.";
    }
}

if (isset($_POST['update_rol_id']) && isset($_POST['update_rol_adi'])) {
    $update_rol_id = intval($_POST['update_rol_id']);
    $update_rol_adi = trim($_POST['update_rol_adi']);
    if (!empty($update_rol_adi)) {
        $stmt = $vt->prepare("UPDATE roller SET rol_adi = :rol_adi WHERE id = :rol_id");
        $stmt->bindValue(':rol_adi', $update_rol_adi, PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $update_rol_id, PDO::PARAM_INT);
        $stmt->execute();
        $mesaj = "Rol başarıyla güncellendi.";
        header("Refresh:2");
    }
}

if (isset($_POST['delete_rol_id'])) {
    $delete_rol_id = intval($_POST['delete_rol_id']);
    $stmt = $vt->prepare("SELECT COUNT(*) FROM kullanicilar WHERE rol_id = :rol_id");
    $stmt->bindValue(':rol_id', $delete_rol_id, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $stmt = $vt->prepare("DELETE FROM roller WHERE id = :rol_id");
        $stmt->bindValue(':rol_id', $delete_rol_id, PDO::PARAM_INT);
        $stmt->execute();
        $mesaj = "Rol başarıyla silindi.";
        header("Refresh:2");
    } else {
        $mesaj = "Bu rol kullanımda olduğu için silinemez.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rolleri Yönet - Galatasaray Stili</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa; /* Hafif açık gri arka plan, sayfa temiz */
        }
        .card-header.bg-primary {
            background-color: #a32638 !important; /* Galatasaray kırmızısı daha koyu ve güçlü */
        }
        .card-header.bg-success {
            background-color: #fcb514 !important; /* Galatasaray sarısı canlı */
            color: #3b1d0f !important; /* Sarı üzerine koyu kahverengi yazı */
        }
        .btn-primary {
            background-color: #a32638;
            border-color: #a32638;
        }
        .btn-primary:hover {
            background-color: #7b1f28;
            border-color: #7b1f28;
        }
        .btn-success {
            background-color: #fcb514;
            border-color: #fcb514;
            color: #3b1d0f;
        }
        .btn-success:hover {
            background-color: #d4a00f;
            border-color: #d4a00f;
            color: #3b1d0f;
        }
        h5, h3 {
            font-weight: 700;
            letter-spacing: 1.2px;
        }
        /* Butonlar arası boşluklar */
        form.d-flex > *:not(:last-child) {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex align-items-center gap-2">
                <i class="fas fa-user-cog fa-lg"></i>
                <h5 class="mb-0">Rol Yönetimi</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($mesaj)): ?>
                <div class="alert <?php echo (strpos($mesaj, 'Hata') !== false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mesaj; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
                </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-4">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Rol Adı</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roller as $rol): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rol['id']); ?></td>
                                <td><?php echo htmlspecialchars($rol['rol_adi']); ?></td>
                                <td>
                                    <form method="POST" class="d-flex flex-wrap gap-2 align-items-center">
                                        <input type="hidden" name="update_rol_id" value="<?php echo $rol['id']; ?>">
                                        <input type="text" name="update_rol_adi" class="form-control form-control-sm flex-grow-1" value="<?php echo $rol['rol_adi']; ?>" required>
                                        <button type="submit" class="btn btn-primary btn-sm" title="Düzenle"><i class="fas fa-edit"></i></button>
                                    </form>
                                    <form method="POST" onsubmit="return confirm('Bu rolü silmek istediğinize emin misiniz?');" class="mt-2">
                                        <input type="hidden" name="delete_rol_id" value="<?php echo $rol['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Sil"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($roller)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Hiç rol bulunamadı.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <hr />

                <h5 class="text-success mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Yeni Rol Ekle
                </h5>
                <form method="POST" class="d-flex gap-2 align-items-center flex-wrap">
                    <input type="text" name="rol_adi" class="form-control flex-grow-1" placeholder="Rol adı" required>
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Ekle</button>
                </form>
            </div>
        </div>
    </div>

    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
