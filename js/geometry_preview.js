$(function () {
    ajax_data_prev();
})

$(document).on('change', '#order_digital_paper_kalc_digitalFormat, #order_digital_paper_kalc_width, #order_digital_paper_kalc_digital_paper, #order_digital_paper_kalc_height, #order_digital_paper_kalc_services, #order_digital_paper_kalc_serviceFolding, #order_digital_paper_kalc_serviceHole, #order_digital_paper_kalc_serviceCrease', function () {
    ajax_data_prev();
});
$(document).on('input', '#order_digital_paper_kalc_width, #order_digital_paper_kalc_height', function () {
    ajax_data_prev();
})

function ajax_data_prev() {
    var idPaper = $(".my-digital-paper input[type=radio]:checked").val();
    var idFormat = $(".my-digital-format input[type=radio]:checked").val();
    var idRounding = $(".my-services-rounding input[type=radio]:checked").val();
    var idFolding = $(".my-services-folding input[type=radio]:checked").val();
    var idHole = $(".my-services-hole input[type=radio]:checked").val();
    var idCrease = $(".my-services-crease input[type=radio]:checked").val();
    var width = $('input[id="order_digital_paper_kalc_width"]').val();
    var height = $('input[id="order_digital_paper_kalc_height"]').val();

    $.ajax({
        url: "/geometry_preview/ajax",
        type: "GET",
        cache: false,
        data: {
            idFormat: idFormat,
            width_dp: width,
            height_dp: height,
            idPaper:idPaper,
            idRounding:idRounding,
            idFolding:idFolding,
            idHole:idHole,
            idCrease:idCrease,
        },
        success: function (response) {


            $("#error").replaceWith(function () {
                var elem = $('<div id="error" ></div>');
                $.each(response, function () {
                    if (width > 450 || height > 450 || width < 40 || height < 40) {

                        elem.append(' <div class="text-danger text-small">Не верный размер , введите размер не менее 40*40mm и не более 450*320 (320*450)mm </div>');
                        $('input[type=submit]', this).attr('disabled', 'disabled');
                        $('form').bind('submit', function (e) {
                            e.preventDefault();
                        });
                    } else {
                        elem.append(' <div id="error" ></div>');
                        $('input[type=submit]', this).removeAttr('disabled', 'disabled');
                        $('form').unbind('submit');

                    }
                })
                return elem;

            });

            $("#downloadFile").replaceWith(function () {
                var elem = $('<a id="downloadFile" ></a>');
                $.each(response, function () {
                    if (idPaper == 25 || idPaper == 26) {
                        console.log(idPaper);
                        elem.append('<a href="/downloads/pluginsDraw/Shablon porezki.cdr" download=""><img src="/imeg/icon/Shablon.svg"></a>');
                    }
                })
                return elem;
            });
           $("#geometryPreview").replaceWith(function () {
                var elem = $('<div id="geometryPreview" class="row">');
                $.each(response, function (index, value) {
                    elem.append('<div class="col-2" style="top:' + value['h_centre'] + 'px;">' + value['h'] + 'мм</div> ' +
                        '<div class="col-auto">' +
                        '<div style="width:250px; text-align: center;">' + value['w'] + 'мм</div> ' +
                        '<div style="width:250px; height:250px; "> ' +
                        '<div class="container-fluid  " style="margin: auto; width:' + value['width'] + '%; height:' + value['height'] + '%; border: 1px solid green; border-radius: ' + value['tr'] + 'px ' + value['tl'] + 'px ' + value['br'] + 'px ' + value['bl'] + 'px;"> ' +
                        '<div style="width:4%; height:4%; border: ' + value['hole'] + 'px solid blue; border-radius: 10px 10px 10px 10px; position: absolute; left: 48%; top: 12%;"><p style="margin-top: 0.5em; ">' + value['sum_hole'] + '</p></div>'+
                        '<div class="container-fluid p-0" style="margin: auto; width:' + value['width_b'] + '%; height:' + value['height_b'] + '%; border-right: 1px solid red; ' + (value['width_b'] !== 0 ? "border-left: 1px solid red;" : "") + '  "> ' +
                        '<div class="container-fluid p-0" style="margin: auto; width:' + value['width_b2'] + '%; height:' + value['height_b2'] + '%; border-right: 1px solid red; ' + (value['width_b2'] !== 0 ? "border-left: 1px solid red;" : "") + '  "> ' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="pt-4" style="font-size: small">' + value['text'] + '</div>'+
                        '</div>' +
                        '</div>');
                })
                return elem;
            });

        },
    });
}

