<?php echo $header; ?>
<?php echo "<!-- " .$post_id . "-->" ?>
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
      <div class="textcontent">
      <?php if($image){ ?>
        <img class="lazy" data-src="<? echo $image ?>">
      <? } ?>
        <h1><? echo $title; ?></h1>
        <? echo $description ?>
        <? if($post_id == 40) { ?>
            <script type="text/javascript" src="//vk.com/js/api/openapi.js?139"></script>
            <script type="text/javascript">VK.init({apiId: API_ID, onlyWidgets: true});</script>
            <div id="vk_comments"></div>
            <script type="text/javascript">VK.Widgets.Comments("vk_comments", {limit: 20, width: "665", attach: "*"});</script>
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
            <a href="javascript:void(0)" class="callme postform">Оставьте заявку прямо сейчас</a>
        <?php } ?>
      </div>
      
      
        <div class="grid" style="padding-bottom: 150px">
          <?php foreach ($products as $product) { ?>
            <div class="product" style="margin-bottom: 30px;"><a href="<?php echo $product['href']; ?>"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>">
            <? if(isset($product['promo']) && $product['promo']) {?>
              <div class="promo" style="<? echo $product['promo']['position']; ?>">
                <img class="lazy" data-src="<? echo $product['promo']['image']; ?>" alt="">
                <span style="<? echo $product['promo']['spanposition']; ?>"><? if($product['promo']['usename']) { echo $product['promo']['text']; } ?></span>
              </div>
            <? } ?>
              <div class="name"><?php echo $product['name']; ?></div>
              <? if(!$product['price'] || $product['price'] == 0 ) { ?>
                <div class="price request">Цена по запросу</div>
              <? } else { ?>
                <? if(!$product['special']){ ?>
                  <div class="price"><?php echo $product['price'];  ?></div>
                <? } else { ?>
                  <div class="oldprice"> <?php echo $product['price'];  ?></div>
                  <div class="newprice"> <?php echo $product['special'];  ?></div>
                <? } ?>
              <? } ?>
              </a>
              </div>


          <? } ?>
        </div>

    </div>
<?php echo $footer; ?>