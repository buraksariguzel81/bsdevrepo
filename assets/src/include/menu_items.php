<?php
//giris veya yapılmamış durum
if (!$kullanici_adi ||  $kullanici_adi)  { 
        $menu_items['Web kütüphanesi'] = ['url' => '../../../web/web.php',
            'icon' => 'fas fa-book', 'text' => 'Kutuphane'];
        // Forum menü öğesi (herkese açık)
        $menu_items['Forum'] = [
                'url' => '../../../forum/forum.php',
                'icon' => 'fas fa-comments',
                'text' => 'Forum'
        ];
}

// kullanici adi buraksariguzeldev ise

        $menu_items['Web kütüphanesi'] = ['url' => '../../../web/web.php',
      'icon' => 'fas fa-book', 'text' => 'Kutuphane'];
  


// Kullanıcı giriş yaptıysa
if ($kullanici_adi) { 
    $menu_items['ayarlar'] = [
        'url' => '#', 'icon' => 'fas fa-cog', 'text' => 'Ayarlar',
        'submenu' => [
            'hesap' => ['url' => 'auth/ayar_islemleri/guvenlik.php', 'icon' => 'fas fa-user-shield', 'text' => 'Hesap'],
            'profil düzenle' => ['url' => '../../../auth/ayar_islemleri/profil_duzenle.php', 'icon' => 'fas fa-user-edit', 'text' => 'Profili Düzenle'],
            'profil gizlilik' => ['url' => '../../../auth/ayar_islemleri/hesap_yonetimi.php', 'icon' => 'fas fa-user-lock', 'text' => 'Hesabı Düzenle']
        ]
    ];
}

if ($kullanici_adi) {
    $menu_items['Oyunlar'] = [
        'url' => '#',
        'icon' => 'fas fa-gamepad',  // Daha oyunsal ikon
        'text' => 'Oyun Dünyası',    // Daha etkileyici başlık
        'submenu' => [
            [
                'url' => 'game/lego_oyunu.php',
                'icon' => 'fas fa-brick-wall',  // Lego temalı ikon
                'text' => 'Lego Macerası'       // Daha şiirsel isim
            ],
         
        ],
    ];
}



// Her bir rolü kontrol et
foreach ($rol_idler as $rol) {
    // Kurucu (rol 1) menüsü
    if ($rol == 1) {
        $menu_items['kurucu'] = [
            'url' => '#',
            'icon' => 'fas fa-cogs',
            'text' => 'kurucu',
            'submenu' => [
                'hesap' => [
                    'url' => '../../../panel/kurucupanel/kurucupanel.php',
                    'icon' => 'fas fa-cogs',
                    'text' => 'kurucu sayfasi'
                ],
                'Private' => [
                    'url' => '../../../private/private.php',
                    'icon' => 'fas fa-lock',
                    'text' => 'Private sayfasi'
                ],
                'Hizli Dosya' => [
                    'url' => '../../../hizlidosya.php',
                    'icon' => 'fas fa-file-alt',
                    'text' => 'Hizli dosya'
                ],
                 
            ]
        ];
    }

    // Admin (rol 3) menüsü
    if ($rol == 3) {
        $menu_items['Admin_panel'] = [
            'url' => '#',
            'icon' => 'fas fa-user-shield',
            'text' => 'Admin',
            'submenu' => [
                'hesap' => [
                    'url' => '../../../panel/adminpanel/adminpanel.php',
                    'icon' => 'fas fa-cogs',
                    'text' => 'Admin panel'
                ],
                'Arsiv' => [
                    'url' => '../../../archive/archive.php',
                    'icon' => 'fas fa-archive',
                    'text' => 'Arsiv'
                ],
                'Kullanici Yonetimi' => [
                    'url' => '../../../auth/auth.php',
                    'icon' => 'fas fa-user-lock',
                    'text' => 'Kullanici Yonetimi'
                ]
            ]
        ];
    }
}

if (isset($additional_menu_items) && is_array($additional_menu_items)) {
    $menu_items = array_merge($menu_items, $additional_menu_items);
}
