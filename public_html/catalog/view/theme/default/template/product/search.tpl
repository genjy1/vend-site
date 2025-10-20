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
      <div class="products">
        <h1><?php echo $heading_title; ?></h1>
        <div class="grid">
          <? if(empty($products)) { ?>
            <h2>Ничего не найдено</h2>
          <? } ?>
          <?php foreach ($products as $product) { ?>
            <div class="product"><a href="<?php echo $product['href']; ?>"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
              <!--<div class="promo">Хит продаж</div>-->
              <div class="name"><?php echo $product['name']; ?></div>
              <? if(!$product['price'] || $product['price'] == 0 ) { ?>
            <div class="price request">Цена по запросу</div>
          <? } else { ?>
            <? if(!$product['special']){ ?>
              <div class="price"><? if(isset($avtomat) && $avtomat) {?> <span>от</span> <?php echo $product['price']; } ?></div>
            <? } else { ?>
              <div class="oldprice"><? if(isset($avtomat) && $avtomat) {?> <span>от</span> <?php echo $product['price']; } ?></div>
              <div class="newprice"><? if(isset($avtomat) && $avtomat) {?> <span>от</span> <?php echo $product['special']; } ?></div>
            <? } ?>
          <? } ?>
            </a></div>
          <? } ?>
        </div>
        <?php echo $pagination; ?>
        <?php echo $content_bottom; ?>
        <div class="catchform">
          <form id="feedback" data-template="category">
            <div class="title">Не нашли автомат под свой товар? Оставьте заявку, мы свяжемся с вами и подберем оборудование для Вашего бизнеса!</div>
            <input placeholder="Как вас зовут*" name="name">
            <input placeholder="Ваш телефон*" name="phone">
            <input placeholder="Email*" name="email">
            <button type="submit">Отправить заявку</button>
          </form>
        </div>
      </div>
    </div>
<script>
// $(document).ready(function() {
//   $("#feedback").feedback();
// });
</script>
<?php echo $footer; ?>