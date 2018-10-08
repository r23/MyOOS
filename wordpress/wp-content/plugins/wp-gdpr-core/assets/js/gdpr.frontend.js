
jQuery(document).ready(function ($) {

    var tables = localized_object_frontend.tables;
    $.each(tables, function (key, data) {
        var table = $(data).DataTable({
            lengthChange: false,
            responsive: true,
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            language: {
                search:     search_text,
                oPaginate: {
                    sNext: next_text,
                    sPrevious: previous_text
                },
                buttons: {
                    copy:       copy_text,
                    print:      print_text
                },
                sInfo: showing_text,
            }
        });

        tables_wrapers = data + '_wrapper .col-md-6:eq(0)';

        table.buttons().container()
            .appendTo(tables_wrapers );
    });



    $("button").on('click', function(e) {
        var formid = $(this).parents('form').attr('id');
        e.preventDefault();
        swal({
            title: gdpra,
            text: gdprb,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-confirm mt-2',
            cancelButtonClass: 'btn btn-cancel ml-2 mt-2',
            confirmButtonText: gdprc,

        }).then(function () {
            swal({
                    title: gdprd,
                    text: gdpre,
                    type: 'success',
                    confirmButtonClass: 'btn btn-confirm mt-2',
                    closeOnCancel: false,
                    allowOutsideClick: false
                }
            ).then(function (result) {
            $('#'+formid).submit();
        }).catch( function(error){console.warn('error:', error)});
        })
    });

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
    $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    } );


});
