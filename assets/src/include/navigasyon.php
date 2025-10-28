<?php
include($_SERVER['DOCUMENT_ROOT'] .'/assets/src/php/kullanici_adi.php');
include 'siteurl.php';
include 'header.php';

$menu_items = [];
$current_url = basename($_SERVER['REQUEST_URI']);

if ($kullanici_adi) {
  include 'menu_items.php';
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>BSD Menü</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>

<div id="bsd-main" class="d-flex justify-content-between align-items-center px-3 py-2 bg-white border-bottom shadow-sm" style="height: 56px;">
  <span class="bsd-openbtn btn btn-outline-secondary d-lg-none" onclick="toggleNav()">☰</span>

  <div class="bsd-middle-section">
    <a href="<?= site_url() ?>" class="d-inline-flex align-items-center">
      <img src="<?= site_url('/img/bsd_logo.png') ?>" alt="Logo" style="height: 32px;">
    </a>
  </div>

  <div class="bsd-topnav d-none d-lg-flex ml-15 ">
    <a href="<?= site_url() ?>" class="d-inline-flex align-items-center">
      <img src="<?= site_url('/img/bsd_logo.png') ?>" alt="Logo" style="height: 32px;">
      
    </a>
    <?php foreach ($menu_items as $item): 
      $isActive = ($item['url'] === $current_url) ? 'active-menu' : '';
    ?>
      <div class="bsd-topnav-item position-relative">
        <a href="<?= $item['url'] !== '#' ? site_url($item['url']) : '#' ?>"
           class="bsd-topnav-link px-3 py-2 text-dark fw-semibold d-flex align-items-center <?= $isActive ?>">
          <i class="<?= $item['icon'] ?> me-2"></i> <?= $item['text'] ?>
          <?php if (isset($item['submenu'])): ?>
            <i class="fas fa-chevron-down ms-2 small"></i>
          <?php endif; ?>
        </a>

        
        <?php if (isset($item['submenu'])): ?>
          <div class="bsd-topnav-submenu bg-white shadow-sm rounded-3 py-2 px-3">
            <?php foreach ($item['submenu'] as $sub): ?>
              <a href="<?= site_url($sub['url']) ?>" class="d-block text-dark text-decoration-none py-1 small">
                <i class="<?= $sub['icon'] ?> me-2"></i> <?= $sub['text'] ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>


  </div>

  <!-- Mobil Sidebar -->
<div id="bsd-mySidebar" class="bsd-sidebar">
  <span class="bsd-closebtn" onclick="closeNav()">&times;</span>
  <img src="<?= site_url('/img/bsd_logo.png') ?>" class="bsd-sidebar-logo" />
  

  <?php foreach ($menu_items as $item): ?>
    <div class="bsd-menu-item">
      <a href="<?= $item['url'] !== '#' ? site_url($item['url']) : '#' ?>" class="bsd-navlink1">
        <span class="bsd-menu-icon"><i class="<?= $item['icon'] ?>"></i></span> <?= $item['text'] ?>
        <?php if (isset($item['submenu'])): ?>
          <span class="bsd-submenu-toggle"><i class="fas fa-chevron-down"></i></span>
        <?php endif; ?>
      </a>


      <?php if (isset($item['submenu'])): ?>
        <div class="bsd-submenu">
          <?php foreach ($item['submenu'] as $sub): ?>
            <a href="<?= site_url($sub['url']) ?>" class="bsd-navlink1">
              <span class="bsd-menu-icon"><i class="<?= $sub['icon'] ?>"></i></span> <?= $sub['text'] ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>

  <div class="bsd-right-section">
   
  </div>
    <?php if (!$kullanici_adi): ?>
      <button class="btn btn-outline-secondary">
        <i class="fas fa-ban"></i>
      </button>
    <?php else : ?>
    <div class="dropdown">
  <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <?= htmlspecialchars($kullanici_adi) ?>
  </button>
  <ul class="dropdown-menu">

 

  <li>
    <a class="dropdown-item text-danger" href="<?= site_url('auth/profil_islemleri/profile.php') ?>">
      <i class="fas fa-user"></i> Profil
    </a>
  </li>

 

  <li>
    <a class="dropdown-item text-danger" href="<?= site_url('/auth/cikis_islemleri/logout.php') ?>">
      <i class="fas fa-sign-out-alt"></i> Çıkış Yap
    </a>
  </li>
</ul>

</div>


    <?php endif; ?>
  </div>
</div>


<?php
$aktifSayfa = basename($_SERVER['PHP_SELF']);
$izinliSayfalar = ['index.php', 'giris.php', 'kayit.php'];

if (!$kullanici_adi && !in_array($aktifSayfa, $izinliSayfalar)): ?>

    <div class="container mt-20">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 col-lg-6">
                <!-- PROJE İPTAL UYARISI -->
                <div class="alert alert-danger text-center mb-4" role="alert">
                    <h5 class="alert-heading mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ⚠️ PROJE İPTAL EDİLMİŞTİR ⚠️
                    </h5>
                    <p class="mb-2">Bu proje iptal edilmiştir.</p>
                    <p class="mb-0"><strong>Giriş yapmak ve kayıt olmak yasaktır.</strong></p>
                </div>

                <div class="card shadow-lg">
                    <div class="card-body text-center p-5">
                        <img src="<?= site_url('/img/bsd_logo.png') ?>" alt="Logo" class="mb-4" style="max-width: 150px;">
                        <h4 class="mb-4">Oturum Açın</h4>
                        <p class="text-muted mb-4">Devam etmek için lütfen giriş yapın</p>

                        <!-- PROJE İPTAL NEDENİYLE DEVRE DIŞI BUTONLAR -->
                        <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                            <i class="fas fa-ban me-2"></i>Giriş Yap (Yasak)
                        </button>

                        <p class="mb-0 text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Sistem bakımdadır. Daha fazla bilgi için yönetici ile iletişime geçin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php exit(); ?>
    <?php else: ?>
    <div class="bsd-content">
<?php endif; ?>

<?php include 'script.php'; ?>
</body>
</html>
