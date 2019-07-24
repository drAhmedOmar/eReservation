$(function () {

    'use strict';

    /*Empty Input In Focus */
    $(':input').on('focus', function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).on('blur', function () {
        if ($(this).val() == '') {
            $(this).attr('placeholder', $(this).data('text'));
        }
    });

    /*Confirm Message */
    $('.confirm').on('click', function(){
        confirm('Are You Sure ?');
    });


});