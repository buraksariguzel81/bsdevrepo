<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auth Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
    <style>
        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }
        .menu-item {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: #0d6efd;
        }
        .menu-item i {
            font-size: 1.8rem;
            color: #0d6efd;
            margin-bottom: 8px;
            display: block;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fas fa-lock me-2"></i> <h5 class="mb-0">Auth Yönetimi</h5>
        </div>
        <div class="card-body">
            <nav class="menu-container">
                <a href="rol_islemleri/rol_listesi.php" class="menu-item">
                    <i class="fas fa-users-cog"></i>
                    <div>Rol Listesi</div>
                </a>
                <a href="kullanici_islemleri/kullanıcı_listesi.php" class="menu-item">
                    <i class="fas fa-users"></i>
                    <div>Kullanıcı Listesi</div>
                </a>
                <a href="kullanici_islemleri/pasif_kullanıcı_listesi.php" class="menu-item">
                    <i class="fas fa-user-secret"></i>
                    <div>Pasif Kullanıcı Yönetimi</div>
                </a>
                <a href="kullanici_islemleri/kullanıcı_durumu.php" class="menu-item">
                    <i class="fas fa-user-check"></i>
                    <div>Kullanıcı Durumu</div>
                </a>
                <a href="preiumkullanıcı_islemleri/premium_ol.php" class="menu-item">
                    <i class="fas fa-crown"></i>
                    <div>Premium Ol</div>
                </a>
                <a href="preiumkullanıcı_islemleri/premium_kulllanıcı.php" class="menu-item">
                    <i class="fas fa-star"></i>
                    <div>Premium Kullanıcı</div>
                </a>
            </nav>
        </div>
    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
