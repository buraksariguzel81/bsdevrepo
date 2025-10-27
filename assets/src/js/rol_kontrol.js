// rol_kontrol.js
function rolKontrol(gerekliRolId) {
    $('body').css('visibility', 'hidden'); // Sayfa içeriğini gizle, ama yer kaplamasına izin ver

    $.ajax({
        url: '../../../bsd_yonetim/src/php/rol_kontrol_api.php',
        method: 'GET',
        data: { rol_id: gerekliRolId },
        dataType: 'json',
        success: function(response) {
            if (!response.yetki) {
                window.location.href = '../../error/401.php';
            } else {
                // Kullanıcının yetkisi var, sayfanın içeriğini göster
                $('body').css('visibility', 'visible');
            }
        },
        error: function() {
            window.location.href = '../../../error/500.php';
        }
    });
}

$(document).ready(function() {
    var gerekliRolId = $('meta[name="gerekli-rol-id"]').attr('content');
    if (gerekliRolId) {
        rolKontrol(parseInt(gerekliRolId));
    } else {
        console.error('Gerekli rol ID bulunamadı.');
        $('body').css('visibility', 'visible'); // Hata durumunda içeriği göster
    }
});
