<?php

// Asset Management Menü Öğeleri

$menu_items['assets'] = [
  'url' => '#',
  'icon' => 'fas fa-folder-open',
  'text' => 'Asset Yönetimi',
  'submenu' => [
    [
      'url' => '/font/font.php',
      'icon' => 'fas fa-font',
      'text' => 'Font Yönetimi'
    ],
    [
      'url' => '/music/music.php',
      'icon' => 'fas fa-music',
      'text' => 'Müzik Yönetimi'
    ],
    [
      'url' => '/hafizaoyunu/hafizaoyunu.php',
      'icon' => 'fas fa-gamepad',
      'text' => 'Hafıza Oyunu'
    ]
  ]
];

$menu_items['tools'] = [
  'url' => '#',
  'icon' => 'fas fa-tools',
  'text' => 'Araçlar',
  'submenu' => [
    [
      'url' => '/page_cdn_scanner.php',
      'icon' => 'fas fa-search',
      'text' => 'CDN Tarayıcı'
    ],
    [
      'url' => '/font/font_upload.php',
      'icon' => 'fas fa-upload',
      'text' => 'Font Yükleme'
    ]
  ]
];

$menu_items['web'] = [
  'url' => '/web/web.php',
  'icon' => 'fas fa-globe',
  'text' => 'Web Yönetimi'
];

if (isset($additional_menu_items) && is_array($additional_menu_items)) {
    $menu_items = array_merge($menu_items, $additional_menu_items);
}
