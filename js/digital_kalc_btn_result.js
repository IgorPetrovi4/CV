$(function () {
    ajax_data1();
})

$(document).on('change', '#order_digital_paper_kalc_digitalFormat, #order_digital_paper_kalc_digital_paper_product', function () {
    ajax_data1();
});

$(document).on('change', '#order_digital_paper_kalc_digital_paper', function () {
    ajax_data2();
});
$(document).on('change', '#order_digital_paper_kalc_digitalFormat, #order_digital_paper_kalc_width, #order_digital_paper_kalc_height, #order_digital_paper_kalc_print_color, #order_digital_paper_kalc_lamination, #order_digital_paper_kalc_services, #order_digital_paper_kalc_serviceHole, #order_digital_paper_kalc_serviceFolding, #order_digital_paper_kalc_serviceCrease, #order_digital_paper_kalc_sum, #order_digital_paper_kalc_sumKit', function () {
    ajax_data();

})
$(document).on('input', '#order_digital_paper_kalc_width, #order_digital_paper_kalc_height, #order_digital_paper_kalc_sum, #order_digital_paper_kalc_sumKit', function () {
    ajax_data();
})
function ajax_data() {
    var digitalFormat = $(".my-digital-format input[type=radio]:checked").val();
    var yourSizeWidth = $('input[id="order_digital_paper_kalc_width"]').val();
    var yourSizeHeight = $('input[id="order_digital_paper_kalc_height"]').val();
    var digitalProduct = $(".my-digital-product input[type=radio]:checked").val();
    var digitalPaper = $(".my-digital-paper input[type=radio]:checked").val();
    var printColor = $(".my-print-digital-color input[type=radio]:checked").val();
    var servicesRounding = $(".my-services-rounding input[type=radio]:checked").val();
    var servicesHole = $(".my-services-hole input[type=radio]:checked").val();
    var servicesFolding = $(".my-services-folding input[type=radio]:checked").val();
    var servicesCrease = $(".my-services-crease input[type=radio]:checked").val();
    var lamination = $(".my-lamination-digital-paper input[type=radio]:checked").val();
    var sum = $('input[id="order_digital_paper_kalc_sum"]').val();
    var sumKit = $('input[id="order_digital_paper_kalc_sumKit"]').val();

    $.ajax({
        url: "/digital_paper_kalc/ajax",
        type: "GET",
        cache: false,
        data: {
            format: format,
            digitalFormat:digitalFormat,
            yourSizeWidth:yourSizeWidth,
            yourSizeHeight:yourSizeHeight,
            digitalProduct: digitalProduct,
            digitalPaper: digitalPaper,
            printColor: printColor,
            servicesRounding:servicesRounding,
            servicesHole:servicesHole,
            servicesFolding:servicesFolding,
            servicesCrease:servicesCrease,
            lamination: lamination,
            sum: sum,
            sumKit:sumKit,
        },

        success: function (response) {
            $("#rezult").replaceWith(function () {
                var elem = $('<h4 id="rezult"></h4>');
                return elem.append('' + response + 'грн.' + '');
            })
           // console.log(response);
        },
    });
}


function ajax_data1() {
    var idProduct = $(".my-digital-product input[type=radio]:checked").val();
    var idFormat = $(".my-digital-format input[type=radio]:checked").val();
    $.ajax({
        url: "/digital_paper/ajax",
        type: 'GET',
        cache: false,
        data: {
            format: format,
            idFormat: idFormat,
            idProduct:idProduct,
        },
        success: function (response) {


            $("#order_digital_paper_kalc_digital_paper").replaceWith(function () {
                var elem = $(' <div id="order_digital_paper_kalc_digital_paper" class="my-digital-paper row form-check-paper form-check-inline ">');
                $.each(response, function (index, value) {
                    if (value['id_paper'] != null) {
                        elem.append('<div class="form-check"><input type="radio" id="order_digital_paper_kalc_digital_paper_' + value['id_paper'] + '" name="order_digital_paper_kalc[digital_paper]" required="required" class="form-check-input" value="' + value['id_paper'] + '" ' + (value['id_paper'] === value['id_first_paper'] ? "checked=\"checked\"" : "") + ' ><label class="form-check-label required mt-2" for="order_digital_paper_kalc_digital_paper_' + value['id_paper'] + '">' + value['name_paper'] + '</label>');
                    }
                })
                return elem;
            });


            $("#order_digital_paper_kalc_print_color").replaceWith(function () {
                var elem = $(' <div id="order_digital_paper_kalc_print_color" class="my-print-digital-color row form-check-custom form-check-inline">');
                $.each(response, function (index, value) {
                    if (value['id_color'] != null) {
                        elem.append('<div class="form-check"><input type="radio" id="order_digital_paper_kalc_print_color_' + value['id_color'] + '" name="order_digital_paper_kalc[print_color]" required="required" class="form-check-input" value="' + value['id_color'] + '" ' + (value['id_color'] === value['id_first_color'] ? "checked=\"checked\"" : "") + ' ><label class="form-check-label required mt-2" for="order_digital_paper_kalc_print_color_' + value['id_color'] + '">' + value['name_color'] + '</label>');
                    }
                })
                return elem;
            });

            ajax_data2();
        }
    });
}


function ajax_data2() {
    var idPaper = $(".my-digital-paper input[type=radio]:checked").val();
    $.ajax({
        url: "/digital_paper_lamination/ajax",
        type: 'GET',
        data: {
            idPaper: idPaper,
        },
        success: function (response) {
            $("#order_digital_paper_kalc_lamination").replaceWith(function () {
                var elem = $(' <div id="order_digital_paper_kalc_lamination" class="my-lamination-digital-paper row form-check-paper-lamin form-check-inline">');
                $.each(response, function (index, value) {
                    elem.append('<div class="form-check"><input type="radio" id="order_digital_paper_kalc_lamination_' + value['id_lamination'] + '" name="order_digital_paper_kalc[lamination]" required="required" class="form-check-input" value="' + value['id_lamination'] + '"  ' + (value['id_lamination'] === value['id_paper_lamination'] ? "checked=\"checked\"" : "") + ' ><label class="form-check-label required mt-2" for="order_digital_paper_kalc_lamination_' + value['id_lamination'] + '">' + value['name_lamination'] + '</label>');
                })
                return elem;
            });
            ajax_data()
           // console.log(response);
        }
    });




}



