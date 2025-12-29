<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="/video-js.min.css" rel="stylesheet" />

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WWRJPN3');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WWRJPN3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<? if(strpos($class, "common-home") !== false){ ?>
<!-- <meta name="yandex-verification" content="736c4da9ed9f419a" /> -->
<meta name="yandex-verification" content="5adfb51121286cea" />
<? } ?>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<link rel="preload" as="font">
<link href="/catalog/view/theme/default/stylesheet/stylesheet.css?ver=2.0.0-8" rel="stylesheet" id="stylesheet">

  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js@1/src/toastify.min.css">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

<script>
  function getAnalytics() {
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-WWRJPN3');
 console.log("getAnalytics")
}

</script>
  <script src="/catalog/view/javascript/new.js"></script>
  <script src="/catalog/view/javascript/hide_links.js"></script>


<!-- Google Tag Manager -->
<script>
if (typeof navigator.userAgent !== "undefined") {
	if (navigator.userAgent.indexOf('Lighthouse') < 0) {
		getAnalytics();
	}
} else {
	getAnalytics();
}
</script>

  <!-- Google tag (gtag.js) -->


<!-- End Google Tag Manager -->

<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>

<!--
<?php foreach ($graphs as $graph) { ?>
<meta property="og:<?php echo $graph['property'] ?>" content="<?php echo $graph['content'] ?>" />
<?php } ?>

-->
<?php echo $push; ?>

  <link rel="stylesheet" href="catalog/view/theme/default/stylesheet/swiper-bundle.min.css">
  <script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_SITE_KEY ?>"></script>
  <script>
grecaptcha.ready(function() {
    grecaptcha.execute('6LenhE8qAAAAAFC-Rd-l5TeTtRMXw-z0EVLjwmLx', {action: 'homepage'}).then(function(token) {
        fetch('index.php?route=extension/module/recaptcha/validateCaptcha', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({token: token})  // Токен передается в теле запроса
        })
        localStorage.setItem('grecaptcha_token', token)
        )
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Валидация прошла успешно');
            } else {
                console.error('Ошибка валидации reCAPTCHA');
            }
        })
        .catch(error => {
            console.error('Ошибка при запросе:', error);
        });
    });
});
  </script>

  <!-- calltouch -->
  <script>
  (function(w,d,n,c){w.CalltouchDataObject=n;w[n]=function(){w[n]["callbacks"].push(arguments)};if(!w[n]["callbacks"]){w[n]["callbacks"]=[]}w[n]["loaded"]=false;if(typeof c!=="object"){c=[c]}w[n]["counters"]=c;for(var i=0;i<c.length;i+=1){p(c[i])}function p(cId){var a=d.getElementsByTagName("script")[0],s=d.createElement("script"),i=function(){a.parentNode.insertBefore(s,a)},m=typeof Array.prototype.find === 'function',n=m?"init-min.js":"init.js";s.async=true;s.src="https://mod.calltouch.ru/"+n+"?id="+cId;if(w.opera=="[object Opera]"){d.addEventListener("DOMContentLoaded",i,false)}else{i()}}})(window,document,"ct","a32r4yxz");
  </script>
  <!-- calltouch -->

</head>
<body class="<?php echo $class; ?> <?php echo $browser; ?>">
<script src="/catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript" ></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript" defer></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
<script src="/catalog/view/javascript/common.js?v=1" defer type="text/javascript"></script>
<!--
<script>new WOW().init();</script>-->
<!--<script src="https://unpkg.com/swiper/swiper-bundle.js" defer></script>

<!-- Google Tag Manager (noscript) -->
<script src="catalog/view/javascript/swiper-bundle.min.js"></script>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WWRJPN3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  <div id="preloader">
    <div class="centrpreload">
      <!-- <img src="image/logotype_top.svg" alt=""> -->
      <img src="catalog/view/theme/default/stylesheet/img/p1.gif" alt="">
      <div class="waiting">Подождите, идет загрузка</div>
    </div>
    <!--<script>$("#preloader img").attr("src", $("#preloader img").attr("data-src")); </script>  
  --></div>
    <header>
      <div class="top">
        <div class="lc">
          <div class="menu">
            <a href="<? echo $origin_server ?>o-kompanii/">О компании</a>
            <a href="<? echo $origin_server ?>oplata/">Оплата и доставка</a>
            <a href="/blog/torgovyy-avtomat-v-lizing/">Лизинг</a>
            <a href="<? echo $origin_server ?>besprotsentnaya-rassrochka/">Кредит и рассрочка</a>
            <a href="<? echo $origin_server ?>photos/">Галерея</a>
            <a href="<? echo $origin_server ?>servisnyy-tsentr/">Сервисный центр</a>
            <a href="<? echo $origin_server ?>arenda/">Аренда</a>
            <a href="<? echo $origin_server ?>kontakty/">Контакты</a>
          </div>
          <div class="sl">
            <?php echo $search; ?>
            <?php echo $language; ?>
          </div>
        </div>
      </div>
      <div class="lc">
        <div class="callme" data-target="#winMain"><? echo $callme ?></div>
        <div class="telm">
          <div><a href="tel:<?php echo $telephone1; ?>"><?php echo $telephone1; ?></a></div>
          <div>Для звонков по Москве</div>
        </div><a id="logo" href="/">
        	<img src="/image/logotype_top.svg">
            <!-- <img src="/image/logos.png"> -->
          <div>Торгово-производственная вендинговая компания</div></a>
        <? echo $cart; ?>
        <div class="telk">
          <div><a href="tel:<?php echo $telephone2; ?>"><?php echo $telephone2; ?></a></div>
          <div>Бесплатный звонок по России</div>
          <div class="mailto"><a href="mailto:info@vend-shop.com">info@vend-shop.com</a></div>
        </div>
      </div>
      <div class="bottom">
        <div class="lc wp">
                <ul id="menu">
                <?php foreach ($categories as $category) { ?>
                  <?php if ($category['children'] && ($category['category_id'] == 2)) { ?>
                    <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
                      <div class="dropdown-menu">
                      <div class="dropdown-inner">
                          <!--<ul>
                          <li><a href="/category/avtomaty/cnekovye-avtomaty/" class="c1">Снековые автоматы</a></li>
                          <li><a href="/category/avtomaty/shtuchnyy-tovar/" class="c2">Автоматы для продажи<br> штучного товара</a></li>
                          <li><a href="/category/avtomaty/kofejnja-samoobsluzhivanija-kofe-point-kofe-spejs/" class="c3">Кофейни
                            <br>самообслуживания</a></li>
                            <li><a href="/category/avtomaty/kofeynye-avtomaty/" class="c4">Кофейные автоматы</a></li>
                            <li><a href="/category/avtomaty/avtomaticheskie-kofemashiny/" class="c5">Автоматические кофемашины</a></li>
                          <li><a href="/category/bytovaja-himija-refill-stancija/" class="c6">Рефил-станции <br> бытовой химии на разлив</a></li>
                          <li><a href="/category/avtomaty/pitevaya-voda-i-napitki/" class="c7">Автоматы питьевой воды</a></li>
                          <li><a href="/category/avtomaty/avtomaty-gazirovannoy-vody/" class="c8">Автоматы газированной воды</a></li>
                          <li><a href="/category/morozhenoe/" class="c9">Автоматы мороженого<br> и замороженной продукции</a></li>
                          <li><a href="/category/avtomaty/moloko/" class="c10">Молокоматы</a></li>
                          <li><a href="/category/avtomaty/nezamerzajushaja-zhidkost/" class="c11">Автоматы для продажи<br>незамерзающей жидкости</a></li>
                          </ul><ul><li><a href="/category/avtomaty/avtomatizirovannye-magaziny/" class="c12">Минимаркеты
                            самообслуживания</a></li>
                            <li><a href="/category/vendingovye-avtomaty-obedov/" class="c18">Автоматы для продажи готовых обедов</a></li>
                            <li><a href="category/avtomaty/korma-dlya-zhivotnykh/?pomosch" class="c17">Автоматы для продажи кормов для животных и птиц</a></li>
                            <li><a href="/category/avtomaty/sportivnoe-pitanie/?pomosch" class="c14">Автоматы для продажи спортивного питания</a></li>
                            <li><a href="/category/avtomaty/flomaty-avtomaty-po-prodazhe-tsvetov/?pomosch" class="c19">Автоматы для продажи цветов (флороматы)</a></li>
                            <li><a href="/category/avtomaty/meditsinskie-maski/?pomosch" class="c20">Автоматы для продажи медицинских масок</a></li>
                            <li><a href="/category/avtomaty/sanitajzery-i-antisepticheskie-geli/?pomosch" class="c15">Автоматы для продажи санитайзеров</a></li>
                            <li><a href="/category/avtomaty/hits/" class="c16">Хиты продаж</a></li>
                            <li><a href="/category/katalog-bu-avtomatov/?pomosch" class="c13">Оборудование б/у</a></li>
                        </ul>-->

                        <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                         <ul>
                          <?php foreach ($children as $child) { ?>
                            <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                          <?php } ?>
                          </ul>
                        <?php } ?>
                      </div>
                      </div>
                    </li>
                      <?php } else { ?>
                        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                      <?php } ?>
                 <?php } ?>
                 <li style="width: 136px;"><a href="/online-kasy/">онлайн кассы</a></li>
                 <li style="width: 150px;"><a href="/loyalcart/">Карты лояльности</a></li>
                </ul>
        </div>
      </div>
    </header>

<div class="easter-egg">
  <img src="/image/catalog/photo_2025-12-03_13-11-40.jpg" alt="Easter egg">
</div>