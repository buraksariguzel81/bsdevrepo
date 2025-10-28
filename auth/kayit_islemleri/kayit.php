<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["kayit"])) {
   $css = ['main.css']; // Sadece Bootstrap
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
}

date_default_timezone_set('Europe/Istanbul');


try {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php'); // MySQL bağlantısı

    // Tabloları kontrol et ve gerekirse oluştur
    try {
        $result = $vt->query("SHOW COLUMNS FROM kullanicilar LIKE 'cevrimici'");
        if ($result && $result->rowCount() == 0) {
            $vt->query("ALTER TABLE kullanicilar ADD COLUMN cevrimici TINYINT(4) DEFAULT 0");
        }

        $result2 = $vt->query("SHOW COLUMNS FROM kullanicilar LIKE 'hesap_durumu'");
        if ($result2 && $result2->rowCount() == 0) {
            $vt->query("ALTER TABLE kullanicilar ADD COLUMN hesap_durumu VARCHAR(20) DEFAULT 'aktif'");
        }

        $result3 = $vt->query("SHOW COLUMNS FROM kullanicilar LIKE 'rol'");
        if ($result3 && $result3->rowCount() == 0) {
            $vt->query("ALTER TABLE kullanicilar ADD COLUMN rol VARCHAR(50) DEFAULT 'kullanici'");
        }

        $result4 = $vt->query("SHOW COLUMNS FROM kullanicilar LIKE 'kayit_tarihi'");
        if ($result4 && $result4->rowCount() == 0) {
            $vt->query("ALTER TABLE kullanicilar ADD COLUMN kayit_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP");
        }
    } catch (Exception $e) {
        error_log("Sütun kontrolü sırasında hata: " . $e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kayit"])) {
        // Kullanıcı sayı limiti kontrolü
        $userCountStmt = $vt->query("SELECT COUNT(*) as count FROM kullanicilar");
        $userCount = $userCountStmt->fetch(PDO::FETCH_ASSOC)['count'];

        if ($userCount >= 3) {
            $hata = "Üzgünüz, sistem maksimum 3 kullanıcıya izin veriyor. Yeni kayıt yapılamaz.";
        } else {
            $kullanici = trim($_POST["kullanici"]);
            $sifre = trim($_POST["sifre"]);
            $eposta = trim($_POST["eposta"]);
            $cinsiyet = trim($_POST["cinsiyet"]);

            if (!preg_match("/^[a-z0-9_]{1,50}$/", $kullanici)) {
            $hata = "Geçerli bir kullanıcı adı girin (en fazla 50 karakter, sadece küçük harf, rakam ve alt çizgi).";
        } elseif (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
            $hata = "Geçerli bir e-posta adresi girin.";
        } elseif (!preg_match("/\.(com|net|org|edu|gov)$/i", $eposta)) {
            $hata = "E-posta adresi geçerli bir uzantıya sahip olmalıdır (.com, .net, .org, .edu, .gov).";
        } elseif (!in_array($cinsiyet, ['erkek', 'kadin', 'diger'])) {
            $hata = "Geçerli bir cinsiyet seçin.";
        } else {
            // E-posta adresinin sistemde olup olmadığını kontrol et
            $stmt = $vt->prepare("SELECT COUNT(*) as count FROM kullanicilar WHERE eposta = :eposta");
            $stmt->bindValue(':eposta', $eposta, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $row['count'];

            if ($count > 0) {
                $hata = "Bu e-posta adresi zaten kullanılmaktadır. Lütfen başka bir e-posta adresi deneyin.";
            } else {
                // Roller tablosunda id'si 2 olan rolü alıyoruz
                $stmtRol = $vt->prepare("SELECT id FROM roller WHERE id = 2");
                $stmtRol->execute();
                $rol = $stmtRol->fetch(PDO::FETCH_ASSOC);
                $rol_id = $rol['id']; // Rol id'yi alıyoruz (id 2'yi)

                // Veritabanındaki tüm id'leri alıyoruz
                $stmtIds = $vt->query("SELECT id FROM kullanicilar");
                $ids = $stmtIds->fetchAll(PDO::FETCH_COLUMN);

                // Boş id'yi bulmak için kontrol et
                $newId = null;
                for ($i = 1; $i <= count($ids) + 1; $i++) {
                    if (!in_array($i, $ids)) {
                        $newId = $i;
                        break;
                    }
                }

                // Eğer boş id bulunmazsa, MAX(id) + 1'yi kullan
                if ($newId === null) {
                    $stmtMaxId = $vt->query("SELECT MAX(id) as max_id FROM kullanicilar");
                    $rowMaxId = $stmtMaxId->fetch(PDO::FETCH_ASSOC);
                    $newId = $rowMaxId['max_id'] + 1;
                }

                // Kullanıcıyı veritabanına ekle
                $stmt = $vt->prepare("INSERT INTO kullanicilar (id, kullanici_adi, sifre, eposta, cinsiyet, hesap_durumu, kayit_tarihi, rol) 
                                       VALUES (:id, :kullanici, :sifre, :eposta, :cinsiyet, 'aktif', NOW(), 'kullanici')");
                $stmt->bindValue(':id', $newId, PDO::PARAM_INT);
                $stmt->bindValue(':kullanici', $kullanici, PDO::PARAM_STR);
                $stmt->bindValue(':sifre', password_hash($sifre, PASSWORD_DEFAULT), PDO::PARAM_STR);
                $stmt->bindValue(':eposta', $eposta, PDO::PARAM_STR);
                $stmt->bindValue(':cinsiyet', $cinsiyet, PDO::PARAM_STR);
                $stmt->execute();

                // Yeni kaydı rollerplus tablosuna ekle
                $userId = $newId; // Burada yeni ID'yi kullanıyoruz
                $stmtPlus = $vt->prepare("INSERT INTO rollerplus (kullanici_id, rol_id) VALUES (:kullanici_id, :rol_id)");
                $stmtPlus->bindValue(':kullanici_id', $userId, PDO::PARAM_INT);
                $stmtPlus->bindValue(':rol_id', $rol_id, PDO::PARAM_INT);
                $stmtPlus->execute();

                // Çevrim içi durumu güncelle
                $stmtCevrimici = $vt->prepare("UPDATE kullanicilar SET cevrimici = 1 WHERE id = :id");
                $stmtCevrimici->bindValue(':id', $userId, PDO::PARAM_INT); // Burada doğru ID'yi kullanıyoruz
                $stmtCevrimici->execute();

                // Kayıt başarılı, giriş yapılmış olarak yönlendir
                $_SESSION["kullanici_adi"] = $kullanici; // Kullanıcıyı giriş yapmış gibi kabul ediyoruz.
                header("Location: ../../index.php");
                exit();
            }
        }
    }
}
} catch (Exception $e) {
    die("Veritabanına bağlanılamadı: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>

    <title>Kayıt Ol</title>


</head>
<body>
    <div class="container">
        <div class="form-container">
            <h5 class=""><i class="fas fa-user-plus"></i> Kayıt Ol</h5>

            <!-- PROJE İPTAL UYARISI -->
            <div class="alert alert-danger mb-4">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>⚠️ PROJE İPTAL EDİLMİŞTİR ⚠️</strong><br>
              Bu proje iptal edilmiştir. Kayıt olmak yasaktır.
            </div>

            <!-- Hata Mesajı -->
            <?php if (isset($hata)): ?>
                <div class="alert alert-danger fade-out" role="alert" id="hataMessage"><?php echo $hata; ?></div>
            <?php endif; ?>

            <!-- Kayıt Formu (Devre Dışı) -->
            <form method="post" id="kayitForm">
                <div>
                    <label for="kullanici_reg" class="form-label"><i class="fas fa-user"></i> Kullanıcı Adı</label>
                    <input type="text" class="form-control" id="kullanici_reg" name="kullanici" required maxlength="50">
                    <div id="kullanici_durumu" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="sifre_reg" class="form-label"><i class="fas fa-lock"></i> Şifre</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="sifre_reg" name="sifre" required minlength="6">

                    </div>
                    <div id="sifre_durumu" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="eposta" class="form-label"><i class="fas fa-envelope"></i> E-posta</label>
                    <input type="email" class="form-control" id="eposta" name="eposta" required>
                    <div id="eposta_durumu" class="form-text"></div>
                </div>
                <div class="mb-3">
                    <label for="cinsiyet" class="bsd-navlink1"><i class="fas fa-venus-mars"></i> Cinsiyet</label>
                    <select class="form-select" id="cinsiyet" name="cinsiyet" required>
                        <option value="" selected disabled>Seçiniz</option>
                        <option value="erkek">Erkek</option>
                        <option value="kadin">Kadın</option>
                        <option value="diger">Diğer</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success" name="kayit">
                    <i class="fas fa-user-plus"></i> Kayıt Ol
                </button>
            </form>
            <hr>
            <p class="text-center">Zaten bir hesabınız var mı? 
                <a href="../giris_islemleri/giris.php" class="bsd-navlink1"><br>Giriş Yapın</a>
            </p>
        </div>
    </div>

    
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
    


</body>
</html>
