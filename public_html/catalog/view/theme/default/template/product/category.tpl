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
  <?php echo $content_top; ?>
  <? echo $filter ?>
  <?php if(isset($description_top)) { ?>
    <div class="cattext">
      <?php echo $description_top; ?>
    </div>
  <?php } ?>
  <div class="products products1">
 
    <!--<?php if(isset($youtube['image'])) { ?>
    <div class="products-banner">
      <img src="/image/catalog/123/banner-kkt-lost-version.png?v=2" alt="" width="100%" />
    </div>

    <?php } ?> -->

    <div class="products-banner"></div>

    <!--
    <?php if(isset($youtube['image'])) { ?>
      <a href="<?php echo $youtube['link'] ?>" style="clear: both;display: block; margin-bottom: 30px"> 
        <img src="image/<?php echo $youtube['image'] ?>" alt="" style="max-width: 100%;">
      </a>
    <?php } ?>-->

    <h1><?php echo $heading_title; ?></h1>
    <div class="grid">
      <?php foreach ($products as $product) { ?>
        <div class="product"><a href="<?php echo $product['href']; ?>"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
         
        <? if(isset($product['promos']) && !empty($product['promos'])) {?>
            <? foreach($product['promos'] as $promo) { ?>
              <div class="promo" style="<? echo $promo['position']; ?>">
                <img src="<? echo $promo['image']; ?>" alt="">
                <span style="<? echo $promo['spanposition']; ?>"><? if($promo['usename']) { echo $promo['text']; } ?></span>
              </div>
            <? } ?>
          <? } ?>
          <div class="name"><?php echo $product['name']; ?></div>
          <? if(!$product['price'] || $product['price'] == 0 ) { ?>
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

    <div class="catchform" id="catchform">
      <form id="feedback" data-template="request" data-subject="Не нашли товар">
        <div class="title">Не нашли автомат под свой товар? Оставьте заявку, мы свяжемся с вами и подберем оборудование для Вашего бизнеса!</div>
        <input placeholder="Как вас зовут" name="name" required >
        <div class="teldiv"><input type="tel" name="ft" maxlength="2" value = "+7" required >
          <input type="tel" name="code" value = "" placeholder="123" pattern="^\d+$" maxlength="3" required >
          <input type="tel" name="phone" value = "" placeholder="456 78 90" pattern="^\d+$" maxlength="8" required ></div>
          <input placeholder="Email" name="email" required>
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?>">
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

      <div class="cattext">
        <?php if(isset($description)) { echo $description; } ?>
        <?php if(isset($description_bottom)) echo $description_bottom; ?>
      </div>
    </div>
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