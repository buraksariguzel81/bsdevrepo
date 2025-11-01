# Font Management System

Bu sistem font klasöründeki fontları otomatik olarak tarar ve CSS dosyasını günceller.

## Nasıl Çalışır?

1. **Otomatik Tarama**: `font/` klasöründeki tüm alt klasörleri tarar
2. **Dosya Tespiti**: Her font klasöründeki `.woff` ve `.woff2` dosyalarını bulur
3. **CSS Oluşturma**: Doğru dosya isimleri ile `@font-face` tanımları oluşturur
4. **Güncelleme**: `font.css` dosyasını otomatik olarak günceller

## Kullanım

### Manuel Güncelleme
```bash
php font/generate_css.php
```

### Web Arayüzü
`font/font.php` dosyasını tarayıcıda açarak fontları görüntüleyebilir ve CSS'i güncelleyebilirsiniz.

## Özellikler

✅ **Otomatik Font Tespiti**: Yeni font klasörleri eklendiğinde otomatik algılar
✅ **Doğru Dosya İsimleri**: Font dosyalarının gerçek isimlerini kullanır
✅ **Çift Format Desteği**: Hem `.woff` hem `.woff2` formatlarını destekler
✅ **GitHub Actions Entegrasyonu**: Değişikliklerde otomatik cache temizleme
✅ **Responsive Tasarım**: Font önizlemesi için modern arayüz

## Desteklenen Fontlar

- Alba
- Argor_Biw_Scaqh
- BlackOpsOne
- Brolink
- ethnocentric
- Langdon
- LeviBrush
- Moonstar
- Nabla
- PottaOne
- Righteous
- Skranji
- SquimFont
- Turkish_Participants
- UnicornPop

## CSS Kullanımı

```css
.Alba {
    font-family: 'Alba', sans-serif;
}
```

## GitHub Actions

Her font değişikliğinde jsDelivr cache'i otomatik temizlenir.

## Son Güncelleme

2025-10-27 - Font management sistemi kuruldu
