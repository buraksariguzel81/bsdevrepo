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
        <?php endif; ?>
