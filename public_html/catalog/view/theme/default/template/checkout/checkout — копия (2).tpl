<?php echo $header; ?>
    <div class="lc">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
      </ul>
      <div class="cart">
        <h1>Контактная информация</h1>
        <div class="checkout">
          <div class="customer">
            <input type="radio" id="r1" name="account" value="register" checked>
            <label for="r1"> <span> </span><span>Я новый покупатель</span></label>
            <input type="radio" id="r2" name="account" value="login">
            <label for="r2"> <span></span><span>Я уже заказывал ранее и у меня есть аккаунт</span></label>
          </div>
          <div class="field l">
            <label>Имя<span class="req">*</span></label>
            <input name="firstname"  type="text">
          </div>
          <div class="field r"  type="text">
            <label>Телефон<span class="req">*</span></label>
            <input name="telephone"  type="text">
          </div>
          <div class="field l">
            <label>Фамилия<span class="req">*</span></label>
            <input name="lastname"  type="text">
          </div>
          <div class="field r">
            <label>Email<span class="req">*</span></label>
            <input name="email"  type="text">
          </div>
          <div class="field l">
            <label>Отчество<span class="req">*</span></label>
            <input name="patronymic"  type="text">
          </div>
          <div class="customer custperson">
            <input type="radio" id="r3" name="customer_group_id" checked value="2">
            <label for="r3"> <span> </span><span>Физическое лицо</span></label>
            <input type="radio" id="r4" name="customer_group_id" value="3">
            <label for="r4"> <span></span><span>Юридическое лицо</span></label>
          </div>
          <div id="personinfo"></div>
          <div class="addr">Адрес доставки</div>
          <div class="field l">
            <label>Индекс</label>
            <input name="postcode" type="text">
          </div>
          <div class="field r">
            <label>Страна</label>
            <input name="country"  type="text">
            <input name="country_id" type="hidden" value="<? echo $country_id ?>">
            <div class="countries">
            <?php foreach ($countries as $country) { ?>
            <div data-value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></div>
          <?php } ?>
            </div>
          </div>
          <div class="field l">
            <label>Город</label>
            <input name="city" type="text">
          </div>
          <div class="field r">
            <label>Регион</label>
            <input name="zone" type="text">
            <input name="zone_id" type="hidden">
            <div class="zones">
            </div>
          </div>
          <div class="field l">
            <label>Улица, дом, квартира</label>
            <input name="address_1" type="text">
          </div>
          <div class="customer custperson">
            <input type="checkbox" id="r5" name="register" value="1">
            <label for="r5"><span>Я хочу зарегистрироваться как постоянный пользователь</span></label>
          </div>
          <div class="pass" style="display: none;">
            <div class="field l">
              <label>Пароль</label>
              <input name="password" type="text">
            </div>
            <div class="field r">
              <label>Подтверждение пароля</label>
              <input name="confirm" type="text">
            </div>
          </div>
          <div class="buttons"><a href="javascript:history.back(-2);" class="return">Вернуться в магазин</a>
            <div class="checkout"><a href="javascript:void(0)" id="nextstep">Дальше</a></div>
          </div>
        </div>
      </div>
    </div>
<script>

$('input[name=\'country\']').on("focus", function(){
  $(".countries").show();
});
$('input[name=\'zone\']').on("focus", function(){
  $(".zones").show();
});
// $('input[name=\'country\']').on("blur", function(){
//   $(".countries").hide();
// });

$('input[name=\'register\']').on("change", function(){
  $(".pass").toggle();
});


$("#nextstep").on('click', function(){
  if(($('input[name=\'account\']:checked').val() == 'register') && ($('input[name=\'register\']:checked').length)){
    $.ajax({
        url: 'index.php?route=checkout/register/save',
        type: 'post',
        data: $('.cart input[type=\'text\'], .cart input[type=\'radio\']:checked, .cart input[type=\'hidden\']'),
        dataType: 'json',
        beforeSend: function() {
        },
        success: function(json) {
          console.log(json);

            if (json['registered']) {
              $.ajax({
                url: 'index.php?route=checkout/confirm',
                type: 'post',
                data: $('.cart input[type=\'text\'], .cart input[type=\'radio\']:checked, .cart input[type=\'hidden\']'),
                dataType: 'json',
                success: function(json1) {
                  console.log(json1)
                  if (json1['redirect']) {
                     location = json1['redirect'];
                  }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
              })
            }

            if (json['redirect']) {
                 // location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#collapse-payment-address .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
  } else if($('input[name=\'account\']:checked').val() == 'register'){
    $.ajax({
        url: 'index.php?route=checkout/guest/save',
        type: 'post',
        data: $('.cart input[type=\'text\'], .cart input[type=\'radio\']:checked, .cart input[type=\'hidden\']'),
        dataType: 'json',
        beforeSend: function() {
        },
        success: function(json) {
          console.log(json);
              $.ajax({
                url: 'index.php?route=checkout/confirm',
                type: 'post',
                data: $('.cart input[type=\'text\'], .cart input[type=\'radio\']:checked, .cart input[type=\'hidden\']'),
                dataType: 'json',
                success: function(json1) {
                  console.log(json1)
                  if (json1['redirect']) {
                     location = json1['redirect'];
                  }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
              })

            if (json['redirect']) {
                 // location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#collapse-payment-address .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
  } else if($('input[name=\'account\']:checked').val() == 'login'){
    $.ajax({
        url: 'index.php?route=checkout/login/save',
        type: 'post',
        data: $('#login input'),
        dataType: 'json',
        beforeSend: function() {
    },
        complete: function() {
        },
        success: function(json) {
              $.ajax({
                url: 'index.php?route=checkout/confirm',
                type: 'post',
                data: $('.cart input[type=\'text\'], .cart input[type=\'radio\']:checked, .cart input[type=\'hidden\']'),
                dataType: 'json',
                success: function(json1) {
                  console.log(json1)
                  if (json1['redirect']) {
                     location = json1['redirect'];
                  }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
              })
            if (json['redirect']) {
                // location = json['redirect'];
            } else if (json['error']) {
        // Highlight any found errors
        $('input[name=\'email\']').parent().addClass('has-error');
        $('input[name=\'password\']').parent().addClass('has-error');
       }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
  }
});

$(document).on('change', 'input[name=\'account\'], input[name=\'customer_group_id\']', function() {
    $.ajax({
        url: 'index.php?route=checkout/' + $('input[name=\'account\']:checked').val() + '&customer_group_id=' + $('input[name=\'customer_group_id\']:checked').val(),
        dataType: 'html',
        beforeSend: function() {
    },
        complete: function() {
        },
        success: function(html) {
          console.log(html);
          $('#personinfo').html(html);
          if ($('input[name=\'account\']:checked').val() == 'register') {

          } else {
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});



$('input[name=\'country_id\']').on('change', function() {
  $.ajax({
    url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
    dataType: 'json',
    beforeSend: function() {
    },
    complete: function() {
    },
    success: function(json) {
      if (json['postcode_required'] == '1') {
        $('input[name=\'postcode\']').parent().parent().addClass('required');
      } else {
        $('input[name=\'postcode\']').parent().parent().removeClass('required');
      }

      html = '';

      if (json['zone'] && json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
          html += '<div data-value="' + json['zone'][i]['zone_id'] + '"';

          if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
            html += '';
          }

          html += '>' + json['zone'][i]['name'] + '</div>';
        }
      } else {
        html += '';
      }

      $('.zones').html(html);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});


  $(document).ready(function() {

    $("#r1").trigger('change');

    $(".countries div").on('click', function(){
      $('input[name=\'country\']').val($(this).text());
      $('input[name=\'country_id\']').val($(this).data('value'));
      $(".countries").hide();
      $('input[name=\'country_id\']').trigger('change');
    });

    $(".zones").on('click', 'div', function(){
      $('input[name=\'zone\']').val($(this).text());
      $('input[name=\'zone_id\']').val($(this).data('value'));
      $(".zones").hide();
    });

    $('input[name="customer"').on("change", function(){
      val = $(this).val();
      if(val > 0){
        $('.field, .addr, .custperson').fadeOut();
      } else {
        $('.field, .addr, .custperson').fadeIn();
      }
    });
  })
</script>
<?php echo $footer; ?>