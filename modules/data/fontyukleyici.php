<?php
// Navigasyonu ve rol kontrolünü dahil et
include($_SERVER['DOCUMENT_ROOT'] . '/assets/src/include/navigasyon.php');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Font Önizleme | BSD Soft</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts CSS -->
  <link href="/assets/src/css/googlefont.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .font-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border: none; border-radius: 0.75rem; overflow: hidden; height: 100%; position: relative; }
    .font-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important; }
    .font-preview { font-size: 1.1rem; line-height: 1.6; min-height: 80px; display: flex; align-items: center; justify-content: center; text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 0.5rem; margin-bottom: 1rem; }
    .font-name { font-size: 0.9rem; color: #6c757d; font-family: 'Courier New', monospace; word-break: break-word; }
    .copy-btn { opacity: 0.7; transition: all 0.2s ease; padding: 0.15rem 0.4rem; font-size: 0.75rem; margin: 0.1rem; }
    .copy-btn:hover { opacity: 1; transform: scale(1.02); }
    .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 1100; }
  </style>
</head>
<body>

<div class="container py-4">


<div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                        <i class="fas fa-file-alt fs-4"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold text-dark">Font Önizleme</h5>
                        <p class="text-muted small mb-0">Toplam <span class="badge bg-primary" id="totalFonts">0</span> farklı yazı tipi bulundu</p>
                    </div>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Yazdır
                    </button>
                </div>
            </div>
        </div>
    </div>

  <div class="row g-4 mb-5">
    <?php
    // Google Fonts CSS dosyasını oku
    $googleFontsCssPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/src/css/googlefont.css';
    if(file_exists($googleFontsCssPath)) {
        $googleFontsCss = file_get_contents($googleFontsCssPath);

        // @import ifadelerini bul
        preg_match_all('/@import url\([^)]+\)/', $googleFontsCss, $imports);

        // Font ailelerini çıkar
        $fontFamilies = [];
        foreach($imports[0] as $import) {
            if(preg_match('/family=([^&:]+)/', $import, $matches)) {
                $fontName = str_replace('+', ' ', $matches[1]);
                $fontId = strtolower(str_replace(' ', '-', $fontName));
                $fontFamilies[$fontId] = ['name' => $fontName, 'import' => $import];
            }
        }

        // CSS'deki font sınıflarını bul
        preg_match_all('/\.([a-zA-Z0-9\-_]+)\s*\{/', $googleFontsCss, $matches);

        // Her font ailesi için kart oluştur
        foreach($fontFamilies as $fontId => $fontData) {
            $fontName = $fontData['name'];
            $fontClass = 'font-' . str_replace(' ', '-', strtolower($fontName));

            // Bu font ailesine ait sınıfları filtrele
            $fontClasses = [];
            foreach($matches[1] as $class) {
                if(strpos($class, strtolower(str_replace(' ', '-', $fontName))) === 0) {
                    $fontClasses[] = $class;
                }
            }

            if(empty($fontClasses)) continue;

            $exampleClass = $fontClasses[0];
            $fontClassesCount = count($fontClasses);

            echo <<<HTML
            <div class="col-12 col-md-6 col-lg-4">
              <div class="card font-card h-100 shadow-sm">
                <div class="card-body position-relative">
                  <div class="position-absolute top-0 end-0 p-2 d-flex gap-1">
                    <button class="btn btn-sm btn-outline-primary copy-btn" data-class="$exampleClass" data-bs-toggle="tooltip" title="Örnek sınıfı kopyala">
                      <i class="fas fa-copy me-1"></i> Örnek Kopyala
                    </button>
                  </div>
                  <div class="font-preview $exampleClass">
                    $fontName<br>
                    ABCÇDEFGĞHIİJKLMNOÖPRSŞTUÜVYZ<br>
                    abcçdefgğhıijklmnoöprsştuüvyz<br>
                    0123456789!?'"^+%&/()=?
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="mb-1">$fontName</h6>
                      <div class="font-name">.$exampleClass</div>
                    </div>
                    <div class="text-end">
                      <span class="badge bg-primary">$fontClassesCount Stil</span>
                    </div>
                  </div>
                  <div class="mt-2 small">
                    <div class="d-flex flex-wrap gap-1">
HTML;
            foreach ($fontClasses as $class) {
                echo <<<HTML
                      <div class="position-relative d-inline-block mb-1">
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-class="$class" data-bs-toggle="tooltip" title="Kopyala">
                          <i class="far fa-copy"></i> .$class
                        </button>
                      </div>
HTML;
            }
            echo <<<HTML
                    </div>
                  </div>
                </div>
              </div>
            </div>
HTML;
        }
    } else {
        echo '<div class="col-12"><div class="alert alert-danger">Google Fonts CSS bulunamadı.</div></div>';
    }
    ?>
  </div>

</div>

<!-- Toast Bildirimi -->
<div class="toast-container">
  <div id="toast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <i class="fas fa-check-circle me-2"></i> <span id="toast-message">Font sınıfı panoya kopyalandı!</span>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Kapat"></button>
    </div>
  </div>
</div>

<!-- Bootstrap JS ve Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toplam font sayısını güncelle
    const totalFonts = document.querySelectorAll('.font-card').length;
    document.getElementById('totalFonts').textContent = totalFonts;

    // Tooltip'leri başlat
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl){ 
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const toastEl = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });

    // Kopyalama işlemi
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const fontClass = this.getAttribute('data-class');
            try {
                // Eski yöntemle kopyalama dene
                const textArea = document.createElement('textarea');
                textArea.value = fontClass;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                let success = false;
                try {
                    success = document.execCommand('copy');
                    if (!success) throw new Error('execCommand failed');
                } catch (e) {
                    // Modern yöntemle dene
                    try {
                        await navigator.clipboard.writeText(fontClass);
                        success = true;
                    } catch (err) {
                        console.error('Clipboard API error:', err);
                        throw err;
                    }
                } finally {
                    document.body.removeChild(textArea);
                }
                
                if (success) {
                    toastMessage.textContent = `"${fontClass}" panoya kopyalandı!`;
                    toast.show();
                }
            } catch (err) {
                console.error('Kopyalama hatası:', err);
                // Kullanıcıya daha detaylı hata mesajı göster
                toastMessage.innerHTML = `Kopyalama başarısız: <br>${fontClass}<br><small>Metni elle kopyalayabilirsiniz</small>`;
                toast.show();
                
                // Hata durumunda kullanıcının metni seçmesini sağla
                const range = document.createRange();
                const selection = window.getSelection();
                const textNode = document.createTextNode(fontClass);
                document.body.appendChild(textNode);
                range.selectNodeContents(textNode);
                selection.removeAllRanges();
                selection.addRange(range);
                
                // 3 saniye sonra seçimi kaldır
                setTimeout(() => {
                    selection.removeAllRanges();
                    if (document.body.contains(textNode)) {
                        document.body.removeChild(textNode);
                    }
                }, 3000);
            }
        });
    });

    // Yenileme butonu
    document.getElementById('refreshBtn').addEventListener('click', function() {
        window.location.reload();
    });

    // Hata yönetimi
    window.addEventListener('error', function(e) {
        console.error('Hata oluştu:', e);
        toastMessage.textContent = 'Bir hata oluştu. Lütfen sayfayı yenileyin.';
        toast.show();
    });
});
</script>

</body>
</html>
