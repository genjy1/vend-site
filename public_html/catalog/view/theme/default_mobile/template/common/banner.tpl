<style>.banner-coffee .text .title {font-size: 38px;}</style>
<div class="swiper swiper1" data-swiper-autoplay="3000">
      <div class="swiper-wrapper">


      <? foreach ($slides as $key => $value) {?> 
      <div class="swiper-slide">
 


        <div class="banner-coffee" style="background: url('/image/<? echo $value['bg']?>') repeat 50% 0; background-size: cover">
            <div class="lc">
              <? if ($value['description']['caption'] !== "") { ?>
              <div class="text">
                <div class="title" style="color: <? echo $value['color_caption']?>"><? echo $value['description']['caption']?></div>
                <div class="desc" style="color: <? echo $value['color_text']?>"><? echo $value['description']['text']?></div>
                <? if ($value['links'][0] == "callme") { ?>
                    <a href="#" class="callme banner-coffee-link" style="background-color: <? echo $value['color_button']?>"><? echo $value['description']['text_link']?></a>
                <? } else { ?>
                    <a href="<? echo $value['links'][0] ?>" style="background-color: <? echo $value['color_button']?>"><? echo $value['description']['text_link']?></a>
                <? }?>
              </div> 
              <?} ?>
              <?if ($value['image']) {?> 
              <img src="/image/<? echo $value['image']?>" alt="" />
              <?}?>
            </div>
          </div>




      </div>
      <? } ?>

      </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <script>
      $(document).ready(function(){
        var swiper = new Swiper(".swiper1", {loop: true, autoplay: true, navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        }});
      })
    </script>