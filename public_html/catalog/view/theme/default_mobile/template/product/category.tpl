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
        <!--<? if ($youtube) { ?> <?php /*echo $youtube['link']*/ ?>
        <a href="tel:88007757349" style="clear: both;display: block; margin-bottom: 30px"> 
          <img class="lazy" data-src="image/catalog/123/banner-kkt-lost-version.png?v=2" alt="" style="max-width: 100%;">
        </a><?}?> -->

          <div class="products-banner"></div>

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
                        <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
                        <button type="submit" id="submit" class="submit">Отправить заявку</button>
                        <div class="agreement-container">
                            <input type="checkbox" name="agreement" id="agreement_main">
                            <label for="agreement_main" class="prv agreement-label">
                                <p class="label__agreement-text">
                                    Даю <a href="/agreement">согласие на обработку персональных данных</a> в соответствии с <a href="/privacy">политикой конфиденциальности</a>
                                </p>
                            </label>
                        </div>
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
  // $("#feedback").feedback();
  $(".request").on("click", function(e){
    e.stopPropagation();
    $("#request").find('input[name="product"]').val($(this).prev().text());
    $(".win_white:eq(2), .winoverlay").show();
    return false;
  })
});
</script>
<?php echo $footer; ?>