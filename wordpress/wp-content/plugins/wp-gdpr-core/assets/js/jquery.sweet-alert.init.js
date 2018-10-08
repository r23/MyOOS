
/**
* Theme: Minton - Responsive Bootstrap 4 Admin Dashboard
* Author: Coderthemes
* SweetAlert
*/

!function ($) {
    "use strict";

    var SweetAlert = function () {
    };

    //examples
    SweetAlert.prototype.init = function () {






        //Warning Message
        $('#sa-warning').click(function () {
            swal({
                title: 'Are you sure?',
                text: translatedText.text1,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-confirm mt-2',
                cancelButtonClass: 'btn btn-cancel ml-2 mt-2',
                confirmButtonText: 'Yes, send delete request!'
            }).then(function () {
                swal({
                        title: 'Request sent !',
                        text: "Your delete request has been sent",
                        type: 'success',
                        confirmButtonClass: 'btn btn-confirm mt-2'
                    }
                )
            })
        });
        $('#sa-warning2').click(function () {
            swal({
                title: 'Are you sure?',
                text: "This will send a delete request for your selected data",
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-confirm mt-2',
                cancelButtonClass: 'btn btn-cancel ml-2 mt-2',
                confirmButtonText: 'Yes, send delete request!'
            }).then(function () {
                swal({
                        title: 'Request sent !',
                        text: "Your delete request has been sent",
                        type: 'success',
                        confirmButtonClass: 'btn btn-confirm mt-2'
                    }
                )
            })
        });


    },
        //init
        $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing
    function ($) {
        "use strict";
        $.SweetAlert.init()
    }(window.jQuery);