<?php echo $header; ?>
    <div class="lc">
      <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
    <?php } ?>
      </ul>
      <div class="textcontent"><img class="lazy" data-src="<? echo $image ?>">
        <h1><? echo $title; ?></h1>
        <? echo $description ?>
        <? if($post_id == 40) { ?>
        <!-- Put this script tag to the <head> of your page -->
<script type="text/javascript" src="//vk.com/js/api/openapi.js?139"></script>
<script type="text/javascript">VK.init({apiId: API_ID, onlyWidgets: true});</script>
<!-- Put this div tag to the place, where the Comments block will be -->
<div id="vk_comments"></div>
<script type="text/javascript">VK.Widgets.Comments("vk_comments", {limit: 20, width: "320", attach: "*"});</script>
        <? } ?>
 <? if($time_end != '') {?>
          <div id="counter">
            <div class="timetext" style="margin-top: 10px;margin-bottom: 30px; text-align: center; font-size: 30px;">До конца акции осталось</div>
            <div class="countdown" data-date="<? echo $time_end; ?> 00:00:00"></div>

          </div>
<script type="text/javascript">
$(document).ready(function() {
  $('.countdown').TimeCircles();
});
</script>
        <? } ?>
        <?php if($form) { ?>
            <button data-target="#offer" class="btn callme postform">Оставьте заявку прямо сейчас</button>
        <?php } ?>
      </div>
        <div class="grid" style="padding-bottom: 150px">
          <?php foreach ($products as $product) { ?>
            <div class="product"><a href="<?php echo $product['href']; ?>"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
            <? if(isset($product['promo']) && $product['promo']) {?>
              <div class="promo" style="<? echo $product['promo']['position']; ?>">
                <img src="<? echo $product['promo']['image']; ?>" alt="">
                <span style="<? echo $product['promo']['spanposition']; ?>"><? if($product['promo']['usename']) { echo $product['promo']['text']; } ?></span>
              </div>
            <? } ?>
              <div class="name"><?php echo $product['name']; ?></div>
              <? if(!$product['price'] ) { ?>
                <div class="price request">Цена по запросу</div></a></div>
              <? } else { ?>
                <div class="price"><?php echo $product['price']; ?></div></a></div>
              <? } ?>
          <? } ?>
        </div>
    </div>
<?php echo $footer; ?>