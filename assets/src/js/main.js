document.addEventListener('DOMContentLoaded', function() {
    console.log('Document is ready');

    // Test amaçlı bir bilgi yazdırma
    const testDiv = document.createElement('div');
    testDiv.style.color = 'red';
    testDiv.textContent = 'JavaScript başarıyla yüklendi!';
    document.body.appendChild(testDiv);

    // JavaScript dosyalarının yollarını tanımlama
    const jsUrls = [
        'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', // jQuery CDN
        '../../bsd_yonetim/src/js/bsd_form.js',
        '../../bsd_yonetim/src/kontrol/bsd_kontrol.js'
    ];

    // Script yükleme fonksiyonu
    function loadScript(url) {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = url;
            script.onload = () => {
                console.log(`Loaded: ${url}`);
                resolve();
            };
            script.onerror = () => {
                console.error(`Error loading: ${url}`);
                reject(new Error(`Failed to load script at ${url}`));
            };
            document.body.appendChild(script);
        });
    }

    // Scriptleri sırayla yükleme
    async function loadScripts() {
        try {
            for (const url of jsUrls) {
                await loadScript(url);
            }
            console.log('All scripts loaded successfully');
            // Tüm scriptler yüklendikten sonra yapılacak işlemler
            initializeApp();
        } catch (error) {
            console.error('Error loading scripts:', error);
        }
    }

    // Tüm scriptler yüklendikten sonra çalışacak fonksiyon
    function initializeApp() {
        // jQuery kullanımını kontrol et
        if (typeof jQuery !== 'undefined') {
            console.log('jQuery is loaded');
            
            // jQuery ile bir işlem yap
            $('body').append('<p>jQuery is working!</p>');
        } else {
            console.error('jQuery is not loaded');
        }

        // Diğer scriptlerin fonksiyonlarını çağır
        if (typeof bsdFormFunction === 'function') {
            bsdFormFunction();
        }
        if (typeof bsdKontrolFunction === 'function') {
            bsdKontrolFunction();
        }
    }

    // Script yükleme işlemini başlat
    loadScripts();
});