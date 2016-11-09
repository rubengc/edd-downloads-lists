jQuery(document).ready(function($) {
    if(edd_downloads_lists.lists !== undefined) {
        $.each(edd_downloads_lists.lists, function(list, list_args) {
            var list_callback = function() {
                var list_settings = [
                    'edd_download_lists_' + list + '_label',
                    'edd_download_lists_' + list + '_icon',
                    'edd_download_lists_' + list + '_style'
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
});