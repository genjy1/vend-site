<?php echo $header; ?>
    <div class="lc">
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
      <?php } ?>
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
          <? } ?>
          <div class="buts">
            <div class="prev"></div>
            <div class="next"></div>
          </div>
        </div>
        <div class="spares list">

        </div>
      </div>
      <div style="display: none;" id="maps">
                    <?php foreach ($maps as $value) { ?>
                    <div id="#sp<? echo $value['map_id'] ?>" class="spitem spitem<? echo $value['map_id'] ?>">
                    <div class="imgbig">
                    <? $num = 1; ?>
                        <? foreach($value['items'] as $item) {?>
                          <? $coords = explode(",",$item['coords']); ?>
                          <div id="<? echo $value['map_id'] ?>_<? echo $item['product_id'] ?>" class="mappoint" style="position:absolute;left:<? echo $coords[0] ?>px;top:<? echo ($coords[1] - 28) ?>px"><img class="lazy" data-src="/image/mappoint.png"><span><? echo $num ?></span></div>
                          <? $num++; ?>
                        <? } ?>
                      <img class="lazy" data-src="<?php echo $value['thumb']; ?>" alt="" title="" />
                    </div>
                      <? $num = 1; ?>
                        <div class="itemlist">
                        <? foreach($value['items'] as $item) { ?>
                        <? if($item['name'] == '' || !$item['name']){ continue; } ?>
                        <div class="item" data-mapid="<? echo $value['map_id'] ?>_<? echo $item['product_id'] ?>">
                          <div class="num">Номер <? echo $num ?></div>
                          <? $num++; ?>
                          <div class="img">
                            <a href="<? echo $item['bigimage'] ?>" class="fancy">
                              <img class="lazy" data-src="<? echo $item['image'] ?>">
                            </a>
                          </div>
                          <div class="title"> 
                            <div class="name"><? echo utf8_substr(strip_tags(html_entity_decode($item['name'] , ENT_QUOTES, 'UTF-8')), 0, 360) ?></div>
                            <div class="descr">
                              <? echo $item['description'] ?>
                           </div>
                          </div>
                          <? if($item['price'] ) { ?>
                            <!-- <div class="price"><? echo $item['price'] ?><span></span></div> -->
                          <? } else { ?>
                            <!-- <div class="price request">Цена по запросу</div></div> -->
                          <? } ?>
                          <div class="add">
                            <? if($item['price'] ) { ?>
                              <button onclick="cart.add(<? echo $item['product_id'] ?>)"></button>
                            <? } ?>
                          </div>
                        </div>
                        <? } ?>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
      <div style="clear: both;padding-top: 30px">
                <ul class="pagination">
                <? if(isset($maps) && !empty($maps) && count($maps) > 1) { ?>
                  <? $num = 1; ?>
                  <?php foreach ($maps as $value) { ?>
                    <li data-id="<? echo $value['map_id'] ?>"><span><? echo $num ?></span></li>
                    <? $num++; ?>
                  <? } ?>
                  <? } ?>
                </ul>
      </div>
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
      $(".list .item[data-mapid!='"+id+"']").hide();
      $(".list .item[data-mapid='"+id+"']").show();
    }).on("mouseout",'.mappoint', function(){
      $(".list .item").show();
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
      itemsDesktopSmall : [320,3]
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