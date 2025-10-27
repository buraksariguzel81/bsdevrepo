# 🎵 Müzik CSS Sınıfları

Bu sistem otomatik olarak müzik dosyalarınızdan CSS sınıfları ve oynatıcılar oluşturur.

## 📁 Dosyalar

- `music.php` - Ana sayfa ve CSS güncelleme
- `music.css` - Oluşturulan CSS sınıfları
- `generate_css.php` - CSS oluşturma scripti
- `*.mp3`, `*.wav` - Müzik dosyaları

## 🚀 Kullanım

### 1. CSS Dosyasını Bağlama
```html
<link rel="stylesheet" href="music/music.css">
```

### 2. Müzik Sınıflarını Kullanma
```html
<!-- Müzik oynatıcı butonu -->
<div class="music-askinolayim" onclick="playMusic('askinolayim')"></div>

<!-- JavaScript ile oynatma -->
<script>
function playMusic(musicName) {
    // Tüm sesleri durdur
    document.querySelectorAll('audio').forEach(audio => {
        audio.pause();
        audio.currentTime = 0;
    });

    // Seçili müziği çal
    const audio = document.querySelector('.audio-' + musicName);
    if (audio) audio.play();
}
</script>
```

## 📋 Mevcut Müzikler

| Müzik | CSS Sınıfı | CDN URL |
|-------|------------|---------|
| Aşkın Olayım | `.music-askinolayim` | [CDN Link](https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/askinolayim.mp3) |
| Gaza Getiren Müzik | `.music-gazagetirenmuzik` | [CDN Link](https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/gazagetirenmuzik.mp3) |
| Kara Sevda Zil Sesi | `.music-KARA_SEVDA-Emir_Kozcuoğlu_Telefon_Zil_Sesi` | [CDN Link](https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/KARA%20SEVDA-Emir%20Kozcuoğlu%20Telefon%20Zil%20Sesi.mp3) |
| M1 40 | `.music-M1_40` | [CDN Link](https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/M1_40.mp3) |
| M3 40 | `.music-M3_40` | [CDN Link](https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/M3_40.wav) |
| Mustafa Cihat - Fizani | `.music-Mustafa_Cihat-_Fizani___2019_Yeni_Tamamı` | [CDN Link](https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/Mustafa%20Cihat-%20Fizani%20_%202019%20Yeni%20Tamamı.mp3) |
| Over the Horizon | `.music-Over_the_Horizon` | [CDN Link](https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/Over_the_Horizon.mp3) |

## 🔧 CSS Güncelleme

Yeni müzik dosyaları eklediğinizde:

1. `music.php` sayfasına gidin
2. "🔄 CSS Güncelle" butonuna tıklayın
3. Yeni CSS sınıfları otomatik oluşturulacak

## 📝 HTML Örnekleri

### Basit Oynatıcı
```html
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="music/music.css">
</head>
<body>
    <div class="music-askinolayim" onclick="playMusic('askinolayim')"></div>

    <!-- Hidden audio elements -->
    <audio class="audio-askinolayim" src="https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/askinolayim.mp3"></audio>

    <script>
        function playMusic(musicName) {
            document.querySelectorAll('audio').forEach(audio => {
                audio.pause();
                audio.currentTime = 0;
            });
            document.querySelector('.audio-' + musicName).play();
        }
    </script>
</body>
</html>
```

### Müzik Listesi
```html
<div class="music-grid">
    <div class="music-card">
        <h3>Aşkın Olayım</h3>
        <div class="music-askinolayim" onclick="playMusic('askinolayim')"></div>
    </div>
    <div class="music-card">
        <h3>Gaza Getiren Müzik</h3>
        <div class="music-gazagetirenmuzik" onclick="playMusic('gazagetirenmuzik')"></div>
    </div>
</div>
```

### Direkt Audio Kullanımı
```html
<audio controls>
    <source src="https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/askinolayim.mp3" type="audio/mpeg">
</audio>
```

## 🎨 CSS Özellikleri

- **Boyut:** 100x100px
- **Hover Efekti:** 1.1x büyüme
- **Play Butonu:** SVG tabanlı müzik ikonu
- **Border:** 2px solid #eee
- **Border Radius:** 8px
- **Cursor:** pointer

## 📂 Yeni Müzik Ekleme

1. Müzik dosyasını `music/` klasörüne koyun
2. Desteklenen formatlar: MP3, WAV, OGG, M4A, AAC
3. `music.php` sayfasından CSS'i güncelleyin
4. Yeni sınıf otomatik oluşacak: `.music-[dosya-adı]`

## 🔗 CDN Kullanımı

Tüm müzik dosyaları CDN üzerinden erişilebilir:
```
https://cdn.jsdelivr.net/gh/buraksariguzel81/buraksariguzeldev@main/music/[dosya-adı]
```

## 📱 Responsive Tasarım

CSS sınıfları responsive olarak çalışır ve farklı ekran boyutlarına uyum sağlar.
