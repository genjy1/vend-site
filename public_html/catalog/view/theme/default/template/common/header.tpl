<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <base href="<?php echo $base; ?>">

  <link href="/video-js.min.css" rel="stylesheet">

  <!-- Google Tag Manager -->
  <script>
    (function(w,d,s,l,i){
      w[l]=w[l]||[];
      w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
      var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),
          dl=l!='dataLayer'?'&l='+l:'';
      j.async=true;
      j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
      f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WWRJPN3');
  </script>
  <!-- End Google Tag Manager -->

  <?php if (strpos($class, 'common-home') !== false) { ?>
  <meta name="yandex-verification" content="5adfb51121286cea">
  <?php } ?>

  <?php if ($description) { ?>
  <meta name="description" content="<?php echo $description; ?>">
  <?php } ?>

  <?php if ($keywords) { ?>
  <meta name="keywords" content="<?php echo $keywords; ?>">
  <?php } ?>

  <link rel="preload" as="font">
  <link href="/catalog/view/theme/default/stylesheet/stylesheet.css?ver=2.0.0-8" rel="stylesheet" id="stylesheet">
  <link href="/catalog/view/theme/default/stylesheet/bem-utilities.css" rel="stylesheet">
  <link href="/catalog/view/theme/default/stylesheet/ui-enhancements.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1/src/toastify.min.css">

  <?php foreach ($styles as $style) { ?>
  <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>">
  <?php } ?>

  <script>
    function getAnalytics() {
      (function(w,d,s,l,i){
        w[l]=w[l]||[];
        w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
        var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),
            dl=l!='dataLayer'?'&l='+l:'';
        j.async=true;
        j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
        f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-WWRJPN3');
    }

    if (typeof navigator.userAgent !== 'undefined') {
      if (navigator.userAgent.indexOf('Lighthouse') < 0) {
        getAnalytics();
      }
    } else {
      getAnalytics();
    }
  </script>

  <script src="/catalog/view/javascript/new.js"></script>
  <script src="/catalog/view/javascript/hide_links.js"></script>
  <script src="/catalog/view/javascript/ui-enhancements.js" defer></script>

  <?php foreach ($links as $link) { ?>
  <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>">
  <?php } ?>

  <?php echo $push; ?>

  <link rel="stylesheet" href="catalog/view/theme/default/stylesheet/swiper-bundle.min.css">
  <script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_SITE_KEY; ?>"></script>

  <script>
    grecaptcha.ready(function() {
      grecaptcha.execute('6LenhE8qAAAAAFC-Rd-l5TeTtRMXw-z0EVLjwmLx', {action: 'homepage'})
        .then(function(token) {
          localStorage.setItem('grecaptcha_token', token);

          fetch('index.php?route=extension/module/recaptcha/validateCaptcha', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({token: token})
          })
          .then(function(response) { return response.json(); })
          .then(function(data) {
            if (data.success) {
              console.log('Валидация прошла успешно');
            } else {
              console.error('Ошибка валидации reCAPTCHA');
            }
          })
          .catch(function(error) {
            console.error('Ошибка при запросе:', error);
          });
        });
    });
  </script>

  <!-- Calltouch -->
  <script>
    (function(w,d,n,c){
      w.CalltouchDataObject=n;
      w[n]=function(){w[n]['callbacks'].push(arguments)};
      if(!w[n]['callbacks']){w[n]['callbacks']=[]}
      w[n]['loaded']=false;
      if(typeof c!=='object'){c=[c]}
      w[n]['counters']=c;
      for(var i=0;i<c.length;i+=1){p(c[i])}
      function p(cId){
        var a=d.getElementsByTagName('script')[0],
            s=d.createElement('script'),
            m=typeof Array.prototype.find==='function',
            n=m?'init-min.js':'init.js';
        s.async=true;
        s.src='https://mod.calltouch.ru/'+n+'?id='+cId;
        if(w.opera=='[object Opera]'){
          d.addEventListener('DOMContentLoaded',function(){a.parentNode.insertBefore(s,a)},false);
        }else{
          a.parentNode.insertBefore(s,a);
        }
      }
    })(window,document,'ct','a32r4yxz');
  </script>
</head>

<body class="<?php echo $class; ?> <?php echo $browser; ?>">
  <!-- Google Tag Manager (noscript) -->
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WWRJPN3" height="0" width="0" class="u-hidden"></iframe>
  </noscript>

  <script src="/catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script src="/catalog/view/javascript/bem-utils.js" defer></script>

  <?php foreach ($scripts as $script) { ?>
  <script src="<?php echo $script; ?>" defer></script>
  <?php } ?>

  <?php foreach ($analytics as $analytic) { ?>
  <?php echo $analytic; ?>
  <?php } ?>

  <script src="/catalog/view/javascript/common.js?v=1" defer></script>
  <script src="catalog/view/javascript/swiper-bundle.min.js"></script>

  <!-- Preloader -->
  <div id="preloader" class="preloader">
    <div class="centrpreload preloader__content">
      <img src="catalog/view/theme/default/stylesheet/img/p1.gif" alt="Loading" class="preloader__image">
      <div class="waiting preloader__text">Подождите, идет загрузка</div>
    </div>
  </div>

  <!-- Header -->
  <header class="header">
    <div class="top header__top">
      <div class="lc layout-container">
        <nav class="menu header__menu">
          <a href="<?php echo $origin_server; ?>o-kompanii/" class="header__menu-link">О компании</a>
          <a href="<?php echo $origin_server; ?>oplata/" class="header__menu-link">Оплата и доставка</a>
          <a href="/blog/torgovyy-avtomat-v-lizing/" class="header__menu-link">Лизинг</a>
          <a href="<?php echo $origin_server; ?>besprotsentnaya-rassrochka/" class="header__menu-link">Кредит и рассрочка</a>
          <a href="<?php echo $origin_server; ?>photos/" class="header__menu-link">Галерея</a>
          <a href="<?php echo $origin_server; ?>servisnyy-tsentr/" class="header__menu-link">Сервисный центр</a>
          <a href="<?php echo $origin_server; ?>arenda/" class="header__menu-link">Аренда</a>
          <a href="<?php echo $origin_server; ?>kontakty/" class="header__menu-link">Контакты</a>
        </nav>
        <div class="sl header__search-language">
          <?php echo $search; ?>
          <?php echo $language; ?>
        </div>
      </div>
    </div>

    <div class="lc layout-container header__main">
      <div class="callme header__callback" data-target="#winMain"><?php echo $callme; ?></div>

      <div class="telm header__phone header__phone--moscow">
        <div class="header__phone-number">
          <a href="tel:<?php echo $telephone1; ?>"><?php echo $telephone1; ?></a>
        </div>
        <div class="header__phone-label">Для звонков по Москве</div>
      </div>

      <a id="logo" href="/" class="header__logo">
        <img src="/image/logotype_top.svg" alt="Логотип" class="header__logo-image">
        <div class="header__logo-text">Торгово-производственная вендинговая компания</div>
      </a>

      <?php echo $cart; ?>

      <div class="telk header__phone header__phone--russia">
        <div class="header__phone-number">
          <a href="tel:<?php echo $telephone2; ?>"><?php echo $telephone2; ?></a>
        </div>
        <div class="header__phone-label">Бесплатный звонок по России</div>
        <div class="mailto header__email">
          <a href="mailto:info@vend-shop.com">info@vend-shop.com</a>
        </div>
      </div>
    </div>

    <div class="bottom header__bottom">
      <div class="lc wp layout-container wrapper">
        <ul id="menu" class="header__nav">
          <?php foreach ($categories as $category) { ?>
            <?php if ($category['children'] && ($category['category_id'] == 2)) { ?>
            <li class="dropdown header__nav-item header__nav-item--dropdown">
              <a href="<?php echo $category['href']; ?>" class="dropdown-toggle header__nav-link" data-toggle="dropdown">
                <?php echo $category['name']; ?>
              </a>
              <div class="dropdown-menu header__dropdown">
                <div class="dropdown-inner header__dropdown-inner">
                  <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                  <ul class="header__dropdown-list">
                    <?php foreach ($children as $child) { ?>
                    <li class="header__dropdown-item">
                      <a href="<?php echo $child['href']; ?>" class="header__dropdown-link"><?php echo $child['name']; ?></a>
                    </li>
                    <?php } ?>
                  </ul>
                  <?php } ?>
                </div>
              </div>
            </li>
            <?php } else { ?>
            <li class="header__nav-item">
              <a href="<?php echo $category['href']; ?>" class="header__nav-link"><?php echo $category['name']; ?></a>
            </li>
            <?php } ?>
          <?php } ?>
          <li class="header__nav-item header__nav-item--fixed-width">
            <a href="/online-kasy/" class="header__nav-link">онлайн кассы</a>
          </li>
          <li class="header__nav-item header__nav-item--fixed-width">
            <a href="/loyalcart/" class="header__nav-link">Карты лояльности</a>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <div class="easter-egg">
    <img src="/image/catalog/photo_2025-12-03_13-11-40.jpg" alt="Easter egg">
  </div>
