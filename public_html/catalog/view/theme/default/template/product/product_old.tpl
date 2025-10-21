<?php echo $header; ?>
<div class="lc">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
    <?php } ?>
  </ul>
  <div class="product" id="product">
    <?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <div class="block1">
      <div class="images">
        <? if(count($images) > 5) { ?>
          <a id="prev"></a>
        <? } ?>
        <div class="imgout">
          <div class="imgin">
            <? foreach($images as $key => $image) { ?>
              <div class="itm"><a href="<? echo $image['popup'] ?>" <?php if($key != 0) { ?> data-fancybox="fullfancy" <?php } ?> rel="fullfanc" ><img data-popup="<? echo $image['popup'] ?>" data-popup2="<? echo $image['popup2'] ?>" src="<? echo $image['thumb'] ?>"></a></div>
            <? } ?>
          </div>
        </div>
        <? if(count($images) > 5) { ?>
          <a id="next"></a>
        <? } ?>
      </div>
      <div class="fullimage">
        <? if($v3d != '') { ?>
          <a class="b3d" id="v3d" href="#v3block"></a>
          <div id="v3block"><? echo $v3d ?></div>
        <? } ?>
        <? if($video != '') { ?>
          <a href="<? echo $video ?>" id="video" class="video"></a>
        <? } ?>
        <a href="<? echo $popup ?>" class="fullfanc" data-fancybox="fullfancy" rel="fullfanc" data-popup2="<? echo $popup2 ?>"><img class="lazy" data-class="lazy" data-src="<?php echo $thumb; ?>"></a>
        <? if(isset($promo) && !empty($promo)) {?>
          <? foreach($promo as $prom){ ?>
            <div class="promo" style="<? echo $prom['position']; ?>">
              <img src="<? echo $prom['image']; ?>" alt="">
              <span style="<? echo $prom['spanposition']; ?>"><? if($prom['usename']) { echo $prom['text']; } ?></span>
            </div>
          <? } ?>
        <? } ?>
      </div>
      <div class="parametrs">
        <?
        $width = 1;
        $height = 1;
        $length = 1;
        $weight = 1;
        ?>

        <?php foreach ($attribute_groups as $attribute_group) { ?>
         <div class="title"><?php echo $attribute_group['name']; ?></div>
         <div class="pars">
          <? $ak = 0; ?>
          <?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>
            <div class="item" <? if($key > 5) { ?> style="display:none" data-hide-item="1" <? } ?>>
              <? $ak = $key; ?>
              <? if($attribute['attribute_id'] == 45){ ?>
                <? $height = $attribute['text']; ?>
              <? } ?>
              <? if($attribute['attribute_id'] == 44){ ?>
                <? $length = $attribute['text']; ?>
              <? } ?>
              <? if($attribute['attribute_id'] == 43){ ?>
                <? $width = $attribute['text']; ?>
              <? } ?>
              <? if($attribute['attribute_id'] == 13){ ?>
                <? $weight = $attribute['text']; ?>
              <? } ?>
              <div><?php echo $attribute['name']; ?>:</div>
              <div><?php echo $attribute['text']; ?></div>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
      <div class="pdf">
        <?php if(!empty($downloads) && $category_id!= 3){ ?>
          <?php foreach($downloads as $download){ ?>
            <?php if(!$download['href']) continue; ?>
            <a href="<?php echo $download['href']; ?>" title="">
              <?php echo $download['name']; ?>
            </a>
            <?php if($download['size']){ ?>
              <span><?php echo $download['size'] . ", " . strtoupper($download['ext']);?></span>
            <?php } ?>
          <?php } ?>

        <?php } ?>
        <? if($category_id!= 250 && $category_id!= 60 && $category_id!= 3){ ?>
          <a class="calc">Рассчитать окупаемость</a>
        <? } ?>
      </div>
    </div>

    <div class="price">
      <? if(isset($ak) && $ak > 5) {?>
        <button value="1" class="showitems">Показать все</button>
      <? } ?>
      <!-- <button value="Калькулятор" class="calc">Калькулятор расчета прибыли</button> -->
      <? if($video != '' && isset($ak) && $avtomat){ ?>
        <iframe frameborder="0" allowfullscreen height="285" src="<? echo $video ?>" style="max-width:100%;top: 160px;right: 0;" width="510"></iframe>
      <? } ?>
    </div>
    <div class="price"> 
      <? if(!$special) { ?>
        <div class="pr"><? if($price) {?> <? if($price && $avtomat){ ?><span>от</span><? } ?> <?php echo $price; ?><? } ?>
      </div>
    <? } else { ?>
      <div class="specprice">
        <div class="oldpr"><? if($avtomat) {?> <span>от</span> <?php echo $price; ?><? } ?>
      </div>
      <div class="newpr"><? if($avtomat) {?> <span>от</span> <?php echo $special; ?><? } ?>
    </div>
  </div>
<? } ?>


<script crossorigin="anonymous"
 src="https://shop.otpbank.ru/form/js/form.min.js">
</script><script crossorigin="anonymous"
 src="https://shop.otpbank.ru/form/js/form.min.js">
</script>

<? if(!$price){ ?>
  <button value="Цена по запросу" class="request">Цена по запросу</button><a class="fastorder">Быстрый заказ</a>
  <a class="getlis" id="getLis" href="#lising-box" data-width="600">Лизинг</a>
    <div id="lising-box" style="display: none;">
          <iframe id="arenzaWidget" src="https://crmpro.arenza.ru/partners/oec0z3hqc2Xo6DoPaaxV95TBoVqBMvl-8QOordPLLHs/widget?iframe=true" height="100%" width="100%" frameborder="0"> </iframe>
          <script>
            window.addEventListener('message', function(e) {
              var iframe = document.getElementById('arenzaWidget');
              var eventName = e.data[0];
              var data = e.data[1];
              switch(eventName) {
                case 'setHeight':
                iframe.style.height = data + "px";
                break;
              }
            }, false);
          </script>
        </div>
<? } else{ ?>
  <? if($avtomat) {?>
    <a href="javascript:void(0)" class="getoffer">получить индивидуальное предложение</a>
    <a class="getlis" id="getLis" href="#lising-box" data-width="600" style="
    float: right;
    margin-right: 25px;
    margin-top: 20px;
">Лизинг</a>


<button class="request" style="
    float: right!important;
    margin-right: 25px;
    margin-top: 20px;
    clear: both;
    width: 300px!important;
" onclick="javascript:otpform.start({
view: 'modal',
accessID: '7390',
tradeID: '14798',
creditFirstPaymentFrom: '0',
creditFirstPaymentTo: '90',
creditTermFrom: '3',
creditTermTo: '36',
creditType: '2',
hostname: 'https://vend-shop.com/blog/rassrochka-oformlena/',
items: [{
	'name': '<?php echo $heading_title; ?>',
	'price': <?php echo (int)str_replace(" ", "", $price); ?>,
	'count': 1,
}],
});" type="button">Рассрочка 0%</button>

    <div id="lising-box" style="display: none;">
          <iframe id="arenzaWidget" src="https://crmpro.arenza.ru/partners/oec0z3hqc2Xo6DoPaaxV95TBoVqBMvl-8QOordPLLHs/widget?iframe=true" height="100%" width="100%" frameborder="0"> </iframe>
          <script>
            window.addEventListener('message', function(e) {
              var iframe = document.getElementById('arenzaWidget');
              var eventName = e.data[0];
              var data = e.data[1];
              switch(eventName) {
                case 'setHeight':
                iframe.style.height = data + "px";
                break;
              }
            }, false);
          </script>
        </div>
  <? } else { ?>
    <? if($category_id == 3) { ?>
      <button id="add" value="В корзину">В корзину</button><a class="fastorder getoffer">запросить прайс
      на ингредиенты</a>
    <? } else { ?>
      <button id="add" value="В корзину">В корзину</button><a class="fastorder">Быстрый заказ</a>
    <? } ?>
  <? } ?>
<? } ?>
</div>
<div class="description">
  <div class="title">Описание</div>
  <div class="desin"><?php echo $description; ?></div>
</div>
<div class="descbut"></div>

<div class="tags">
  <?php 
    if($tags){ ?>
        <h3>Подборки:</h3>
    <?php }
   ?>
  <?php foreach($tags as $tag){ ?>
    <a href="<?php echo $tag['href'] ?>/"><?php echo $tag['tag']; ?></a>
  <?php } ?>
</div>
</div>
<div class="block2">
  <? $count = 0; ?>
  <?php if ($options) { ?>
    <? $service_line = ""; ?>
    <? $i = 1; ?>
    <div class="switchoptions">
      <?php foreach ($options as $option) { ?>
        <div class="num"><? echo $i ?></div>
        <? $i++; ?>
        <?php if ($option['type'] == 'radio') { ?>
          <div class="title"><?php echo $option['name']; ?></div>
          <?php foreach ($option['product_option_value'] as $option_value) { ?>
            <? $count++; ?>
            <div>
              <input type="radio" id="r<?php echo $option_value['product_option_value_id']; ?>" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>">
              <label for="r<?php echo $option_value['product_option_value_id']; ?>"> <span> </span><span><?php echo $option_value['name']; ?><? if($option_value['description'] && $option_value['description']!='') { ?>
                <a class="inf"></a>
                <? } ?></span>
                
                <div class="popupopt">
                  <div><img class="lazy" data-src="<? echo $option_value['image'] ?>" alt=""></div>
                  <div><? echo $option_value['description'] ?></div>
                </div>
              </label>
              <?php if ($option_value['price']) { ?>
                <!-- <div class="optpr"><?php echo $option_value['price']; ?></div> -->
              <? } ?>
            </div>
          <? } ?>
        <? } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
          <div class="title"><?php echo $option['name']; ?></div>
          <?php foreach ($option['product_option_value'] as $option_value) { ?>
            <? $count++; ?>
            <div>
              <input type="checkbox" id="c<?php echo $option_value['product_option_value_id']; ?>" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>">
              <label for="c<?php echo $option_value['product_option_value_id']; ?>"> <span> </span><span><?php echo $option_value['name']; ?><? if($option_value['description'] && $option_value['description']!='') { ?><a class="inf"></a> <? } ?></span>

               <div class="popupopt">
                <div><img class="lazy" data-src="<? echo $option_value['image'] ?>" alt=""></div>
                <div><? echo $option_value['description'] ?></div>
              </div>
            </label>
            <?php if ($option_value['price']) { ?>
              <div class="optpr"> &nbsp;<?php //echo $option_value['price']; ?></div>
            <? } ?>
          </div>
        <? } ?>
      <? } ?>
    <? } ?>
    <div class="flag"></div>
  </div>
<? } else { $service_line = "service_line"; }?>
<?php if ($count < 7) { 
  $service_line = "service_line"; 
} ?>
<div class="services <? echo $service_line; ?>">
  <div> <span>Настройка полок и подбор спиралей</span>
    <p>Мы бесплатно адаптируем торговый автомат под ваш товар, в соответствии с заполненной вами планограммой</p>
  </div>
  <div> <span>Прошивка под любую валюту</span>
    <p>Специалисты нашего IT-центра бесплатно запрограммируют платежные системы вендинговых автоматов под валюту любой страны</p>
  </div>
  <div> <span>Гарантия и техподдержка</span>
    <p>Гарантия 12 месяцев. Техподдержка осуществляется по телефону: 8 (800) 775-73-49</p>
  </div>
</div>
<div class="total">
  <input type="hidden" name="product_id" value="<? echo $product_id ?>">
  <? if($price){ ?>
    <?php if ($options) { ?>
      <? if($avtomat) {?>
        <a href="javascript:void(0)" class="getoffer" id="getOffer">рассчитать выбранную комплектацию</a>
      <? } else { ?>
        <? if($category_id == 3) { ?>
          <button id="add2" value="В корзину">В корзину</button><a class="fastorder getoffer">запросить прайс
          на ингредиенты</a>
        <? } else { ?>
          <button id="add2" value="В корзину">В корзину</button><a class="fastorder">Быстрый заказ</a>
        <? } ?>
      <? } ?>
    <? } ?>
  <? } ?>
  <? if($category_id != 60 || $category_id != 250) { ?>
    <div class="totalprice" style="display: none;"><span>Всего: </span><span id="totalprice"><? if($avtomat) {?> <span>от</span> <? } ?><?php echo $price; ?></span></div>
  <? } ?>
</div>
</div>
<div class="block3">
  <div class="delivery">
    <div class="title">Доставка</div>
    <form id="deliveryform">
      <div>
        <label>Город доставки</label>
        <input placeholder="В какой город доставить?" name="delcity" value="<? echo $city; ?>">
      </div>
      <div>
        <label>Количество:</label>
        <input name="qq" value="1">
      </div>
      <div>
        <label>Ширина:</label>
        <input value="<? echo trim($width) ?>" name="width">

      </div>
      <div>
        <label>Глубина:</label>
        <input value="<? echo trim($length) ?>" name="length">
      </div>
      <div> 
        <label>Высота:</label>
        <input value="<? echo trim($height) ?>" name="height">
      </div>
      <input type="hidden" id="sizedWeight" value="<? echo (int)$weight ?>"/>
      <input type="hidden" value="0" id="oversizedWeight"/>
      <input type="hidden" value="0" id="oversizedVolume"/>
      <input type="hidden" id="sizedVolume" value="<? echo ((int)$width/1000) * ((int)$length/1000) * ((int)$height/1000) ?>"/>
      <input type="hidden" id="derivalPoint">
      <input type="hidden" id="arrivalPoint">
      <button id="delcalc">Рассчитать</button>
      <div class="total"><div class="spiner"></div><span>Всего: </span><span id="tot">0</span></div>
    </form>
    <script>
      $(document).ready(function(){
        $("#deliveryform input").on("change", function(){
          width = parseInt($("#deliveryform input[name='width'").val());
          length = parseInt($("#deliveryform input[name='length'").val());
          height = parseInt($("#deliveryform input[name='height'").val());

          sizedVolume = (width/1000) * ( length/1000) * (height/1000);
          $("#sizedVolume").val(sizedVolume);
        });
      });
    </script>
    <div class="text">Доставка осуществляется транспортными компаниями до терминала и оплачивается отдельно, по факту прибытия автомата. <br/><br/>Внимание! Данный расчет является приблизительным. Для получения более точного расчета звоните 8-800-775-73-49 (бесплатные звонки по России)</div>
  </div>
  <div class="similar">
    <? if(!empty($similar)){ ?>
      <h2>Похожие модели</h2>
      <div class="buttons" id="sb">
        <div class="prev"></div>
        <div class="next"></div>
      </div>
      <div class="items" id="sim">
        <? foreach($similar as $product){ ?>
          <div class="item">
            <a href="<?php echo $product['href']; ?>"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
              <div class="name"><?php echo $product['name']; ?></div>
              <? if(!$product['price'] ) { ?>
                <div class="price request">Цена по запросу</div>
              <? } else { ?>
                <div class="price"><?php echo $product['price']; ?></div>
              <? } ?>
            </a></div>
          <? } ?>
        </div>

      <? } ?>

      <? if(isset($examples) && !empty($examples)) {?>
        <h2>Установленные</h2>
        <div class="buttons" id="eb">
          <div class="prev"></div>
          <div class="next"></div>
        </div>
        <div class="items" id="ex">
          <? foreach($examples as $product){ ?>
            <div class="item">
              <a href="<?php echo $product['full'] ?>" data-fancybox="installed" class="fanc" rel="example_group"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="" title=""></a>
            </div>
          <? } ?>
        </div>
      <? } ?>


    </div>

    <div class="questform">
      <div class="title">Есть вопросы? Задайте их менеджеру!</div>
      <div class="subtitle">Можете позвонить по телефону 8 (800) 775-73-49 или задать через форму ниже</div>
      <form data-template="request" id="feedback" data-subject="Заявка на товар">
        <div class="left">
          <input placeholder="Как вас зовут" name="name" required>
          <input placeholder="Контактный телефон*" name="phone" required>
          <input placeholder="Электронная почта" name="email" required>
          <input type="text" name="region" required placeholder="Ваш регион">
           <input type="text" name="amount" required placeholder="Количество автоматов">
        </div>
        <div class="right">
          <div class="radios">
              <div>
                <input type="radio" checked="checked" name="firma" id="m_fiz" value="Физическое лицо" required> <label for="m_fiz"> <span></span>Физическое лицо</label>
                
                <br>
              </div>
              <div>
                <input type="radio" name="firma" id="m_jur" value="Юридическое лицо" required> <label for="m_jur"> <span></span>Юридическое лицо </label>
              </div>
            </div>
            <div class="radios has">
                <br>
              <div>
                <input type="radio" checked="checked" name="has" id="m_has_y" value="Да" required> <label for="m_has_y"> <span></span>У меня есть автоматы</label>
              </div>

              <div>
                <input type="radio" name="has" id="m_has_no" value="Нет" required> <label for="m_has_no"> <span></span>У меня нет автоматов </label>
                <br>
                <br>
              </div>
           </div>
           <div>
              <input type="checkbox" name="credit" id="m_crdit"><label for="m_crdit"> Кредит/лизинг</label> 
          </div>
          <textarea placeholder="Какие автоматы интересуют?" required name="note"></textarea>
        </div>
        <button id="submit" class="submit">Отправить заявку</button>
        <div class="prv">
            Нажимая на кнопку "отправить", вы даете согласие на обработку <a href="https://vend-shop.com/privacy/">персональных данных</a>.
          </div>
      </form>
    </div>
  </div>
  <div class="block4">
    <? if((isset($products[3]) && !empty($products[3])) || (isset($products[5]) && !empty($products[5]) )) { ?>
      <div class="productfeature">
        <div class="top">
          <? if(isset($products[3]) && !empty($products[3]) ) {?>
            <div class="active" data-block="ind">Рекомендуемые ингредиенты</div>
          <? } ?>
          <? if(isset($products[5]) && !empty($products[5]) ) {?>
            <div data-block="spare">Запчасти</div>
          <? } ?>
        </div>
        <div class="cont">
          <? if(isset($products[3]) && !empty($products[3]) ) {?>
            <div class="ind" id="ind">
              <? $it = 0; ?>
              <? foreach($products[3] as $product) {?>
                <? if($it > 3) break; ?>
                <div class="item">
                  <a href="<? echo $product['href']; ?>">
                    <img class="lazy" data-src="<? echo $product['thumb'] ?>">
                    <div class="name"><? echo $product['name']; ?></div>
                    <div class="pars">
                    <!-- <div>Производитель: Carraro, Италия.</div>
                    <div>Вес: 1 кг</div>
                    <div>В упаковке: 6 кг</div> -->
                    <? if(!$product['price'] || $product['price'] == 0 ) { ?>
                      <div class="price">Цена по запросу</div>
                    <?php } else {?>
                      <div class="price"><? echo $product['price']; ?></div>
                    <?php } ?>
                  </div>
                </a>
              </div>
              <? $it++; ?>
            <? } ?>
            <div class="all"><a href="/index.php?route=product/category&path=3&relate=<? echo $product_id ?>">Все ингредиенты</a></div>
          </div>
        <? } ?>
        <? if(isset($products[5]) && !empty($products[5]) ) {?>
          <div class="ind" id="spare" style="display: none;">
            <? $it = 0; ?>
            <? foreach($products[5] as $product) {?>
              <? if($it > 3) break; ?>
              <div class="item">
                <a href="<? echo $product['href']; ?>">
                  <img class="lazy" data-src="<? echo $product['thumb'] ?>">
                  <div class="name"><? echo $product['name']; ?></div>
                  <div class="pars">
                    <? if(!$product['price'] || $product['price'] == 0 ) { ?>
                      <div class="price">Цена по запросу</div>
                    <?php } else {?>
                      <div class="price"><? echo $product['price']; ?></div>
                    <?php } ?>
                    
                  </div>
                </a>
              </div>
              <? $it++; ?>
            <? } ?>
            <div class="all"><a href="/index.php?route=product/category&path=5&relate=<? echo $product_id ?>">Все запчасти</a></div>
          </div>
        <? } ?>


      </div>
    </div>
  <?php } ?>

  <?php if(isset($products["related"])) { ?>
    <div class="productfeature">
      <?php if($products){ ?>
      <div class="top">

        <div class="w100" data-block="ind">Рекомендуемые товары</div>

      </div>
      
      <div class="cont">

        <div class="ind" id="ind">
          <? $it = 0; ?>
          <?php foreach($products["related"] as $product) {?>
              <? if($it > 3) break; ?>
              <div class="item"><img class="lazy" data-src="<? echo $product['thumb'] ?>">
                <a href="<? echo $product['href']; ?>">
                  <div class="name"><? echo $product['name']; ?></div>
                  <div class="pars">
                    <?php if((int)$product['price']) { ?>
                    <div class="price"><?php echo $product['price']; ?></div>
                  <?php } ?>
                  </div>
                </a>
              </div>
              <? $it++; ?>
          <? } ?>
        </div>


      </div>
      <?php } ?>
    </div>

  <?php } ?>
  <? if(isset($variants) && !empty($variants)) {?>
    <div class="whatsale">
      <div class="title">Что продают через автомат</div>
      <div id="whats">
        <? foreach($variants as $variant) {?>
          <div class="item"><a href="<? echo $variant['href'] ?>"><img src="<? echo $variant['thumb'] ?>">
            <div class="name"><? echo $variant['text'] ?></div></a>
          </div>
        <? } ?>
      </div>
      <div class="nav"><a class="prev"></a>
       <a class="next"></a>
     </div>
   </div>
 <? } ?>
 <div class="comments">
  <div id="vk_comments"></div>
  <script type="text/javascript">
    VK.Widgets.Comments("vk_comments", {limit: 15, width: "760", attach: false});
  </script>

</div>
<div class="catdesc">
  <p><? echo $category_description ?></p>
</div>
</div>


<? if($avtomat){ ?>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#offerform").feedback();
      $("#getOffer, .getoffer").on("click", function(){
        $("#offer .zvonok").text($(this).text());
        $("#offer #win").attr("data-subject", $(this).text());
        $("#offer, .winoverlay").show();
        $("#offer input#codph").focus();
      });

      $("#complects").next('input').val($('h1').text());

      $(".switchoptions input").on("change", function(){
        var labels = "";
        $(".switchoptions input[type=\"checkbox\"]:checked").map(function(i){
          label = $(this).next('label').find("span:eq(1)").text();
          if(labels == ""){
            labels = label;
          } else {
            labels = labels + ", " + label;
          }
        });

        $(".switchoptions input[type=\"radio\"]:checked").map(function(i){
          label = $(this).next('label').find("span:eq(1)").text();
          if(labels == ""){
            labels = label;
          } else {
            labels = labels + ", " + label;
          }
        });

        $("[data-template=\"request\"]").append("<input type=\"hidden\" name=\"opts\" value=\"" + labels + "\">");
      });
    });
  </script>
  <?}?>





  <script type="text/javascript">
    $(".fullimage img").load(function(){
      fi = $(".fullimage").offset().top + $(".fullimage").height();
      ifram = $(".price iframe").offset().top + $(".price iframe").height();

      dif = ifram - fi;

      if(dif > 45) dif = 0;

      if(dif > 4){
        $(".parametrs + .price").css({"position": "relative", "top": "-" + dif + "px"})
      }
    });
    $(document).ready(function(){


      if($(".desin").height() <= 390){
        $(".descbut").hide();
      }
      $(".descbut").on("click", function(){
        $(this).prev(".description").toggleClass("fulldesc");
        $(this).toggleClass("vp");
        $('body,html').animate({
          scrollTop: $(".description").offset().top
        }, 400);
      });

      $('.images .itm:eq(0) img').addClass("active");


      $(".switchoptions input[type=\"radio\"]:eq(0)").prop("checked", true);

      what = $("#whats");
      what.owlCarousel({
      lazyLoad: true,
      autoPlay: 3000000, //Set AutoPlay to 3 seconds

      items : 4,
      navigation : true,
      pagination : true,
      navigationText : ["", ""],
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3]

    });

      $('.whatsale .next').on("click", function(){
        what.trigger('owl.next');
      })
      $('.whatsale .prev').on("click", function(){
        what.trigger('owl.prev');
      })

      $('.whatsale .owl-pagination').prepend($('.whatsale .prev'));
      $('.whatsale .owl-pagination').append($('.whatsale .next'));



      sim = $(".similar #sim");
      sim.owlCarousel({
        lazyLoad: true, 
      autoPlay: 3000000, //Set AutoPlay to 3 seconds

      items : 1,
      navigation : true,
      pagination : false,
      navigationText : ["", ""],
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3]

    });


      ex = $(".similar #ex");
      ex.owlCarousel({
        lazyLoad: true,
      autoPlay: 3000000, //Set AutoPlay to 3 seconds

      items : 1,
      navigation : true,
      pagination : false,
      navigationText : ["", ""],
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3]

    });


      $('#sb .next').on("click", function(){
        sim.trigger('owl.next');
      })
      $('#sb .prev').on("click", function(){
        sim.trigger('owl.prev');
      })


      $('#eb .next').on("click", function(){
        ex.trigger('owl.next');
      })
      $('#eb .prev').on("click", function(){
        ex.trigger('owl.prev');
      })


      $('.inf').on('mouseover', function(e){
        x = e.clientX + 3;
        y = e.clientY;
        $(this).parent().next().show();
        $(this).parent().next().css({"left":x,"top":y});
      }).on('mouseout', function(){
       setTimeout(function(){
        if($(".popupopt:hover").length == 0){
         $(".popupopt").hide();
       }
     }, 500);
    //$(this).parent().next().hide();
  });

      $(".popupopt").on('mouseleave', function(){ $(this).hide() });

      $(".fastorder").on("click", function(e){
        e.stopPropagation();
        $("#fast").find('input[name="product"]').val($('h1').text());
        $(".win_white:eq(3), .winoverlay").show();
        return false;
      });

      $(".calc").on("click", function(e){
        e.stopPropagation();
        $("#calc, .winoverlay").show();
        return false;
      })

      $(".showitems").on("click", function(e){
        e.stopPropagation();
        $(".parametrs .item[data-hide-item]").toggle();

        if($(this).text() == "Скрыть"){
          $(this).text("Показать все");
        } else {
          $(this).text("Скрыть");
        }
      })



      $(".request").on("click", function(e){
        e.stopPropagation();
        $("#request").find('input[name="product"]').val($(this).prev().text());
        $(".win_white input[name=\"product\"]").val($('h1').text().trim());
        $(".win_white:eq(2), .winoverlay").show();
        return false;
      })

      $('.images img').click(function(e){
        e.preventDefault();
        $('.images img').removeClass("active");
        $(this).addClass('active');
        url500 = $(this).attr('data-popup');
        url350 = $(this).attr('data-popup2');
        url82 = $(this).attr('src');


        $('.fullfanc').attr('href', url500);
        $('.fullfanc').attr('data-popup2', url82);
        $('.fullfanc img').attr('src', url350);

        return false;
      });

      $("#getLis").fancybox({});

      $('a[rel="fullfanc"]').fancybox({
        openEffect : 'none',
        closeEffect : 'none',
        prevEffect : 'none',
        nextEffect : 'none',
        loop: true,
        'width': 750,
        'autoSize': false,

        arrows : true,
        helpers : {
          media : {},
          buttons : {}
        }
      });

      $('a[rel="example_group"]').fancybox({
        openEffect : 'none',
        closeEffect : 'none',
        'width': 750,
        'autoSize': false,

        arrows : true,
        helpers : {
          media : {},
          buttons : {}
        }
      });

      // $("#feedback").feedback();

      $('.top div').on('click', function(){
       $('.top div').removeClass('active');
       $(this).addClass('active');
       div = $(this).data('block');
       $('.cont > div').hide();
       $('#'+div).show();
     })
    });
  </script>
</div>
</div>
<? if($video != '') { ?>
  <script>
    $(document).ready(function() {
      $('#video').attr('rel', 'media-gallery').fancybox({
        openEffect : 'none',
        closeEffect : 'none',
        prevEffect : 'none',
        nextEffect : 'none',

        arrows : false,
        helpers : {
          media : {},
          buttons : {}
        }
      });
    });
  </script>
<? } ?>
<? if($v3d != '') { ?>
  <script>
    $(document).ready(function() {
      $('#v3d').attr('rel', 'media-gallery').fancybox({
        openEffect : 'none',
        closeEffect : 'none',
        prevEffect : 'none',
        nextEffect : 'none',

        arrows : false,
        helpers : {
          media : {},
          buttons : {}
        }
      });
    });
  </script>
<? } ?>
<script type="text/javascript">
  $('#add, #add2').on('click', function() {
    $.ajax({
      url: 'index.php?route=checkout/cart/add',
      type: 'post',
      data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
      dataType: 'json',
      beforeSend: function() {
      },
      complete: function() {
      },
      success: function(json) {
        $('.cartadded').show('swing');
        $('.cart #totals').text(json.total);
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  });
</script>
<script>
  $(document).ready(function(){
    $(".block2").after($(".service_line"));
    $("#calc input").on("change", function(){
      zak = parseFloat($("#zak").val());
      prod = parseFloat($("#prod").val());
      qd = parseFloat($("#qd").val());
      days = parseFloat($("#days").val());
      res = (((prod - zak) * qd )* days) * 0.94;
      $("#money").text(parseInt(res));

      arenda = $("#arenda").val();
      gsm = $("#gsm").val();
      salar = $("#salar").val();
      kvt = $("#kvt").val();
      power = $("#power").val();
      minus = parseInt(arenda) + parseInt(gsm) + parseInt(salar) + (parseInt(kvt) * parseInt(power));
      total = parseInt(res) - parseInt(minus);
      $("#totalmon").text(parseInt(total));
    });

    top_desc = $(".description").offset().top;
    top_fulim = $(".fullimage").offset().top + $(".fullimage").height();
    if(top_desc > top_fulim){
      $(".description").css({"width":"100%"});
    }

    top_desc = $(".description").offset().top + $(".description").height();
    top_fulim = $(".fullimage").offset().top + $(".fullimage").height();
    if(top_desc > top_fulim){
      $(".description").css({"width":"100%"});
    }

  });
</script>
<div class="cartadded">
  Товар добавлен в корзину
  <div class="cin">
    <img class="lazy" data-src="<? echo $mini ?>" alt="">
    <div class="buts">
      <a href="javascript:void(0)" class="getoffer continue">продолжить выбор товара</a>
      <a href="/cart/" class="getoffer">оформить заявку</a>
    </div>
  </div>
</div>

<?php echo $footer; ?>
