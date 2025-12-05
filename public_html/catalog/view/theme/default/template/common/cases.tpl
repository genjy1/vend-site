  <div class="caseh1">
      <h1>Наши проекты</h1>
  </div>
  <div class="banner cases" id="slider">
            <div class="sliderContent">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach($slides as $key => $slide) { ?>
                            <div class="swiper-slide">
                                <div class="itembg" >
                                    <div><img class="lazy" data-src="<? echo $slide['image'] ?>" alt="" /></div>
                                    <div class="textblock">
                                        <div class="text">
                                            <? $slide['text'] = str_replace("src", "class='lazy' data-src", $slide['text']); ?>
                                            <p><?php echo htmlspecialchars_decode($slide['text']) ?></p>
                                            <button data-target="#winMain" class="btn callme proposal cases-btn">Получить предложение</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
        <?php //$slides_chunks = array_chunk($slides, 2); ?>
  <!--       <?php foreach($slides_chunks as $key => $slides) { ?>
            <div class="item">
                <div class="itemwrap">
                <?php foreach($slides as $key => $slide) { ?>
                    <div class="itembg" >
                        <div><img src="<? echo $slide['image'] ?>" alt="" /></div>
                        <div class="textblock">
                            <div class="text">
                                <p><?php echo htmlspecialchars_decode($slide['text']) ?></p>
                                <a href="javascript:void(0)" class="callme">Получить предложение</a>
                            </div>
                        </div>
                    </div>
                <? } ?>
                </div>
            </div>
            <? } ?> -->
        </div>
  </div>
<!--         <div class="sliderContent">
        <?php $slides_chunks = array_chunk($slides, 2); ?>
        <?php foreach($slides_chunks as $key => $slides) { ?>
            <div class="item">
                <div class="itemwrap">
                <?php foreach($slides as $key => $slide) { ?>
                    <div class="itembg" >
                        <div><img src="<? echo $slide['image'] ?>" alt="" /></div>
                        <div class="textblock">
                            <div class="text">
                                <p><?php echo htmlspecialchars_decode($slide['text']) ?></p>
                                <a href="javascript:void(0)" class="callme">Получить предложение</a>
                            </div>
                        </div>
                    </div>
                <? } ?>
                </div>
            </div>
            <? } ?>
        </div>
  </div> -->
  <script>
    // $(document).ready(function(){
    //   $('#slider').mobilyslider({
    //     transition: "fade",
    //     autoplay: true,
    //     autoplaySpeed: 6000,
    //   });
    // });
  </script>
 <!-- <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>-->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    var swiper = new Swiper('.swiper-container', {
      slidesPerView: 2,
      spaceBetween: 30,
      loop: true,
      lazy: true,
      breakpoints: {
        320: {
            slidesPerView: 1,
        },
        991: {
            slidesPerView: 2,
            spaceBetween: 30,
        }
      },
      // autoplay: {
      //   delay: 2500,
      //   disableOnInteraction: false,
      // },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
    $(".swiper-button-next, .swiper-button-prev").click(function(){
        $('.cases .lazy').lazy({
            bind: "event",
            delay: 0
        });
    })

  </script>