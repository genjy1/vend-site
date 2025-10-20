$(document).ready(function() {
	$('#derivalPoint').val("5003000500000000000000000");
	$('#arrivalPoint').val('7700000000000000000000000')

	$('input[name="delcity"]').autocomplete({
		minLength: 3,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=common/delin/cities',
				type: 'POST',
				data: { city: request.term },
				dataType: 'json',
				success: function(data) {
					if (!data.cities || !Array.isArray(data.cities)) {
						response([]);
						return;
					}
					const cities = data.cities.map(city => ({
						label: city.fullname,
						value: city.fullname,
						code: city.code
					}));
					response(cities);
				},
				error: function() {
					response([]);
				}
			});
		},
		select: function(event, ui) {
			event.preventDefault();
			$('input[name="delcity"]').val(ui.item.value);
			$('#arrivalPoint').val(ui.item.code);
		},
		focus: function(event, ui) {
			event.preventDefault();
			$('input[name="delcity"]').val(ui.item.value);
		}
	})
		.autocomplete("instance")._renderItem = function(ul, item) {
		return $("<li>")
			.append($("<div>").text(item.label))
			.appendTo(ul);
	};









	// $("#delcalc").on("click", function(e){
	// 	e.preventDefault();
	//
	// 	if($('#arrivalPoint').val() == ''){
	// 		alert("Выберите город доставки");
	// 		return false;
	// 	}
	//
	// 	$("#deliveryform .total > *").hide();
	// 	$("#deliveryform .total > .spiner").show();
	// 	$.ajax({
	// 		url: 'index.php?route=common/delin/calc'
	// 		, data: {
	// 			derivalPoint: $('#derivalPoint').val()
	// 			, arrivalPoint: $('#arrivalPoint').val()
	// 			, sizedWeight: $('#sizedWeight').val()
	// 			, sizedVolume: $('#sizedVolume').val() * $('input[name="qq"]').val()
	// 			, oversizedWeight: $('#oversizedWeight').val()
	// 			, oversizedVolume: $('#oversizedVolume').val()
	// 			// , quantity: $("#deliveryform input[name='qq']").val()
	// 			, rnd: '0.6040447200648487'
	// 		}
	// 		, type: 'POST'
	// 		, dataType: 'json'
	// 		, success:  function(data){
	// 			console.log(data);
	// 			price = parseFloat(data.result.insurance) + parseFloat(data.result.price);
	// 			price = price * parseInt($("#deliveryform input[name='qq']").val());
	// 			$(".delivery #tot").html(price + ' </span>₽</span>');
	// 			$("#deliveryform .total > *").show();
	// 			$("#deliveryform .total > .spiner").hide();
	//
	//
	// 		},
	// 	});
	// });


	const form = document.querySelector('#deliveryform')
	const totalSpan = document.querySelector('#tot');
	const totalWrapper = form.querySelector('.total');
	const spinner = form.querySelector('.spiner');

	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		try {
			const formData = new FormData(form);

			// Используйте set, если хотите обновить значение
			formData.set('sizedWeight', form.querySelector('#sizedWeight').value);
			formData.set('sizedVolume', form.querySelector('#sizedVolume').value);
			formData.set('arrivalPoint', form.querySelector('#arrivalPoint').value);
			$('.spiner').show()
			$('.total-text__wrapper').hide()
			const res = await fetch('index.php?route=common/delin/calc', {
				method: 'POST',
				body: formData
			});


			if (!res.ok) throw new Error('Ошибка сети: ' + res.status);

			$('.spiner').hide()
			$('.total-text__wrapper').show()

			const data = await res.json();

			if (data.result.price) {

				console.log(formData.get('qq'))

				totalSpan.textContent = data.result.price * formData.get('qq');
			}

			if (data.result.errors) {
				totalWrapper.textContent = Object.values(data.result.errors);
			}

		} catch (error) {
			totalWrapper.textContent = 'Ошибка при отправке: ' + error.message;
			console.error(error);
		}
	});





// $('input[name="delcity"]').autocomplete({
//     source: cities,
//     select: function(event, item) {
//     	console.log("//");
//     	console.log(item)
//     	$('input[name="delcity"]').val(item.label);
//         $('#arrivalPoint').val(item.item.code);
//     },
// });

// $('input[name="delcity"]').autocomplete("search");

	// $.ajax({
	// 	url: 'index.php?route=common/delin/cities',
	// 	type: 'post',
	// 	data: {city: $('input[name="delcity"]').val()},
	// 	dataType: 'json',
	// 	success: function(json) {
	// 		console.log(json);
	// 		$('#arrivalPoint').val(json.arrivalPoint);
	// 		for (var i = json.cities.length - 1; i >= 0; i--){
	// 			obj = new Object;
	// 			obj.label = json.cities[i][1];
	// 			obj.code = json.cities[i][2];
	// 		cities.push(obj);
	// 		}
	// 		$("#delcalc").trigger("click");
	// 	},
	// 	error: function(xhr, ajaxOptions, thrownError) {
	// 		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	// 	}
	// });




});