<?php
session_start();
$menu_name = "auth";
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php');
rol_kontrol(3);

try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
    $vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}

if (isset($_GET['id'])) {
    $kullanici_id = intval($_GET['id']);
    $vt->beginTransaction();
    try {
        $stmt = $vt->prepare("DELETE FROM rollerplus WHERE kullanici_id = :kullanici_id");
        $stmt->bindValue(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $vt->prepare("DELETE FROM bans WHERE id = :kullanici_id");
        $stmt->bindValue(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $vt->prepare("DELETE FROM kullanicilar WHERE id = :kullanici_id");
        $stmt->bindValue(':kullanici_id', $kullanici_id, PDO::PARAM_INT);
        $stmt->execute();

        $vt->commit();
        $_SESSION['mesaj'] = "Kullanıcı başarıyla silindi.";
    } catch (Exception $e) {
        $vt->rollBack();
        $_SESSION['hata'] = "Kullanıcı silinirken bir hata oluştu: " . $e->getMessage();
    }
}

$query = "SELECT kullanicilar.*, bans.id AS ban_durumu 
          FROM kullanicilar 
          LEFT JOIN bans ON kullanicilar.id = bans.id";
$result = $vt->query($query);
$kullanicilar = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kullanıcı Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container py-4">

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white d-flex align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i> Kullanıcı Listesi</h5>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['mesaj'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['mesaj'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['mesaj']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['hata'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['hata'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['hata']); ?>
            <?php endif; ?>

            <input type="text" id="aramaInput" class="form-control mb-3" placeholder="Kullanıcı adı veya e-posta ara" oninput="kullaniciAra()">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="kullaniciTablosu">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-id-badge"></i> ID</th>
                            <th><i class="fas fa-user"></i> Kullanıcı Adı</th>
                            <th><i class="fas fa-envelope"></i> E-posta</th>
                            <th><i class="fas fa-user-tag"></i> Rol</th>
                            <th><i class="fas fa-user-shield"></i> Hesap Durumu</th>
                            <th><i class="fas fa-cogs"></i> İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($kullanicilar as $kullanici): ?>
                        <tr>
                            <td><?= htmlspecialchars($kullanici['id']) ?></td>
                            <td><?= htmlspecialchars($kullanici['kullanici_adi']) ?></td>
                            <td><?= htmlspecialchars($kullanici['eposta']) ?></td>
                            <td>
                                <?php
                                $rollerQuery = "SELECT rol_adi FROM roller
                                                INNER JOIN rollerplus ON roller.id = rollerplus.rol_id
                                                WHERE rollerplus.kullanici_id = :kullanici_id";
                                $rollerStatement = $vt->prepare($rollerQuery);
                                $rollerStatement->bindValue(':kullanici_id', $kullanici['id'], PDO::PARAM_INT);
                                $rollerStatement->execute();
                                $rollerler = $rollerStatement->fetchAll(PDO::FETCH_COLUMN);
                                echo htmlspecialchars(implode(', ', $rollerler));
                                ?>
                            </td>
                            <td><?= htmlspecialchars($kullanici['hesap_durumu']) ?></td>
                            <td>
                                <a href="../rol_islemleri/rol_durumu.php?id=<?= $kullanici['id'] ?>" class="btn btn-sm btn-primary" title="Rol Durumu"><i class="fas fa-user-cog"></i></a>
                                <a href="kullanici_sil.php?id=<?= $kullanici['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');" title="Sil"><i class="fas fa-trash-alt"></i></a>
                                <?php if ($kullanici['ban_durumu']): ?>
                                    <a href="kullanici_bankaldir.php?id=<?= $kullanici['id'] ?>" class="btn btn-sm btn-warning" title="Ban Kaldır"><i class="fas fa-user-check"></i></a>
                                <?php else: ?>
                                    <a href="kullanici_banla.php?id=<?= $kullanici['id'] ?>" class="btn btn-sm btn-dark" title="Banla"><i class="fas fa-user-slash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function kullaniciAra() {
    const input = document.getElementById("aramaInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("kullaniciTablosu");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        const tdKullaniciAdi = tr[i].getElementsByTagName("td")[1];
        const tdEposta = tr[i].getElementsByTagName("td")[2];
        if (tdKullaniciAdi && tdEposta) {
            const txtKullanici = tdKullaniciAdi.textContent.toLowerCase();
            const txtEposta = tdEposta.textContent.toLowerCase();
            tr[i].style.display = (txtKullanici.includes(filter) || txtEposta.includes(filter)) ? "" : "none";
        }
    }
}
</script>
</body>
</html>
