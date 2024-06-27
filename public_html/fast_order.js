$(document).ready(function () {
    //$('#fast_order').colorbox({href:"#fast_order_form",inline:true, width:"650px", height:"330px", title:" "});
 //    $('#fast_order_form .fast_order_center button').click(function () {
 //       var product_name = $('#product_name').val(); 
 //       var product_price = $('#product_price').val();
 //      // var customer_name = $('#customer_name').val();
 //      var customer_phone = $('#customer_phone').val();
 //      // var customer_message = $('#customer_message').val();
 //    //  $('#result').html('Обрабатываем введенные данные..');
 //    //  $.post('http://all-eco.ru/fast_order.php', { 'product_name': product_name, 'product_price': product_price, 'customer_name': customer_name, 'customer_phone': customer_phone, 'customer_message': customer_message }, function (data) { if (data == 'empty') { $('#fast_order_result').html('<span class="fast_order_error">Обязательно укажите ваше имя и телефон, иначе мы не сможем вам перезвонить!</span>'); } else { $('#fast_order_result').html('<span class="fast_order_success">Ваш заказ успешно оформлен!</span><br /><span>Мы перезвоним вам в течение дня. <a onclick="$(window).colorbox.close();">Закрыть</a> это окно?</span>'); } });
 // $.post('http://all-eco.ru/fast_order.php', { 'product_name': product_name, 'product_price': product_price, 'customer_phone': customer_phone}, function (data) { if (data == 'empty') { $('#fast_order_result').html('<span class="fast_order_error">Обязательно укажите ваше имя и телефон, иначе мы не сможем вам перезвонить!</span>'); } else { $('#fast_order_result').html('<span class="fast_order_success">Ваш заказ успешно оформлен!</span><br /><span>Мы перезвоним вам в течение дня. <a onclick="$(window).colorbox.close();">Закрыть</a> это окно?</span>'); } });

 //    });

$('#fast_win').colorbox({href:"#fast_order_form_win",inline:true, width:"430px", height:"160px", title:" "});

 $('#fast_order, #fast_order-cb').click(function () {
 var product_name = $('#product_name_f').val(); 
 var product_price = $('#product_price_f').val();
 var customer_phone = $(this).prev().prev('.fast_number_f').val();
 var quantity = $("input[name='fastquantity']").val();

 $.post('http://www.all-eco.ru/fast_order.php', {
  'product_name': product_name,
  'product_price': product_price,
  'quantity': quantity,
 'customer_phone': customer_phone},
  function (data) { if (data == 'empty') {
   $('#fast_order_result_1').html('<span class="fast_order_error">Обязательно укажите ваш телефон, иначе мы не сможем вам перезвонить!</span>'); 
   $('#fast_win').click();
 }
   else {
$('#fast_order_result_1').html('<span class="fast_order_success">Ваш заказ принят!</span><br /><span> Менеджеры перезвонят вам в течении 15 минут! <a onclick="$(window).colorbox.close();">Закрыть</a> это окно?</span>'); 
$('#fast_win').click();
} 
});


    });


$('#fast_fmc').live('click',function(){
$('#fast_order_mc').trigger('click');
});

$('#fast_order_mc').colorbox({href:"#fast_order_form_mc",inline:true, width:"430px", height:"180px", title:" "});

    $('#fast_order_mc').live('click',function () {
       var product_name = $('#product_name_mc').val(); 
       var product_price = $('#product_price_mc').val();
      // var customer_name = $('#customer_name_mc').val();
      var customer_phone = $('#fast_number_mc').val();
      var cartquant = $('#product_q_mc').val();

      // var customer_message = $('#customer_message_mc').val();

     // $('#result').html('Обрабатываем введенные данные..');
      //$.post('http://all-eco.ru/fast_order.php', { 'product_name': product_name, 'product_price': product_price, 'customer_name': customer_name, 'customer_phone': customer_phone, 'customer_message': customer_message }, function (data) { if (data == 'empty') { $('#fast_order_result').html('<span class="fast_order_error">Обязательно укажите ваше имя и телефон, иначе мы не сможем вам перезвонить!</span>'); } else { $('#fast_order_result').html('<span class="fast_order_success">Ваш заказ успешно оформлен!</span><br /><span>Мы перезвоним вам в течение дня. <a onclick="$(window).colorbox.close();">Закрыть</a> это окно?</span>'); } });
 $.post('http://www.all-eco.ru/fast_order.php', 
  { 'product_name': product_name, 
  'product_price': product_price, 
  'customer_phone': customer_phone,
  'cartquant' : cartquant,
}, 
  function (data) { if (data == 'empty') {
   //$('#fast_order_result_mc').html('<span class="fast_order_error">Обязательно укажите ваше имя и телефон, иначе мы не сможем вам перезвонить!</span>'); 
 } else {
  //$('#fast_order_result_mc').html('<span class="fast_order_success">Ваш заказ принят!</span><br />Менеджеры перезвонят вам в течении 15 минут!<span> <a onclick="$(window).colorbox.close();">Закрыть</a> это окно?</span>'); 
}
  });
    });

});