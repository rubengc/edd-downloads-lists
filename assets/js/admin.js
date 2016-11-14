jQuery(document).ready(function($) {
    if(edd_downloads_lists.lists !== undefined) {
        $.each(edd_downloads_lists.lists, function(list, list_args) {
            var list_callback = function() {
                var list_settings = [
                    'edd_downloads_lists_' + list + '_label',
                    'edd_downloads_lists_' + list + '_icon',
                    'edd_downloads_lists_' + list + '_count',
                    'edd_downloads_lists_' + list + '_style',
                    'edd_downloads_lists_' + list + '_page_view',
                    'edd_downloads_lists_' + list + '_guest',
                    'edd_downloads_lists_' + list + '_cart',
                    'edd_downloads_lists_' + list + '_share'
                ];

                var show = false;

                if ($('[id*="edd_downloads_lists_' + list + '_link"]').prop('checked')) {
                    show = true;
                }

                for (var i = 0; i < list_settings.length; i++) {
                    var row = $('[id*="' + list_settings[i] + '"]').closest('tr');

                    if (show) {
                        row.attr('style', '');
                    } else {
                        row.attr('style', 'display: none;');
                    }
                }
            };

            list_callback();

            $('[id*="edd_downloads_lists_' + list + '_link"]').change(list_callback);
        });
    }

    $('#edd_downloads_lists_favorites_import_button').click(function(e) {
        e.preventDefault();

        var button = $(this);

        button.addClass('disabled');
        button.attr('disabled', 'disabled');
        button.next('.spinner').addClass('is-active');
        $('#edd_downloads_lists_favorites_import_response').text('');

        $.ajax({
            url: ajaxurl,
            dataType: 'json',
            data: {
                action: 'edd_downloads_lists_favorites_import'
            },
            success: function(response) {
                // console.log(response); // Uncomment to see users updated

                button.removeClass('disabled');
                button.removeAttr('disabled');
                button.next('.spinner').removeClass('is-active');
                $('#edd_downloads_lists_favorites_import_response').text(response.message);
            }
        });
    });
});