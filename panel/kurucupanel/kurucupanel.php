<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php');
rol_kontrol(1);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Kurucu Panel</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
    <style>
        /* Galatasaray turuncusu */
        .gs-header {
            background: #FF7F00; /* turuncu */
            color: #A71930; /* kırmızı */
            font-weight: 700;
        }
        /* Butonlar dolu renkli ve cesur */
        .btn-gs-orange {
            background-color: #FF7F00;
            color: white;
            border: none;
        }
        .btn-gs-red {
            background-color: #A71930;
            color: white;
            border: none;
        }
        .btn-gs-orange:hover, .btn-gs-red:hover {
            opacity: 0.85;
            color: white;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-sm">
        <div class="card-header gs-header d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-cogs me-2"></i> Kurucu Panel
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="../../modules/modules.php" class="btn btn-gs-orange w-100 text-start">
                        <i class="fas fa-server me-2"></i> Modules
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="../../private/private.php" class="btn btn-gs-red w-100 text-start">
                        <i class="fas fa-user-secret me-2"></i> Özel
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="../../error/error.php" class="btn btn-danger w-100 text-start">
                        <i class="fas fa-exclamation-triangle me-2"></i> Hata
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="../../hizlidosya.php" class="btn btn-secondary w-100 text-start">
                        <i class="fas fa-file-alt me-2"></i> Hızlı Dosya
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="../../api/mailersendapi/mailersendapi.php" class="btn btn-primary w-100 text-start">
                        <i class="fas fa-code me-2"></i> API
                    </a>
                </div>

                <?php if (isset($kullanici_adi) && $kullanici_adi === "buraksariguzeldev"): ?>
                <div class="col-md-4">
                    <a href="../../mail/eposta_gonderme/eposta_gonderme_ms.php" class="btn btn-success w-100 text-start">
                        <i class="fas fa-envelope me-2"></i> E-posta Gönder
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
