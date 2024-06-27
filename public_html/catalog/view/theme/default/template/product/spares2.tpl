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
      <div class="spares2">
        <h1><? echo $heading_title; ?></h1>
        <div class="sparesimg">
        <? if(isset($maps) && !empty($maps) && count($maps) > 1) { ?>
          <div class="mini">
            <?php foreach ($maps as $value) { ?>
              <div class="item">
                <img class="lazy" data-src="<? echo $value['mini'] ?>" alt="" data-id="<? echo $value['map_id'] ?>">
              </div>
            <? } ?>
          </div>
          <div class="buts">
            <div class="prev"></div>
            <div class="next"></div>
          </div>
          <? } ?>
        </div>
        <div class="spares list">
          <div class="headers">
            <div>Номер</div>
            <div>Запчасть</div>
            <div></div>
          </div>

        </div>
      </div>
      <? if(isset($maps) && !empty($maps)) { ?>
      <div style="display: none;" id="maps">
                    <? $num = 1; ?>
                    <? $num1 = 1; ?>
                    <?php foreach ($maps as $value) { ?>
                    <div id="#sp<? echo $value['map_id'] ?>" class="spitem spitem<? echo $value['map_id'] ?>">
                    <div class="imgbig">
                        <? foreach($value['items'] as $item) {?>
                          <? $coords = explode(",",$item['coords']); ?>
                          <?php if($value['pins'] == 1) { ?>
                          <div id="<? echo $value['map_id'] ?>_<? echo $item['product_id'] ?>" class="mappoint" style="position:absolute;left:<? echo $coords[0] ?>px;top:<? echo ($coords[1] - 28) ?>px"><img class="lazy" data-src="/image/mappoint.png"><span><? echo $num ?></span></div>
                          <? $num++; ?>
                          <?php } ?>
                        <? } ?>
                            <img class="lazy" data-src="<?php echo $value['thumb']; ?>" alt="" title="" />
                          </div>
                        <div class="itemlist">
                        <? foreach($value['items'] as $item) { ?>
                        <? if($item['name'] == '' || !$item['name']){ continue; } ?>
                        <div class="item" data-mapid="<? echo $value['map_id'] ?>_<? echo $item['product_id'] ?>">
                          <div class="num"><? echo $num1 ?></div>
                          <? $num1++; ?>
                          <div class="img">
                            <a href="<? echo $item['bigimage'] ?>" class="fancy">
                              <img class="lazy" data-src="<? echo $item['image'] ?>">
                            </a>
                          </div>
                          <div class="title"> 
                            <div class="name"><? echo utf8_substr(strip_tags(html_entity_decode($item['name'] , ENT_QUOTES, 'UTF-8')), 0, 160) ?></div>
                            <div class="descr">
                              <? echo $item['description'] ?>
                           </div>
                          </div>
                          <? if(!$item['price'] ) { ?>
                            <!-- <div class="price request">Цена по запросу</div></div> -->
                          <? } else { ?>
                            <!-- <div class="price"><? echo $item['price'] ?><span></span></div> -->
                          <? } ?>
                          <? if($item['price'] ) { ?>
                          <div class="add">
                            <button id="add" onclick="cart.add(<? echo $item['product_id'] ?>)"></button>
                          </div>
                          <? } ?>
                        </div>
                        <? } ?>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
      <div style="clear: both;padding-top: 30px">
                <ul class="pagination">
                  <? $num2 = 1; ?>
                  <? if(count($maps) > 1){ ?>
                  <?php foreach ($maps as $value) { ?>
                    <li data-id="<? echo $value['map_id'] ?>"><span><? echo $num2 ?></span></li>
                    <? $num2++; ?>
                  <? } ?>
                  <? } ?>
                </ul>
      </div>
      <? } ?>
    </div>
<script>
  $(document).ready(function() {
    $("a.fancy").fancybox();
    $(".mini .item:eq(0) img").addClass('itcur');
    $(".pagination li:eq(0)").addClass('active');

    $(".pagination li").on("click", function(){
      $(".pagination li").removeClass('active');
      $(this).addClass('active');
      sid = $(this).data('id');
      $('.sparesimg .imgbig').remove();
      $('.spares.list .item').remove();
      $('.sparesimg').prepend($(".spitem"+sid).find('.imgbig').clone());
      $('.spares.list').append($(".spitem"+sid).find('.itemlist .item').clone());
    });


    $(".mini .item img").on("click", function(){
      $(".mini .item img").removeClass('itcur');
      $(this).addClass('itcur');
      sid = $(this).data('id');
      $('.sparesimg .imgbig').remove();
      $('.spares.list .item').remove();
      $('.sparesimg').prepend($(".spitem"+sid).find('.imgbig').clone());
      $('.spares.list').append($(".spitem"+sid).find('.itemlist .item').clone());
    });

    $('.sparesimg').prepend($('#maps').find('.spitem').eq(0).find('.imgbig').clone());
    $('.spares.list').append($('#maps').find('.spitem').eq(0).find('.itemlist .item').clone());

    $('body').on('mouseover','.mappoint',function(){
      id = $(this).attr('id');
      $(".list .item").removeClass('marked');
      // $(".list .item[data-mapid!='"+id+"']").hide();
      $('.spares.list .item:eq(0)').before($(".list .item[data-mapid='"+id+"']"));
      $(".list .item[data-mapid='"+id+"']").addClass('marked');
      // $(".list .item[data-mapid='"+id+"']").show();
    }).on("mouseout",'.mappoint', function(){
      // $(".list .item").show();
    });

    $('body').on('mouseover', '.list .item',function(){
      id = $(this).data('mapid');
      $("#"+id).addClass('pulse');
    }).on("mouseout", '.list .item', function(){
      $("#"+id).removeClass('pulse');
    });

    mini = $(".mini");
    mini.owlCarousel({
      autoPlay: false, //Set AutoPlay to 3 seconds
      items : 3,
      lazyLoad: true,
      navigation : true,
      pagination : false,
      navigationText : ["", ""],
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3]
  });

    $('.buts .next').on("click", function(){
      mini.trigger('owl.next');
    })
    $('.buts .prev').on("click", function(){
      mini.trigger('owl.prev');
    })

    $("body").on("click",".add button", function(){
      src = $(this).parent().parent().find('.img img').attr('src');
      $('.cartadded img').attr('src', src);
      $('.cartadded').show("swing");
    });


  });
</script>
<div class="cartadded">
  Товар добавлен в корзину
  <div class="cin">
  <img class="lazy" data-src="<? echo $mini ?>" alt="">
  <div class="buts">
    <a href="javascript:void(0)" class="getoffer continue">продолжить выбор товара</a>
    <a href="/cart/" class="getoffer">оформить заявку</a>
  </div>
  </div>
</div>
<?php echo $footer; ?>