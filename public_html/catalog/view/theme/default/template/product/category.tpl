<?php echo $header; ?>
<div class="lc layout-container">
  <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
    <?php $i = 1; foreach ($breadcrumbs as $breadcrumb) { ?>
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
      <a href="<?php echo $breadcrumb['href']; ?>" itemprop="name"><?php echo $breadcrumb['text']; ?></a>
      <span>/</span>
      <meta itemprop="position" content="<?php echo $i; ?>" />
    </li>
    <?php $i++; } ?>
  </ul>

  <?php echo $content_top; ?>
  <?php echo $filter; ?>

  <?php if (isset($description_top)) { ?>
  <div class="cattext category__description category__description--top">
    <?php echo $description_top; ?>
  </div>
  <?php } ?>

  <div class="products products1 category__products">
    <div class="products-banner category__banner"></div>

    <h1 class="category__title"><?php echo $heading_title; ?></h1>

    <div class="grid category__grid">
      <?php foreach ($products as $product) { ?>
      <div class="product category__product">
        <a href="<?php echo $product['href']; ?>" class="category__product-link">
          <img class="lazy category__product-image" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">

          <?php if (isset($product['promos']) && !empty($product['promos'])) { ?>
            <?php foreach ($product['promos'] as $promo) { ?>
            <div class="promo product__promo-badge" data-position="<?php echo htmlspecialchars($promo['position']); ?>">
              <img src="<?php echo $promo['image']; ?>" alt="">
              <span class="product__promo-text" data-position="<?php echo htmlspecialchars($promo['spanposition']); ?>">
                <?php if ($promo['usename']) { echo $promo['text']; } ?>
              </span>
            </div>
            <?php } ?>
          <?php } ?>

          <div class="name category__product-name"><?php echo $product['name']; ?></div>

          <?php if (!$product['price'] || $product['price'] == 0) { ?>
          <div class="price request category__product-price category__product-price--request">Цена по запросу</div>
          <?php } else { ?>
            <?php if (!$product['special']) { ?>
            <div class="price category__product-price">
              <?php if ($avtomat) { ?><span class="category__product-price-prefix">от</span> <?php echo $product['price']; } ?>
            </div>
            <?php } else { ?>
            <div class="oldprice category__product-price category__product-price--old">
              <?php if ($avtomat) { ?><span class="category__product-price-prefix">от</span> <?php echo $product['price']; } ?>
            </div>
            <div class="newprice category__product-price category__product-price--special">
              <?php if ($avtomat) { ?><span class="category__product-price-prefix">от</span> <?php echo $product['special']; } ?>
            </div>
            <?php } ?>
          <?php } ?>
        </a>
      </div>
      <?php } ?>
    </div>

    <?php echo $pagination; ?>
    <?php echo $content_bottom; ?>

    <div class="catchform category__feedback-form" id="catchform">
      <form id="feedback" class="form" data-template="request" data-subject="Не нашли товар">
        <div class="title form__title">
          Не нашли автомат под свой товар? Оставьте заявку, мы свяжемся с вами и подберем оборудование для Вашего бизнеса!
        </div>

        <div class="form__group">
          <input class="form__input" placeholder="Как вас зовут" name="name" required>
        </div>

        <div class="teldiv form__phone-group">
          <input type="tel" class="form__input form__input--country-code" name="ft" maxlength="2" value="+7" required>
          <input type="tel" class="form__input form__input--area-code" name="code" value="" placeholder="123" pattern="^\d+$" maxlength="3" required>
          <input type="tel" class="form__input form__input--phone" name="phone" value="" placeholder="456 78 90" pattern="^\d+$" maxlength="8" required>
        </div>

        <div class="form__group">
          <input class="form__input" placeholder="Email" name="email" required>
        </div>

        <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>">

        <button type="submit" id="submit" class="submit button button--primary form__submit">Отправить заявку</button>

        <div class="agreement-container form__agreement">
          <input type="checkbox" class="form__checkbox" name="agreement" id="agreement_main">
          <label for="agreement_main" class="prv agreement-label form__checkbox-label">
            <p class="label__agreement-text form__agreement-text">
              Даю <a href="/agreement">согласие на обработку персональных данных</a> в соответствии с <a href="/privacy">политикой конфиденциальности</a>
            </p>
          </label>
        </div>
      </form>
    </div>

    <div class="cattext category__description category__description--bottom">
      <?php if (isset($description)) { echo $description; } ?>
      <?php if (isset($description_bottom)) { echo $description_bottom; } ?>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.request').on('click', function(e) {
      e.stopPropagation();
      $('#request').find('input[name="product"]').val($(this).prev().text());
      $('.win_white:eq(2), .winoverlay').show();
      return false;
    });
  });
</script>

<?php echo $footer; ?>
