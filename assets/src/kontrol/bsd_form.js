$(document).ready(function() {
    const $usernameInput = $('#kullanici_reg');
    const $usernameStatus = $('#kullanici_durumu');
    const $emailInput = $('#eposta');
    const $emailStatus = $('#eposta_durumu');
    const $passwordInput = $('#sifre_reg');
    const $passwordStatus = $('#sifre_durumu');

    // Kullanıcı adı kontrolü
    $usernameInput.on('input', function() {
        const username = $(this).val().toLowerCase().trim();
        $(this).val(username);

        if (username === '') {
            $usernameStatus.text('Kullanıcı adı alanı boş. Lütfen burayı doldurun.')
                .removeClass('valid error').addClass('status-message')
                .prepend('<i class="fa fa-exclamation-circle"></i> '); // Uyarı ikonu
            return;
        }

        $usernameStatus.text('Kontrol ediliyor...').removeClass('valid error').addClass('status-message')
            .prepend('<i class="fa fa-spinner fa-spin"></i> '); // Yükleniyor ikonu

        $.ajax({
            url: '../../assets/src/kontrol/bsd_kontrol.php',
            type: 'GET',
            data: { action: 'checkUsername', username: username },
            dataType: 'json',
            success: function(data) {
                if (data.exists) {
                    if (data.status === 'aktif') {
                        $usernameStatus.text('Bu kullanıcı adı zaten mevcut ve aktif.')
                            .removeClass('valid').addClass('status-message error')
                            .prepend('<i class="fa fa-times-circle"></i> '); // Hata ikonu
                    } else if (data.status === 'dondurulmus') {
                        $usernameStatus.text('Bu kullanıcı adı dondurulmuş durumda.')
                            .removeClass('valid').addClass('status-message error')
                            .prepend('<i class="fa fa-ban"></i> '); // Dondurulmuş ikonu
                    } else if (data.status === 'silinecek') {
                        $usernameStatus.text('Bu kullanıcı adı silinecek durumda.')
                            .removeClass('valid').addClass('status-message error')
                            .prepend('<i class="fa fa-trash"></i> '); // Silinecek ikonu
                    } else if (data.status === 'banli') {
                        $usernameStatus.text('Bu kullanıcı adı banlanmış.')
                            .removeClass('valid').addClass('status-message error')
                            .prepend('<i class="fa fa-ban"></i> '); // Banlı ikonu
                    }
                } else {
                    $usernameStatus.text('Kullanıcı adı kullanılabilir.')
                        .removeClass('error').addClass('status-message valid')
                        .prepend('<i class="fa fa-check-circle"></i> '); // Başarılı ikonu
                }
            },
            error: function(xhr, status, error) {
                const errorMessage = `Hata: ${xhr.status} - ${xhr.statusText}. Ayrıntı: ${error}`;
                $usernameStatus.text(`Kullanıcı adı kontrolünde bir hata oluştu: ${errorMessage}`)
                    .removeClass('valid').addClass('status-message error')
                    .prepend('<i class="fa fa-exclamation-circle"></i> '); // Uyarı ikonu
            }
        });
    });

    // E-posta kontrolü
    $emailInput.on('input', function() {
        const email = $(this).val();
        if (email === '') {
            $emailStatus.text('E-posta alanı boş. Lütfen burayı doldurun.')
                .removeClass('valid error').addClass('status-message')
                .prepend('<i class="fa fa-exclamation-circle"></i> '); // Uyarı ikonu
            return;
        }

        $emailStatus.text('Kontrol ediliyor...').removeClass('valid error').addClass('status-message')
            .prepend('<i class="fa fa-spinner fa-spin"></i> '); // Yükleniyor ikonu

        $.ajax({
            url: '../../assets/src/kontrol/bsd_kontrol.php',
            type: 'GET',
            data: { action: 'checkEmail', email: email },
            dataType: 'json',
            success: function(data) {
                if (data.exists) {
                    if (data.status === 'aktif') {
                        $emailStatus.text('Bu e-posta adresi zaten mevcut ve aktif.')
                            .removeClass('valid').addClass('status-message error')
                            .prepend('<i class="fa fa-times-circle"></i> '); // Hata ikonu
                    } else if (data.status === 'dondurulmus') {
                        $emailStatus.text('Bu e-posta adresi dondurulmuş durumda.')
                            .removeClass('valid').addClass('status-message error')
                            .prepend('<i class="fa fa-ban"></i> '); // Dondurulmuş ikonu
                    } else if (data.status === 'silinecek') {
                        $emailStatus.text('Bu e-posta adresi silinecek durumda.')
                            .removeClass('valid').addClass('status-message error')
                            .prepend('<i class="fa fa-trash"></i> '); // Silinecek ikonu
                    } else if (data.status === 'banli') {
                        $emailStatus.text('Bu e-posta adresi banlanmış.')
                            .removeClass('valid').addClass('status-message error')
                            .prepend('<i class="fa fa-ban"></i> '); // Banlı ikonu
                    }
                } else {
                    $emailStatus.text('E-posta adresi kullanılabilir.')
                        .removeClass('error').addClass('status-message valid')
                        .prepend('<i class="fa fa-check-circle"></i> '); // Başarılı ikonu
                }
            },
            error: function(xhr, status, error) {
                const errorMessage = `Hata: ${xhr.status} - ${xhr.statusText}. Ayrıntı: ${error}`;
                $emailStatus.text(`E-posta kontrolünde bir hata oluştu: ${errorMessage}`)
                    .removeClass('valid').addClass('status-message error')
                    .prepend('<i class="fa fa-exclamation-circle"></i> '); // Uyarı ikonu
            }
        });
    });

    // Şifre kontrolü
    $passwordInput.on('input', function() {
        const password = $(this).val();
        const length = password.length;
        let message = '';

        if (length < 6) {
            message = `Şifre en az 6 karakter olmalıdır. Şu anda ${length} karakter girdiniz.`;
            $passwordStatus.removeClass('valid').addClass('status-message error')
                .prepend('<i class="fa fa-times-circle"></i> '); // Hata ikonu
        } else {
            message = `Şifre uygun. Şu anda ${length} karakter girdiniz.`;
            $passwordStatus.removeClass('error').addClass('status-message valid')
                .prepend('<i class="fa fa-check-circle"></i> '); // Başarılı ikonu
        }

        // Şifre uzunluğu için görsel geri bildirim
        const feedback = Array.from({ length: Math.min(length, 10) }, (_, i) => '●').join('');
        $passwordStatus.html(`${message}<br>${feedback}`);
    });
});

