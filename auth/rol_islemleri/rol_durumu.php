<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

// Veritabanı bağlantısı
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Geçersiz kullanıcı ID.");
}

$query = "SELECT * FROM kullanicilar WHERE id = :id";
$statement = $vt->prepare($query);
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$kullanici = $statement->fetch(PDO::FETCH_ASSOC);

if (!$kullanici) {
    die("Kullanıcı bulunamadı.");
}

// Roller tablosundan tüm roller
$rollerQuery = "SELECT * FROM roller ORDER BY rol_adi ASC";
$rollerStatement = $vt->prepare($rollerQuery);
$rollerStatement->execute();
$roller = $rollerStatement->fetchAll(PDO::FETCH_ASSOC);

// Kullanıcının mevcut rollerini çek
$mevcutRollerQuery = "SELECT rol_id FROM rollerplus WHERE kullanici_id = :kullanici_id";
$mevcutRollerStatement = $vt->prepare($mevcutRollerQuery);
$mevcutRollerStatement->bindParam(':kullanici_id', $id, PDO::PARAM_INT);
$mevcutRollerStatement->execute();
$mevcutRoller = $mevcutRollerStatement->fetchAll(PDO::FETCH_COLUMN);

$mesaj = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Öncelikle mevcut roller silinir
    $deleteQuery = "DELETE FROM rollerplus WHERE kullanici_id = :kullanici_id";
    $deleteStatement = $vt->prepare($deleteQuery);
    $deleteStatement->bindParam(':kullanici_id', $id, PDO::PARAM_INT);
    $deleteStatement->execute();

    // Yeni roller eklenir
    if (!empty($_POST['rol_id']) && is_array($_POST['rol_id'])) {
        $insertQuery = "INSERT INTO rollerplus (kullanici_id, rol_id) VALUES (:kullanici_id, :rol_id)";
        $insertStatement = $vt->prepare($insertQuery);

        foreach ($_POST['rol_id'] as $rol_id) {
            $rol_id = (int)$rol_id;
            $insertStatement->bindParam(':kullanici_id', $id, PDO::PARAM_INT);
            $insertStatement->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
            $insertStatement->execute();
        }
    }
    $mesaj = "Roller başarıyla güncellendi.";
    // Yenileme yapalım, mesaj görünür
    header("Refresh:2");
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rol Ekle - Kullanıcı: <?php echo htmlspecialchars($kullanici['kullanici_adi']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header.bg-primary {
            background-color: #a32638 !important; /* Galatasaray kırmızısı */
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }
        label {
            user-select: none;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 5px;
            display: inline-block;
            margin: 5px 10px 5px 0;
            background-color: #fff3cd;
            border: 1px solid #fcb514;
            transition: background-color 0.3s ease;
        }
        label:hover {
            background-color: #f9e79f;
        }
        input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.2);
            cursor: pointer;
        }
        .bsd-btn1, .btn {
            background-color: #a32638;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .bsd-btn1:hover, .btn:hover {
            background-color: #7b1f28;
            color: #fff;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
            font-weight: 600;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary">
                <i class="fas fa-user-tag"></i> <h5 class="mb-0">Kullanıcı: <?php echo htmlspecialchars($kullanici['kullanici_adi']); ?> - Rolleri Seç</h5>
            </div>
            <div class="card-body">
                <?php if ($mesaj): ?>
                    <div class="message success"><?php echo $mesaj; ?></div>
                <?php endif; ?>

                <form method="post">
                    <?php foreach ($roller as $rol): ?>
                        <label>
                            <input 
                                type="checkbox" 
                                name="rol_id[]" 
                                value="<?php echo (int)$rol['id']; ?>" 
                                <?php echo in_array($rol['id'], $mevcutRoller) ? 'checked' : ''; ?>
                            >
                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($rol['rol_adi']); ?>
                        </label>
                    <?php endforeach; ?>
                    <br><br>
                    <button type="submit" class="bsd-btn1"><i class="fas fa-save"></i> Güncelle</button>
                </form>
            </div>
        </div>
    </div>

    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
