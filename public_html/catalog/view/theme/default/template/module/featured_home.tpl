<section class="top">
    <div class="container">
        <h2>ТОП - 5 САМЫХ ПОПУЛЯРНЫХ МОДЕЛЕЙ ЭТОГО СЕЗОНА </h2>
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
        </a></div>
      <? } ?>
    </div>
    </div>
</section>