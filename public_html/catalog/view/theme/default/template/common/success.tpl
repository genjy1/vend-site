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
      <div class="thanks">
        <h1>Спасибо</h1>
        <div class="text">Ваш заказ успешно оформлен. Мы свяжемся с вами в ближайшее время.<br/>
        <? if(isset($order_id)) { ?>Номер вашего заказа <span>№<? echo $order_id ?>.</span><? } ?></div><a href="/" class="gotoshop">Вернуться в магазин</a>
      </div>
    </div>
<?php echo $footer; ?>