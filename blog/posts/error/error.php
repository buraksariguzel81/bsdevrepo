<?php






 
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>


<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../../bsd_yonetim/src/css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Hata Sayfaları</title>

</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="card shadow-sm rounded">
      <div class="card-body">

        <!-- Başlık -->
        <h5 class="card-title mb-4 text-danger text-center">
          <i class="fas fa-exclamation-triangle me-2"></i> Hata Sayfaları
        </h5>

        <!-- Hata Kodları Menü -->
        <div class="row g-3">
          <?php
          $hatalar = [
            '400' => 'fa-exclamation-circle',
            '401' => 'fa-lock',
            '402' => 'fa-hand-holding-usd',
            '403' => 'fa-ban',
            '404' => 'fa-question-circle',
            '405' => 'fa-times-circle',
            '406' => 'fa-exclamation-circle',
            '407' => 'fa-user-lock',
            '408' => 'fa-hourglass-end',
            '409' => 'fa-exchange-alt',
            '410' => 'fa-unlink',
            '500' => 'fa-server'
          ];
          foreach ($hatalar as $kod => $ikon):
          ?>
            <div class="col-md-4 col-sm-6">
              <a href="<?= $kod ?>.php" class="btn btn-outline-secondary w-100 text-start">
                <i class="fas <?= $ikon ?> me-2"></i> <?= $kod ?>
              </a>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </div>

  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
</body>

</html>