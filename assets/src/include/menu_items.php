<?php

// kullanici adi buraksariguzeldev ise

$menu_items['font'] = [
  'url' => '../../../font/font.php',
  'icon' => 'fas fa-font',
  'text' => 'font'
];


$menu_items['web'] = [
  'url' => '../../../web/web.php',
  'icon' => 'fas fa-web',
  'text' => 'web'
];



if (isset($additional_menu_items) && is_array($additional_menu_items)) {
    $menu_items = array_merge($menu_items, $additional_menu_items);
}
