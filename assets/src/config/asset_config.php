<?php

/**
 * Asset Management Configuration
 */

return [
    'cdn_base_url' => 'https://cdn.jsdelivr.net/gh/buraksariguzel81/bsdevrepo@main',

    'supported_formats' => [
        'music' => ['mp3', 'wav', 'ogg', 'm4a', 'flac'],
        'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        'fonts' => ['woff2', 'woff', 'ttf', 'otf']
    ],

    'asset_types' => [
        'music' => [
            'icon' => '🎵',
            'title' => 'Music Management System',
            'description' => 'Müzik klasörünü otomatik tarar ve JSON dosyasını günceller'
        ],
        'hafizaoyunu' => [
            'icon' => '🧠',
            'title' => 'Hafıza Oyunu Management System',
            'description' => 'Hafıza oyunu resimlerini otomatik tarar ve JSON dosyasını günceller'
        ],
        'font' => [
            'icon' => '🖋️',
            'title' => 'Font Management System',
            'description' => 'Font klasörünü otomatik tarar ve CSS dosyasını günceller'
        ]
    ],

    'file_size_limits' => [
        'music' => 50 * 1024 * 1024, // 50MB
        'images' => 10 * 1024 * 1024, // 10MB
        'fonts' => 5 * 1024 * 1024    // 5MB
    ]
];
