    <footer>

       <div class="lc"><a id="footerlogo"><img class="lazy" data-src="image/logotype_bottom.svg"></a>
        <div class="mcolumn">
          <div class="title">Информация</div>
          <div><a href="/blog/torgovyy-avtomat-v-lizing/">Торговый автомат в лизинг</a></div>
          <div><a href="/blog/torgovyj-avtomat-v-kredit/">Торговый автомат в кредит</a></div>
          <div><a href="/blog/besprotsentnaya-rassrochka/">Беспроцентная рассрочка</a></div>
          <div><a href="/blog/mesta-pod-avtomaty/">Места под автоматы</a></div>
          <div><a href="/blog/vopros-otvet/">Вопрос-ответ</a></div>
        </div>
        <div class="mcolumn">
          <div class="title">Библиотека Вендора</div>
          <div><a href="/blog/s-chego-nachat-biznes/">С чего начать бизнес</a></div>
          <div><a href="/blog/oshibki-na-nachalnom-etape-biznesa/">Ошибки на начальном этапе бизнеса</a></div>
          <div><a href="/blog/yuridicheskie-aspekty-vendinga/">Юридические аспекты вендинга</ a></div>
          <div><a href="/blog/biznes-idei/">Бизнес идеи</a></div>
        </div>
        <div class="mcolumn"> <a href="<? echo $origin_server ?>sitemap/">Карта сайта</a></div>
        <div><a href="/blog/biblioteka-vendora/" id="gotolib">Перейти в библиотеку</a></div>
        <div class="cop">2003-<?php echo date('Y'); ?> © VendShop – надежные торговые автоматы для Вашего бизнеса</div>
      </div>
    </footer>
    <?php echo $feedback; ?>

<script type="text/javascript" defer src="/catalog/view/javascript/jquery.lazy.min.js"></script>
<script>
  $(document).ready(function(){
    if (typeof navigator.userAgent !== "undefined") {
      if (navigator.userAgent.indexOf('Lighthouse') < 0) {
        setLazy();
      }
    } else {
      setLazy();
    }
    function setLazy() {
      $('.lazy').Lazy({effect: 'fadeIn',effectTime: 1000});
    }
  })
</script>
  <script>
    $(document).ready(function(){
      $(".callme").on("click", function(){
        $(".win_white:eq(0), .winoverlay").show();
      });
      $(".open_close, .winoverlay").on("click", function(){
        $(".win_white, .winoverlay").hide();
      });
    });
    /*$(document).ready(function() {
      $('#preloader').find('i').fadeOut().end().fadeOut('slow');
    });*/
    $(window).load(function() {
      $('#preloader').find('i').fadeOut().end().delay(400).fadeOut('slow');
    });
  </script>

  <div class="help">
      <h2>Какой тип автоматов Вас интересует?</h2>
      <div class="selects">
        <?php foreach($help['categories'] as $category){ ?>
          <div>
            <a href="<?php echo $category['href'] ?>?pomosch"> <?php echo $category['name'] ?> </a>
          </div>
        <?php } ?>
  
        <div>
          <select name="" id="helpcats">
            <?php foreach($categories as $category) { ?>
              <option value="<?php echo $category['href'] ?>?pomosch"><?php echo $category['name'] ?></option>
            <? } ?>
          </select>
        </div>


      </div>
      <a href="javascript:void(0)" class="close" id="pomoschnet">Спасибо, я сам разберусь</a>
    </div>

    <div class="showHelp"></div>

<?php echo $scripts; ?>
</div>
<style>
.citem{
  margin-bottom:3px;
  font-size:13px;
  padding:3px;
  width:270px;
}
</style>
    <div id="cookieNotice" class="cookie-notice" role="alertdialog" aria-live="polite" aria-label="Сообщение о cookie">
        <p>
            Мы используем файлы <a href="/cookie">cookie</a> и <a href="/metrika">Яндекс. Метрику</a> для улучшения работы сайта.
        </p>
        <button id="cookieAcceptBtn">Согласен</button>
    </div>


    <div style="display: none" id="arenza"></div>
    <script>
    setTimeout(() => {
      $("arenza").html('<iframe id="arenzaWidget" src="https://crmpro.arenza.ru/partners/oec0z3hqc2Xo6DoPaaxV95TBoVqBMvl-8QOordPLLHs/widget?iframe=true" height="100%" width="100%" frameborder="0"> </iframe>');
      window.addEventListener('message', function(e) {
        var iframe = document.getElementById('arenzaWidget');
        var eventName = e.data[0];
        var data = e.data[1];
        switch(eventName) {
        case 'setHeight':
          iframe.style.height = data + "px";
          break;
        }
      }, false);
    }, 10000);

    </script>
    <script src="catalog/view/javascript/hammerjs/hammer.min.js"></script>
 </div>
    </body>
</html>