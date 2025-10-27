// Menü yükleyici
$(document).ready(function () {
    // Menü öğesini bul
    var menuElement = $('.user-menu');

    // Menü PHP dosyasının yolu (Base URL'den geliyor)
    var menuPath = '/assets/src/include/navigasyon.php';

    // Hata mesajını göstermek için bir element oluştur
    var errorElement = $('<div class="menu-error" style="color: red; font-weight: bold; margin-top: 10px;"></div>');

    // AJAX ile PHP dosyasını çağır ve sonucu menüye yerleştir
    $.get(menuPath, function (data) {
        menuElement.html(data);
    }).fail(function () {
        // Hata durumunda hata mesajını ekrana yaz
        errorElement.text('Menü yüklenirken bir hata oluştu. Path: ' + menuPath);
        menuElement.after(errorElement);
    });
});