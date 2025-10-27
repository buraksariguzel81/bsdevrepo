<?php

include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');

?>

<!DOCTYPE html>
<html lang="tr">
<head>

  <title>Document</title>

</head>
<body>
  
  <h5><i class="fas fa-tachometer-alt"></i> Panel</h5>

  <nav class="menu-container">
   
   <?php if($rol_id == 1): ?>
   <a href="kurucupanel/kurucupanel.php" class="menu-item bsd-navlink1">
     <i class="fas fa-crown"></i> Kurucu
   </a>
   <?php endif; ?>
   
   <?php if($rol_id == 1 || $rol_id == 3): ?>
   <a href="adminpanel/adminpanel.php" class="menu-item bsd-navlink1">
     <i class="fas fa-user-shield"></i> Admin 
   </a>
   <?php endif; ?>
   
   <?php if($cinsiyet == 'erkek'): ?>
   <a href="erkekpanel/erkekpanel.php" class="menu-item bsd-navlink1">
     <i class="fas fa-male"></i> Erkek
   </a>
   <?php endif; ?>
   
   <?php if($cinsiyet == 'kadin'): ?>
   <a href="kadinpanel/kadinpanel.php" class="menu-item bsd-navlink1">
     <i class="fas fa-female"></i> Kadin 
   </a>
   <?php endif; ?>
  
   
  </nav>
  
  
<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/src/include/footer.php"; ?>

</body>
</html>
