$(document).ready(function () {
    $('#dataTable').DataTable();

    $('.select2').select2({
        theme: 'bootstrap4'
    })

    // ic number only
    $('#ic').on('keypress', function (e) {
        // only number and min 12 and max 12
        var char = e.which;
        if (char > 31 && (char < 48 || char > 57) || $(this).val().length > 11) {
            return false;
        }
    });

    // phone number only
    $('#phone').on('keypress', function (e) {
        var char = e.which;
        if (char > 31 && (char < 48 || char > 57)) {
            return false;
        }
    });

    // postcode
    $('#postcode').on('keypress', function (e) {
        // number and min 5 and max 5
        var char = e.which;
        if (char > 31 && (char < 48 || char > 57) || $(this).val().length > 4) {
            return false;
        }
    });

    // fullname uppercase
    $('#fullname').on('keyup', function () {
        $(this).val($(this).val().toUpperCase());
    });

    // product_quantity
    $('#product_quantity').on('keypress', function (e) {
        // number 
        var char = e.which;
        if (char > 31 && (char < 48 || char > 57)) {
            return false;
        }
    });

});


