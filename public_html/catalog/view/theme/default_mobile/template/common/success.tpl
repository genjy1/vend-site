<?php echo $header; ?>
    <div class="lc">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
    <?php } ?>
  </ul>
      <div class="thanks">
        <h1>Спасибо</h1>
        <div class="text">Ваш заказ успешно оформлен. Мы свяжемся с вами в ближайшее время.<br/>Номер вашего заказа <span>№<? echo $order_id ?>.</span></div><a href="/" class="gotoshop">Вернуться в магазин</a>
      </div>
    </div>
<?php echo $footer; ?>