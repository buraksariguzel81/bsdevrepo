$(document).ready(function () {
    // Menü öğesini bul
    var FooterElement = $('.user-Footer');

    // Menü PHP dosyasının yolu (Base URL'den geliyor)
    var FooterPath = '/assets/src/include/footer.php';

    // Hata mesajını göstermek için bir element oluştur
    var errorElement = $('<div class="Footer-error" style="color: red; font-weight: bold; margin-top: 10px;"></div>');

    // AJAX ile PHP dosyasını çağır ve sonucu menüye yerleştir
    $.get(FooterPath, function (data) {
        FooterElement.html(data);
        positionFooter();  // Footer yüklendikten sonra konumlandırma fonksiyonunu çalıştır
    }).fail(function () {
        // Hata durumunda hata mesajını ekrana yaz
        errorElement.text('Menü yüklenirken bir hata oluştu. Path: ' + FooterPath);
        FooterElement.after(errorElement);
    });

    // Footer'ı sayfanın altına yerleştirme fonksiyonu
    function positionFooter() {
        var footerHeight = FooterElement.outerHeight();  // Footer yüksekliğini al
        var windowHeight = $(window).height();  // Pencere yüksekliğini al
        var bodyHeight = $('body').height();  // Sayfa içeriğinin toplam yüksekliği

        // Eğer sayfa içeriği pencere yüksekliğinden küçükse, footer'ı body bitiminin üstüne yerleştir
        if (bodyHeight + footerHeight < windowHeight) {
            FooterElement.css({
                'position': 'absolute',
                'bottom': '0',
                'width': '100%',
                'left': '0'
            });
        } else {
            FooterElement.css({
                'position': 'relative',
                'bottom': 'auto',
                'left': '0'
            });
        }
    }

    // Sayfa boyutu değiştiğinde footer'ın konumunu güncelle
    $(window).resize(function () {
        positionFooter();
    });
});