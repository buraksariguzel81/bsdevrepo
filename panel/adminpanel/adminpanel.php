<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/config/vt_baglanti.php');
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/rol_kontrol.php');

// Rol kontrolü (örnek: 3 ID'li kullanıcı admin rolü için)
rol_kontrol(3);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e7b0d0f1.js" crossorigin="anonymous"></script>
    <style>
        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .menu-item {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: #0d6efd;
        }
        .menu-item i {
            color: #0d6efd;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fas fa-user-shield me-2"></i> <h5 class="mb-0">Admin Panel</h5>
        </div>
        <div class="card-body">
            <nav class="menu-container">
                <a href="#" class="menu-item">
                    <i class="fas fa-vials fa-2x mb-2"></i>
                    <div>Resource</div>
                </a>
                <a href="../../auth/auth.php" class="menu-item">
                    <i class="fas fa-lock fa-2x mb-2"></i>
                    <div>Auth</div>
                </a>
                <a href="../../archive/archive.php" class="menu-item">
                    <i class="fas fa-archive fa-2x mb-2"></i>
                    <div>Arşiv</div>
                </a>
            </nav>
        </div>
    </div>
</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
