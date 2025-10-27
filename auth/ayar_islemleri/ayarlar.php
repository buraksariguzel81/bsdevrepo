<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<!DOCTYPE html>
<html lang="tr">
<head>

  <title> kullanıcı ayarları</title>
  
</head>
<body>
  

  <h5><i class="fas fa-cog"></i> Ayarlar</h5>
  
<nav class="menu-container">
    <a href="guvenlik.php" class="menu-item bsd-navlink1"><i class="fas fa-shield-alt"></i> Güvenlik Ayarları</a>
  
  <a href="#" class="menu-item bsd-navlink1"><i class="fas fa-bell"></i> Bildirim Ayarları</a>
  

  
  <a href="hesap_yonetimi.php" class="menu-item bsd-navlink1"><i class="fas
  fa-paint-brush"></i> hesap yönetim</a>
  
  <a href="profil_duzenle.php" class="menu-item bsd-navlink1"><i class="fas
  fa-paint-brush"></i> Profil düzenle</a>
  
    <a href="tema.php" class="menu-item bsd-navlink1"><i class="fas
  fa-paint-brush"></i> tema </a>
  
 
  
  
</nav>
  
 
  <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>
  
</body>
</html>
