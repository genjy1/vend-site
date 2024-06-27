<?php echo $header; ?>
  <div class="lc">
  <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
    <?php $i=1; foreach ($breadcrumbs as $breadcrumb) { ?>
      <li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem">
      <a href="<?php echo $breadcrumb['href']; ?>" itemprop="name"><?php echo $breadcrumb['text']; ?></a><span>/</span>
      <meta itemprop="position" content="<?=$i?>" />
      </li>
    <?php $i++; } ?>
  </ul>
      <div class="cart">
        <h1>Корзина</h1>
        <form action="<? echo $action ?>" method='post' class="formacart">
        <div class="cartform">
          <div class="head">
            <div class="col1">Фото</div>
            <div class="col2">Наименование товара</div>
            <div class="col3">Количество, шт</div>
            <!-- <div class="col4">
            Стоимость
            </div> -->
          </div>
          <? $args = array(); ?>
          <?php foreach ($products as $product) { ?>
          <? $args[] = array(
              'MODEL' => $product['name'],
              'COUNT' => (float)$product['quantity'],
              'PRICE' => $product['total'],
          ); ?>
          <div class="item">
            <div class="col1"><img class="lazy" data-src="<? echo $product['thumb'] ?>"></div>
            <div class="col2">
              <div class="name"><? echo $product['name'] ?></div>
              <?php if ($product['options']) { ?>
              <div class="options">Опции:
              <?php foreach ($product['options'] as $option) { ?>
            <?php if ($option['type'] == 'radio') { ?>
            <div class="title"><?php echo $option['name']; ?></div>
            <?php foreach ($option['product_option_value'] as $option_value) { ?>
            <div>
              <input type="radio" id="r<?php echo $product['cart_id']; ?><?php echo $option_value['product_option_value_id']; ?>" name="option[<?php echo $product['cart_id']; ?>][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" <? if(in_array( $option['product_option_id'] ."-" . $option_value['product_option_value_id'],$product['option_selected'])) { ?> checked <? } ?>>
              <label for="r<?php echo $product['cart_id']; ?><?php echo $option_value['product_option_value_id']; ?>"><span></span><span><?php echo $option_value['name']; ?></span><? if($option_value['description'] != '') { ?><a class="inf"></a> <? } ?>

               <div class="popupopt">
                  <div><img class="lazy" data-src="<? echo $option_value['image'] ?>" alt=""></div>
                  <div><? echo $option_value['description'] ?></div>
                </div>

              </label>
              <?php if ($option_value['price']) { ?>
                    <!-- <div class="optprice"><?php echo $option_value['price']; ?></div> -->
            <? } ?>
            </div>
            <? } ?>
            <? } ?>
            <?php if ($option['type'] == 'checkbox') { ?>
            <div class="title"><?php echo $option['name']; ?></div>
            <?php foreach ($option['product_option_value'] as $option_value) { ?>
            <div>
              <input type="checkbox" id="c<?php echo $product['cart_id']; ?><?php echo $option_value['product_option_value_id']; ?>" name="option[<?php echo $product['cart_id']; ?>][<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" <? if(in_array( $option['product_option_id'] ."-" . $option_value['product_option_value_id'],$product['option_selected'])) { ?> checked <? } ?>>
              <label for="c<?php echo $product['cart_id']; ?><?php echo $option_value['product_option_value_id']; ?>"><span><?php echo $option_value['name']; ?></span><? if($option_value['description'] != '') { ?><a class="inf"></a> <? } ?>
               <div class="popupopt">
                  <div><img class="lazy" data-src="<? echo $option_value['image'] ?>" alt=""></div>
                  <div><? echo $option_value['description'] ?></div>
                </div>

              </label>
              <?php if ($option_value['price']) { ?>
                    <!-- <div class="optprice"><?php echo $option_value['price']; ?></div> -->
            <? } ?>
            </div>
            <? } ?>
            <? } ?>
              <? } ?>
              </div>
              <? } ?>
            </div>
            <div class="col3">
              <div class="qch">
                <a class="minus" onclick="minus(this)">- </a>
                  <input name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>">
                <a class="plus" onclick="plus(this)">+</a>
              </div>
            </div>
            <div class="col4">
              <!-- <div class="price">
              <?php echo $product['total']; ?>
              </div> -->
              <a class="remove" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"></a>
            </div>
          </div>
          <? } ?>
          <div class="item">
            <!-- <div class="total">Итого: <span>
              <?php foreach ($totals as $total) { ?>
                <? if($total['code'] !='total') continue; ?>
                <? echo $total['text'] ?>
              <? } ?>
            </span></div> -->
          </div>
          <input type="hidden" name="credit" value="0">
          <div class="buttons"><a href="javascript:history.back();">Вернуться в магазин</a>
            <div class="checkout"><a onclick='retailcreditdialog(<?php echo json_encode($args); ?>,2);' href="javascript:void(0);">Оформить кредит</a>
            <a href="<?php echo $checkout ?>">Оформить заказ</a></div>
          </div>
        </div>
        </form>
      </div>
    </div>
<script>
  $('.options input').on("change", function(){
    $('.cart form').submit();
  });
  function plus(el){
    $(el).prev('input').val(parseInt($(el).prev('input').val()) + 1);
    $(el).closest('form').submit();
  }
  function minus(el){
    val = parseInt($(el).next('input').val()) - 1;
    if(val < 1){
      val = 1;
    }
    $(el).next('input').val(val);
    $(el).closest('form').submit();
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){

  $('.inf').on('mouseover', function(e){
    x = e.clientX + 4;
    y = e.clientY;
    $(this).next().show();
    $(this).next().css({"left":x,"top":y});
  }).on('mouseout', function(){
    $(this).next().hide();
  });
});
</script>


<div id="credit-modal" class="box-modal" data-url="/" style="display: none;">
    <div class="close-btn arcticmodal-close">закрыть</div>
    <h2>Заявка на кредит</h2>
    <form action="/shop-rcs-plugin-ask/" method="post">
      <input type="hidden" name="product_id" value="828">
      <input type="hidden" name="sku_id" value="914">
      <input type="hidden" name="quantity" value="1">
      <div class="wa-form">
        <div class="wa-field wa-required">
          <div class="wa-name">Фамилия Имя Отчество</div>
          <div class="wa-value">
            <input type="text" name="credit[Name]" value="">
          </div>
        </div>
        <div class="wa-field wa-required">
          <div class="wa-name">Контактный телефон</div>
          <div class="wa-value">
            
            <input type="text" name="credit[phone]" value="" id="rcs-phone">
          </div>
        </div>
        <div class="wa-field wa-required">
          <div class="wa-name">Срок, мес</div>
          <div class="wa-value">
            <select name="credit[term]">
                            <option value="6" selected="">6</option>
                            <option value="10">10</option>
                            <option value="12">12</option>
                            <option value="24">24</option>
                            <option value="30">30</option>
                            <option value="36">36</option>
                          </select>
          </div>
        </div>
        <div class="wa-field">
          <div class="wa-name">Сумма, руб</div>
          <div class="wa-value">
            <span id="summ-tovar">174237</span>
            <input type="hidden" name="credit[SummTovar]">
          </div>
        </div>
        <div class="wa-field">
          <div class="wa-name">Первоначальный взнос (не менее 10%), руб</div>
          <div class="wa-value">
            <input type="text" name="credit[start_summ]" value="0">
          </div>
        </div>
        <div class="wa-field">
          <div class="wa-name">Регион регистрации</div>
          <div class="wa-value">
            <select name="credit[Region]">
                            <option selected="">Москва</option>
                            <option>Санкт-Петербург</option>
                            <option>Ленинградская область</option>
                            <option>Московская область</option>
                            <option>Казань</option>
                            <option>Татарстан</option>
                            <option>Екатеринбург</option>
                            <option>Нижний Новгород</option>
                            <option>Волгоград</option>
                            <option>Уфа</option>
                            <option>Калининрад</option>
                            <option>Саратов</option>
                            <option>Липецк</option>
                            <option>Оренбург</option>
                            <option>Магнитогорск</option>
                            <option>Пермь</option>
                          </select>
          </div>
        </div>
        <div class="wa-field">
          <div class="wa-name">Ежемесячный доход</div>
          <div class="wa-value">
            <input type="text" name="credit[PersonProfit]" value="30000">
          </div>
        </div>
        <div class="wa-field">
          <div class="wa-value">
            <input type="checkbox" name="credit[approve]" value="1"> Согласен с <a target="_blank" href="http://retail-credit.ru/agreement.htm">условиями Соглашения</a>
          </div>
        </div>
        <input name="credit[Rass]" type="hidden" value="0">
        <div class="wa-field">
          <div class="wa-value">
            <input type="submit" name="credit[sub_form]" value="Отправить заявку">
          </div>
        </div>
      </div>
    </form>
    <p>В сумму включена единоразовая комиссия за оформление кредита – 5% от стоимости товара. БЕЗ СКРЫТЫХ КОМИССИЙ И ПЛАТЫ ЗА ВЫДАЧУ.</p>
<ol>
  <li><i>Я выбрал товар и хочу приобрести его в кредит.</i><br>
    Заполните кредитную заявку и нажмите кнопку «Отправить заявку».</li>
  <li><i>Я заполнил и отправил кредитную заявку на сайте интернет-магазина. Что дальше?</i><br>
    В течение часа ваша заявка будет обработана, после чего с вами свяжется кредитный
    менеджер.<br>
    Вам нужно будет ответить на несколько стандартных вопросов и подтвердить свои паспортные
    данные.</li>
  <li><i>Кредитный менеджер позвонил мне.</i><br>
    После звонка ваша заявка будет рассматриваться банком.<br>
    Решение будет сообщено вам в течение 2-х часов.</li>
  <li><i>Мне позвонили и сообщили, что банк одобрил мою заявку. Что дальше?</i><br>
    Вам позвонит кредитный менеджер и договорится о времени приезда к вам для подписания
    платежных документов.<br>
    После подписания договора Вы ожидаете доставки вашей покупки и при получении оплачиваете
    первоначальный взнос.</li>
</ol>
<p>Заявки, принятые после 19:00 рабочего дня и в выходные, обрабатываются с 10:00 первого рабочего дня.</p>
  </div>

<?php echo $footer; ?> 