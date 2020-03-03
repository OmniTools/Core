var $ = require("jquery");

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

import '@fortawesome/fontawesome-pro/js/all.js'

import '../scss/app.scss';

window.toastr = require('toastr');
// import "./../../../../../node_modules/toastr/build/toastr.min.css";

/**
 * Prompt confirmation for clicked action
 */
$(document).on('click', '[data-confirm]', function ( event ) {

    if (!confirm($(this).attr('data-confirm'))) {

        event.preventDefault();
        event.stopImmediatePropagation();

        return;
    }
});

/**
 *
 */
$(document).on('click', 'a.ajax', function ( event ) {

    event.preventDefault();
    event.stopImmediatePropagation();

    if ($(this).attr('href').match(/ajaxModal/)) {

        $('#genericModal').modal('show');

        $('#genericModal .modal-title').html($(this).attr('title'));

        $.ajax({
            url: $(this).attr('href'),
            success: function(response) {

                if (typeof response.modalSize !== 'undefined') {
                    $('#genericModal .modal-dialog').addClass('modal-' + response.modalSize);
                }

                $('#genericModal .modal-content-wrapper').html(response.html);
            },
            error: function(xhr, status, error) {

                $('#genericModal .modal-content-wrapper').html('<div class="modal-body">' + xhr.responseText + '</div>');
            }
        });
    }
    else {

        $.ajax({
            url: $(this).attr('href'),
            headers: {
                Accept: "application/json; charset=utf-8",
            },
            success: function (response) {

                if (typeof response.redirect !== 'undefined') {
                    window.location.href = response.redirect;
                }

                if (typeof response.modalDismiss !== 'undefined') {
                    $('#genericModal').modal('hide');
                }

                if (typeof response.fadeOut !== 'undefined') {
                    $(response.fadeOut).fadeOut();
                }

                if (typeof response.replace !== 'undefined') {
                    $(response.replace.selector).html(response.replace.html);
                }

                if (typeof response.success !== 'undefined') {
                    toastr.success(response.success);
                }
            },
            error: function(xhr, status, error) {
                toastr.error(xhr.responseText);
            }
        });
    }


});


$(document).on('submit', 'form.ajax', function ( event ) {

    event.preventDefault();
    event.stopImmediatePropagation();

    $.ajax({
        url: $(this).attr('action'),
        headers: {
            Accept: "application/json; charset=utf-8",
        },
        type : $(this).attr('method'),
        data : $(this).serialize(),
        success : function(response) {

            if (typeof response.redirect !== 'undefined') {
                window.location.href = response.redirect;
            }

            if (typeof response.replace !== 'undefined') {
                $(response.replace.selector).html(response.replace.html);
            }

            if (typeof response.replacements !== 'undefined') {

                $.each(response.replacements, function ( key, value ) {
                    $(value.selector).html(value.html);
                });
            }

            if (typeof response.modalDismiss !== 'undefined') {
                $('#genericModal').modal('hide');
            }

            toastr.success('Die Daten wurden gespeichert.');
        },
        error: function(xhr, status, error) {
            toastr.error(xhr.responseText);
        }
    });
});

$(document).on('mouseenter', 'tr[data-href]', function () {
    $(this).addClass('highlighted');
});

$(document).on('mouseleave', 'tr[data-href]', function () {
    $(this).removeClass('highlighted');
});

$(document).on('click', '[data-href]', function () {
    window.location.href = $(this).attr('data-href');
});