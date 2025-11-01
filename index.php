<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<!DOCTYPE html>
<html lang="tr">

<head>

  <title> buraksariguzeldev</title>


</head>

<body class="bg-light">

  <div class="container py-5">

    <div class="card shadow-sm rounded">
      <div class="card-body text-center">

        <?php if (!$kullanici_adi): ?>
          <!-- Giriş yapmamış kullanıcılar için giriş formu ve kayıt linki -->
          <h5 class="card-title text-primary mb-3">
            <i class="fas fa-home me-2"></i> Hoş Geldiniz kardesim
          </h5>
          <p class="text-muted mb-4 ">
            <strong>buraksariguzeldev.wuaze.com</strong> sitesine hoş geldiniz.
            Lütfen giriş yapın veya yeni bir hesap oluşturun.
          </p>

          <!-- Giriş Formu -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <h6 class="text-center mb-3"><i class="fas fa-sign-in-alt me-2"></i>Giriş Yap</h6>
              <form method="post" action="auth/giris_islemleri/giris.php">
                <div class="mb-2">
                  <input type="text" class="form-control form-control-sm" name="kullanici" placeholder="Kullanıcı Adı" required>
                </div>
                <div class="mb-2">
                  <input type="password" class="form-control form-control-sm" name="sifre" placeholder="Şifre" required>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="checkbox" name="benihatirla" id="benihatirla">
                  <label class="form-check-label form-check-label-sm" for="benihatirla">
                    Beni Hatırla
                  </label>
                </div>
                <button type="submit" name="giris" class="btn btn-primary btn-sm w-100">
                  <i class="fas fa-sign-in-alt me-1"></i> Giriş Yap
                </button>
              </form>

            </div>



            <div class="alert alert-danger d-inline-block px-4 py-2 mt-3">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>⚠️ PROJE İPTAL EDİLMİŞTİR ⚠️</strong><br>
              Bu proje iptal edilmiştir. Giriş yapmak ve kayıt olmak yasaktır.
            </div>

          <?php else: ?>
            <!-- Giriş yapmış kullanıcılar için mesaj -->
            <h5 class="card-title text-success mb-3">
              <i class="fas fa-user-check me-2 MagicVintageRegular"></i> Hoş geldiniz,
              <span style="color: <?= $kullanici_color ?>;">
                <?= htmlspecialchars($kullanici_adi); ?>
              </span>
            </h5>
            <p class="text-muted">
              Sisteme başarıyla giriş yaptınız.<br>
              Artık özel içeriklere erişebilirsiniz.
            </p>

            <!-- Asset İstatistikleri -->
            <div class="row mt-4 mb-4">
              <div class="col-12">
                <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Asset İstatistikleri</h6>
              </div>
              <?php
              // Font sayısını al
              $font_count = 0;
              if (is_dir('font')) {
                $font_folders = array_filter(scandir('font'), function ($item) {
                  return is_dir('font/' . $item) && !in_array($item, ['.', '..']);
                });
                $font_count = count($font_folders);
              }

              // Müzik sayısını al
              $music_count = 0;
              if (is_dir('music')) {
                $music_files = array_filter(scandir('music'), function ($item) {
                  $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                  return is_file('music/' . $item) && in_array($extension, ['mp3', 'wav', 'ogg', 'm4a', 'flac']);
                });
                $music_count = count($music_files);
              }

              // Hafıza oyunu resim sayısını al
              $game_count = 0;
              if (is_dir('hafizaoyunu')) {
                $image_files = array_filter(scandir('hafizaoyunu'), function ($item) {
                  $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                  return is_file('hafizaoyunu/' . $item) && in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                });
                $game_count = count($image_files);
              }
              ?>
              <div class="col-md-4">
                <div class="alert alert-primary text-center mb-2">
                  <h4 class="mb-1"><?= $font_count ?></h4>
                  <small>Font Ailesi</small>
                </div>
              </div>
              <div class="col-md-4">
                <div class="alert alert-success text-center mb-2">
                  <h4 class="mb-1"><?= $music_count ?></h4>
                  <small>Müzik Dosyası</small>
                </div>
              </div>
              <div class="col-md-4">
                <div class="alert alert-info text-center mb-2">
                  <h4 class="mb-1"><?= $game_count ?></h4>
                  <small>Oyun Resmi</small>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <h6 class="mb-3"><i class="fas fa-cogs me-2"></i>Asset Yönetim Sistemi</h6>
              <div class="row g-2">
                <div class="col-md-4">
                  <div class="card border-primary">
                    <div class="card-body text-center p-3">
                      <h1 class="text-primary mb-2">🖋️</h1>
                      <h6 class="card-title">Font Yönetimi</h6>
                      <p class="card-text small text-muted">Font dosyalarını yönet ve CSS oluştur</p>
                      <a href="/font/font.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-font me-1"></i> Yönet
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card border-success">
                    <div class="card-body text-center p-3">
                      <h1 class="text-success mb-2">🎵</h1>
                      <h6 class="card-title">Müzik Yönetimi</h6>
                      <p class="card-text small text-muted">Müzik dosyalarını yönet ve JSON oluştur</p>
                      <a href="/music/music.php" class="btn btn-success btn-sm">
                        <i class="fas fa-music me-1"></i> Yönet
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card border-info">
                    <div class="card-body text-center p-3">
                      <h1 class="text-info mb-2">🧠</h1>
                      <h6 class="card-title">Hafıza Oyunu</h6>
                      <p class="card-text small text-muted">Oyun resimlerini yönet ve JSON oluştur</p>
                      <a href="/hafizaoyunu/hafizaoyunu.php" class="btn btn-info btn-sm">
                        <i class="fas fa-gamepad me-1"></i> Yönet
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-4">
                <h6 class="mb-3"><i class="fas fa-tools me-2"></i>Diğer Araçlar</h6>
                <div class="btn-group-vertical w-100" role="group">
                  <a href="/page_cdn_scanner.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-search me-2"></i> CDN Tarayıcı
                  </a>
                  <a href="/font/font_upload.php" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-upload me-2"></i> Font Yükleme
                  </a>
                </div>
              </div>
            </div>
          <?php endif; ?>
          </div>
      </div>

      <!-- Footer -->
      <div class="text-center mt-4">
        <small class="text-muted">
          <i class="fas fa-code me-1"></i>
          Asset Management System v2.0 |
          <i class="fas fa-calendar me-1"></i>
          <?= date('Y') ?> buraksariguzeldev
        </small>
      </div>

    </div>

</body>

</html>