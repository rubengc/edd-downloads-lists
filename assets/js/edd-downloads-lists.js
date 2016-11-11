jQuery(document).ready(function ($) {

    $('body').on('click.eddDownloadsLists', '.edd-downloads-lists-add', function (e) {
        e.preventDefault();

        var $spinner      	= $(this).find('.edd-loading');

        var spinnerWidth    = $spinner.width(),
            spinnerHeight       = $spinner.height();

        // Show the spinner
        $(this).attr('data-edd-loading', '');

        // center spinner
        $spinner.css({
            'margin-left': spinnerWidth / -2,
            'margin-top' : spinnerHeight / -2
        });

        var linkClicked = $(this);
        var container = $(this).closest('div');

        //var form 			= $(this).closest('form'); // get the closest form element
        var form            = jQuery('.edd_download_purchase_form');
        var list            = $(this).attr('href').replace('#', '');
        var download        = $(this).data('download-id');
        var variable_price  = $(this).data('variable-price');
        var price_mode      = $(this).data('price-mode');
        var item_price_ids  = [];

        // we're only saving the download ID
        item_price_ids[0] = download;

        var action          = $(this).data('action');

        var data = {
            action:     	action,
            list:  	        list,
            download_id:  	download,
            price_ids:  	item_price_ids,
            nonce:      	edd_scripts.ajax_nonce
        };

        $.ajax({
                type:       "POST",
                data:       data,
                dataType:   "json",
                url:        edd_scripts.ajaxurl,
                success: function (response) {
                    var cssClass = 'listed';

                    $( linkClicked ).removeAttr( 'data-edd-loading' );

                    if ( response.removed ) {
                        $( linkClicked ).removeClass( cssClass );
                    }
                    else if ( response.added ) {
                        $( linkClicked ).addClass( cssClass );
                    }

                    if ( response.label !== undefined ) {
                        $(linkClicked).find('.label').text(response.label);
                    }

                }
            })
            .fail(function (response) {
                console.log(response);
            })
            .done(function (response) {
                console.log(response);
            });

        return false;
    });

});