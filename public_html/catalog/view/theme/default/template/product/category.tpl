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
  <div class="cattext">
    <?php echo $description_top; ?>
  </div>
  <?php } ?>

  <div class="products products1">
    <div class="products-banner"></div>

    <h1><?php echo $heading_title; ?></h1>

    <div class="grid">
      <?php foreach ($products as $product) { ?>
      <div class="product">
        <a href="<?php echo $product['href']; ?>">
          <img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">

          <?php if (isset($product['promos']) && !empty($product['promos'])) { ?>
            <?php foreach ($product['promos'] as $promo) { ?>
            <div class="promo" data-position="<?php echo htmlspecialchars($promo['position']); ?>">
              <img src="<?php echo $promo['image']; ?>" alt="">
              <span data-position="<?php echo htmlspecialchars($promo['spanposition']); ?>">
                <?php if ($promo['usename']) { echo $promo['text']; } ?>
              </span>
            </div>
            <?php } ?>
          <?php } ?>

          <div class="name"><?php echo $product['name']; ?></div>

          <?php if (!$product['price'] || $product['price'] == 0) { ?>
          <div class="price request">Цена по запросу</div>
          <?php } else { ?>
            <?php if (!$product['special']) { ?>
            <div class="price">
              <?php if ($avtomat) { ?><span>от</span> <?php echo $product['price']; } ?>
            </div>
            <?php } else { ?>
            <div class="oldprice">
              <?php if ($avtomat) { ?><span>от</span> <?php echo $product['price']; } ?>
            </div>
            <div class="newprice">
              <?php if ($avtomat) { ?><span>от</span> <?php echo $product['special']; } ?>
            </div>
            <?php } ?>
          <?php } ?>
        </a>
      </div>
      <?php } ?>
    </div>

    <?php echo $pagination; ?>
    <?php echo $content_bottom; ?>

    <div class="catchform" id="catchform">
      <form id="feedback" data-template="request" data-subject="Не нашли товар">
        <div class="title">
          Не нашли автомат под свой товар? Оставьте заявку, мы свяжемся с вами и подберем оборудование для Вашего бизнеса!
        </div>

        <div>
          <input placeholder="Как вас зовут" name="name" required>
        </div>

        <div class="teldiv">
          <input type="tel" name="ft" maxlength="2" value="+7" required>
          <input type="tel" name="code" value="" placeholder="123" pattern="^\d+$" maxlength="3" required>
          <input type="tel" name="phone" value="" placeholder="456 78 90" pattern="^\d+$" maxlength="8" required>
        </div>

        <div>
          <input placeholder="Email" name="email" required>
        </div>

        <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>">

        <button type="submit" id="submit" class="submit">Отправить заявку</button>

        <div class="agreement-container">
          <input type="checkbox" name="agreement" id="agreement_main">
          <label for="agreement_main" class="prv agreement-label">
            <p>
              Даю <a href="/agreement">согласие на обработку персональных данных</a> в соответствии с <a href="/privacy">политикой конфиденциальности</a>
            </p>
          </label>
        </div>
      </form>
    </div>

    <div class="cattext">
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
