        <div class="map">
          <div class="top">
            <div class="active" data-block="maplive">Карта проезда</div>
            <div  data-block="images">Как пройти в офис</div>
          </div>
          <div class="cont">
            <div class="maplive">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2212.025738886561!2d38.127520315796666!3d56.32938595484179!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b4c43d4ebcae59%3A0xbe2989b96d1cb9ae!2z0J3QvtCy0L7Rg9Cz0LvQuNGH0YHQutC-0LUg0YguLCA2Nywg0KHQtdGA0LPQuNC10LIg0J_QvtGB0LDQtCwg0JzQvtGB0LrQvtCy0YHQutCw0Y8g0L7QsdC7LiwgMTQxMzAx!5e0!3m2!1sru!2sru!4v1486896094449" frameborder="0" style="border:0; width: 100%;height: 820px" allowfullscreen></iframe>
            </div>
            <div class="images">
              <ul class="slides">
              <? foreach($images as $image){ ?>
                <li>
                  <img src="<? echo $image['image'] ?>" />
                </li>
              <? } ?>
              </ul>
            </div>
          </div>
        </div>
        <script type="text/javascript">
        $(window).load(function() {
          $('.images').flexslider({
          animation: "slide",
              prevText: "",
              nextText: "",
         });
        });
        $(document).ready(function(){
           $('.top div').on('click', function(){
            $('.top div').removeClass('active');
            $(this).addClass('active');
            div = $(this).data('block');
            $('.cont>div').hide();
            $('.cont .'+div).show();
           })
        });
       </script>