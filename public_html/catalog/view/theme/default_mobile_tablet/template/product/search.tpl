<?php echo $header; ?>
    <div class="lc">
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
      <?php } ?>
    </ul>
<?php echo $content_top; ?>
      <div class="products">
        <h1><?php echo $heading_title; ?></h1>
        <div class="grid">
          <?php foreach ($products as $product) { ?>
            <div class="product"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
              <div class="promo">Хит продаж</div>
              <div class="name"><?php echo $product['name']; ?></div>
              <div class="price"><?php echo $product['price']; ?></div></a></div>
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
$(document).ready(function() {
  // $("#feedback").feedback();
});
</script>
<?php echo $footer; ?>