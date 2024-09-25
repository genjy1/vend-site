(function( $ ) {	
	
  $.fn.feedback = function() {
    return this.each(function (){

      var error = false;

      $(this).submit(function(e){
        e.preventDefault();
      });

      var form = $(this);

      $(this).find(".submit").on("click", function(){
      	
        var button = $(this);

        if(button.hasClass("disabled"))
        {
            return false;
        }

        button.addClass("disabled");

        setTimeout(function(){
            button.removeClass("disabled");
        }, 1500);

        data = new Object();
        $(this).closest('form').find('input').map(function(el){
          name = $(this).attr('name');

          type = $(this).attr('type');

          if(type == "radio" && !$(this).prop("checked")){
            return false;
          }

          if(type == "checkbox" && !$(this).prop("checked")){
            required = $(this).attr('required');

            if(required !== undefined){
              $(this).addClass("error");
              error = true;
            }

            return false;
          }

          value = $(this).val();

          required = $(this).attr('required');

          if(required !== undefined){
            if(value == ''){
              error = true;
              console.log(name + ' is required');
            }
          }

          pattern = $(this).attr('pattern');
          if(pattern !== undefined){
            if(value.search(new RegExp(pattern,'i')) < 0){
              error = true;
              console.log('wrong pattern');
            }
          }

          data[name] = value;
        });

        $(this).closest('form').find('textarea').map(function(el){
          name = $(this).attr('name');
          value = $(this).val();

          required = $(this).attr('required');

          if(required !== undefined){
            if(value == ''){
              error = true;
              console.log(name + ' is required');
            }
          }


          data[name] = value;
        });
        
        data['template'] = $(this).closest('form').data('template');
        data['subject'] = $(this).closest('form').attr("data-subject");//$(this).closest('form').data('subject');
        var dataJson = JSON.stringify(data);
        if(!error){
          $.ajax({
           url: 'index.php?route=common/feedback',
           type: 'post',
            data: 'data=' + dataJson,
            dataType: 'json',
            success: function(json) {
              // console.loglog(json);
              if (json['success']) {
              	
        grecaptcha.ready(function () {
            grecaptcha.execute('6Lcn7DgpAAAAAOtz5NCMN3R4TUUc-JjHYSzKUCJ6', { action: 'contact' }).then(function (token) {
                var recaptchaResponse1 = document.getElementById('recaptchaResponseF');
                recaptchaResponse1.value = token;
                var recaptchaResponse2 = document.getElementById('recaptchaResponseF2');
                recaptchaResponse2.value = token;
                var recaptchaResponse3 = document.getElementById('recaptchaResponseF3');
                recaptchaResponse3.value = token;
                var recaptchaResponse4 = document.getElementById('recaptchaResponseF4');
                recaptchaResponse4.value = token;
                var recaptchaResponse5 = document.getElementById('recaptchaResponseInfo');
                recaptchaResponse5.value = token;
                var recaptchaResponse6 = document.getElementById('recaptchaResponseCont');
                recaptchaResponse6.value = token;
                var recaptchaResponse7 = document.getElementById('recaptchaResponseCateg');
                recaptchaResponse7.value = token;
                var recaptchaResponse8 = document.getElementById('recaptchaResponseHome');
                recaptchaResponse8.value = token;
                var recaptchaResponse9 = document.getElementById('recaptchaResponseProdOld');
                recaptchaResponse9.value = token;
                var recaptchaResponse10 = document.getElementById('recaptchaResponseProd');
                recaptchaResponse10.value = token;
                var recaptchaResponse11 = document.getElementById('recaptchaResponseCat');
                recaptchaResponse11.value = token;
                var recaptchaResponse12 = document.getElementById('recaptchaResponseCtlgOld');
                recaptchaResponse12.value = token;
                var recaptchaResponse13 = document.getElementById('recaptchaResponseCtlg');
                recaptchaResponse13.value = token;
                var recaptchaResponse14 = document.getElementById('recaptchaResponseInf');
                recaptchaResponse14.value = token;
            });
        });
              	
                if(true){
                  $(".winoverlay").show();
                  $(".win_white").hide();

                  $(".win_white:eq(1)").show();
                  //form.find('input').val("");
                  //form.find('textarea').val("");
                } else {
                  alert("Сообщение отправлено");
                  //$(this).find('input').val("");
                  //$(this).find('textarea').val("");
               }
               // alert(data['subject'])
                if (data['subject'] == "Обратный звонок") {
                  dataLayer.push({'event': 'send-zvonok'})
                  //alert('send-zvonok')
                }

                if (data['subject'] == "получить индивидуальное предложение") {
                  dataLayer.push({'event': 'send-ind-pred'})
              //    alert('send-ind-pred')
                }

                if (data['subject'] == "рассчитать выбранную комплектацию") {
                  dataLayer.push({'event': 'send-komplekt'})
               //   alert('send-komplekt')
                }

                if (data['subject'] == "Заявка на товар") {
                  dataLayer.push({'event': 'send-tovar'})
                //  alert('send-tovar')
                }

                if (data['subject'] == "Запрос цены") {
                  dataLayer.push({'event': 'send-cost'})
               //   alert('send-cost')
                }

                if (data['subject'] == "Быстрый заказ") {
                  dataLayer.push({'event': 'send-zakaz'})
              //    alert('send-zakaz')
                }

                if (data['subject'] == "Форма связи") {
                  dataLayer.push({'event': 'send-kontakt'})
            //      alert('send-kontakt')
                }

                if (data['subject'] == "Получить предложение") {
                    dataLayer.push({'event': 'send-pred'})
              //      alert('send-pred')
                }

                if (data['subject'] == "Заявка с главной страницы") {
                    dataLayer.push({'event': 'send-zayavka'})
              //      alert('send-zayavka')
                }

                if (data['subject'] == "Не нашли товар") {
                    dataLayer.push({'event': 'send-avtomat'})
              //      alert('send-avtomat')
                }
                if (data['subject'] == "Главный баннер") {
                  dataLayer.push({'event': 'send-predlogenie'})
              //    alert('send-predlogenie')
                  
                }
                if (data['subject'] == "Форма Рассрочка 0%") {
                  dataLayer.push({'event': 'send-rassrochka'})
           //       alert('send-rassrochka')
                }
                if (data['subject'] == "Подобрать автомат") {
                  dataLayer.push({'event': 'send-podbor-avtomat'})
             //     alert('send-podbor-avtomat')
                }
                if (data['subject'] == "Купить в кредит") {
                  dataLayer.push({'event': 'send-buy-credit'})
             //     alert('send-buy-credit')
                }
                
                //
              }
            },
            error: function(xhr, ajaxOptions, thrownError) {
             alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
          });

          // $(this).closest('form').find('input').val("");
          // $(this).closest('form').find('textarea').val("");
        }
        error = false;
      });
    });
  };
})(jQuery);