$(document).ready(function() {
    $(".copy-btn").click(function() {
        // Kopyalanacak kod bloğunu al
        var kodBlogu = $(this).siblings("pre").text();
        
        // Kopyalama işlemini gerçekleştir
        navigator.clipboard.writeText(kodBlogu).then(function() {
            // Bildirim göster
            var bildirim = $("#copyNotification");
            bildirim.addClass("show");
            
            // 3 saniye sonra bildirimi gizle
            setTimeout(function() {
                bildirim.removeClass("show");
            }, 3000);
        }, function() {
            console.error("Kod kopyalanamadı.");
        });
    });
});
