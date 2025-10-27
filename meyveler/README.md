# 🍎 Meyve CSS Sınıfları

Bu sistem otomatik olarak meyve resimlerinizden CSS sınıfları oluşturur.

## 📁 Dosyalar

- `meyveler.php` - Ana sayfa ve CSS güncelleme
- `meyveler.css` - Oluşturulan CSS sınıfları
- `generate_css.php` - CSS oluşturma scripti
- `test.html` - Test sayfası
- `*.png`, `*.jpg` - Meyve resimleri

## 🚀 Kullanım

### 1. CSS Dosyasını Bağlama
```html
<link rel="stylesheet" href="meyveler/meyveler.css">
```

### 2. Meyve Sınıflarını Kullanma
```html
<!-- Normal boyut -->
<p class="meyve-incir"></p>
<p class="meyve-karpuz"></p>

<!-- Büyük boyut -->
<p class="meyve-incir-large"></p>
```

## 📋 Mevcut Sınıflar

| Meyve | CSS Sınıfı | Büyük Boyut |
|-------|------------|-------------|
| Avokado | `.meyve-avokado` | `.meyve-avokado-large` |
| İncir | `.meyve-incir` | `.meyve-incir-large` |
| Karpuz | `.meyve-karpuz` | `.meyve-karpuz-large` |
| Kavun | `.meyve-kavun` | `.meyve-kavun-large` |
| Kivi | `.meyve-kivi` | `.meyve-kivi-large` |
| Vişne | `.meyve-vişne` | `.meyve-vişne-large` |
| Üzüm | `.meyve-üzüm` | `.meyve-üzüm-large` |

## 🔧 CSS Güncelleme

Yeni meyve resimleri eklediğinizde:

1. `meyveler.php` sayfasına gidin
2. "🔄 CSS Güncelle" butonuna tıklayın
3. Yeni CSS sınıfları otomatik oluşturulacak

## 📝 HTML Örnekleri

### Basit Kullanım
```html
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="meyveler/meyveler.css">
</head>
<body>
    <p class="meyve-incir"></p>
    <p class="meyve-karpuz"></p>
</body>
</html>
```

### Buton Olarak
```html
<button style="background: none; border: none;">
    <p class="meyve-incir"></p>
    İncir Seç
</button>
```

### Liste İçinde
```html
<ul>
    <li><p class="meyve-avokado"></p> Avokado</li>
    <li><p class="meyve-incir"></p> İncir</li>
    <li><p class="meyve-karpuz"></p> Karpuz</li>
</ul>
```

## 🎨 CSS Özellikleri

- **Boyut:** 100x100px (normal), 200x200px (large)
- **Hover Efekti:** 1.1x büyüme
- **Border:** 2px solid #eee (normal), 3px solid #ddd (large)
- **Border Radius:** 8px (normal), 12px (large)
- **Background:** contain, no-repeat, center

## 📂 Yeni Meyve Ekleme

1. Meyve resmini `meyveler/` klasörüne koyun
2. Desteklenen formatlar: PNG, JPG, JPEG, GIF, WEBP
3. `meyveler.php` sayfasından CSS'i güncelleyin
4. Yeni sınıf otomatik oluşacak: `.meyve-[dosya-adı]`
