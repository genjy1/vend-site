<?php echo $header; ?>
    <div class="lc">
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
      <?php } ?>
    </ul>
<?php echo $content_top; ?>
    </div>
      <? echo $filter ?>
      <div class="lc">
      <div class="products">
        <? if ($youtube) { ?> <?php /*echo $youtube['link']*/ ?>
        <a href="tel:88007757349" style="clear: both;display: block; margin-bottom: 30px"> 
          <img class="lazy" data-src="image/catalog/123/banner-kkt-lost-version.png?v=2" alt="" style="max-width: 100%;">
        </a><?}?>
        <h1><?php echo $heading_title; ?></h1>
        <div class="grid">
          <?php foreach ($products as $product) { ?>
            <div class="product"><a href="<?php echo $product['href']; ?>"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
            <? if(isset($product['promos']) && !empty($product['promos'])) {?>
            <? foreach($product['promos'] as $promo) { ?>
              <div class="promo" style="<? echo $promo['position']; ?>">
                <img class="lazy" data-src="<? echo $promo['image']; ?>" alt="">
                <span style="<? echo $promo['spanposition']; ?>"><? if($promo['usename']) { echo $promo['text']; } ?></span>
              </div>
              <? } ?>
            <? } ?>
              <div class="name"><?php echo $product['name']; ?></div>
              <? if(!$product['price'] ) { ?>
                <div class="price request">Цена по запросу</div>
              <? } else { ?>
                <? if(!$product['special']){ ?>
                  <div class="price"><? if($avtomat) {?> <span>от</span> <?php echo $product['price']; } ?></div>
                <? } else { ?>
                  <div class="oldprice"><? if($avtomat) {?> <span>от</span> <?php echo $product['price']; } ?></div>
                  <div class="newprice"><? if($avtomat) {?> <span>от</span> <?php echo $product['special']; } ?></div>
                <? } ?>
              <? } ?>
              </a></div>
          <? } ?>
        </div>
        <?php echo $pagination; ?>
        <?php echo $content_bottom; ?>
        </div>
        </div>
        
        <script src="https://www.google.com/recaptcha/api.js?render=6Lcn7DgpAAAAAOtz5NCMN3R4TUUc-JjHYSzKUCJ6"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6Lcn7DgpAAAAAOtz5NCMN3R4TUUc-JjHYSzKUCJ6', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponseCat');
                recaptchaResponse.value = token;
            });
        });
    </script>
        
        <div class="pink_form" id="catchform">
            <div class="lc">
                <div class="text_form">Не нашли автомат под свой товар? Оставьте заявку, мы свяжемся с вами и подберем оборудование для Вашего бизнеса!</div>
                <div class="p_form">
                    <form id="feedback" data-template="request" data-subject="Не нашли товар">
                        <input placeholder="Как вас зовут" name="name">
                        <div class="teldiv"><input type="tel" name="ft" maxlength="2" value = "+7" required >
                        <input type="tel" name="code" value = "" placeholder="123" pattern="^\d+$" maxlength="3" required >
                        <input type="tel" name="phone" value = "" placeholder="456 78 90" pattern="^\d+$" maxlength="8" required ></div>
                        <input placeholder="Email" name="email" required>
                        <input type="text" name="region" required placeholder="Ваш регион">
                        <div class="radios">
                            <div>
                                <input type="radio" checked="checked" name="firma" id="c_fiz" value="Физическое лицо"> <label for="c_fiz"> <span></span>Физическое лицо</label>
                            </div>
                            <div>
                            <input type="radio" name="firma" id="c_jur" value="Юридическое лицо"> <label for="c_jur"> <span></span>Юридическое лицо </label>
                            </div>
                        </div>
                        <div class="radios has">
                            <div>
                                <input type="radio" checked="checked" name="has" id="c_has_y" value="Да"> <label for="c_has_y"> <span></span>У меня есть автоматы</label>
                            </div>
                        <div>
                        <input type="radio" name="has" id="c_has_no" value="Нет"> <label for="c_has_no"> <span></span>У меня нет автоматов </label>
                        </div>
                    </div>
                    <div>
                        <input type="checkbox" name="credit" id="c_crdit"><label for="c_crdit"> Кредит/лизинг</label> 
                    </div>
                    <div class="amount">
                        <input type="text" name="amount" required placeholder="Количество автоматов">
                    </div>
          
                    <div class="note">
                        <textarea name="note" required placeholder="Какие автоматы интересуют?"></textarea>
                    </div>
                    <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
					<input type="hidden" name="recaptcha_response" id="recaptchaResponseCat">

                    <div class="prv">
                         Нажимая на кнопку "отправить", вы даете согласие на обработку <a href="https://vend-shop.com/privacy/">персональных данных</a>.
                    </div>
                    <button type="submit" id="submit" class="submit">Отправить заявку</button>
                </form>
                </div>
            </div>
        </div>
        <div class="cattext">
            <?php if(isset($description)){ ?>
          <?php echo $description ?>
      <?php } ?>
        </div>

<script>
$(document).ready(function() {
  $("#feedback").feedback();
  $(".request").on("click", function(e){
    e.stopPropagation();
    $("#request").find('input[name="product"]').val($(this).prev().text());
    $(".win_white:eq(2), .winoverlay").show();
    return false;
  })
});
</script>
<?php echo $footer; ?>