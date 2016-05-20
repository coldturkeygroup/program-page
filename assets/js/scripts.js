jQuery('document').ready(function ($) {
    // Simple AJAX listeners
    $(document).bind("ajaxSend", function () {
        $('.btn-primary').attr('disabled', 'disabled');
    }).bind("ajaxComplete", function () {
        $('.btn-primary').removeAttr('disabled');
    });

    // Submit quiz results
    $('#submit-results').click(function (e) {
        e.preventDefault();
        var form = $('#days-on-market');

        $.ajax({
            type: 'POST',
            url: DaysOnMarket.ajaxurl,
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function () {
                $('#submit-results').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            },
            async: true,
            success: function (response) {
                setTimeout(function () {
                    $('#get-results-modal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('#days-on-market,.modal-backdrop').remove();
                    $('.results').show();

                    var retargeting = $('#retargeting').val(),
                        conversion = $('#conversion').val();
                    if (conversion != '') {
                        if (conversion !== retargeting) {
                            !function (f, b, e, v, n, t, s) {
                                if (f.fbq)return;
                                n = f.fbq = function () {
                                    n.callMethod ?
                                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                                };
                                if (!f._fbq)f._fbq = n;
                                n.push = n;
                                n.loaded = !0;
                                n.version = '2.0';
                                n.queue = [];
                                t = b.createElement(e);
                                t.async = !0;
                                t.src = v;
                                s = b.getElementsByTagName(e)[0];
                                s.parentNode.insertBefore(t, s)
                            }(window,
                                document, 'script', '//connect.facebook.net/en_US/fbevents.js');

                            fbq('init', conversion);
                        }

                        fbq('track', "Lead");
                    }
                }, 1000);
            }
        });
    });
});