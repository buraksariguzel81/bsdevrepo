<?php
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
include 'web_items.php';

function kategoriBasligi($key) {
    return ucwords(str_replace('_', ' ', $key));
}
?>

<!doctype html>
<html lang="tr">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Web Uygulaması</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
   <style>
      /* Başlıklar */
      .kategori-baslik {
         background-color: #f8f9fa; /* Bootstrap’in light background’u */
         color: #333; /* Koyu gri okunaklı renk */
         font-weight: 600;
         text-transform: uppercase;
         padding: 0.5rem 1rem;
         border-left: 4px solid #0d6efd; /* Bootstrap primary renk vurgusu */
         margin-top: 1.5rem;
         margin-bottom: 0.5rem;
         border-radius: 0.25rem;
         font-size: 0.9rem;
      }

      /* Linkler */
      .list-group-item-action {
         border-radius: 0.375rem;
         color: #0d6efd;
         font-weight: 500;
         padding: 0.75rem 1rem;
         transition: background-color 0.2s ease, color 0.2s ease;
      }

      .list-group-item-action:hover, 
      .list-group-item-action:focus {
         background-color: #e7f1ff;
         color: #0a58ca;
         text-decoration: none;
      }

      /* İkon */
      .list-group-item-action i {
         font-size: 1.5rem;
         margin-bottom: 0.25rem;
      }
   </style>
</head>
<body class="bg-white p-3">

<div class="container">

   <?php foreach ($webkutuphanesi as $kategori => $icerikler): ?>
      <div class="kategori-baslik"><?= kategoriBasligi($kategori) ?></div>

      <div class="list-group mb-4">
         <?php foreach ($icerikler as $icerik): ?>
            <a href="<?= $icerik['link'] ?>" target="_blank" class="list-group-item list-group-item-action d-flex flex-column align-items-center text-center gap-1">
               <i class="<?= $icerik['ikon'] ?>"></i>
               <span><?= $icerik['baslik'] ?></span>
            </a>
         <?php endforeach; ?>
      </div>
   <?php endforeach; ?>

</div>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
