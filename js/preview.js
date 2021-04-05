
$(document).ajaxSend(function() {
	$('#loading-indicator').show();
});

$(document).on('change', '#orders_all_upload, #orders_all_uploadNext', function () {
	ajax_data_prev();

});
$(document).ajaxComplete(function() {
	$('#loading-indicator').hide();
	$('#orders_all_upload, #orders_all_uploadNext').val(null);

});


function ajax_data_prev() {
	var	oldForm = document.forms.orders_all,
		formData = new FormData(oldForm)
	;
	console.log(
		formData.get("print_manager[upload]"),
	);

	$.ajax({
		url: "/partner/upload/ajax",
		type: "POST",
		cache: false,
		data: formData,
		processData: false,  // Сообщить jQuery не передавать эти данные
		contentType: false,   // Сообщить jQuery не передавать тип контента

		success: function (response) {
			if (response['formFile'] == 1){
				$("#preview").replaceWith(function () {
					var elem = $('<div  class="col-md-4 mt-3 pt-3" id="preview" style=" height:300px; line-height: 300px; text-align: center; overflow: hidden;  ">');
					return elem.append('<img class="mh-100"  src= "'+ response['file'] +'"  style=" vertical-align: middle;">');
					//return elem.append(response);
				});


				if( response['size'] !== undefined) {
					$("#tablePreview").replaceWith(function () {
						var elem = $('<div id="tablePreview"></div>');
						return elem.append('' +
							'<table class="table table-striped table-sm text-center  " style="font-size: 9pt;">' +
							'<thead class="thead-dark ">' +
							'<tr>' +
							'<th scope="col">Параметры</th>' +
							'<th scope="col">Тех.требования</th>' +
							'<th scope="col">Ваш макет</th>' +
							'<th scope="col"></th>' +
							'</tr>' +
							'</thead>' +
							'<tbody>' +
							'<tr>' +
							'<td>Формат, мм:</td>' +
							'<td>' + response['sizeTt'] + '</td>' +
							'<td>' + response['size'] + '</td>' +
							'<td>' + response['sizeVerification'] + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Разрешение, dpi:</td>' +
							'<td>' + response['resolutionTt'] + '</td>' +
							'<td>' + response['resolution'] + '</td>' +
							'<td>' + response['resolutionVerification'] + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Цыетовая модель:</td>' +
							'<td>' + response['colorSpaceTt'] + '</td>' +
							'<td>' + response['colorSpace'] + '</td>' +
							'<td>' + response['colorSpaceVerification'] + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Размер, Mb:</td>' +
							'<td> < ' + response['fileSizeTt'] + '</td>' +
							'<td>' + response['fileSize'] + '</td>' +
							'<td>' + response['fileSizeVerification'] + '</td>' +
							'</tr>' +
							'<tr class="table-warning">' +
							'<td  colspan="4">' + response['resultVerification'] + '</td>' +
							'</tr>' +
							'</tbody>' +
							'</table>'
						);
						//return elem.append(response);
					});
				} else {
					$("#tablePreview").replaceWith(function () {
						var elem = $('<div id="tablePreview" class=" col border mt-5 pt-3"></div>');
						return elem.append('<img src="/imeg/icon/warning.png" width="25">' +
							'<p><small>Внимание, размер макета не должен привышать 55Mb. <br> Файлы принимаются в форматах ' +
							'TIFF, JPG, PDF. Для других форматов файлов используйте файлообменник.</small>' +
							'</p>');
						//return elem.append(response);
					});

				}




			}


			if (response['formFile'] == 2) {

				$("#preview1").replaceWith(function () {
					var elem = $('<div  class="col-md-4 mt-3 pt-3" id="preview1" style=" height:300px; line-height: 300px; text-align: center; overflow: hidden;  ">');
					return elem.append('<img class="mh-100"  src= "'+ response['file'] +'"  style=" vertical-align: middle;">');

				});


				if (response['size'] !== undefined) {
					$("#tablePreview1").replaceWith(function () {
						var elem = $('<div id="tablePreview1"></div>');
						return elem.append('' +
							'<table class="table table-striped table-sm text-center  " style="font-size: 9pt;">' +
							'<thead class="thead-dark ">' +
							'<tr>' +
							'<th scope="col">Параметры</th>' +
							'<th scope="col">Тех.требования</th>' +
							'<th scope="col">Ваш макет</th>' +
							'<th scope="col"></th>' +
							'</tr>' +
							'</thead>' +
							'<tbody>' +
							'<tr>' +
							'<td>Формат, мм:</td>' +
							'<td>' + response['sizeTt'] + '</td>' +
							'<td>' + response['size'] + '</td>' +
							'<td>' + response['sizeVerification'] + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Разрешение, dpi:</td>' +
							'<td>' + response['resolutionTt'] + '</td>' +
							'<td>' + response['resolution'] + '</td>' +
							'<td>' + response['resolutionVerification'] + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Цыетовая модель:</td>' +
							'<td>' + response['colorSpaceTt'] + '</td>' +
							'<td>' + response['colorSpace'] + '</td>' +
							'<td>' + response['colorSpaceVerification'] + '</td>' +
							'</tr>' +
							'<tr>' +
							'<td>Размер, Mb:</td>' +
							'<td> < ' + response['fileSizeTt'] + '</td>' +
							'<td>' + response['fileSize'] + '</td>' +
							'<td>' + response['fileSizeVerification'] + '</td>' +
							'</tr>' +
							'<tr class="table-warning">' +
							'<td  colspan="4">' + response['resultVerification'] + '</td>' +
							'</tr>' +
							'</tbody>' +
							'</table>'
						);
						//return elem.append(response);
					});
				} else {
					$("#tablePreview1").replaceWith(function () {
						var elem = $('<div id="tablePreview1" class=" col border mt-5 pt-3"></div>');
						return elem.append('<img src="/imeg/icon/warning.png" width="25">' +
							'<p><small>Внимание, размер макета не должен привышать 55Mb. <br> Файлы принимаются в форматах ' +
							'TIFF, JPG, PDF. Для других форматов файлов используйте файлообменник.</small>' +
							'</p>');
						//return elem.append(response);
					});


				}

			}


			console.log(response);
		},
	});
}


