$(document).ready(function() {
	$('#derivalPoint').val("5003000500000000000000000");

// $('input[name="delcity"]').on("input", function(){ 
// 	cities = new Array;
// 	if($('input[name="delcity"]').val().length < 3) return false;
// 	$.ajax({
// 		url: 'index.php?route=common/delin/cities',
// 		type: 'post',
// 		data: {city: $('input[name="delcity"]').val()},
// 		dataType: 'json',
// 		success: function(json) {
// 			for (var i = json.cities.length - 1; i >= 0; i--){
// 				obj = new Object;
// 				// obj.label = json.cities[i].name;
// 				obj.value = json.cities[i].name;
// 				obj.code = json.cities[i].code;
// 				cities.push(obj);
// 			}
// 			console.log(cities);
// 			console.log(" ");
// 			$('input[name="delcity"]').autocomplete({
//     			source: cities,
//     			select: function(event, item) {
//     				console.log(item);
//     				$('input[name="delcity"]').val(item.item.value);
//         			$('#arrivalPoint').val(item.item.code);
//     			},
// 			});

// 		},
// 		error: function(xhr, ajaxOptions, thrownError) {
// 			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
// 		}
// 	});
// });


	$("#delcalc").on("click", function(e){
		e.preventDefault();

		if($('#arrivalPoint').val() == ''){
			alert("Выберите город доставки");
			return false;
		}

		$("#deliveryform .total > *").hide();
		$("#deliveryform .total > .spiner").show();
		$.ajax({
			url: 'index.php?route=common/delin/calc'
			, data: {
				derivalPoint: $('#derivalPoint').val()
				, arrivalPoint: $('#arrivalPoint').val()
				, sizedWeight: $('#sizedWeight').val()
				, sizedVolume: $('#sizedVolume').val() * $('input[name="qq"]').val()
				, oversizedWeight: $('#oversizedWeight').val()
				, oversizedVolume: $('#oversizedVolume').val()
				// , quantity: $("#deliveryform input[name='qq']").val()
				, rnd: '0.6040447200648487'
			}
			, type: 'POST'
			, dataType: 'json'
			, success:  function(data){
				console.log(data);
				price = parseFloat(data.result.insurance) + parseFloat(data.result.price);
				price = price * parseInt($("#deliveryform input[name='qq']").val());
				$(".delivery #tot").html(price + ' </span>₽</span>');
				$("#deliveryform .total > *").show();
				$("#deliveryform .total > .spiner").hide();
				
		
			},
		});
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