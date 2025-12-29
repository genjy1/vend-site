<div class="banner slider" id="slider">
  <div class="sliderContent slider__content">
    <?php foreach ($slides as $key => $slide) { ?>
    <div class="slider__slide">
      <?php
        $backgroundStyle = !empty($slide['background'])
          ? 'background: url(' . $slide['background'] . ')'
          : 'background: url(/image/home/bg_banner.png)';
        $positionClass = ($slide['position'] == 0) ? 'left' : 'right';
        $imageClass = ($positionClass == 'left') ? 'right' : 'left';
      ?>

      <div class="itembg slider__background"
        <?php if ($key == 0) { ?>style="<?php echo $backgroundStyle; ?>"<?php } ?>
        data-style="<?php echo $backgroundStyle; ?>">

        <div class="item slider__item">
          <div class="textblock slider__text-block slider__text-block--<?php echo $positionClass; ?> <?php echo $positionClass; ?>">

            <div class="caption slider__caption" data-color="<?php echo htmlspecialchars($slide['color_caption']); ?>">
              <?php echo $slide['caption']; ?>
            </div>

            <div class="text slider__text" data-color="<?php echo htmlspecialchars($slide['color_text']); ?>">
              <?php echo $slide['text']; ?>
            </div>

            <div class="links slider__links<?php if (empty($slide['text'])) { ?> slider__links--bottom<?php } ?>">
              <?php foreach ($slide['links'] as $k => $link) { ?>
                <?php
                  $linkText = (isset($slide['text_links'][$k]) && $slide['text_links'][$k] != '')
                    ? $slide['text_links'][$k]
                    : 'Перейти к автомату';
                  $buttonStyle = ($slide['color_button'] != '#ffffff')
                    ? 'background: ' . $slide['color_button']
                    : '';
                ?>
                <a href="<?php echo $link; ?>" class="goto slider__button button"
                  <?php if ($buttonStyle) { ?>data-background="<?php echo htmlspecialchars($buttonStyle); ?>"<?php } ?>>
                  <?php echo $linkText; ?>
                </a>
              <?php } ?>
            </div>

            <?php if (isset($slide['time_end']) && $slide['time_end']) { ?>
            <div class="slidertimer slider__timer">
              <div class="timetext slider__timer-label">До конца акции осталось</div>
              <div class="slider__timer-counter">
                <div id="counter<?php echo $slide['id']; ?>" class="slider__countdown" data-date="<?php echo $slide['time_end']; ?> 00:00:00"></div>
              </div>
            </div>
            <?php } ?>

          </div>

          <img class="simg<?php echo $imageClass; ?> slider__image slider__image--<?php echo $imageClass; ?> lazy"
            data-src="<?php echo $slide['image']; ?>"
            alt="<?php echo htmlspecialchars($slide['caption']); ?>">
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>

<script>
  (function() {
    'use strict';

    function initSlider() {
      // Применяем цвета из data-атрибутов
      document.querySelectorAll('.slider__caption[data-color]').forEach(function(el) {
        el.style.color = el.dataset.color;
      });

      document.querySelectorAll('.slider__text[data-color]').forEach(function(el) {
        el.style.color = el.dataset.color;
      });

      document.querySelectorAll('.slider__button[data-background]').forEach(function(el) {
        el.style.cssText = el.dataset.background;
      });

      // Инициализация слайдера
      var homeslider = $('#slider').mobilyslider({
        transition: '<?php echo $effect; ?>',
        animationSpeed: 600,
        autoplay: false,
        autoplaySpeed: 8000,
        pauseOnHover: true,
        bullets: true,
        arrowsHide: false,
        animationStart: function() {
          var nextSlide = $('.sliderContent .item:visible').parent().parent().next();
          var itembg = nextSlide.find('.itembg');
          if (itembg.length && itembg.data('style')) {
            itembg.attr('style', itembg.data('style'));
          }
        },
        animationComplete: function() {
          var visibleItem = $('.sliderContent .item:visible');
          var timer = visibleItem.find('.slidertimer');

          if (!timer.length || visibleItem.hasClass('fixed')) {
            return;
          }

          var timerBottom = timer.offset().top + timer.height();
          var bulletsTop = $('#slider').find('.sliderBullets').offset().top;

          if (timerBottom > bulletsTop) {
            var diff = timerBottom - bulletsTop;
            var paddingTop = Math.max(10, 172 - diff);
            visibleItem.addClass('fixed').find('.caption').css('padding-top', paddingTop + 'px');
          }
        }
      });

      // Инициализация таймеров
      <?php foreach ($slides as $slide) { ?>
        <?php if (isset($slide['time_end']) && $slide['time_end']) { ?>
        $('#counter<?php echo $slide['id']; ?>').TimeCircles();
        <?php } ?>
      <?php } ?>

      // Клик по слайду
      $('#slider .item').on('click', function() {
        var href = $(this).find('a').eq(0).attr('href');
        if (href === '#') {
          $('.callme').trigger('click');
        }
      });

      // Корректировка позиции таймера при загрузке
      $('.sliderContent > div').each(function() {
        var timer = $(this).find('.slidertimer');
        if (!timer.length) return;

        var timerBottom = timer.offset().top + timer.height();
        var bulletsTop = $('.sliderBullets').offset().top;

        if (timerBottom > bulletsTop) {
          $('.sliderContent .item:visible').addClass('fixed');
          var diff = timerBottom - bulletsTop;
          $(this).find('.caption').css('padding-top', (176 - (diff + 54)) + 'px');
        }
      });
    }

    $(document).ready(initSlider);
  })();
</script>
