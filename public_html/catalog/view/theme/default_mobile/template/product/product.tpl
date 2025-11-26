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
      <div class="fullimage">
        <? if($v3d != '') { ?>
        <a class="b3d" id="v3d" href="#v3block"></a>
        <div id="v3block"><? echo $v3d ?></div>
        <? } ?>
        <? if($video != '') { ?>
        <a href="<? echo $video ?>" id="video" class="video"></a>
        <? } ?>
        <a href="<? echo $popup ?>" class="fullfanc" data-fancybox="fullfancy" rel="fullfanc" data-popup2="<? echo $popup2 ?>"><img src="<?php echo $thumb; ?>"></a>
        <? if(isset($promo) && !empty($promo)) {?>
        <? foreach($promo as $prom){ ?>
        <div class="promo" style="<? echo $prom['position']; ?>">
          <img src="<? echo $prom['image']; ?>" alt="">
          <span style="<? echo $prom['spanposition']; ?>"><? if($prom['usename']) { echo $prom['text']; } ?></span>
        </div>
        <? } ?>
        <? } ?>
      </div>
      <div class="images owl-carousel owl-theme"">
        <? foreach($images as $key => $image) if ($image['popup']) { ?>
        <div><a href="<? echo $image['popup'] ?>" rel="fullfanc" <?php if($key != 0) { ?> data-fancybox="fullfancy" <?php } ?> ><img data-popup="<? echo $image['popup'] ?>" data-popup2="<? echo $image['popup2'] ?>" src="<? echo $image['thumb'] ?>"></a></div>
        <? } ?>
      </div>
      <div class="price" style="margin: 15px 0 30px">
      <? if(!$special) { ?>
      <div class="pr"><? if($avtomat) {?> <span>от</span> <? } ?><?php echo $price; ?>
      </div>
      <? } else { ?>
      <div class="specprice">
        <div class="oldpr"><? if($avtomat) {?> <span>от</span> <? } ?><?php echo $price; ?>
        </div>
        <div class="newpr"><? if($avtomat) {?> <span>от</span> <? } ?><?php echo $special; ?>
        </div>
      </div>
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
          <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
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
          <div class="item">
            <div><?php echo $attribute['name']; ?>:</div>
            <div><?php echo $attribute['text']; ?></div>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
        <div class="pdf<?= count($downloads) < 1 ? 'hidden' : '' ?>">
          <?php if(!empty($downloads) && $category_id!= 3){ ?>
          <?php foreach($downloads as $download){ ?>
          <a href="<?php echo $download['href']; ?>" title="">
            <?php echo $download['name']; ?>
          </a>
          <span><?php echo $download['size'] . ", " . strtoupper($download['ext']);?></span>
          <?php } ?>

          <?php } ?>
        </div>
        <div class="calculator-btn__wrapper">
          <? if($category_id!= 250 && $category_id!= 60 && $category_id!= 3){ ?>
          <button data-target="#calc" class="text-btn calculator-btn">Рассчитать окупаемость</button>
          <? } ?>
        </div>
      </div>
      <div class="price">
      <?
      if (strpos($video, "autoplay") > 0) $video = $video."&mute=1&enablejsapi=1";

      $s1 = strpos($video, "/embed/");
      $s2 = strpos($video, "?");
      if (!$s2) $s2 = strlen($video);
      $id = substr($video, $s1 + 7, $s2 - $s1 - 7);
      if (strpos($video, '?') > 0 && $id)
      $video = $video.'&loop=1&playlist='.$id;
      else $video = $video.'/?loop=1&playlist='.$id;

      if($video != ''){ ?>
        <iframe allow='autoplay' frameborder="0" height="285" src="<? echo $video ?>" style="max-width:100%;top: 160px;right: 0;" width="510"></iframe>
      <? } ?>
      </div>

    </div>
    <div class="price">
      <? if(!$price){ ?>
      <button value="Цена по запросу" class="request">Цена по запросу</button><button data-target="#fast" class="fastorder btn">Быстрый заказ</button>
      <? } else{ ?>
      <? if($avtomat) {?>
      <button data-target="#offer" href="javascript:void(0)" class="btn getoffer">получить индивидуальное предложение</button>
       <button data-target="#fast" data-subject="Купить в кредит" class="btn buy-kredit">Купить в кредит</button>
      <? } else { ?>
      <? if($category_id == 3) { ?>
      <button id="add" value="В корзину">В корзину</button><a class="fastorder getoffer">запросить прайс
      на ингредиенты</a>
      <? } else { ?>
      <button id="add" value="В корзину">В корзину</button><a class="fastorder">Быстрый заказ</a>
      <? } ?>
      <? } ?>
      <? } ?>

      <a class="getlis" id="getLis" href="#lising-box" data-width="600" style="display: none">Лизинг</a>
     <!-- <script crossorigin="anonymous"
 src="https://shop.otpbank.ru/form/js/form.min.js">
</script><script crossorigin="anonymous"
 src="https://shop.otpbank.ru/form/js/form.min.js">
</script>-->
<script>
$("#preloader").fadeOut(300);
</script>
<button class="request" style="
display: none;
    margin-right: 25px;
    margin-top: 0px;
    clear: both;
    width: 300px!important;
    margin-bottom: 20px;
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
    </div>
    <div class="description">
      <div class="title">Описание</div>
      <div class="desin"><p><?php echo $description; ?></p></div>
    </div>
    <div class="descbut"></div>
    <div class="tags">
      <?php 
      if($tags){ ?>
      <h3>Подборки:</h3>
      <?php }
      ?>
      <?php foreach($tags as $tag){ ?>
      <a href="<?php echo $tag['href'] ?>"><?php echo $tag['tag']; ?></a>
      <?php } ?>
    </div>
  </div>
  <div class="block2">
   <!-- <?php if (false/*$options*/) { ?>
    <? $i = 1; ?>
    <div class="switchoptions">
      <?php foreach ($options as $option) { ?>
 
      <? $i++; ?>
      <?php if ($option['type'] == 'radio') { ?>
      <div class="title"><?php echo $option['name']; ?></div>
      <?php foreach ($option['product_option_value'] as $option_value) { ?>
      <div>
        <input type="radio" id="r<?php echo $option_value['product_option_value_id']; ?>" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>">
        <label for="r<?php echo $option_value['product_option_value_id']; ?>"> <span> </span><span><?php echo $option_value['name']; ?></span>
          <? if($option_value['description'] && $option_value['description']!='') { ?>
          <a class="inf"></a>
          <? } ?>
          <div class="popupopt">
            <div><img class="lazy" data-src="<? echo $option_value['image'] ?>" alt=""></div>
            <div><? echo $option_value['description'] ?></div>
          </div>
        </label>
        <?php if ($option_value['price']) { ?>
        <div class="optpr"> &nbsp; <?php //echo $option_value['price']; ?></div>
        <? } ?>
      </div>
      <? } ?>
      <? } ?>
      <?php if ($option['type'] == 'checkbox') { ?>
      <div class="title"><?php echo $option['name']; ?></div>
      <?php foreach ($option['product_option_value'] as $option_value) { ?>
      <div>
        <input type="checkbox" id="c<?php echo $option_value['product_option_value_id']; ?>" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>">
        <label for="c<?php echo $option_value['product_option_value_id']; ?>"> <span> </span><span><?php echo $option_value['name']; ?></span>
         
        </label>
        <? if($option_value['description'] && $option_value['description']!='') { ?>
        <a class="inf"></a>
        <div class="popupopt">
          <div><img class="lazy" data-src="<? echo $option_value['image'] ?>" alt=""></div>
          <div><? echo $option_value['description'] ?></div>
        </div>
        <? } ?>
        <?php if ($option_value['price']) { ?>
        <div class="optpr"> &nbsp;<?php //echo $option_value['price']; ?></div>
      </div>
      <? } ?>

      <? } ?>
      <? } ?>
      <? } ?>
      <div class="flag"></div>
    </div>
    <? } ?>
    <div class="total" style="display: none">
      <? if($category_id != 60 || $category_id != 250) { ?>
      <div class="totalprice" style="display: none;"><span>Всего: </span><span id="totalprice"><? if($avtomat) {?> <span>от</span> <? } ?><?php echo $price; ?></span></div>
      <? } ?>
      <input type="hidden" name="product_id" value="<? echo $product_id ?>">
      <?php if ($options) { ?>
      <? if($avtomat) {?>
      <a href="javascript:void(0)" class="getoffer">рассчитать выбранную комплектацию</a>
      <? } else { ?>
      <? if($category_id == 3) { ?>
      <button id="add2" value="В корзину">В корзину</button><a class="fastorder getoffer">запросить прайс
      на ингредиенты</a>
      <? } else { ?>
      <button id="add2" value="В корзину">В корзину</button><a class="fastorder">Быстрый заказ</a>
      <? } ?>
      <? } ?>
      <?}?>
    </div>-->
    <div class="services">
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
  </div>
  <div class="similar examples" style="margin-top: 20px">
  <? if(isset($examples) && !empty($examples)) {?>
  <div class="relative">
    <h2>Установленные автоматы</h2>
    <div class="buttons" id="eb">
      <? if(count($examples) > 1) { ?>
      <div class="prev"></div>
      <div class="next"></div>
      <? } ?>
    </div>
  </div>
  <div class="items owl-carousel owl-theme" id="ex">
    <? foreach($examples as $product){ ?>
    <div class="item">
      <a href="<?php echo $product['full'] ?>" data-fancybox="installed" class="fanc" rel="example_group"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="" title=""></a>
    </div>
    <? } ?>
  </div>
  <? } ?></div>
  <div class="block3">
    <div class="delivery">
      <div class="title">Доставка</div>
      <form id="deliveryform">
        <div>
          <label>Город доставки</label>
          <input placeholder="В какой город доставить?" name="delcity">
        </div>
        <div>
          <label>Количество:</label>
          <input name="qq" value="1">
        </div>
        <div>
          <label>Ширина:</label>
          <input value="<? echo trim($width) ?>"  name="width">
        </div>
        <div>
          <label>Глубина:</label>
          <input value="<? echo trim($length) ?>"  name="length">
        </div>
        <div> 
          <label>Высота:</label>
          <input value="<? echo trim($height) ?>"  name="height">
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
      <div class="relative">
        <h2>Похожие модели</h2>
        <div class="buttons" id="sb">
          <? if(count($similar) > 1) { ?>
          <div class="prev"></div>
          <div class="next"></div>
          <? } ?>
        </div>
      </div>
      <div class="items owl-carousel owl-theme" id="sim">
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




      </div>
      <div class="questform">
      <div class="title">Есть вопросы? Задайте их менеджеру!</div>
      <div class="subtitle">Можете позвонить по телефону 8 (800) 775-73-49 или задать через форму ниже</div>
      <form data-template="request" id="feedback">
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
        <div class="prv">
            Нажимая на кнопку "отправить", вы даете согласие на обработку <a href="https://vend-shop.com/privacy/">персональных данных</a>.
          </div>
        <button id="submit" class="submit">Отправить заявку</button>
        
      </form>
    </div>
    </div>
    <div class="block4">
      <? if(isset($products[3]) && !empty($products[3]) && isset($products[5]) && !empty($products[5]) ) {?>
      <div class="productfeature">
        <div class="top">
          <? if(isset($products[3]) && !empty($products[3]) ) {?>
          <div class="active" data-block="ind">Рекомендуемые ингредиенты</div>
          <? } ?>
          <? if(isset($products[5]) && !empty($products[5]) ) {?>
          <div data-block="spare">Запчасти</div>
          <? } ?>
        </div>
        <? if(isset($products[3]) && !empty($products[3]) ) {?>
        <div class="cont">
          
          <div class="ind" id="ind">
            <? $it = 0; ?>
            <? foreach($products[3] as $product) {?>
            <? if($it > 3) break; ?>
            <div class="item">
              <a href="<? echo $product['href']; ?>">
                <img src="<? echo $product['thumb'] ?>">
              
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
                  <img src="<? echo $product['thumb'] ?>">
                
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
              <div class="all"><a href="/index.php?route=product/category&path=5&relate=<? echo $product_id ?>">Все запчасти</a></div>
            </div>
            
          </div>
          <? } ?>
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
              <div class="item"><img src="<? echo $product['thumb'] ?>">
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
          <div class="whats owl-carousel owl-theme">
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
          VK.Widgets.Comments("vk_comments", {limit: 15, attach: false});
        </script>
      </div>
      <div class="catdesc">
        <p><? echo $category_description ?></p>
      </div>
    </div>

    <? if($avtomat){ ?>
    <script type="text/javascript">

    // var focused = $('input#codph'); //this is just to have a starting point

    // $('.getoffer').on('click', function () { // trigger touch on element to set focus
    //   focused.next('input').trigger('touchstart'); // trigger touchstart

    // });

    // $('input#codph').on('touchstart', function () {

    //      setTimeout(function(){focused.focus()});
    //     $(this).focus();   // inside this function the focus works
    //     focused = $(this); // to point to currently focused
    // });


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

      $("#getOffer, .getoffer").on("click", function(){
        $("#offer .zvonok").text($(this).text());
        $("#offer, .winoverlay").show();
        $("#offer input#codph").focus();
      });
      $(".switchoptions input").on("change", function(){
        labels = "";
        $(".switchoptions input[type=\"checkbox\"]:checked").map(function(i){
          label = $(this).next('label').find("span:eq(1)").text();
          if(labels == ""){
            labels = label;
          } else {
            labels = labels + ", " + label;
          }
        });
        $("#complects input").remove();
        $("#complects").append("<input type=\"hidden\" name=\"opts\" value=\"" + labels + "\">");
      });
    });
  </script>
  <?}?>
  
  <script>
    $(document).ready(function(){
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
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){

      $(".calc").on("click", function(e){
        e.stopPropagation();
        $("#calc, .winoverlay").show();
        return false;
      })

      imgs = $(".images");
      imgs.owlCarousel({
      autoPlay: 3000000, //Set AutoPlay to 3 seconds
      items : 4,
      nav : false,
      dots:false
      // pagination : true,
      //navigationText : ["", ""],
      // itemsDesktop : [1199,3],
      // itemsDesktopSmall : [979,3]
    });
      $(".switchoptions input[type=\"radio\"]:eq(0)").prop("checked", true);

      what = $(".whats");
    what.owlCarousel({
      autoPlay: 3000000, //Set AutoPlay to 3 seconds
      items : 1,
      nav : false,
      //pagination : true,
      //navigationText : ["", ""],
      //itemsDesktop : [1199,3],
      //itemsDesktopSmall : [979,3]
    });

      $('.whatsale .next').on("click", function(){
      	console.log(111);
        what.trigger('next.owl.carousel');
      })
      $('.whatsale .prev').on("click", function(){
        what.trigger('prev.owl.carousel');
      })

      $('.whatsale .whats').prepend($('.whatsale .prev'));
      $('.whatsale .whats').append($('.whatsale .next'));


      sim = $(".similar #sim");
      sim.owlCarousel({
        lazyLoad:true, 
        items:1,
        nav:true,
        dots:false
      });


      ex = $("#ex");
      ex.owlCarousel({
        lazyLoad:true,
        loop:true, 
        items:3,
        margin: 15,
        nav:true,
        dots:false,
        responsive : {
            // breakpoint from 0 up
            0 : {
              items:1
            },
            480 : {
              items:2
            },
            768 : {
              items:3
            }
        }
      });


      $('#sb .next').on("click", function(){
        sim.trigger('next.owl.carousel');
      })

      $('#sb .prev').on("click", function(){
        sim.trigger('prev.owl.carousel');
      })



      $('#eb .next').on("click", function(){
        ex.trigger('next.owl.carousel');
      })

      $('#eb .prev').on("click", function(){
        ex.trigger('prev.owl.carousel');
      })


      $('.inf').on('click', function(e){
        x = e.clientX + 4 - e.clientX;
        y = e.clientY;
        $(this).next().toggle();
        $(this).next().css({"left":x,"top":y});
      })

      $('.inf').on('mouseout', function(){
        $(this).next().hide();
      });

      $(".fastorder").on("click", function(e){
        e.stopPropagation();
         $(".win_white:eq(3) .zvonok").text("Быстрый заказ");
        $("#fast").find('input[name="product"]').val($('h1').text());
        $(".win_white:eq(3), .winoverlay").show();
        return false;
      });
     $(".buy-kredit").on("click", function(e){
        e.stopPropagation();
        $(".win_white:eq(3) .zvonok").text("Купить в кредит");
        $(".win_white:eq(3), .winoverlay").show();
        return false;
      });

      $(".request").on("click", function(e){
        e.stopPropagation();
        $("#request").find('input[name="product"]').val($(this).prev().text());
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

      // $('.fullfanc').fancybox({
      //   openEffect : 'none',
      //   closeEffect : 'none',
      //   prevEffect : 'none',
      //   nextEffect : 'none',
      //   loop: true,
      //   arrows : true,
      //   helpers : {
      //     media : {},
      //     buttons : {}
      //   }
      // });

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
