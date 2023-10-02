/* global redux_change, wp */

(function($) {
    "use strict";
    $.redux = $.redux || {};
    $(document).ready(function() {
        $.redux.wbc_importer();
    });
    $.redux.wbc_importer = function() {

        var Progress = {
            show: function (){
                jQuery(this).closest('.wrap-importer').find('.importer_status').css({ 'opacity' : '1'});
            },
            addPercents: function (i) {
                this.find('.importer_status .progressbar .progressbar_condition').css(
                    'height', i * 10 + '%'
                );
                this.find('.importer_status .progressbar_val').html(i * 10 + '%');
            },
            error: function (xhr, textStatus, errorThrown) {
                console.warn('was 500 (Internal Server Error) but we try to load import again');
                if (typeof this.tryCount !== "number") this.tryCount = 1;
                switch (true) {
                    case (this.tryCount < 10):
                        this.tryCount++;
                        jQuery.post(this).fail(Progress.error);
                        break;
                    default:
                        alert('There was an error importing demo content. Please reload page and try again.');
                }
            }
        }

        $('.wrap-importer.theme.not-imported, #wbc-importer-reimport').unbind('click').on('click', function(e) {
            e.preventDefault();

            var parent = jQuery(this);
            Progress.show.call(parent);

            var reimport = false;
            var message = 'Import Demo Content?';

            if (e.target.id == 'wbc-importer-reimport') {
                reimport = true;
                message = 'Re-Import Content?';

                if (!jQuery(this).hasClass('rendered')) {
                    parent = jQuery(this).parents('.wrap-importer');
                }
            }
            if (parent.hasClass('imported') && reimport == false) return;
            var r = confirm(message);
            if (r == false) return;
            if (reimport == true) {
                parent.removeClass('active imported').addClass('not-imported');
            }
            parent.find('.spinner').css('display', 'inline-block');

            parent.removeClass('active imported');

            parent.find('.importer-button').hide();

            var data = jQuery(this).data();

            data.action = "redux_wbc_importer";
            data.demo_import_id = parent.attr("data-demo-id");
            data.nonce = parent.attr("data-nonce");
            data.type = 'import-demo-content';
            data.wbc_import = (reimport == true) ? 're-importing' : ' ';
            data.content = 1;
            parent.find('.wbc_image').css('opacity', '0.5');
            loadContent(parent, data, reimport);

            return false;
        });

        $('.wrap-importer.theme.not-licence').unbind('click').on('click', function(e) {
            e.preventDefault();
            window.location.href = jQuery(this).find('.importer-button').data('url');
            return false;
        });

        function loadContent(parent, data, reimport) {
            jQuery.post(ajaxurl, data, function(response) {
                if (response.length > 0 && response.match(/Have fun!/gi)) {
                    Progress.addPercents.call(parent, data.content);
                    data.content++;
                    if (data.content > 10) {
                        console.log('Finished');
                        parent.find('.wbc_image').css('opacity', '1');
                        parent.find('.spinner').css('display', 'none');
                        if (reimport == false) {
                            parent.addClass('rendered').find('.wbc-importer-buttons .importer-button').removeClass('import-demo-data');

                            var reImportButton = '<div id="wbc-importer-reimport" class="wbc-importer-buttons button-primary import-demo-data importer-button">Re-Import</div>';
                            parent.find('.theme-actions .wbc-importer-buttons').append(reImportButton);
                        }
                        parent.find('.importer-button:not(#wbc-importer-reimport)').removeClass('button-primary').addClass('button').text('Imported').show();
                        parent.find('.importer-button').attr('style', '');
                        parent.addClass('imported active').removeClass('not-imported');
                        parent.parents('fieldset.wbc_importer.redux-field').find('.redux-success').show('slow');
                        location.reload();
                    } else {
                        loadContent(parent, data);
                    }
                } else {
                    parent.find('.import-demo-data').show();

                    if (reimport == true) {
                        parent.find('.importer-button:not(#wbc-importer-reimport)').removeClass('button-primary').addClass('button').text('Imported').show();
                        parent.find('.importer-button').attr('style', '');
                        parent.addClass('imported active').removeClass('not-imported');
                    }

                    alert('There was an error importing demo content: \n\n' + response.replace(/(<([^>]+)>)/gi, ""));
                }
            }).fail(Progress.error)
        }

        function wbc_show_progress(data) {
            data.action = "redux_wbc_importer_progress";

            if (imported_demo == false) {

                jQuery.ajax({
                    url: ajaxurl,
                    data: data,
                    success: function(response) {
                        var obj = jQuery.parseJSON(response);
                        if (response.length > 0 && typeof obj == 'object'){
                            console.log(obj);
                            var percentage = Math.floor((obj.imported_count / obj.total_post ) * 100);
                            console.log(percentage);
                            percentage = (percentage > 0) ? percentage - 1 : percentage;
                            parent.find('.wbc-progress-bar').css('width',percentage + "%");
                            parent.find('.wbc-progress-count').text(percentage + "%");
                            setTimeout(function() {
                                wbc_show_progress(data);
                            }, 2000);
                        }
                    }
                });

            } else {
                parent.find('.wbc-progress-back').remove();
            }
        }
    };
})(jQuery);