<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="/catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.js" type="text/javascript"></script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WWRJPN3');</script>
<!-- End Google Tag Manager -->

<link href="/catalog/view/theme/default_mobile_tablet/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="/catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
<script>new WOW().init();</script>
<script type="text/javascript" src="//vk.com/js/api/openapi.js?139"></script>
<script type="text/javascript">
  VK.init({apiId: 5876809, onlyWidgets: true});
</script>
<?php echo $push; ?>
<link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">

<script src="https://unpkg.com/swiper/js/swiper.min.js"></script>
</head>
<body class="<?php echo $class; ?>">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WWRJPN3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  <div id="preloader">
    <div class="centrpreload">
      <img src="catalog/view/theme/default/stylesheet/img/p1.gif" alt="">
      <div class="waiting">Подождите, идет загрузка</div>
    </div>
  </div>
    <header>
      <div class="top">
        <div class="lc">
          <div class="menu">Меню</div>
          <?php echo $cart; ?>
          <?php echo $search; ?>
          <?php echo $language; ?>
        </div>
      </div>
      <div class="menu_container">
        <div class="closemenu"></div>
        <div class="pages">
          <a href="/o-kompanii/">О компании</a>
           <a href="/oplata/">Оплата и доставка</a>
           <a href="/blog/torgovyy-avtomat-v-lizing/">Лизинг</a>
           <a href="/besprotsentnaya-rassrochka/">Кредит и рассрочка</a>
           <a href="/photos/">Галерея</a>
           <a href="/servisnyy-tsentr/">Сервисный центр</a>
           <a href="/arenda/">Аренда</a>
           <a href="/kontakty/">Контакты</a>
         </div>
         <div class="mcats">
             <?php foreach ($categories as $key => $category) { ?>
                <?php if($key == 1) { ?>
                  <a href="/online-kasy/">онлайн кассы</a>
                <?php } else { ?>
                  <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
                <?php } ?>
              <?php } ?>
              <a href="/online-kasy/">онлайн кассы</a>
         </div>
       </div>
      <div class="lc">
      <a id="logo" href="/">
        <img src="/image/logotype_top.svg">
        <!-- <img src="/image/logos.png"> -->
  </a>

        <div class="hats">
          <div class="hat">Торгово-производственная </div>
          <div class="hat">вендинговая компания</div>
          <div class="callme">
           <span><? echo $callme ?></span>
         </div>
          <div class="leftmenu">
            <div class="hat"><a href="tel:<?php echo $telephone1; ?>"><?php echo $telephone1; ?></a></div>
            <div class="hat">Для звонков по Москве</div>
          </div>
          <div class="rightmenu">
            <div class="hat"><a href="tel:<?php echo $telephone2; ?>"><?php echo $telephone2; ?></a></div>
            <div class="hat">Бесплатный звонок по России</div>
          </div>
        </div>
      </div>
    </header>
    <script>
      $(document).ready(function(){
        $(".menu").on("click", function(){
          $(".menu_container").toggle();
        });
        $(".menu_container .closemenu").on("click", function(){
          $(".menu_container").hide();
        });
      });
    </script>